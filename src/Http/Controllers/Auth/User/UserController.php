<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class UserController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        dd($request->user()->toArray());
        return response()->json($request->user());
    }
}
