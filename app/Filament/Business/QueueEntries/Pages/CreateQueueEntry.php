<?php

namespace App\Filament\Business\QueueEntries\Pages;

use App\Filament\Business\QueueEntries\QueueEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQueueEntry extends CreateRecord
{
    protected static string $resource = QueueEntryResource::class;
}
