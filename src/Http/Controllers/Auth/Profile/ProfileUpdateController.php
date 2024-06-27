<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Profile;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use IOF\DiscreteApi\Base\Contracts\Auth\Profile\ProfileUpdateContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class ProfileUpdateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse|Response
    {
        return app(ProfileUpdateContract::class)->do($request->user(), $request->only(['firstname', 'lastname', 'current_password']));
    }
}
