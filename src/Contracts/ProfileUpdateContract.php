<?php

namespace IOF\DiscreteApi\Base\Contracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class ProfileUpdateContract
{
    abstract public function do(User $User, array $input): ?JsonResponse;
}
