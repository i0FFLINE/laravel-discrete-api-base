<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use IOF\DiscreteApi\Base\Contracts\Auth\User\UserChangeEmailContract;

class UserChangeEmailAction extends UserChangeEmailContract
{
    public function do(User $User): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $change = $User->emailChanges()->latest()->first();
            if (! is_null($change)) {
                if ($change->isValid()) {
                    // fix change email LOG
                    $change->forceFill([
                        'new_email_verified_at' => now(),
                        'deleted_at' => null,
                    ])->save();
                    // fix user's email
                    $User->forceFill([
                        'email' => $change->new_email,
                        'email_verified_at' => null,
                    ])->save();
                    // cleanup
                    $User->emailChanges()->whereNot('id', $change->id)->whereNull('new_email_verified_at')->delete();
                    return response()->json(null, 204);
                }
            }

            return response()->json(null, 404);
        }

        return null;
    }
}
