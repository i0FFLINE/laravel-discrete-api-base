<?php

namespace IOF\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Rules\MatchCurrentPasswordRule;
use IOF\DiscreteApi\Base\Contracts\UserDeleteContract;

class UserDeleteAction extends UserDeleteContract
{
    public function do(User $User, array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'current_password' => ['required', 'string', new MatchCurrentPasswordRule()],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $User->tokens()->delete();
            $User->delete();

            return response()->json($User->toArray(), 200);
        }

        return null;
    }
}
