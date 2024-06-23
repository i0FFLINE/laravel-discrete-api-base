<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Guest\AuthenticateContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class AuthenticateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(AuthenticateContract::class)->do($request->only(['email', 'password']));
    }
}
