<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use IOF\DiscreteApi\Base\Models\Organization;
use IOF\DiscreteApi\Base\Models\OrganizationMember;

/**
 * USE ONLY IN User MODEL !
 */
trait HasOrganizations
{
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organizations_members', 'user_id')->withPivot(['role']);
    }

    public function role(): ?string
    {
        if (isset($this->pivot)) {
            return config('discreteapibase.roles')[$this->pivot->role];
        }

        return null;
    }

    public function membership(): HasMany
    {
        return $this->hasMany(OrganizationMember::class, 'user_id');
    }

}
