<?php

namespace App\Filament\Business;

use App\Models\QrCode;
use BackedEnum;
use chillerlan\QRCode\QRCode as QRCodeGenerator;
use chillerlan\QRCode\QROptions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QrCodePage extends Page
{
    protected string $view = 'filament.business.qr-code-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static ?string $navigationLabel = 'QR Code';

    protected static ?int $navigationSort = 3;

    public ?QrCode $qrCode = null;
    public ?string $qrImageUrl = null;

    public function mount(): void
    {
        $this->qrCode = QrCode::where('business_id', Auth::user()->business_id)->first();

        if ($this->qrCode) {
            $this->qrImageUrl = asset('storage/' . str_replace('public/', '', $this->qrCode->image_path));
        }
    }

    public function generate(): void
    {
        $business = Auth::user()->business;

        // Build WA join URL
        $waNumber = config('qline.wa_number');
        $url = "https://wa.me/{$waNumber}?text=JOIN%20{$business->join_code}";

        // Generate QR as PNG
        $options = new QROptions([
            'outputType'    => \chillerlan\QRCode\Output\QROutputInterface::GDIMAGE_PNG,
            'eccLevel'      => \chillerlan\QRCode\QRCode::ECC_H,
            'scale'         => 10,
            'imageBase64'   => false,
        ]);

        $qrcode  = new QRCodeGenerator($options);
        $imgData = $qrcode->render($url);

        // Save to storage
        $filename = 'qrcodes/' . $business->slug . '.png';
        Storage::disk('public')->put($filename, $imgData);

        // Upsert QrCode record
        $this->qrCode = QrCode::updateOrCreate(
            ['business_id' => $business->id],
            [
                'label'      => $business->name,
                'url'        => $url,
                'image_path' => 'public/' . $filename,
                'is_active'  => true,
            ]
        );

        $this->qrImageUrl = asset('storage/' . $filename);

        Notification::make()->title('QR Code generated')->success()->send();
    }
}