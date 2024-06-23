<?php

namespace IOF\DiscreteApi\Base\Contracts\Auth\NotificationAlerts;

use App\Models\User;
use Illuminate\Http\JsonResponse;

abstract class NotificationAlertsContract
{
    abstract public function do(User $User, array $input = []): ?JsonResponse;
}
