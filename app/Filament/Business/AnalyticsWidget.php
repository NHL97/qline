<?php

namespace App\Filament\Business;

use App\Models\QueueEntry;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsWidget extends ChartWidget
{
    protected ?string $heading = 'Daily Entries';

    protected static ?int $sort = 2;

    public ?string $filter = '7days';

    protected int | string | array $columnSpan = 2;

    protected function getFilters(): ?array
    {
        return [
            '7days'  => 'Last 7 Days',
            '30days' => 'Last 30 Days',
            '90days' => 'Last 90 Days',
        ];
    }

    protected function getData(): array
    {
        $businessId = Auth::user()->business_id;

        $days = match($this->filter) {
            '7days'  => 7,
            '30days' => 30,
            '90days' => 90,
            default  => 7,
        };

        $from = now()->subDays($days)->startOfDay();

        $daily = QueueEntry::where('business_id', $businessId)
            ->where('joined_at', '>=', $from)
            ->select(DB::raw('DATE(joined_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data   = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date     = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $data[]   = $daily[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Entries',
                    'data'            => $data,
                    'backgroundColor' => '#14b8a6',
                    'borderRadius'    => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}