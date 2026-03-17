<?php

namespace App\Filament\Admin\QueueEntries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QueueEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('business_id')
                    ->relationship('business', 'name')
                    ->required(),
                TextInput::make('wa_id'),
                TextInput::make('ticket_number')
                    ->required()
                    ->numeric(),
                TextInput::make('ticket_code')
                    ->required(),
                Select::make('status')
                    ->options([
            'waiting' => 'Waiting',
            'called' => 'Called',
            'serving' => 'Serving',
            'done' => 'Done',
            'skipped' => 'Skipped',
            'cancelled' => 'Cancelled',
        ])
                    ->default('waiting')
                    ->required(),
                Select::make('source')
                    ->options(['whatsapp' => 'Whatsapp', 'manual' => 'Manual'])
                    ->default('whatsapp')
                    ->required(),
                TextInput::make('position')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('joined_at')
                    ->required(),
                DateTimePicker::make('called_at'),
                DateTimePicker::make('served_at'),
                DateTimePicker::make('done_at'),
                TextInput::make('wait_minutes')
                    ->numeric(),
                TextInput::make('service_minutes')
                    ->numeric(),
            ]);
    }
}
