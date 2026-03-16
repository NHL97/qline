<?php

namespace App\Filament\Admin;

use App\Models\Business;
use App\Models\Payment;
use App\Models\QueueEntry;
use App\Models\Subscription;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalBusinesses    = Business::where('is_active', true)->count();
        $activeSubscriptions = Subscription::where('status', 'active')
            ->where('expires_at', '>=', today())
            ->count();
        $todayEntries       = QueueEntry::whereDate('joined_at', today())->count();
        $monthRevenue       = Payment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');
        $totalUsers         = User::where('role', 'business_owner')->count();
        $totalEntries       = QueueEntry::count();

        return [
            Stat::make('Active Businesses', $totalBusinesses)
                ->description($activeSubscriptions . ' with active subscription')
                ->color('success'),

            Stat::make('Monthly Revenue', 'RM ' . number_format($monthRevenue, 2))
                ->description('Paid this month')
                ->color('success'),

            Stat::make('Entries Today', $todayEntries)
                ->description('Across all businesses')
                ->color('info'),

            Stat::make('Total Entries', $totalEntries)
                ->description($totalUsers . ' business owners')
                ->color('warning'),
        ];
    }
}