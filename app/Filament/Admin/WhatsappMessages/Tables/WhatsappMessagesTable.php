<?php

namespace App\Filament\Admin\WhatsappMessages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WhatsappMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('business.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('wa_id')
                    ->label('WhatsApp ID')
                    ->searchable(),
                TextColumn::make('direction')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'inbound'  => 'info',
                        'outbound' => 'success',
                        default    => 'gray',
                    }),
                TextColumn::make('template')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('body')
                    ->limit(40)
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'sent'      => 'info',
                        'delivered' => 'success',
                        'read'      => 'success',
                        'failed'    => 'danger',
                        default     => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('direction')
                    ->options([
                        'inbound'  => 'Inbound',
                        'outbound' => 'Outbound',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'sent'      => 'Sent',
                        'delivered' => 'Delivered',
                        'read'      => 'Read',
                        'failed'    => 'Failed',
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