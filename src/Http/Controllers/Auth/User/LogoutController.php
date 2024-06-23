<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\User\LogoutContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class LogoutController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(LogoutContract::class)->do($request->user());
    }
}
