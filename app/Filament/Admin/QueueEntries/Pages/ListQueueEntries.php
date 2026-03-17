<?php

namespace App\Filament\Admin\QueueEntries\Pages;

use App\Filament\Admin\QueueEntries\QueueEntryResource;

use Filament\Resources\Pages\ListRecords;

class ListQueueEntries extends ListRecords
{
    protected static string $resource = QueueEntryResource::class;
}
