<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\Profile;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Auth\Profile\ProfileUpdateContract;
use IOF\DiscreteApi\Base\Rules\MatchCurrentPasswordRule;

class ProfileUpdateAction extends ProfileUpdateContract
{
    public function do(User $User, array $input): JsonResponse|Response|null
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'current_password' => ['required', 'string', new MatchCurrentPasswordRule()],
                'firstname' => ['string', 'max:255'],
                'lastname' => ['string', 'max:255'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            if (!is_null($User->profile)) {
                $User->profile->forceFill([
                    'firstname' => $input['firstname'] != $User->profile->firstname ? $input['firstname'] : $User->profile->firstname,
                    'lastname' => $input['lastname'] != $User->profile->lastname ? $input['lastname'] : $User->profile->lastname,
                ])->save();
                return response()->noContent();
            } else {
                $User->profile()->create([
                    'firstname' => $input['firstname'],
                    'lastname' => $input['lastname'],
                ]);
                return response()->noContent(201);
            }
        }

        return null;
    }
}
