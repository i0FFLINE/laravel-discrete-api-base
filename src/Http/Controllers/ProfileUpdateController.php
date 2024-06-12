<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\ProfileUpdateContract;

class ProfileUpdateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(ProfileUpdateContract::class)->do($request->user(), $request->all());
    }
}
