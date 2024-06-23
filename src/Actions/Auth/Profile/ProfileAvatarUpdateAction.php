<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\Profile;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Auth\User\Profile\ProfileAvatarUpdateContract;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiFilesystem;

class ProfileAvatarUpdateAction extends ProfileAvatarUpdateContract
{
    public function do(User $User, array $input = []): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'avatar' => ['required', 'image', 'nullable', 'mimes:jpg,jpeg,png,gif', 'max:1024'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            } else {
                if (! is_null($User->profile)) {
                    DiscreteApiFilesystem::put_file(request()->file('avatar'), 'profile_avatars/' . $User->id, $User->profile, 'avatar_path');
                }
                $User->load(['profile']);
                return response()->json($User->toArray());
            }
        }
        return null;
    }
}
