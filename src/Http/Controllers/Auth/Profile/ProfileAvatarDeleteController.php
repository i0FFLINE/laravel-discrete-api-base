<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Profile;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiFilesystem;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class ProfileAvatarDeleteController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse|Response
    {
        if (! is_null($request->user()->profile)) {
            DiscreteApiFilesystem::del_file($request->user()->profile, 'avatar_path');
        }
        $request->user()->load(['profile']);
        return response()->noContent();
    }
}
