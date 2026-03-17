<?php

namespace App\Filament\Admin\WhatsappMessages\Pages;

use App\Filament\Admin\WhatsappMessages\WhatsappMessageResource;
use Filament\Resources\Pages\ViewRecord;

class ViewWhatsappMessage extends ViewRecord
{
    protected static string $resource = WhatsappMessageResource::class;
}