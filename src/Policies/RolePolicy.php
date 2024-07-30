<?php

namespace IOF\DiscreteApi\Base\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use IOF\DiscreteApi\Base\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super', 'admin', 'support']);
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasRole(['super', 'admin', 'support']) || $role->users()->where('user_id', $user->id)->count() > 0;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['super', 'admin']);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasRole(['super', 'admin']);
    }

    public function delete(User $user, Role $role): bool
    {
        return !$role->users()->count() > 0
               && $role->is_protected == false
               && $user->hasRole(['super', 'admin']);
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole(['super', 'admin']);
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return !$role->users->count() > 0 && $user->hasRole(['super']);
    }

    public function attachUser(User $user, Role $role, User $User): bool
    {
        return $user->hasRole(['super', 'admin', 'support']) || $role->name == 'user';
    }

    public function detachUser(User $user, Role $role, User $User): bool
    {
        return ($user->hasRole(['super', 'admin', 'support']) && !in_array($role->name, ['user', 'super']));
    }

    public function attachAnyUser(User $user, Role $role): bool
    {
        return $user->hasRole(['super', 'admin', 'support']) || $role->name == 'user';
    }
}
