<?php

namespace App\Filament\Business;

use App\Models\QueueEntry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class QueueStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $businessId = Auth::user()->business_id;
        $business   = Auth::user()->business;

        $waitingCount    = QueueEntry::where('business_id', $businessId)->where('status', 'waiting')->count();
        $todayDone       = QueueEntry::where('business_id', $businessId)->where('status', 'done')->whereDate('done_at', today())->count();
        $avgWait         = round(QueueEntry::where('business_id', $businessId)->whereNotNull('wait_minutes')->whereDate('joined_at', today())->avg('wait_minutes') ?? 0, 1);
        $avgService      = $business->avgServiceMinutes();

        return [
            Stat::make('Queue Status', strtoupper($business->queue_status))
                ->description($business->entries_today . ' / ' . $business->daily_limit . ' today')
                ->color(match($business->queue_status) {
                    'open'   => 'success',
                    'paused' => 'warning',
                    'closed' => 'danger',
                }),

            Stat::make('Waiting', $waitingCount)
                ->description('Currently in queue')
                ->color('info'),

            Stat::make('Served Today', $todayDone)
                ->description('Completed visits')
                ->color('success'),

            Stat::make('Avg Wait', $avgWait . ' min')
                ->description('Avg service: ' . $avgService . ' min')
                ->color('warning'),
        ];
    }
}