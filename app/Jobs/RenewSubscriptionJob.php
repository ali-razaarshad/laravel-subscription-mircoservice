<?php

namespace App\Jobs;

use App\Events\SubscriptionRenewed;
use App\Models\RenewalLog;
use App\Models\Subscription;
use App\Services\RenewSubscriptionAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class RenewSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $subscriptionId) {}

    public function handle(RenewSubscriptionAction $action): void
    {
        $subscription = Subscription::with('user')->find($this->subscriptionId);
        if (!$subscription || !$subscription->is_active) {
            return;
        }

        // Mocked card valid = true (could be toggled by config/flag if needed)
        $cardValid = true;

        if (! $subscription->auto_renew) {
            RenewalLog::create([
                'subscription_id' => $subscription->id,
                'status'          => 'skipped',
                'run_at'          => now(),
                'message'         => 'Auto renew disabled.',
            ]);
            return;
        }

        try {
            if (!$cardValid) {
                throw new \RuntimeException('Payment authorization failed (mock).');
            }

            $action->execute($subscription);

            RenewalLog::create([
                'subscription_id' => $subscription->id,
                'status'          => 'success',
                'run_at'          => now(),
                'message'         => 'Renewed successfully.',
            ]);

            // Fire event for listeners
            SubscriptionRenewed::dispatch($subscription->id);

        } catch (Throwable $e) {
            RenewalLog::create([
                'subscription_id' => $subscription->id,
                'status'          => 'failed',
                'run_at'          => now(),
                'message'         => $e->getMessage(),
            ]);
            throw $e; // Let the queue’s retry/failed-jobs handle it
        }
    }
}
