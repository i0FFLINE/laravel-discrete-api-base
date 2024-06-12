<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\PasswordResetContract;

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
