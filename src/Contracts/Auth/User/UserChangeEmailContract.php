<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class UserChangeEmailContract
{
    abstract public function do(User $User): ?JsonResponse;
}
