<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class UserPublicNameController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['public_name' => DiscreteApiHelper::generate_unique_public_user_name()]);
    }
}
