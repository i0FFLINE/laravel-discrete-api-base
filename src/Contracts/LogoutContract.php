<?php

namespace IOF\DiscreteApi\Base\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class LogoutContract
{
    abstract public function do(User $User): ?JsonResponse;
}
