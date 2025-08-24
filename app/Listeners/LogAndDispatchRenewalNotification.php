<?php

namespace App\Listeners;

use App\Events\SubscriptionRenewed;
use App\Jobs\SendNotificationJob;
use App\Models\NotificationLog;

class LogAndDispatchRenewalNotification
{
    public function handle(SubscriptionRenewed $event): void
    {
        // Log immediately (job will send the email)
        NotificationLog::create([
            'user_id'         => optional(optional(\App\Models\Subscription::find($event->subscriptionId))->user)->id,
            'subscription_id' => $event->subscriptionId,
            'type'            => 'renewal',
            'message'         => 'Subscription renewed event fired.',
            'sent_at'         => null,
        ]);

        SendNotificationJob::dispatch($event->subscriptionId);
    }
}
