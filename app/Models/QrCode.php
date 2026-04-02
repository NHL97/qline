<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\BusinessScope;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'label',
        'url',
        'image_path',
        'scan_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/'.str_replace('public/', '', $this->image_path));
    }

    

    protected static function booted(): void
    {
        static::addGlobalScope(new BusinessScope);
    }
}
