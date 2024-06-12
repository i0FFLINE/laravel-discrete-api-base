<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfileAvatarController extends DiscreteApiController
{
    public function __invoke(Request $request): Response|BinaryFileResponse
    {
        if (is_null($request->user()->profile->avatar_path)) {
            return response()->noContent(204);
        }

        return response()->file(($request->user()->profile->avatarDisk() == 'public' ? 'storage/' : null).$request->user()->profile->avatar_path);
    }
}
