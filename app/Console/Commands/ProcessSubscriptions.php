<?php

namespace App\Console\Commands;

use App\Jobs\RenewSubscriptionJob;
use App\Jobs\SendReminderJob;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ProcessSubscriptions extends Command
{
    protected $signature = 'subscriptions:process {--days=3}';
    protected $description = 'Process expiring subscriptions (reminders & auto-renew)';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        // Queue reminders for those expiring within N days
        Subscription::expiringWithinDays($days)
            ->with(['user'])
            ->chunkById(500, function ($subs) {
                foreach ($subs as $subscription) {
                    SendReminderJob::dispatch($subscription->id);
                }
            });

        // Queue renewals for active, auto_renew = true that end **today**
        Subscription::query()
            ->where('is_active', true)
            ->where('auto_renew', true)
            ->whereDate('end_date', now()->toDateString())
            ->chunkById(500, function ($subs) {
                foreach ($subs as $subscription) {
                    RenewSubscriptionJob::dispatch($subscription->id);
                }
            });

        $this->info('Subscription processing queued.');
        return self::SUCCESS;
    }
}
