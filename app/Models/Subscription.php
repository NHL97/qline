<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'type',
        'status',
        'starts_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'  => 'date',
            'expires_at' => 'date',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active' &&
               $this->expires_at->greaterThanOrEqualTo(now());
    }

    public function business(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }
}