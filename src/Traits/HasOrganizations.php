<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use IOF\DiscreteApi\Base\Models\Organization;
use IOF\DiscreteApi\Base\Models\OrganizationMember;

/**
 * FOR User MODEL ONLY
 */
trait HasOrganizations
{
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

    public function groups(): BelongsToMany
    {
        return $this->organizations();
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organizations_members', 'user_id')->withPivot(['role']);
    }

    public function teams(): BelongsToMany
    {
        return $this->organizations();
    }

    public function projects(): BelongsToMany
    {
        return $this->organizations();
    }
}
