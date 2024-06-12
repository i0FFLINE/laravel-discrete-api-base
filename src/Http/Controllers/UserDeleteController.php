<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\UserDeleteContract;

class UserDeleteController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(UserDeleteContract::class)->do($request->user(), $request->only(['current_password']));
    }
}
