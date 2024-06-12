<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\ProfileAvatarUpdateContract;

class ProfileAvatarUpdateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(ProfileAvatarUpdateContract::class)->do($request->user(), $request->only(['avatar']));
    }
}
