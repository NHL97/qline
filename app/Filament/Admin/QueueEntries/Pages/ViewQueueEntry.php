<?php

namespace App\Filament\Admin\QueueEntries\Pages;

use App\Filament\Admin\QueueEntries\QueueEntryResource;
use Filament\Resources\Pages\ViewRecord;

class ViewQueueEntry extends ViewRecord
{
    protected static string $resource = QueueEntryResource::class;
}