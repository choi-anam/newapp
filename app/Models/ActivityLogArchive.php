<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class ActivityLogArchive extends Model
{
    protected $fillable = ['activity_id', 'log_type', 'activity_data', 'archived_at'];

    protected $casts = [
        'activity_data' => 'json',
        'archived_at' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
