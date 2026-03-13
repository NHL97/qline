<?php

namespace App\Filament\Admin\Payments;

use App\Filament\Admin\Payments\Pages\CreatePayment;
use App\Filament\Admin\Payments\Pages\EditPayment;
use App\Filament\Admin\Payments\Pages\ListPayments;
use App\Filament\Admin\Payments\Schemas\PaymentForm;
use App\Filament\Admin\Payments\Tables\PaymentsTable;
use App\Models\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Billing';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'id';

    public static function canAccess(): bool
    {
        // qline_staff can view only — no create/edit/delete
        return Auth::user()?->isSuperAdmin() || Auth::user()?->isQlineStaff();
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->isSuperAdmin();
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->isSuperAdmin();
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->isSuperAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit'   => EditPayment::route('/{record}/edit'),
        ];
    }
}