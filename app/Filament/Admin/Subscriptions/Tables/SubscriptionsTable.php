<?php

namespace App\Filament\Admin\Subscriptions\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business.name')
                    ->label('Business')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'daily'   => 'success',
                        'monthly' => 'info',
                        default   => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'    => 'success',
                        'pending'   => 'warning',
                        'expired'   => 'danger',
                        'cancelled' => 'gray',
                        default     => 'gray',
                    }),
                TextColumn::make('starts_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'daily'   => 'Daily',
                        'monthly' => 'Monthly',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'active'    => 'Active',
                        'expired'   => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'active')
                    ->action(function ($record) {
                        $record->update(['status' => 'active']);
                        Notification::make()->title('Subscription activated')->success()->send();
                    }),
                Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'active')
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);
                        Notification::make()->title('Subscription deactivated')->success()->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}