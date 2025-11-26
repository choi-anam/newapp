<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class CleanupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup {--days=365 : Delete logs older than N days} {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete archived and old activity logs (use with caution!)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $force = $this->option('force');
        $cutoffDate = now()->subDays($days);

        $this->warn("⚠️  This will PERMANENTLY DELETE logs older than {$days} days!");
        $this->info("Cutoff date: {$cutoffDate->format('Y-m-d H:i:s')}");

        // Get count first
        $count = Activity::where('created_at', '<', $cutoffDate)->count();

        if ($count === 0) {
            $this->info('✓ No logs to delete.');
            return Command::SUCCESS;
        }

        $this->info("Found {$count} logs to delete.");

        if (!$force && !$this->confirm("Are you sure you want to permanently delete {$count} activity logs?")) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $this->info("🗑️  Deleting activity logs older than {$days} days...");

        $deleted = Activity::where('created_at', '<', $cutoffDate)->delete();

        $this->info("✓ Successfully deleted {$deleted} activity logs!");

        return Command::SUCCESS;
    }
}
