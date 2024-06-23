<?php

namespace IOF\DiscreteApi\Base\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use IOF\DiscreteApi\Base\Models\OrganizationMember as Model;

class OrganizationMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $User): bool
    {
        // if global super/admin/support
        if ($User->hasRole(['super', 'admin', 'support'])) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
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

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
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

    /**
     * Determine whether the user can delete the model.
     */
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

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $User, Model $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $User, Model $Model): bool
    {
        return false;
    }
}
