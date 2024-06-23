<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class UserDeleteContract
{
    abstract public function do(User $User, array $input): Response|JsonResponse|null;
}
