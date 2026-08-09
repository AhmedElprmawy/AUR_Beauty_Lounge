<?php

namespace App\Console\Commands;

use App\Models\BookingReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanOldReminders extends Command
{
    protected $signature = 'reminders:clean {--days=30 : Delete reminders older than X days}';
    protected $description = 'Clean old booking reminders';

    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoff = Carbon::now()->subDays($days);

        $count = BookingReminder::where('created_at', '<', $cutoff)->delete();

        $this->info("🗑️ Deleted {$count} reminders older than {$days} days.");
    }
}