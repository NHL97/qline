<?php

namespace App\Filament\Admin;

use App\Models\Business;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopBusinessesWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $defaultPaginationPageOption = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Business::withCount('queueEntries')
                    ->withSum(['payments' => fn ($q) => $q->where('status', 'paid')], 'amount')
                    ->orderByDesc('queue_entries_count')
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('queue_status')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'open'   => 'success',
                        'paused' => 'warning',
                        'closed' => 'danger',
                    }),
                TextColumn::make('queue_entries_count')
                    ->label('Total Entries')
                    ->sortable(),
                TextColumn::make('entries_today')
                    ->label('Today'),
                TextColumn::make('payments_sum_amount')
                    ->label('Revenue')
                    ->formatStateUsing(fn ($state) => 'RM ' . number_format($state ?? 0, 2))
                    ->sortable(),
                TextColumn::make('is_active')
                    ->label('Active')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
            ])
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}