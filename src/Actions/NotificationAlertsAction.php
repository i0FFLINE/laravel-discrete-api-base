<?php

namespace IOF\DiscreteApi\Base\Actions;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use IOF\DiscreteApi\Base\Models\NotificationAlerts;
use IOF\DiscreteApi\Base\Contracts\NotificationAlertsContract;

class NotificationAlertsAction extends NotificationAlertsContract
{
    public function do(User $User, array $input = []): ?JsonResponse
    {
        if (! app()->runningInConsole()) {
            $pools = ['black', 'toast', 'info', 'success', 'warning', 'danger'];
            $types = ['all', 'unread', 'read'];
            $Validator = Validator::make($input, [
                'age' => ['integer', 'max:120'],
                'pool' => ['string', Rule::in($types)],
                'type' => ['string', Rule::in($pools)],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray()], 404);
            }
            if (! isset($input['age']) || ! $input['age'] > 0) {
                $input['age'] = 7;
            }
            if (! isset($input['pool']) || ! in_array($input['pool'], $pools)) {
                $input['pool'] = 'all';
            }
            if (! isset($input['type']) || ! in_array($input['type'], $types)) {
                $input['type'] = '*';
            }
            $N = NotificationAlerts::where('user_id', $User->id);
            $interval = Carbon::now()->subDays($input['age']);
            $N = $N->whereDate('created_at', '>=', $interval);
            switch ($input['pool']) {
                case 'unread':
                    $N = $N->where('read_at', false);
                    break;
                case 'read':
                    $N = $N->where('read_at', true);
                    break;
            }
            switch ($input['type']) {
                case 'black':
                    $N = $N->where('type', 'black');
                    break;
                case 'toast':
                    $N = $N->where('type', 'toast');
                    break;
                case 'info':
                    $N = $N->where('type', 'info');
                    break;
                case 'success':
                    $N = $N->where('type', 'success');
                    break;
                case 'warning':
                    $N = $N->where('type', 'warning');
                    break;
                case 'danger':
                    $N = $N->where('type', 'danger');
                    break;
            }

            return response()->json($N->orderBy('created_at')->get()->toArray());
        }

        return null;
    }
}
