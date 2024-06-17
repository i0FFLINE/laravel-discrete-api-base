<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\UserChangeEmailContract;

class UserChangeEmailController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(UserChangeEmailContract::class)->do($request->user());
    }
}
