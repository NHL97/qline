<?php

namespace App\Filament\Business\QueueEntries;

use App\Filament\Business\QueueEntries\Pages\ListQueueEntries;
use App\Filament\Business\QueueEntries\Schemas\QueueEntryForm;
use App\Filament\Business\QueueEntries\Tables\QueueEntriesTable;
use App\Models\QueueEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QueueEntryResource extends Resource
{
    protected static ?string $model = QueueEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Queue Entries';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'ticket_code';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    // Scope all queries to current business
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('business_id', Auth::user()->business_id);
    }

    public static function form(Schema $schema): Schema
    {
        return QueueEntryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QueueEntriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQueueEntries::route('/'),
        ];
    }
}