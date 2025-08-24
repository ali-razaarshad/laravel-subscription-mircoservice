<?php

namespace App\Providers;

use App\Events\SubscriptionRenewed;
use App\Listeners\LogAndDispatchRenewalNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubscriptionRenewed::class => [
            LogAndDispatchRenewalNotification::class,
        ],
    ];
}
