<?php

namespace IOF\DiscreteApi\Base\TwoFactorAuthProviders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

abstract class TwoFAProviderAbstract
{
    public function generateSecret(User $User, int $length = 20): string
    {
        if ($length > 20) {
            $length = 20;
        }
        return Str::random($length);
    }

    public function checkSecret(mixed $secret, string $code = null): bool
    {
        return Hash::check($code, $secret);
    }

    public function resetSecret(mixed $secret): ?bool
    {
        return null;
    }
}
