<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\NotificationAlerts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Contracts\Auth\NotificationAlerts\NotificationAlertsContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;

class NotificationAlertsController extends DiscreteApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return app(NotificationAlertsContract::class)->do($request->user(), $request->only(['age', 'pool']));
    }
}
