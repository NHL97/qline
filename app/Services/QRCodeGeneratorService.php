<?php

namespace App\Services;

use App\Models\Business;
use App\Models\QrCode;
use chillerlan\QRCode\QRCode as QRCodeGenerator;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class QRCodeGeneratorService
{
    public function generateForBusiness(Business $business): QrCode
    {
        $waNumber = config('qline.wa_number');
        $url      = "https://wa.me/{$waNumber}?text=JOIN%20{$business->join_code}";

        $options = new QROptions([
            'outputType'  => \chillerlan\QRCode\Output\QROutputInterface::GDIMAGE_PNG,
            'eccLevel'    => \chillerlan\QRCode\QRCode::ECC_H,
            'scale'       => 10,
            'imageBase64' => false,
        ]);

        $qrcode  = new QRCodeGenerator($options);
        $imgData = $qrcode->render($url);

        $filename = 'qrcodes/' . $business->slug . '.png';
        Storage::disk('public')->put($filename, $imgData);

        return QrCode::updateOrCreate(
            ['business_id' => $business->id],
            [
                'label'      => $business->name,
                'url'        => $url,
                'image_path' => 'public/' . $filename,
                'is_active'  => true,
            ]
        );
    }
}