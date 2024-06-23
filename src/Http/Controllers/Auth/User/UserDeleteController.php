<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserDeleteContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class UserDeleteController extends DiscreteApiController
{
    public function __invoke(Request $request): Response|JsonResponse|null
    {
        return app(UserDeleteContract::class)->do($request->user(), $request->only(['current_password']));
    }
}
