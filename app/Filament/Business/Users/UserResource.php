<?php

namespace App\Filament\Business\Users;

use App\Filament\Business\Users\Pages\CreateUser;
use App\Filament\Business\Users\Pages\EditUser;
use App\Filament\Business\Users\Pages\ListUsers;
use App\Filament\Business\Users\Schemas\UserForm;
use App\Filament\Business\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Staff';

    protected static ?int $navigationSort = 1; // Just before Customer Feedback

    protected static ?string $recordTitleAttribute = 'name';

    // Only business_owner can access
    public static function canAccess(): bool
    {
        return Auth::user()?->isBusinessOwner();
    }

    // Scope to current business staff only
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('business_id', Auth::user()->business_id)
            ->where('role', 'business_staff');
    }

    // Auto-assign business_id and role on create
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['business_id']       = Auth::user()->business_id;
        $data['role']              = 'business_staff';
        $data['email_verified_at'] = now();
        return $data;
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}