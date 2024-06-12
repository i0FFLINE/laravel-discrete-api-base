<?php

namespace IOF\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\AuthenticateContract;

class AuthenticateAction extends AuthenticateContract
{
    public function do(array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'exists:users'],
                'password' => ['required', 'string'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $User = User::where('email', $input['email'])->first();
            if (! is_null($User) && Hash::check($input['password'], $User->password)) {
                $User->tokens()->delete();
                return response()->json(['token' => $User->createToken('browser', ['*'])->plainTextToken], 201);
            }

            return response()->json(null, 404);
        }

        return null;
    }
}
