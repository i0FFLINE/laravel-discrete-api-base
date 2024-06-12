<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\NotificationReadAlertsContract;
use IOF\DiscreteApi\Base\Models\NotificationAlerts;

class NotificationReadAlertsController extends DiscreteApiController
{
    public function __invoke(Request $request, NotificationAlerts $notification = null): JsonResponse
    {
        return app(NotificationReadAlertsContract::class)->do($request->user(), $notification);
    }
}
