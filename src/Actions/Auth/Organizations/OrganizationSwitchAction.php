<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationSwitchContract;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;
use IOF\DiscreteApi\Base\Models\Organization;

class OrganizationSwitchAction extends OrganizationSwitchContract
{
    public function do(User $User, Organization $Organization): Response|JsonResponse|null
    {
        if (!app()->runningInConsole()) {
            if (is_null($User->profile)) {
                DiscreteApiHelper::create_user_profile($User, [
                    'locale' => DiscreteApiHelper::compute_locale()
                ]);
            }
            Gate::forUser($User)->authorize('view', $Organization);
            $User->profile->forceFill([config('discreteapibase.organization.singular_name').'_id' => $Organization->id,])->save();
            $User->load(['profile.organization']);
            return response()->noContent();
        }
        return null;
    }
}
