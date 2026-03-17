<?php

namespace App\Filament\Admin\CustomerFeedback\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerFeedbackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('business.name')
                    ->label('Business'),
                TextEntry::make('wa_id')
                    ->label('WhatsApp')
                    ->placeholder('—'),
                TextEntry::make('queueEntry.ticket_code')
                    ->label('Ticket'),
                TextEntry::make('rating')
                    ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state)),
                TextEntry::make('comment')
                    ->placeholder('No comment')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y H:i'),
            ]);
    }
}