<?php

namespace IOF\DiscreteApi\Base\Actions\Guest;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use IOF\DiscreteApi\Base\Contracts\Guest\PasswordResetContract;

class PasswordResetAction extends PasswordResetContract
{
    public function do(array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'string', 'max:255', 'exists:users'],
                'password' => ['required', 'string', 'confirmed', (new Password(8))->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'token' => 'required',
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            //
            $status = PasswordBroker::reset($input, function (Authenticatable $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)]);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            });

            return $status === PasswordBroker::PASSWORD_RESET ? response()->json(null, 204) : response()->json(['error' => __($status)]);
        }

        return null;
    }
}
