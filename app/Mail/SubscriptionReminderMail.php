<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription) {}

    public function build() {
        return $this->subject('Your subscription is expiring soon')
            ->markdown('mail.subscription-reminder', [
                'subscription' => $this->subscription,
            ]);
    }
}
