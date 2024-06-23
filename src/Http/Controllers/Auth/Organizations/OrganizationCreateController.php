<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Organizations;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationCreateContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class OrganizationCreateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(OrganizationCreateContract::class)->do($request->user(), $request->only(['o_title', 'o_description', 'w_title', 'w_description']));
    }
}
