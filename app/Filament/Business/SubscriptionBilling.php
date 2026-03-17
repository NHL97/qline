<?php

namespace App\Filament\Business;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\BillPlzService;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SubscriptionBilling extends Page
{
    protected string $view = 'filament.business.subscription-billing';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Account';

    protected static ?string $navigationLabel = 'Subscription';

    protected static ?int $navigationSort = 1; // Just before Settings

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

    public function subscribe(string $type): void
    {
        $business = Auth::user()->business;
        $owner = $business->owner;

        $amount = $type === 'daily' ? 1500 : 40000; // cents
        $amountRm = $type === 'daily' ? 15.00 : 400.00;
        $description = "QLine {$type} subscription — {$business->name}";

        // Create subscription record
        $subscription = Subscription::create([
            'business_id' => $business->id,
            'type' => $type,
            'status' => 'pending',
            'starts_at' => now()->toDateString(),
            'expires_at' => $type === 'daily'
                             ? now()->toDateString()
                             : now()->addMonth()->toDateString(),
        ]);

        // Create payment record
        $payment = Payment::create([
            'business_id' => $business->id,
            'subscription_id' => $subscription->id,
            'amount' => $amountRm,
            'currency' => 'MYR',
            'method' => 'fpx',
            'status' => 'pending',
        ]);

        // Create BillPlz bill
        try {
            $bill = app(BillPlzService::class)->createBill(
                name: $owner->name,
                email: $owner->email,
                amountCents: $amount,
                description: $description,
                redirectUrl: route('billplz.redirect'),
                callbackUrl: route('billplz.callback'),
                reference: 'payment_'.$payment->id,
            );

            // Update payment with bill URL
            $payment->update(['reference' => $bill['id']]);

            // Redirect to BillPlz payment page
            $this->redirect($bill['url']);

        } catch (\RuntimeException $e) {
            // Cleanup on failure
            $payment->delete();
            $subscription->delete();

            Notification::make()->title('Payment failed: '.$e->getMessage())->danger()->send();
        }
    }
}
