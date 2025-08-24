<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'subscription_id', 'type', 'message', 'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function subscription() {
        return $this->belongsTo(Subscription::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
