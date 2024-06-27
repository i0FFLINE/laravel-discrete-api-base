<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use IOF\DiscreteApi\Base\Models\Organization;

trait BelongsToOrganization
{
    public function group(): BelongsTo
    {
        return $this->organization();
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function team(): BelongsTo
    {
        return $this->organization();
    }

    public function project(): BelongsTo
    {
        return $this->organization();
    }
}
