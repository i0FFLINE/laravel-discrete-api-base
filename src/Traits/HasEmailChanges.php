<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use IOF\DiscreteApi\Base\Models\UserEmailChange;

trait HasEmailChanges
{
    public function emailChanges(): HasMany
    {
        return $this->hasMany(UserEmailChange::class, 'user_id');
    }
}
