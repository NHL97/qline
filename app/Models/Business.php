<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\QueueEntry;

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
        'postcode',
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
        'pause_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_reset_at' => 'datetime',
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function isOpen(): bool
    {
        return $this->queue_status === 'open';
    }

    public function isPaused(): bool
    {
        return $this->queue_status === 'paused';
        
    }

    public function isClosed(): bool
    {
        return $this->queue_status === 'closed';
    }

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
            'entries_today' => 0,
            'last_reset_at' => now(),
            'queue_status' => 'open',
        ]);
    }

    public function nextTicketNumber(): int
    {
        $this->increment('current_number');

        return $this->current_number;
    }

    public function avgServiceMinutes(): int
    {
        $avg = QueueEntry::where('business_id', $this->id)
            ->where('status', 'done')
            ->whereNotNull('service_minutes')
            ->latest('done_at')
            ->limit(20)
            ->avg('service_minutes');

        return (int) round($avg ?? 5);
    }

    // ── Relationships ─────────────────────────────────────────────
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'business_staff');
    }

    public function queueEntries(): HasMany
    {
        return $this->hasMany(QueueEntry::class);
    }

    public function activeEntries(): HasMany
    {
        return $this->hasMany(QueueEntry::class)
            ->whereIn('status', ['waiting', 'called', 'serving'])
            ->orderBy('position');
    }

    public function whatsappMessages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function customerFeedback(): HasMany
    {
        return $this->hasMany(CustomerFeedback::class);
    }
}
