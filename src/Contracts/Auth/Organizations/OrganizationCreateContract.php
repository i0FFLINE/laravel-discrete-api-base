<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class OrganizationCreateContract
{
    abstract public function do(User $User, array $input = []): JsonResponse|Response|null;
}
