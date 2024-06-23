<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\Profile;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class ProfileAvatarUpdareContract
{
    abstract public function handle(User $User, array $input = []): ?JsonResponse;
}
