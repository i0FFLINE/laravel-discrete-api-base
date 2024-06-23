<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Guest\PasswordResetContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class PasswordResetController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(PasswordResetContract::class)->do(
            $request->only([
                'email',
                'password',
                'password_confirmation',
                'token',
            ])
        );
    }
}
