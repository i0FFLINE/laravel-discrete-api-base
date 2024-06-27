<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class User2faContract
{
    abstract public function do(User $User, array $input): ?JsonResponse;
}
