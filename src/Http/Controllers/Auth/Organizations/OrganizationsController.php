<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Organizations;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationsContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class OrganizationsController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(OrganizationsContract::class)->do($request->user());
    }
}
