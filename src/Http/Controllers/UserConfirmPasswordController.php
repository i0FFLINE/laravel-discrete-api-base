<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Rules\MatchCurrentPasswordRule;

class UserConfirmPasswordController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $Validator = Validator::make($request->only(['password']), [
            'password' => ['required', 'string', new MatchCurrentPasswordRule()],
        ]);
        if ($Validator->fails()) {
            return response()->json(['errors' => $Validator->errors()->toArray()], 404);
        }
        return response()->json(null, 204);
    }
}
