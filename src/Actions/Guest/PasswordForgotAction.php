<?php

namespace IOF\DiscreteApi\Base\Actions\Guest;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Guest\PasswordForgotContract;
use IOF\DiscreteApi\Base\Notifications\ResetPasswordNotification;

class PasswordForgotAction extends PasswordForgotContract
{
    public function do(array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'exists:users'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            //
            $status = Password::sendResetLink($input, function ($user, $token) {
                $user->notify(new ResetPasswordNotification($token));
                return Password::RESET_LINK_SENT;
            });

            return $status === Password::RESET_LINK_SENT ? response()->json(null, 204) : response()->json(['error' => [$status]], 204);
        }

        return null;
    }
}
