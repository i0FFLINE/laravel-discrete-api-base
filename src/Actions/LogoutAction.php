<?php

namespace IOF\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use IOF\DiscreteApi\Base\Contracts\LogoutContract;

class LogoutAction extends LogoutContract
{
    public function do(User $User): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $User->tokens()->delete();

            return response()->json(null, 204);
        }

        return null;
    }
}
