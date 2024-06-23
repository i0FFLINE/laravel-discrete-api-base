<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserUpdateContract;
use IOF\DiscreteApi\Base\Notifications\ChangeEmailOldNotification;
use IOF\DiscreteApi\Base\Rules\MatchCurrentPasswordRule;

class UserUpdateAction extends UserUpdateContract
{
    public function do(User $User, array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'current_password' => ['required', 'string', new MatchCurrentPasswordRule()],
                'email' => ['email', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'public_name' => ['required', 'string', 'max:12', 'min:6'],
                'password' => [
                    'string',
                    'confirmed',
                    (new Password(8))->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(),
                ],
            ]);
            if ($Validator->fails()) {
                return response()->json([
                    'errors' => $Validator->errors()
                        ->toArray(),
                ], 404);
            }
            //
            if (strlen($input['email'])) {
                if ($input['email'] != $User->email) {
                    $User->emailChanges()
                        ->create([
                            'old_email' => $User->email,
                            'old_email_verified_at' => $User->email_verified_at,
                            'new_email' => $input['email'],
                            'valid_until' => now()->addHour(),
                        ]);
                    $User->notify(new ChangeEmailOldNotification($User));
                }
            }
            //
            $saveFlag = false;
            if (! empty($input['name']) && $input['name'] != $User->name) {
                $User->name = $input['name'];
                $saveFlag = true;
            }
            if (! empty($input['public_name']) && $input['public_name'] != $User->public_name) {
                $User->public_name = $input['public_name'];
                $saveFlag = true;
            }
            if (! empty($input['password']) && $input['current_password'] != $input['password']) {
                // save new passord immediatelly
                $User->forceFill(['password' => Hash::make($input['password'])]);
                $User->tokens()->delete();
            }
            if ($saveFlag) {
                $User->save();
            }

            return response()->json(null, 204);
        }

        return null;
    }
}
