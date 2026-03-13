<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'join_code',
        'phone',
        'address',
        'city',
        'state',
        'is_active',
        'queue_status',
        'queue_prefix',
        'current_number',
        'daily_limit',
        'entries_today',
        'notify_turns_before',
        'last_reset_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'      => 'boolean',
            'last_reset_at'  => 'datetime',
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function isOpen(): bool   { return $this->queue_status === 'open'; }
    public function isPaused(): bool { return $this->queue_status === 'paused'; }
    public function isClosed(): bool { return $this->queue_status === 'closed'; }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>=', now()->toDateString())
            ->exists();
    }

    public function isAtDailyLimit(): bool
    {
        return $this->entries_today >= $this->daily_limit;
    }

    public function needsReset(): bool
    {
        return is_null($this->last_reset_at) ||
               $this->last_reset_at->toDateString() < now()->toDateString();
    }

    public function resetQueue(): void
    {
        $this->update([
            'current_number' => 0,
            'entries_today'  => 0,
            'last_reset_at'  => now(),
            'queue_status'   => 'open',
        ]);
    }

    public function nextTicketNumber(): int
    {
        $this->increment('current_number');
        return $this->current_number;
    }

    public function avgServiceMinutes(): int
    {
        $avg = $this->queueEntries()
            ->where('status', 'done')
            ->whereNotNull('service_minutes')
            ->latest('done_at')
            ->limit(20)
            ->avg('service_minutes');

        return (int) round($avg ?? 5);
    }

    // ── Relationships ─────────────────────────────────────────────
    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class)->where('role', 'business_staff');
    }

    public function queueEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QueueEntry::class);
    }

    public function activeEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QueueEntry::class)
            ->whereIn('status', ['waiting', 'called', 'serving'])
            ->orderBy('position');
    }

    public function whatsappMessages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function qrCode(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(QrCode::class);
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function customerFeedback(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CustomerFeedback::class);
    }
}