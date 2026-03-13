<?php

namespace App\Filament\Business\QueueEntries\Schemas;

use Filament\Schemas\Schema;

class QueueEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}