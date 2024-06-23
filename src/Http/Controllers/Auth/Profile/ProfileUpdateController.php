<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Profile;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\User\Profile\ProfileUpdateContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class ProfileUpdateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(ProfileUpdateContract::class)->do($request->user(), $request->only(['firstname', 'lastname', 'locale']));
    }
}
