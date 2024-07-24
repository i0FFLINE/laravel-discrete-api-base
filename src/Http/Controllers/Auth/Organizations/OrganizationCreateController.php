<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Organizations;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationCreateContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class OrganizationCreateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse|Response|null
    {
        return app(OrganizationCreateContract::class)->do($request->user(), $request->only(['title', 'description']));
    }
}
