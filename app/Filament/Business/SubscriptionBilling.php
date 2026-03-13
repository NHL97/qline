<?php

namespace App\Filament\Business;

use App\Models\Subscription;
use App\Models\Payment;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class SubscriptionBilling extends Page
{
    protected string $view = 'filament.business.subscription-billing';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'Subscription';

    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        return Auth::user()?->isBusinessOwner();
    }

    public ?Subscription $activeSubscription = null;
    public $recentPayments = [];
    public bool $hasActive = false;

    public function mount(): void
    {
        $businessId = Auth::user()->business_id;

        $this->activeSubscription = Subscription::where('business_id', $businessId)
            ->where('status', 'active')
            ->where('expires_at', '>=', now()->toDateString())
            ->latest('expires_at')
            ->first();

        $this->hasActive = $this->activeSubscription !== null;

        $this->recentPayments = Payment::where('business_id', $businessId)
            ->where('status', 'paid')
            ->latest('paid_at')
            ->take(5)
            ->get()
            ->toArray();
    }
}