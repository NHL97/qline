<?php

namespace App\Filament\Admin\WhatsappMessages\Pages;

use App\Filament\Admin\WhatsappMessages\WhatsappMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWhatsappMessages extends ListRecords
{
    protected static string $resource = WhatsappMessageResource::class;
}
