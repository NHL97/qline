<?php

namespace App\Filament\Admin\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->label(fn (string $operation) => $operation === 'create' ? 'Password' : 'New Password (leave blank to keep)'),
                        Select::make('role')
                            ->options([
                                'superadmin'       => 'Super Admin',
                                'qline_staff'      => 'QLine Staff',
                                'business_owner'   => 'Business Owner',
                                'business_staff'   => 'Business Staff',
                            ])
                            ->default('business_owner')
                            ->required()
                            ->live(),
                    ]),

                Section::make('Business Assignment')
                    ->columns(2)
                    ->schema([
                        Select::make('business_id')
                            ->relationship('business', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Assigned Business')
                            ->helperText('Leave empty for superadmin and qline_staff'),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}