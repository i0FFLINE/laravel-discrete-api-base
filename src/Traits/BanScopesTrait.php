<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BanScopesTrait
{
    /**
     * Only Banned
     */
    public function scopeOnlyBanned(Builder $query): void
    {
        $query->where('is_banned', false);
    }
    /**
     * Only Banned
     */
    public function scopeNotBanned(Builder $query): void
    {
        $query->where('is_banned', true);
    }

}