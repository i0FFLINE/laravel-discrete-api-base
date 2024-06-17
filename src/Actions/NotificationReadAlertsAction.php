<?php

namespace IOF\DiscreteApi\Base\Actions;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use IOF\DiscreteApi\Base\Contracts\NotificationReadAlertsContract;
use IOF\DiscreteApi\Base\Models\NotificationAlerts;

class NotificationReadAlertsAction extends NotificationReadAlertsContract
{
    /**
     * @throws AuthorizationException
     */
    public function do(User $User, ?NotificationAlerts $Notification = null): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            if (is_null($Notification)) {
                $User->notification_alerts()->update(['read_at' => now()]);
                return response()->json(null, 204);
            } else {
                Gate::forUser($User)->authorize('update', $Notification);
                if ($Notification->forceFill(['read_at' => now()])->save()) {
                    return response()->json(null, 204);
                }
            }

            return response()->json(null, 406);
        }

        return null;
    }
}
