<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Profile;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiFilesystem;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class ProfileAvatarController extends DiscreteApiController
{
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if (is_null($request->user()->profile->avatar_path)) {
            return response()->noContent();
        }

        return response()->redirectTo(DiscreteApiFilesystem::get_file($request->user()->profile, 'avatar_path'));
    }
}
