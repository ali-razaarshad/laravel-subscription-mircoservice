<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan_name', 'start_date', 'end_date', 'is_active', 'auto_renew',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
        'auto_renew' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function renewalLogs() {
        return $this->hasMany(RenewalLog::class);
    }

    public function notificationLogs() {
        return $this->hasMany(NotificationLog::class);
    }

    /** Scope: active ones expiring within N days */
    public function scopeExpiringWithinDays(Builder $q, int $days): Builder {
        $now = now()->startOfDay();
        $to  = now()->addDays($days)->endOfDay();
        return $q->where('is_active', true)
            ->whereBetween('end_date', [$now, $to]);
    }

    /** Extend by one calendar month, no overflow (e.g., Jan 31 -> Feb 29/28). */
    public function extendOneMonth(): void {
        $anchor = $this->end_date->isPast() ? now() : $this->end_date;
        $this->end_date = $anchor->copy()->addMonthNoOverflow()->startOfDay();
    }
}
