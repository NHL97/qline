<?php

namespace App\Filament\Business\QueueEntries\Pages;

use App\Filament\Business\QueueEntries\QueueEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQueueEntry extends EditRecord
{
    protected static string $resource = QueueEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
