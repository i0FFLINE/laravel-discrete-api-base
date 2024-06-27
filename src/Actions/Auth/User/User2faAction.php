<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Auth\User\User2faContract;
use IOF\DiscreteApi\Base\Rules\MatchCurrentPasswordRule;

class User2faAction extends User2faContract
{
    public function do(User $User, array $input): ?JsonResponse
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'current_password' => ['required', 'string', new MatchCurrentPasswordRule()],
                'two_factor_enabled' => ['required', 'boolean'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $User->forceFill(['two_factor_enabled' => (bool)$input['two_factor_enabled']])->save();
            $User->tokens()->delete();
            return response()->json(null, 204);
        }

        return null;
    }
}
