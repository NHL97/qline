<?php

namespace App\Filament\Admin\Businesses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BusinessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Business Details')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Business Owner'),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                    $set('join_code', strtoupper(Str::slug($state)));
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->helperText('Auto-generated from name'),
                        TextInput::make('join_code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->helperText('Used in QR code — must be unique'),
                        TextInput::make('phone')
                            ->tel()
                            ->nullable(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->inline(false),
                    ]),

                Section::make('Location')
                    ->columns(3)
                    ->schema([
                        TextInput::make('address')
                            ->nullable()
                            ->columnSpanFull(),
                        TextInput::make('postcode')
                            ->nullable()
                            ->maxLength(6),
                        TextInput::make('city')
                            ->nullable(),
                        TextInput::make('state')
                            ->nullable(),
                    ]),

                Section::make('Queue Settings')
                    ->columns(3)
                    ->schema([
                        TextInput::make('queue_prefix')
                            ->required()
                            ->default('Q')
                            ->maxLength(5)
                            ->label('Ticket Prefix'),
                        TextInput::make('daily_limit')
                            ->required()
                            ->numeric()
                            ->default(100)
                            ->minValue(1)
                            ->maxValue(1000)
                            ->label('Daily Limit'),
                        TextInput::make('notify_turns_before')
                            ->required()
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->label('Notify Turns Before'),
                    ]),
            ]);
    }
}