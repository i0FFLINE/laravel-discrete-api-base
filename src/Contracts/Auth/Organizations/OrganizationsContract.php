<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class OrganizationsContract
{
    abstract public function do(User $User): ?JsonResponse;
}
