<?php

namespace IOF\DiscreteApi\Base\Contracts\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class AuthenticateContract
{
    abstract public function do(array $input): JsonResponse|Response|null;
}
