<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\UserUpdateContract;

class UserUpdateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(UserUpdateContract::class)->do($request->user(), $request->only(['current_password', 'email', 'password', 'password_confirmation']));
    }
}
