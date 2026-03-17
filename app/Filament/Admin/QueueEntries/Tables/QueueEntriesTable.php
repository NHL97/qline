<?php

namespace App\Filament\Admin\QueueEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QueueEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('joined_at')
                    ->label('Time')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('business.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ticket_code')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('wa_id')
                    ->label('WhatsApp')
                    ->searchable()
                    ->placeholder('Anonymous'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'waiting'   => 'warning',
                        'called'    => 'info',
                        'serving'   => 'primary',
                        'done'      => 'success',
                        'skipped'   => 'gray',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),
                TextColumn::make('source')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'whatsapp' => 'success',
                        'manual'   => 'gray',
                        default    => 'gray',
                    }),
                TextColumn::make('wait_minutes')
                    ->label('Wait')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' min' : '—')
                    ->sortable(),
                TextColumn::make('service_minutes')
                    ->label('Service')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' min' : '—')
                    ->sortable(),
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
                SelectFilter::make('business')
                    ->relationship('business', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}