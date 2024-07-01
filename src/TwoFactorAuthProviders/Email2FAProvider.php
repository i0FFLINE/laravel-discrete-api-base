<?php

namespace IOF\DiscreteApi\Base\TwoFactorAuthProviders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Email2FAProvider extends TwoFAProviderAbstract implements TwoFAProviderInterface
{
    public function __construct()
    {
    }

    public function __invoke(): void
    {
    }

    public function generateSecret(User $User, int $length = 20): string
    {
        if ($length > 20) {
            $length = 20;
        }
        $secret = Str::random($length);
        $User->forceFill(['two_factor_secret' => Hash::make($secret)])->save();
        return $secret;
    }

    public function checkSecret(mixed $secret, string $code = null): bool
    {
        return Hash::check($code, $secret->two_factor_secret);
    }

    public function resetSecret(mixed $secret): ?bool
    {
        return $secret->forceFill(['two_factor_secret' => null])->save();
    }
}
