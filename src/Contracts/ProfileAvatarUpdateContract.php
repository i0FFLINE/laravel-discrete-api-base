<?php

namespace IOF\DiscreteApi\Base\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class ProfileAvatarUpdateContract
{
    abstract public function do(User $User, array $input = []): ?JsonResponse;
}
