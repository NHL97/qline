<?php

namespace App\Filament\Admin\CustomerFeedback\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomerFeedbackTable
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
                TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->sortable(),
                TextColumn::make('wa_id')
                    ->label('WhatsApp')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('queueEntry.ticket_code')
                    ->label('Ticket')
                    ->placeholder('—'),
                TextColumn::make('comment')
                    ->limit(50)
                    ->placeholder('No comment'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('rating')
                    ->options([
                        '5' => '★★★★★',
                        '4' => '★★★★☆',
                        '3' => '★★★☆☆',
                        '2' => '★★☆☆☆',
                        '1' => '★☆☆☆☆',
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