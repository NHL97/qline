<?php

namespace App\Filament\Admin\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Details')
                    ->columns(2)
                    ->schema([
                        Select::make('business_id')
                            ->relationship('business', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Business'),
                        Select::make('subscription_id')
                            ->relationship('subscription', 'id')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Subscription'),
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('RM')
                            ->minValue(0),
                        TextInput::make('currency')
                            ->required()
                            ->default('MYR')
                            ->maxLength(3),
                        Select::make('method')
                            ->options([
                                'fpx'  => 'FPX (BillPlz)',
                                'card' => 'Card',
                            ])
                            ->nullable(),
                        Select::make('status')
                            ->options([
                                'pending'  => 'Pending',
                                'paid'     => 'Paid',
                                'failed'   => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                        TextInput::make('reference')
                            ->nullable()
                            ->label('Gateway Reference'),
                        DateTimePicker::make('paid_at')
                            ->nullable(),
                    ]),
            ]);
    }
}