<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\Profile;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class ProfileUpdateContract
{
    abstract public function do(User $User, array $input): JsonResponse|Response|null;
}
