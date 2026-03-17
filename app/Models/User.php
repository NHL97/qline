<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'business_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ── Filament access gate ──────────────────────────────────────
    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return match ($panel->getId()) {
            'admin' => in_array($this->role, ['superadmin', 'qline_staff']),
            'business' => in_array($this->role, ['business_owner', 'business_staff']),
            default => false,
        };
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isQlineStaff(): bool
    {
        return $this->role === 'qline_staff';
    }

    public function isBusinessOwner(): bool
    {
        return $this->role === 'business_owner';
    }

    public function isBusinessStaff(): bool
    {
        return $this->role === 'business_staff';
    }

    public function isAdminPanel(): bool
    {
        return in_array($this->role, ['superadmin', 'qline_staff']);
    }

    public function isBusinessPanel(): bool
    {
        return in_array($this->role, ['business_owner', 'business_staff']);
    }

    // ── Relationships ─────────────────────────────────────────────
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function ownedBusiness(): HasOne
    {
        return $this->hasOne(Business::class, 'user_id');
    }

    public function canBeImpersonated(): bool
    {
        return in_array($this->role, ['business_owner', 'business_staff']);
    }

    public function canImpersonate(): bool
    {
        return $this->role === 'superadmin';
    }
}
