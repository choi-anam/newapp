<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLogSetting extends Model
{
    protected $fillable = ['model_class', 'enabled', 'tracked_attributes'];

    protected $casts = [
        'tracked_attributes' => 'array',
        'enabled' => 'boolean',
    ];
}
