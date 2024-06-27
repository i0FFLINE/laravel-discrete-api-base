<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use IOF\DiscreteApi\Base\Contracts\Guest\AuthenticateContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class AuthenticateController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse|Response
    {
        return app(AuthenticateContract::class)->do($request->only(['email', 'password', 'code']));
    }
}
