<?php

namespace IOF\DiscreteApi\Base\Contracts;

use Illuminate\Http\JsonResponse;

abstract class AuthenticateContract
{
    abstract public function do(array $input): ?JsonResponse;
}
