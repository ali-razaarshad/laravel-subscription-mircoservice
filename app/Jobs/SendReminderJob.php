<?php

namespace App\Jobs;

use App\Mail\SubscriptionReminderMail;
use App\Models\NotificationLog;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $subscriptionId) {}

    public function handle(): void
    {
        $subscription = Subscription::with('user')->find($this->subscriptionId);
        if (!$subscription || !$subscription->is_active) {
            return;
        }

        Mail::to($subscription->user->email)->send(new SubscriptionReminderMail($subscription));

        NotificationLog::create([
            'user_id'        => $subscription->user_id,
            'subscription_id'=> $subscription->id,
            'type'           => 'reminder',
            'message'        => 'Reminder email queued/sent for expiring subscription.',
            'sent_at'        => now(),
        ]);
    }
}
