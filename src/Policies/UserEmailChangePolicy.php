<?php

namespace IOF\DiscreteApi\Base\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use IOF\DiscreteApi\Base\Models\UserEmailChange as Model;

class UserEmailChangePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $User): bool
    {
        return $User->hasRole(['super', 'admin', 'support']);
    }

    public function view(User $User, Model $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->user_id == $User->id;
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $User, Model $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->user_id == $User->id;
    }

    public function delete(User $User, Model $Model): bool
    {
        return false;
    }

    public function restore(User $User, Model $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']);
    }

    public function forceDelete(User $User, Model $Model): bool
    {
        return false;
    }
}
