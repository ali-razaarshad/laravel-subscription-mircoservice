<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class RenewSubscriptionAction
{
    public function execute(Subscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {
            $subscription->extendOneMonth();
            $subscription->save();
        });
    }
}
