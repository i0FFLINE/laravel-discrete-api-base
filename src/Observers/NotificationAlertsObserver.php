<?php

namespace IOF\DiscreteApi\Base\Observers;

use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Models\NotificationAlerts as Model;

class NotificationAlertsObserver
{
    public function creating(Model $model): void
    {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        }
    }
}
