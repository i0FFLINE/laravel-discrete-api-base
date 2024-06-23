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
        // INIT
        $profile = [];
        // ROLE
        if (User::all()->count() == 1) {
            $model->assignRole(config('discreteapibase.roles.super_role'));
            $model->assignRole(config('discreteapibase.roles.admin_role'));
            $model->assignRole(config('discreteapibase.roles.support_role'));
            $model->assignRole(config('discreteapibase.roles.user_role'));
        } else {
            $model->assignRole(config('discreteapibase.roles.default_role'));
        }
        // ORGANIZATION (IF ENABLED)
        $Organization = DiscreteApiHelper::new_organization($model);
        if (!is_null($Organization)) {
            $profile['organization_id'] = $Organization->id;
            $profile['workspace_id'] = $Organization->workspaces()->first()->id;
        }
        // FIRST-TIME LOCALE
        $headers_locale = request()->headers->get('Accept-Language', 'en');
        if (!is_null($headers_locale) && in_array($headers_locale, array_keys(config('discreteapibase.locales')))) {
            $profile['locale'] = $headers_locale;
        }
        // PROFILE (IF ENABLED)
        if (config('discreteapibase.features.profile') === true) {
            $model->profile()->create($profile);
        }
    }
}
