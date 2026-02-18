<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Total Revenue (Monthly) - Mocked for now or use Transaction model
        $revenue = \App\Models\Transaction::whereMonth('created_at', now()->month)->sum('total_amount') ?? 0;

        // 2. Active Pilgrims (Currently in Saudi)
        // Logic: Status = 'Departed' AND packet.end_date >= now()
        $activePilgrims = \App\Models\Jamaah::where('status', 'Departed')->count();

        // 3. Upcoming Departures (Next 30 Days)
        // Logic: Transaction -> Packet -> start_date within 30 days
        // Simplified: using Jamaah status 'Visa Issued' as proxy or just mock
        $upcomingDepartures = \App\Models\TransactionDetail::whereHas('transaction.packet', function ($query) {
            $query->whereBetween('start_date', [now(), now()->addDays(30)]);
        })->count();

        // 4. Visa Status
        $visaApproved = \App\Models\Jamaah::where('status', 'Visa Issued')->count();
        $visaPending = \App\Models\Jamaah::where('status', 'Documents Complete')->count();

        return [
            Stat::make('Total Revenue (Monthly)', 'IDR ' . number_format($revenue, 0))
                ->description('Sales this month')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Mock chart

            Stat::make('Active Pilgrims', $activePilgrims)
                ->description('Currently in Saudi')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Upcoming Departures', $upcomingDepartures)
                ->description('Next 30 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Visa Status', "$visaApproved Approved / $visaPending Pending")
                ->description('Visa Processing')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('gray'),
        ];
    }
}
