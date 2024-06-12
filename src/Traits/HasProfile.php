<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use IOF\DiscreteApi\Base\Models\Profile;

trait HasProfile
{
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
