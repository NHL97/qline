<?php

namespace App\Filament\Admin\Payments\Pages;

use App\Filament\Admin\Payments\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
