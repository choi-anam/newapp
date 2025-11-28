<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityLogsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Activity::with(['causer', 'subject'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Log Name',
            'Description',
            'Subject Type',
            'Subject ID',
            'Event',
            'Causer Type',
            'Causer ID',
            'Causer Name',
            'Properties',
            'Created At',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->log_name,
            $activity->description,
            $activity->subject_type,
            $activity->subject_id,
            $activity->event,
            $activity->causer_type,
            $activity->causer_id,
            $activity->causer ? $activity->causer->name : 'System',
            $activity->properties->toJson(),
            $activity->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
