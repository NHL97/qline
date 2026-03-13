<?php

namespace App\Filament\Business;

use App\Models\Business;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BusinessSettings extends Page
{
    protected string $view = 'filament.business.business-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?int $navigationSort = 5;

    // Only business_owner can access
    public static function canAccess(): bool
    {
        return Auth::user()?->isBusinessOwner();
    }

    // Business details
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $city = '';
    public string $state = '';

    // Queue settings
    public string $queue_prefix = 'Q';
    public int $daily_limit = 100;
    public int $notify_turns_before = 3;

    public function mount(): void
    {
        $business = Auth::user()->business;

        $this->name                 = $business->name;
        $this->phone                = $business->phone ?? '';
        $this->address              = $business->address ?? '';
        $this->city                 = $business->city ?? '';
        $this->state                = $business->state ?? '';
        $this->queue_prefix         = $business->queue_prefix;
        $this->daily_limit          = $business->daily_limit;
        $this->notify_turns_before  = $business->notify_turns_before;
    }

    public function saveBusinessDetails(): void
    {
        $this->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city'    => 'nullable|string|max:100',
            'state'   => 'nullable|string|max:100',
        ]);

        Auth::user()->business->update([
            'name'    => $this->name,
            'phone'   => $this->phone,
            'address' => $this->address,
            'city'    => $this->city,
            'state'   => $this->state,
        ]);

        Notification::make()->title('Business details saved')->success()->send();
    }

    public function saveQueueSettings(): void
    {
        $this->validate([
            'queue_prefix'        => 'required|string|max:5',
            'daily_limit'         => 'required|integer|min:1|max:1000',
            'notify_turns_before' => 'required|integer|min:1|max:20',
        ]);

        Auth::user()->business->update([
            'queue_prefix'        => strtoupper($this->queue_prefix),
            'daily_limit'         => $this->daily_limit,
            'notify_turns_before' => $this->notify_turns_before,
        ]);

        Notification::make()->title('Queue settings saved')->success()->send();
    }
}