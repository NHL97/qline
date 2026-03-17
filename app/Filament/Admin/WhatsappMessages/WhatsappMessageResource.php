<?php

namespace App\Filament\Admin\WhatsappMessages;

use App\Filament\Admin\WhatsappMessages\Pages\ListWhatsappMessages;
use App\Filament\Admin\WhatsappMessages\Pages\ViewWhatsappMessage;
use App\Filament\Admin\WhatsappMessages\Schemas\WhatsappMessageForm;
use App\Filament\Admin\WhatsappMessages\Tables\WhatsappMessagesTable;
use App\Models\WhatsappMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WhatsappMessageResource extends Resource
{
    protected static ?string $model = WhatsappMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeft;

    protected static ?string $navigationLabel = 'WA Messages';

    protected static string|\UnitEnum|null $navigationGroup = 'Logs';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'wa_id';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return WhatsappMessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WhatsappMessagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWhatsappMessages::route('/'),
            'view' => ViewWhatsappMessage::route('/{record}'),
        ];
    }
}
