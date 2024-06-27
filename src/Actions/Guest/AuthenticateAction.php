<?php

namespace IOF\DiscreteApi\Base\Actions\Guest;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Guest\AuthenticateContract;
use IOF\DiscreteApi\Base\Notifications\TwoFactorSecretNotification;

class AuthenticateAction extends AuthenticateContract
{
    public function do(array $input): JsonResponse|Response|null
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'exists:users'],
                'password' => ['required', 'string'],
                'code' => ['string', 'min:0', 'max:20'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $User = User::where('email', $input['email'])->where('is_banned', false)->first();
            if (!is_null($User) && Hash::check($input['password'], $User->password)) {
                $User->tokens()->delete();
                if ($User->two_factor_enabled) {
                    if (is_null($User->two_factor_secret)) {
                        // make two-factor secret and mail user
                        $User->notify(new TwoFactorSecretNotification($User));
                        return response()->json(['message' => Lang::get('Two-factor authentication required')], 201);
                    } elseif (!empty($input['code']) && Hash::check($input['code'], $User->two_factor_secret)) {
                        // check secret and return token
                        $User->forceFill(['two_factor_secret' => null])->save();
                        // и отдаём токен, все счастливы.
                        return response()->json(['token' => $User->createToken('browser', ['*'])->plainTextToken], 201);
                    }
                    // reset secret and return unauth
                    $User->forceFill(['two_factor_secret' => null])->save();
                    return response()->json([
                        'errors' => [
                            Lang::get('The given data was invalid. Perhaps an incomplete two-factor login. In that case, it iss not an error.')
                        ]
                    ], 404);
                } else {
                    return response()->json(['token' => $User->createToken('browser', ['*'])->plainTextToken], 201);
                }
            }
            // ж.о.п.а.
            return response()->noContent(404);
            /**
             * RETURNS:
             *      201+message = 2fa required
             *      201+token   = OK
             *      404+errors  = wrong login data
             *      empty 404   = user not found
             */
        }

        return null;
    }
}
