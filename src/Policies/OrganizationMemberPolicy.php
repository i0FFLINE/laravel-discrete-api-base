<?php

namespace IOF\DiscreteApi\Base\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use IOF\DiscreteApi\Base\Models\OrganizationMember as Model;

class OrganizationMemberPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $User): bool
    {
        // if global super/admin/support
        if ($User->hasRole(['super', 'admin', 'support'])) {
            return true;
        }
        return false;
    }

    public function view(User $User, Model $Model): bool
    {
        // if global super/admin/support
        if ($User->hasRole(['super', 'admin', 'support'])) {
            return true;
        }
        // if local (org) owner (super)
        if (!is_null($Model->organization) && $User->id == $Model->organization->owner_id) {
            return true;
        }
        // if is member
        if ($Model->user_id == $User->id) {
            return true;
        }
        return false;
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $User, Model $Model): bool
    {
        // if global super/admin/support
        if ($User->hasRole(['super', 'admin', 'support'])) {
            return true;
        }
        // if local (org) owner (super)
        if (!is_null($Model->organization) && $User->id == $Model->organization->owner_id) {
            return true;
        }
        // if member is admin
        if ($Model->role >= 9) {
            return true;
        }
        return false;
    }

    public function delete(User $User, Model $Model): bool
    {
        // if global super/admin/support
        if ($User->hasRole(['super', 'admin', 'support'])) {
            return true;
        }
        // if local (org) owner (super)
        if (!is_null($Model->organization) && $User->id == $Model->organization->owner_id) {
            return true;
        }
        // if member (self-delete/quit)
        if ($Model->user_id == $User->id) {
            return true;
        }
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
