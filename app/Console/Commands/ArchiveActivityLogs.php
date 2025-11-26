<?php

namespace App\Console\Commands;

use App\Models\ActivityLogArchive;
use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class ArchiveActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:archive {--days=90 : Archive logs older than N days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive activity logs older than specified days to prevent database bloat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("🔄 Archiving activity logs older than {$days} days (before {$cutoffDate->format('Y-m-d')})...");

        // Get activities to archive
        $activitiesToArchive = Activity::where('created_at', '<', $cutoffDate)->get();

        if ($activitiesToArchive->isEmpty()) {
            $this->info('✓ No logs to archive.');
            return Command::SUCCESS;
        }

        $this->info("Found {$activitiesToArchive->count()} logs to archive.");

        $bar = $this->output->createProgressBar($activitiesToArchive->count());
        $bar->start();

        // Archive each activity
        foreach ($activitiesToArchive as $activity) {
            ActivityLogArchive::create([
                'activity_id' => $activity->id,
                'log_type' => 'scheduled',
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
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $count = $activitiesToArchive->count();
        $this->info("✓ Successfully archived {$count} activity logs!");

        return Command::SUCCESS;
    }
}
