<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\RegisterContract;

class RegisterController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(RegisterContract::class)->do($request->only(['email', 'password', 'password_confirmation', 'name']));
    }
}
