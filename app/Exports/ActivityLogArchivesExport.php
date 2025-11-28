<?php

namespace App\Exports;

use App\Models\ActivityLogArchive;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityLogArchivesExport implements FromCollection, WithHeadings, WithMapping
{
    protected ?string $logType;
    protected ?string $dateFrom;
    protected ?string $dateTo;

    public function __construct(?string $logType = null, ?string $dateFrom = null, ?string $dateTo = null)
    {
        $this->logType = $logType;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = ActivityLogArchive::query()->latest();
        if (!empty($this->logType)) {
            $query->where('log_type', $this->logType);
        }
        if (!empty($this->dateFrom)) {
            $query->whereDate('archived_at', '>=', $this->dateFrom);
        }
        if (!empty($this->dateTo)) {
            $query->whereDate('archived_at', '<=', $this->dateTo);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Archive ID',
            'Activity ID',
            'Archived At',
            'Description',
            'Model',
            'Model Class',
            'Model ID',
            'Event',
            'Causer Name',
            'Causer ID',
            'Log Type',
            'Original Created At',
            'Batch UUID',
            'Properties (JSON)',
        ];
    }

    public function map($archive): array
    {
        $data = $archive->activity_data ?? [];
        $causerName = null;
        if (!empty($data['causer_id'])) {
            $user = \App\Models\User::find($data['causer_id']);
            $causerName = $user?->name ?: 'Deleted';
        } else {
            $causerName = 'System';
        }

        return [
            $archive->id,
            $archive->activity_id,
            optional($archive->archived_at)->format('Y-m-d H:i:s'),
            $data['description'] ?? null,
            $data['subject_type'] ? class_basename($data['subject_type']) : null,
            $data['subject_type'] ?? null,
            $data['subject_id'] ?? null,
            $data['event'] ?? null,
            $causerName,
            $data['causer_id'] ?? null,
            $archive->log_type,
            isset($data['created_at']) ? (string) $data['created_at'] : null,
            $data['batch_uuid'] ?? null,
            isset($data['properties']) ? json_encode($data['properties'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
        ];
    }
}
