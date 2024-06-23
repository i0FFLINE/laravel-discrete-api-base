<?php

namespace IOF\DiscreteApi\Base\Contracts\Guest;

use Illuminate\Http\JsonResponse;

abstract class RegisterContract
{
    abstract public function do(array $input): ?JsonResponse;
}
