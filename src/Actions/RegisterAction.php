<?php

namespace IOF\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use IOF\DiscreteApi\Base\Contracts\RegisterContract;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;

class RegisterAction extends RegisterContract
{
    public function do(array $input): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'email' => ['required', 'email', 'string', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed', (new Password(8))->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'name' => ['required', 'string','max:255'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            $User = User::create([
                'email' => $input['email'],
                'name' => $input['name'],
                'password' => Hash::make($input['password']),
                'public_name' => DiscreteApiHelper::generate_unique_public_user_name(),
            ]);

            event(new Registered($User));

            return response()->json($User->toArray());
        }

        return null;
    }
}
