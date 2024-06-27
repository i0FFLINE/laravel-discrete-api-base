<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\NotificationAlerts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Contracts\Auth\NotificationAlerts\NotificationReadAlertsContract;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;
use IOF\DiscreteApi\Base\Models\NotificationAlerts;

class NotificationReadAlertsController extends DiscreteApiController
{
    public function __invoke(Request $request, string $id = null): JsonResponse|Response
    {
        if (is_null($id) || !Str::isUuid($id)) {
            return response()->noContent(404);
        }
        return app(NotificationReadAlertsContract::class)->do($request->user(), NotificationAlerts::findOrFail($id));
    }
}
