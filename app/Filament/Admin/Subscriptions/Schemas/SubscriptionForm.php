<?php

namespace App\Filament\Admin\Subscriptions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscription Details')
                    ->columns(2)
                    ->schema([
                        Select::make('business_id')
                            ->relationship('business', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Business'),
                        Select::make('type')
                            ->options([
                                'daily'   => 'Daily — RM 12',
                                'monthly' => 'Monthly — RM 300',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === 'daily') {
                                    $set('expires_at', now()->toDateString());
                                } elseif ($state === 'monthly') {
                                    $set('expires_at', now()->addMonth()->toDateString());
                                }
                            }),
                        Select::make('status')
                            ->options([
                                'pending'   => 'Pending',
                                'active'    => 'Active',
                                'expired'   => 'Expired',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),
                        DatePicker::make('starts_at')
                            ->required()
                            ->default(now()),
                        DatePicker::make('expires_at')
                            ->required(),
                    ]),
            ]);
    }
}