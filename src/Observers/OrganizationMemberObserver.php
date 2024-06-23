<?php

namespace IOF\DiscreteApi\Base\Observers;

use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Models\OrganizationMember as Model;

class OrganizationMemberObserver
{
    public function creating(Model $model): void
    {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        }
    }

    public function updating(Model $model): void
    {
        $model->updated_by = request()->user()->id;
    }

}
