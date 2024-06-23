<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserChangeEmailContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class UserChangeEmailController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(UserChangeEmailContract::class)->do($request->user());
    }
}
