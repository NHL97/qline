<?php

namespace App\Filament\Business\QueueEntries\Pages;

use App\Filament\Business\QueueEntries\QueueEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQueueEntries extends ListRecords
{
    protected static string $resource = QueueEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
