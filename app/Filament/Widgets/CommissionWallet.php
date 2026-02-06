<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommissionWallet extends BaseWidget
{
    protected function getStats(): array
    {
        // Mocking Data for now as Authentication/Agent context is not fully set up
        $earned = \App\Models\Commission::where('status', 'paid')->sum('amount');
        $pending = \App\Models\Commission::where('status', 'pending')->sum('amount');
        $withdrawn = \App\Models\Commission::where('status', 'withdrawn')->sum('amount');

        return [
            Stat::make('Total Commission Earned', 'IDR ' . number_format($earned, 0))
                ->description('All time earnings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Pending Payout', 'IDR ' . number_format($pending, 0))
                ->description('Waiting for approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Paid / Withdrawn', 'IDR ' . number_format($withdrawn, 0))
                ->description('Successfully processed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('gray'),
        ];
    }
}
