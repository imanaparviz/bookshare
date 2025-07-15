<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule loan reminders to run daily at 9:00 AM
Schedule::command('loans:send-reminders')
    ->dailyAt('09:00')
    ->timezone('Europe/Berlin')
    ->description('Send daily loan reminder notifications');

// Optional: Schedule a weekly summary (could be added later)
// Schedule::command('loans:weekly-summary')
//     ->weeklyOn(1, '10:00')
//     ->timezone('Europe/Berlin')
//     ->description('Send weekly loan summary to administrators');
