<?php

namespace App\Filament\Admin\Businesses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BusinessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable(),
                TextColumn::make('join_code')
                    ->label('Join Code')
                    ->searchable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('city')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('state')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('queue_status')
                    ->label('Queue')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open'   => 'success',
                        'paused' => 'warning',
                        'closed' => 'danger',
                    }),
                TextColumn::make('entries_today')
                    ->label('Today')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('daily_limit')
                    ->label('Limit')
                    ->numeric(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('queue_status')
                    ->options([
                        'open'   => 'Open',
                        'paused' => 'Paused',
                        'closed' => 'Closed',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}