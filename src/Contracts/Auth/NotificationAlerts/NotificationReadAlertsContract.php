<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\NotificationAlerts;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use IOF\DiscreteApi\Base\Models\NotificationAlerts;

abstract class NotificationReadAlertsContract
{
    abstract public function do(User $User, NotificationAlerts $Notification = null): ?JsonResponse;
}
