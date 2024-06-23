<?php

namespace IOF\DiscreteApi\Base\Contracts\Guest;

use Illuminate\Http\JsonResponse;

abstract class PasswordResetContract
{
    abstract public function do(array $input): ?JsonResponse;
}
