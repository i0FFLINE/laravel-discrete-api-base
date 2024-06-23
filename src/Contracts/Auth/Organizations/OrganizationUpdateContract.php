<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\Response;
use IOF\DiscreteApi\Base\Models\Organization;

abstract class OrganizationUpdateContract
{
    abstract public function do(User $User, Organization $Organization, array $input = []): ?Response;
}
