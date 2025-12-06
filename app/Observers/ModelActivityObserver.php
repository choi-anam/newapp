<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ModelActivityObserver
{
    protected function causer()
    {
        return Auth::user();
    }

    protected function getIpAndUserAgent()
    {
        return [
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];
    }

    public function created($model)
    {
        activity()
            ->causedBy($this->causer())
            ->performedOn($model)
            ->withProperties(['attributes' => $this->attributesFor($model), ...$this->getIpAndUserAgent()])
            ->log('created ' . class_basename($model));
    }

    public function updated($model)
    {
        $old = $model->getOriginal();
        activity()
            ->causedBy($this->causer())
            ->performedOn($model)
            ->withProperties(['old' => $old, 'changes' => $model->getChanges(), 'attributes' => $this->attributesFor($model), ...$this->getIpAndUserAgent()])
            ->log('updated ' . class_basename($model));
    }

    public function deleted($model)
    {
        $attrs = $model->getOriginal();
        activity()
            ->causedBy($this->causer())
            ->withProperties(['attributes' => $attrs, ...$this->getIpAndUserAgent()])
            ->log('deleted ' . class_basename($model));
    }

    public function restored($model)
    {
        activity()
            ->causedBy($this->causer())
            ->performedOn($model)
            ->withProperties(['attributes' => $this->attributesFor($model), ...$this->getIpAndUserAgent()])
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
