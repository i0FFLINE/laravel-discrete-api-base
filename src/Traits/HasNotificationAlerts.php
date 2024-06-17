<?php

namespace IOF\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use IOF\DiscreteApi\Base\Models\NotificationAlerts;

trait HasNotificationAlerts
{
    public function notification_alerts(): HasMany
    {
        return $this->hasMany(NotificationAlerts::class, 'user_id');
    }
}
