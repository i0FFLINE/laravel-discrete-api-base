<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use IOF\DiscreteApi\Base\Models\Organization;

abstract class OrganizationSwitchContract
{
    abstract public function do(User $User, Organization $Organization): Response|JsonResponse|null;
}
