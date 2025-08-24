<?php

namespace App\Jobs;

use App\Mail\SubscriptionRenewedMail;
use App\Models\NotificationLog;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $subscriptionId) {}

    public function handle(): void
    {
        $subscription = Subscription::with('user')->find($this->subscriptionId);
        if (!$subscription) return;

        Mail::to($subscription->user->email)->send(new SubscriptionRenewedMail($subscription));

        NotificationLog::create([
            'user_id'         => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'type'            => 'renewal',
            'message'         => 'Notified user of successful renewal.',
            'sent_at'         => now(),
        ]);
    }
}
