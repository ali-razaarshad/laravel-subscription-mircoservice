<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id', 'status', 'run_at', 'message',
    ];

    protected $casts = [
        'run_at' => 'datetime',
    ];

    public function subscription() {
        return $this->belongsTo(Subscription::class);
    }
}
