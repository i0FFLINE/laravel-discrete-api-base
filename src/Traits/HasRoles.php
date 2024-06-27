<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use IOF\DiscreteApi\Base\Models\Role;

trait HasRoles
{
    /**
     * Assign the Role (by name) to the User
     *
     * @param string $role
     *
     * @return Model|null
     */
    public function assignRole(string $role): ?Model
    {
        $Role = (new Role())->where('name', 'ILIKE', $role)->first();
        if (!is_null($Role)) {
            return $this->roles()->save($Role);
        }
        return null;
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * check if User has Role (by array of names)
     *
     * @param array|string|int $role
     *
     * @return boolean
     */
    public function hasRole(array|string|int $role): bool
    {
        if (is_array($role)) {
            if ($this->roles->count()) {
                foreach ($this->roles as $r) {
                    if (in_array($r->name, $role)) {
                        return true;
                    }
                }
            }
        } elseif (is_string($role) && $this->roles->count()) {
            return $this->roles->contains('name', $role);
        } elseif (is_numeric($role)) {
            return $this->roles->contains('id', $role);
        }
        return false;
    }
}
