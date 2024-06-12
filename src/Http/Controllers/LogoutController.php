<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\LogoutContract;

class LogoutController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(LogoutContract::class)->do($request->user());
    }
}
