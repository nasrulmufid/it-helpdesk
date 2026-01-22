<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Clean up old notifications (older than 30 days)
Schedule::call(function () {
    $deleted = DB::table('notifications')
        ->where('created_at', '<', now()->subDays(30))
        ->delete();

    info("Deleted {$deleted} old notifications");
})->daily()->at('02:00');
