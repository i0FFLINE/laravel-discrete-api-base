<?php

namespace IOF\DiscreteApi\Base\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $User): bool
    {
        return $User->hasRole(['super', 'admin', 'support']);
    }

    public function view(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->id == $User->id;
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->id == $User->id;
    }

    public function delete(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->id == $User->id;
    }

    public function restore(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']);
    }

    public function forceDelete(User $User, User $Model): bool
    {
        return $User->hasRole(['super']);
    }
}
