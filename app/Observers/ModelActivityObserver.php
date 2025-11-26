<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class ModelActivityObserver
{
    protected function causer()
    {
        return Auth::user();
    }

    public function created($model)
    {
        activity()
            ->causedBy($this->causer())
            ->performedOn($model)
            ->withProperties(['attributes' => $this->attributesFor($model)])
            ->log('created ' . class_basename($model));
    }

    public function updated($model)
    {
        $old = $model->getOriginal();
        activity()
            ->causedBy($this->causer())
            ->performedOn($model)
            ->withProperties(['old' => $old, 'changes' => $model->getChanges(), 'attributes' => $this->attributesFor($model)])
            ->log('updated ' . class_basename($model));
    }

    public function deleted($model)
    {
        $attrs = $model->getOriginal();
        activity()
            ->causedBy($this->causer())
            ->withProperties(['attributes' => $attrs])
            ->log('deleted ' . class_basename($model));
    }

    public function restored($model)
    {
        activity()
            ->causedBy($this->causer())
            ->performedOn($model)
            ->withProperties(['attributes' => $this->attributesFor($model)])
            ->log('restored ' . class_basename($model));
    }

    protected function attributesFor($model)
    {
        $attrs = config('activity-logger.attributes');
        if ($attrs === null) {
            return $model->getAttributes();
        }

        return collect($model->getAttributes())->only($attrs)->toArray();
    }
}
