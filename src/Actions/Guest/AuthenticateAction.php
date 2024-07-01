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
use IOF\DiscreteApi\Base\TwoFactorAuthProviders\TwoFAProviderInterface;

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
                $TwofaProvider = config('discreteapibase.features.2fa');
                $TFA = new $TwofaProvider();
                if ($User->two_factor_enabled && $TFA instanceof TwoFAProviderInterface) {
                    if (is_null($User->two_factor_secret)) {
                        // make two-factor secret and mail user
                        $User->notify(new TwoFactorSecretNotification($User));
                        return response()->json(['message' => Lang::get('Two-factor authentication required')], 201);
                    } elseif (!empty($input['code']) && $TFA->checkSecret($User, $input['code'])) {
                        // check secret and return token
                        $TFA->resetSecret($User);
                        // и отдаём токен, все счастливы.
                        return response()->json(['token' => $User->createToken('browser', ['*'])->plainTextToken], 201);
                    }
                    // reset secret and return unauth
                    $TwofaProvider->resetSecret($User);
                    return response()->json([
                        'errors' => [
                            Lang::get('The given data was invalid. Perhaps an incomplete two-factor login. In that case, it iss not an error.')
                        ]
                    ], 404);
                } else {
                    return response()->json(['token' => $User->createToken('browser', ['*'])->plainTextToken], 201);
                }
            }
            return response()->noContent(404);
        }

        return null;
    }
}
