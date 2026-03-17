<?php

namespace App\Filament\Admin\CustomerFeedback;

use App\Filament\Admin\CustomerFeedback\Pages\ListCustomerFeedback;
use App\Filament\Admin\CustomerFeedback\Pages\ViewCustomerFeedback;
use App\Filament\Admin\CustomerFeedback\Schemas\CustomerFeedbackInfolist;
use App\Filament\Admin\CustomerFeedback\Tables\CustomerFeedbackTable;
use App\Models\CustomerFeedback;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerFeedbackResource extends Resource
{
    protected static ?string $model = CustomerFeedback::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Feedback';

    protected static string|\UnitEnum|null $navigationGroup = 'Logs';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'rating';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerFeedbackInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerFeedbackTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerFeedback::route('/'),
            'view'  => ViewCustomerFeedback::route('/{record}'),
        ];
    }
}