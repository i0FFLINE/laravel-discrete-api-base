<?php

namespace IOF\DiscreteApi\Base\Observers;

use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Models\Workspace as Model;

class WorkspaceObserver
{
    public function creating(Model $model): void
    {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        }
    }
}
