<?php

namespace IOF\DiscreteApi\Base\Observers;

use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Models\Organization as Model;

class OrganizationObserver
{
    public function creating(Model $model): void
    {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        }
    }
}
