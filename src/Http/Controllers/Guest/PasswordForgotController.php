<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Guest\PasswordForgotContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class PasswordForgotController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(PasswordForgotContract::class)->do($request->only(['email']));
    }
}
