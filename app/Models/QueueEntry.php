<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'wa_id',
        'ticket_number',
        'ticket_code',
        'status',
        'source',
        'position',
        'joined_at',
        'called_at',
        'served_at',
        'done_at',
        'wait_minutes',
        'service_minutes',
    ];

    protected function casts(): array
    {
        return [
            'joined_at'  => 'datetime',
            'called_at'  => 'datetime',
            'served_at'  => 'datetime',
            'done_at'    => 'datetime',
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function isWaiting(): bool  { return $this->status === 'waiting'; }
    public function isCalled(): bool   { return $this->status === 'called'; }
    public function isServing(): bool  { return $this->status === 'serving'; }
    public function isDone(): bool     { return $this->status === 'done'; }
    public function isAnonymous(): bool { return is_null($this->wa_id); }
    public function hasWhatsApp(): bool { return !is_null($this->wa_id); }

    public function isActive(): bool
    {
        return in_array($this->status, ['waiting', 'called', 'serving']);
    }

    // ── Relationships ─────────────────────────────────────────────
    public function business(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function whatsappMessages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function feedback(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CustomerFeedback::class);
    }
}