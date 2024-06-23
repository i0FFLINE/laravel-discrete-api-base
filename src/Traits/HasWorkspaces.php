<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use IOF\DiscreteApi\Base\Models\Workspace;

trait HasWorkspaces
{
    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'organization_id');
    }
}
