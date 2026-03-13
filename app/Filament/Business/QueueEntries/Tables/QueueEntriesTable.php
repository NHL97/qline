<?php

namespace App\Filament\Business\QueueEntries\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QueueEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_code')
                    ->label('Ticket')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('wa_id')
                    ->label('WhatsApp')
                    ->searchable()
                    ->placeholder('Anonymous'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'waiting'   => 'warning',
                        'called'    => 'info',
                        'serving'   => 'primary',
                        'done'      => 'success',
                        'skipped'   => 'gray',
                        'cancelled' => 'danger',
                    }),
                TextColumn::make('source')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'whatsapp' => 'success',
                        'manual'   => 'gray',
                    }),
                TextColumn::make('position')
                    ->label('Pos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('joined_at')
                    ->label('Joined')
                    ->dateTime('d M H:i')
                    ->sortable(),
                TextColumn::make('wait_minutes')
                    ->label('Wait')
                    ->suffix(' min')
                    ->placeholder('—'),
                TextColumn::make('service_minutes')
                    ->label('Service')
                    ->suffix(' min')
                    ->placeholder('—'),
            ])
            ->defaultSort('joined_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'waiting'   => 'Waiting',
                        'called'    => 'Called',
                        'serving'   => 'Serving',
                        'done'      => 'Done',
                        'skipped'   => 'Skipped',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('source')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'manual'   => 'Manual',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}