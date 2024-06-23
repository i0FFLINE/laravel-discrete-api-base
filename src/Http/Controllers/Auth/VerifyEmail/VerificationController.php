<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\VerifyEmail;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class VerificationController extends DiscreteApiController
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json(null, 204);
    }
}
