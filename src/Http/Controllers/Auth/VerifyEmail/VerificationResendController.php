<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\VerifyEmail;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class VerificationResendController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => [
                Lang::get('Verification link sent!'),
            ],
        ]);
    }
}
