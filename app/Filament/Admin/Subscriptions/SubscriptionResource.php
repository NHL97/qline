<?php

namespace App\Filament\Admin\Subscriptions;

use App\Filament\Admin\Subscriptions\Pages\CreateSubscription;
use App\Filament\Admin\Subscriptions\Pages\EditSubscription;
use App\Filament\Admin\Subscriptions\Pages\ListSubscriptions;
use App\Filament\Admin\Subscriptions\Schemas\SubscriptionForm;
use App\Filament\Admin\Subscriptions\Tables\SubscriptionsTable;
use App\Models\Subscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Billing';

    protected static ?string $navigationLabel = 'Subscriptions';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'id';

    public static function canAccess(): bool
    {
        return Auth::user()?->isSuperAdmin() || Auth::user()?->isQlineStaff();
    }

    public static function form(Schema $schema): Schema
    {
        return SubscriptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSubscriptions::route('/'),
            'create' => CreateSubscription::route('/create'),
            'edit'   => EditSubscription::route('/{record}/edit'),
        ];
    }
}