<?php

namespace App\Filament\Business;

use App\Models\Business;
use App\Models\QrCode;
use BackedEnum;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode as QRCodeGenerator;
use chillerlan\QRCode\QROptions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessSettings extends Page
{
    protected string $view = 'filament.business.business-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Account';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?int $navigationSort = 2; // Always at the bottom

    public static function canAccess(): bool
    {
        return Auth::user()?->isBusinessOwner();
    }

    // Business details
    public string $name = '';

    public string $phone = '';

    public string $address = '';
    
    public string $postcode = '';

    public string $city = '';

    public string $state = '';

    // Queue settings
    public string $queue_prefix = 'Q';

    public int $daily_limit = 100;

    public int $notify_turns_before = 3;

    // QR code
    public ?QrCode $qrCode = null;

    public ?string $qrImageUrl = null;

    public function mount(): void
    {
        $business = Auth::user()->business->firstOrFail();

        $this->name = $business->name;
        $this->phone = $business->phone ?? '';
        $this->address = $business->address ?? '';
        $this->postcode = $business->postcode ?? '';
        $this->city = $business->city ?? '';
        $this->state = $business->state ?? '';
        $this->queue_prefix = $business->queue_prefix;
        $this->daily_limit = $business->daily_limit;
        $this->notify_turns_before = $business->notify_turns_before;

        // Load existing QR if any
        $this->qrCode = QrCode::where('business_id', Auth::user()->business_id)->first();

        if ($this->qrCode) {
            $this->qrImageUrl = asset('storage/'.str_replace('public/', '', $this->qrCode->image_path));
        }
    }

    public function saveBusinessDetails(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:6',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        Auth::user()->business->firstOrFail()->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'city' => $this->city,
            'state' => $this->state,
        ]);

        Notification::make()->title('Business details saved')->success()->send();
    }

    public function saveQueueSettings(): void
    {
        $this->validate([
            'queue_prefix' => 'required|string|max:5',
            'daily_limit' => 'required|integer|min:1|max:500',
            'notify_turns_before' => 'required|integer|min:1|max:5',
        ]);

        Auth::user()->business->firstOrFail()->update([
            'queue_prefix' => strtoupper($this->queue_prefix),
            'daily_limit' => $this->daily_limit,
            'notify_turns_before' => $this->notify_turns_before,
        ]);

        Notification::make()->title('Queue settings saved')->success()->send();
    }

    public function generate(): void
    {
        $business = Auth::user()->business()->firstOrFail(); // fresh query, no cache

        $waNumber = config('qline.wa_number');
        $newUrl = "https://wa.me/{$waNumber}?text=JOIN%20{$business->join_code}";

        // Check if URL has changed
        $existing = QrCode::where('business_id', $business->id)->first();
        if ($existing && $existing->url === $newUrl) {
            Notification::make()
                ->title('QR code is already up to date')
                ->body('The join code has not changed.')
                ->warning()
                ->send();

            return;
        }

        $options = new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => QRCodeGenerator::ECC_H,
            'scale' => 10,
            'imageBase64' => false,
        ]);

        $qrcode = new QRCodeGenerator($options);
        $imgData = $qrcode->render($newUrl);

        $filename = 'qrcodes/'.$business->slug.'.png';
        Storage::disk('public')->put($filename, $imgData);

        $this->qrCode = QrCode::updateOrCreate(
            ['business_id' => $business->id],
            [
                'label' => $business->name,
                'url' => $newUrl,
                'image_path' => 'public/'.$filename,
                'is_active' => true,
            ]
        );

        $this->qrImageUrl = asset('storage/'.$filename);

        Notification::make()->title('QR Code generated')->success()->send();
    }
}
