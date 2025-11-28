<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogArchive;
use App\Exports\ActivityLogsExport;
use App\Exports\ActivityLogArchivesExport;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{
    /**
     * Display a listing of activity logs (admin only).
     */
    public function index(Request $request)
    {
        // For filter form options
        $users = \App\Models\User::orderBy('name')->get();
        // Distinct model list based on subject_type (use class basename)
        $models = Activity::whereNotNull('subject_type')
            ->pluck('subject_type')
            ->map(function ($t) { return class_basename($t); })
            ->unique()
            ->values();

        return view('admin.activities.index-gridjs', compact('users', 'models'));
    }

    /**
     * Get activities data for Grid.js (AJAX)
     */
    public function getData(Request $request)
    {
        $query = Activity::with('causer');
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhereHas('causer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by user
        if ($request->filled('user')) {
            $query->where('causer_id', $request->get('user'));
        }
        
        // Filter by model (subject_type basename)
        if ($request->filled('model')) {
            $model = $request->get('model');
            $query->whereNotNull('subject_type')
                ->where('subject_type', 'like', "%{$model}%");
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        // Pagination - Grid.js uses 0-based page indexing
        $page = max(0, $request->get('page', 0));
        $limit = $request->get('limit', 20);
        $offset = $page * $limit;
        
        $total = $query->count();
        $activities = $query->latest()->skip($offset)->take($limit)->get();
        
        $data = $activities->map(function($activity) {
            $eventColors = [
                'created' => 'success',
                'updated' => 'info',
                'deleted' => 'danger',
                'restored' => 'warning'
            ];
            $color = $eventColors[$activity->event] ?? 'secondary';
            
            return [
                $activity->id,
                $activity->created_at->diffForHumans(),
                $activity->causer ? $activity->causer->name : 'System',
                $activity->description,
                class_basename($activity->subject_type ?? ''),
                '<span class="badge bg-' . $color . '">' . ucfirst($activity->event) . '</span>',
            ];
        });
        
        return response()->json([
            'data' => $data,
            'total' => $total,
        ]);
    }

    /**
     * Export activities to Excel
     */
    public function export()
    {
        return Excel::download(new ActivityLogsExport, 'activity-logs-' . date('Y-m-d-His') . '.xlsx');
    }

    /**
     * Display a specific activity
     */
    public function show(Activity $activity)
    {
        $activity->load('causer');
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Show log management page
     */
    public function management()
    {
        $totalLogs = Activity::count();
        $logsToday = Activity::whereDate('created_at', now()->toDateString())->count();
        $logsThisMonth = Activity::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $archivedCount = ActivityLogArchive::count();

        // Get oldest activity
        $oldestActivity = Activity::oldest()->first();

        return view('admin.activities.management', compact(
            'totalLogs',
            'logsToday',
            'logsThisMonth',
            'archivedCount',
            'oldestActivity'
        ));
    }

    /**
     * Archive activities older than specified days
     */
    public function archive(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $days = $request->get('days');
        $cutoffDate = now()->subDays($days);

        // Get activities to archive
        $activitiesToArchive = Activity::where('created_at', '<', $cutoffDate)->get();

        if ($activitiesToArchive->isEmpty()) {
            return back()->with('info', 'Tidak ada log yang perlu diarsipkan.');
        }

        // Archive each activity
        foreach ($activitiesToArchive as $activity) {
            ActivityLogArchive::create([
                'activity_id' => $activity->id,
                'log_type' => 'manual',
                'activity_data' => [
                    'description' => $activity->description,
                    'subject_type' => $activity->subject_type,
                    'subject_id' => $activity->subject_id,
                    'causer_type' => $activity->causer_type,
                    'causer_id' => $activity->causer_id,
                    'properties' => $activity->properties,
                    'created_at' => $activity->created_at,
                    'batch_uuid' => $activity->batch_uuid,
                    'event' => $activity->event,
                ],
            ]);

            // Delete the activity
            $activity->delete();
        }

        $count = $activitiesToArchive->count();
        return back()->with('success', "Berhasil mengarsipkan {$count} log aktivitas.");
    }

    /**
     * Delete activities older than specified days
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $days = $request->get('days');
        $cutoffDate = now()->subDays($days);

        $count = Activity::where('created_at', '<', $cutoffDate)->delete();

        if ($count === 0) {
            return back()->with('info', 'Tidak ada log yang perlu dihapus.');
        }

        return back()->with('success', "Berhasil menghapus {$count} log aktivitas.");
    }

    /**
     * Delete all activities (permanent)
     */
    public function truncate(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:yes',
        ]);

        $count = Activity::count();
        Activity::truncate();

        return back()->with('success', "Berhasil menghapus semua {$count} log aktivitas.");
    }

    /**
     * View archived logs
     */
    public function archives(Request $request)
    {
        $query = ActivityLogArchive::latest();

        if ($request->filled('log_type')) {
            $query->where('log_type', $request->get('log_type'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('archived_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('archived_at', '<=', $request->get('date_to'));
        }

        $archives = $query->paginate(20)->withQueryString();

        return view('admin.activities.archives', compact('archives'));
    }

    /**
     * Export archived logs to Excel
     */
    public function exportArchives(Request $request)
    {
        $logType = $request->get('log_type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $filename = 'activity-archives' . ($logType ? "-{$logType}" : '') . '-' . date('Y-m-d-His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new ActivityLogArchivesExport($logType, $dateFrom, $dateTo), $filename);
    }

    /**
     * Restore archived activity
     */
    public function restoreArchive(ActivityLogArchive $archive)
    {
        if ($archive->activity_data) {
            Activity::create([
                'description' => $archive->activity_data['description'] ?? null,
                'subject_type' => $archive->activity_data['subject_type'] ?? null,
                'subject_id' => $archive->activity_data['subject_id'] ?? null,
                'causer_type' => $archive->activity_data['causer_type'] ?? null,
                'causer_id' => $archive->activity_data['causer_id'] ?? null,
                'properties' => $archive->activity_data['properties'] ?? [],
                'batch_uuid' => $archive->activity_data['batch_uuid'] ?? null,
                'event' => $archive->activity_data['event'] ?? null,
            ]);
        }

        $archive->delete();

        return back()->with('success', 'Log berhasil dipulihkan dari arsip.');
    }

    /**
     * Permanently delete an archived activity
     */
    public function destroyArchive(ActivityLogArchive $archive)
    {
        $archive->delete();
        return back()->with('success', 'Arsip berhasil dihapus.');
    }

    /**
     * Bulk delete archives based on filters
     */
    public function bulkDeleteArchives(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:yes',
        ], [
            'confirm.required' => 'Konfirmasi diperlukan.',
            'confirm.in' => 'Konfirmasi tidak valid.',
        ]);

        $query = ActivityLogArchive::query();

        if ($request->filled('log_type')) {
            $query->where('log_type', $request->get('log_type'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('archived_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('archived_at', '<=', $request->get('date_to'));
        }

        $count = (clone $query)->count();
        $query->delete();

        return back()->with('success', "Berhasil menghapus {$count} arsip sesuai filter.");
    }

    /**
     * Bulk restore archives based on filters
     */
    public function bulkRestoreArchives(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:yes',
        ]);

        $query = ActivityLogArchive::query();

        if ($request->filled('log_type')) {
            $query->where('log_type', $request->get('log_type'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('archived_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('archived_at', '<=', $request->get('date_to'));
        }

        $archives = $query->get();
        $count = 0;
        foreach ($archives as $archive) {
            if ($archive->activity_data) {
                Activity::create([
                    'description' => $archive->activity_data['description'] ?? null,
                    'subject_type' => $archive->activity_data['subject_type'] ?? null,
                    'subject_id' => $archive->activity_data['subject_id'] ?? null,
                    'causer_type' => $archive->activity_data['causer_type'] ?? null,
                    'causer_id' => $archive->activity_data['causer_id'] ?? null,
                    'properties' => $archive->activity_data['properties'] ?? [],
                    'batch_uuid' => $archive->activity_data['batch_uuid'] ?? null,
                    'event' => $archive->activity_data['event'] ?? null,
                ]);
            }
            $archive->delete();
            $count++;
        }

        return back()->with('success', "Berhasil memulihkan {$count} arsip sesuai filter.");
    }
}
