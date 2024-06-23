<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Profile;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\User\Profile\ProfileAvatarUpdateContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class ProfileAvatarUpdateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(ProfileAvatarUpdateContract::class)->do($request->user(), $request->only(['avatar']));
    }
}
