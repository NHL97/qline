<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\BusinessScope;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'queue_entry_id',
        'wa_id',
        'direction',
        'template',
        'body',
        'message_id',
        'status',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function queueEntry(): BelongsTo
    {
        return $this->belongsTo(QueueEntry::class);
    }

    

    protected static function booted(): void
    {
        static::addGlobalScope(new BusinessScope);
    }
}
