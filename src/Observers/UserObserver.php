<?php

namespace IOF\DiscreteApi\Base\Observers;

use Illuminate\Support\Str;
use App\Models\User;

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
        // ROLE
        if (User::all()->count() == 1) {
            $model->assignRole(config('discreteapibase.roles.super_role'));
            $model->assignRole(config('discreteapibase.roles.admin_role'));
            $model->assignRole(config('discreteapibase.roles.support_role'));
            $model->assignRole(config('discreteapibase.roles.user_role'));
        } else {
            $model->assignRole(config('discreteapibase.roles.default_role'));
        }
        // PROFILE (IF ENABLED)
        if (config('discreteapibase.features.profile') === true) {
            $model->profile()->create([]);
        }
        // ORGANIZATION (IF ENABLED)
    }
}
