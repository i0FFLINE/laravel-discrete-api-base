<?php

namespace IOF\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\ProfileUpdateContract;

class ProfileUpdateAction extends ProfileUpdateContract
{
    public function do(User $User, array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'firstname' => ['string', 'max:255'],
                'lastname' => ['string', 'max:255'],
                'locale' => ['string', 'min:2', 'max:2'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $Profile = $User->profile;
            if (! is_null($Profile)) {
                foreach ($input as $key => $value) {
                    // check only attributes
                    if (array_key_exists($key, $Profile->getAttributes())) {
                        $Profile->{$key} = $value;
                    }
                }
                if ($Profile->isDirty()) {
                    $Profile->save();
                    $User->load(['profile']);
                }
            }

            return response()->json($User->toArray());
        }

        return null;
    }
}
