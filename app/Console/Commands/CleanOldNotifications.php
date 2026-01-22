<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanOldNotifications extends Command
{
    protected $signature = 'notifications:clean {--days=30 : Number of days to keep notifications}';

    protected $description = 'Delete old notifications that are older than specified days';

    public function handle()
    {
        $days = $this->option('days');

        $this->info("Cleaning notifications older than {$days} days...");

        $deleted = DB::table('notifications')
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info("✓ Deleted {$deleted} old notifications");

        return Command::SUCCESS;
    }
}
