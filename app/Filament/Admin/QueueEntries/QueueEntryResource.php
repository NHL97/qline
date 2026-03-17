<?php

namespace App\Filament\Admin\QueueEntries;

use App\Filament\Admin\QueueEntries\Pages\ListQueueEntries;
use App\Filament\Admin\QueueEntries\Pages\ViewQueueEntry;
use App\Filament\Admin\QueueEntries\Schemas\QueueEntryForm;
use App\Filament\Admin\QueueEntries\Tables\QueueEntriesTable;
use App\Models\QueueEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QueueEntryResource extends Resource
{
    protected static ?string $model = QueueEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Queue Entries';

    protected static string|\UnitEnum|null $navigationGroup = 'Logs';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'ticket_code';

    public static function canCreate(): bool
    {
        return false;
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
            'view'  => ViewQueueEntry::route('/{record}'),
        ];
    }
}