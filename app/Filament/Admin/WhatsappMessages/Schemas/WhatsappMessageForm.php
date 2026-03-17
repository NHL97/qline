<?php

namespace App\Filament\Admin\WhatsappMessages\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WhatsappMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('business_id')
                    ->relationship('business', 'name')
                    ->disabled(),
                TextInput::make('wa_id')
                    ->disabled(),
                Select::make('direction')
                    ->options(['inbound' => 'Inbound', 'outbound' => 'Outbound'])
                    ->disabled(),
                TextInput::make('template')
                    ->disabled(),
                Textarea::make('body')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('message_id')
                    ->disabled(),
                Select::make('status')
                    ->options([
                        'sent'      => 'Sent',
                        'delivered' => 'Delivered',
                        'read'      => 'Read',
                        'failed'    => 'Failed',
                    ])
                    ->disabled(),
            ]);
    }
}