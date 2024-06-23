<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Guest\RegisterContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class RegisterController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(RegisterContract::class)->do($request->only(['email', 'password', 'password_confirmation', 'name']));
    }
}
