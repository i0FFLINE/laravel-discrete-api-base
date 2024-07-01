<?php

namespace IOF\DiscreteApi\Base\TwoFactorAuthProviders;

use App\Models\User;

interface TwoFAProviderInterface
{
    public function generateSecret(User $User, int $length = 20);
    public function checkSecret(mixed $secret, string $code = null);
    public function resetSecret(mixed $secret): ?bool;
}
