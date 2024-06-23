<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserDeleteContract;
use IOF\DiscreteApi\Base\Rules\MatchCurrentPasswordRule;

class UserDeleteAction extends UserDeleteContract
{
    public function do(User $User, array $input): Response|JsonResponse|null
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'current_password' => ['required', 'string', new MatchCurrentPasswordRule()],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $User->tokens()->delete();
            $User->delete();

            return response()->noContent();
        }

        return null;
    }
}
