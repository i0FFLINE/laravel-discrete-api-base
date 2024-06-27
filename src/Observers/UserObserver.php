<?php

namespace IOF\DiscreteApi\Base\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;

class UserObserver
{
    public function creating(User $model): void
    {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        }
    }

    public function created(User $model): void
    {
        // LOCALE
        $profile['locale'] = DiscreteApiHelper::compute_locale();
        // ROLE
        if (User::all()->count() == 1) {
            // first user always root admin
            $model->assignRole(config('discreteapibase.roles.super_role'));
            $model->assignRole(config('discreteapibase.roles.admin_role'));
            $model->assignRole(config('discreteapibase.roles.support_role'));
            $model->assignRole(config('discreteapibase.roles.user_role'));
        } else {
            $model->assignRole(config('discreteapibase.roles.default_role'));
        }
        // ORGANIZATION
        if (config('discreteapibase.features.organizations') === true) {
            $Organization = DiscreteApiHelper::new_organization($model);
            if (!is_null($Organization)) {
                $profile[config('discreteapibase.organization.singular_name') . '_id'] = $Organization->id;
            }
        }
        // PROFILE
        if (config('discreteapibase.features.profile') === true) {
            DiscreteApiHelper::create_user_profile($model, $profile);
        }
    }
}
