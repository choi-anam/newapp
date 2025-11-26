<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogArchive;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of activity logs (admin only).
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer');

        // Filter by causer (user id)
        if ($request->filled('user')) {
            $query->where('causer_id', $request->get('user'));
        }

        // Filter by model (accept short name like 'User' or full class)
        if ($request->filled('model')) {
            $modelParam = $request->get('model');
            // Get mapping of short => full
            $rawModels = Activity::select('subject_type')->distinct()->pluck('subject_type')->filter()->values();
            $map = [];
            foreach ($rawModels as $m) {
                if (! $m) continue;
                $map[class_basename($m)] = $m;
            }

            if (isset($map[$modelParam])) {
                $query->where('subject_type', $map[$modelParam]);
            } else {
                // allow full class name passed directly
                $query->where('subject_type', $modelParam);
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $activities = $query->latest()->paginate(20)->withQueryString();

        // For filter form options
        $users = \App\Models\User::orderBy('name')->get();

        // Build model options mapping shortName => fullClass
        $rawModels = Activity::select('subject_type')->distinct()->pluck('subject_type')->filter()->values();
        $modelOptions = collect();
        foreach ($rawModels as $m) {
            if (! $m) continue;
            $short = class_basename($m);
            // Avoid overwriting if multiple full classes share the same short name
            if (! $modelOptions->has($short)) {
                $modelOptions->put($short, $m);
            }
        }

        return view('admin.activities.index', compact('activities', 'users', 'modelOptions'));
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

        $archives = $query->paginate(20)->withQueryString();

        return view('admin.activities.archives', compact('archives'));
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
}
