<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription) {}

    public function build() {
        return $this->subject('Your subscription has been renewed')
            ->markdown('mail.subscription-renewed', [
                'subscription' => $this->subscription,
            ]);
    }
}
