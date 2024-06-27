<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\User\User2faContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class User2faController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(User2faContract::class)->do($request->user(), $request->only(['current_password', 'two_factor_enabled']));
    }
}
