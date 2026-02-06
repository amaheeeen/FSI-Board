<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Real-time Cashflow (Bank Balance)
        // assuming '1101' is Bank. We sum Debits (Increase Asset) - Credits (Decrease Asset) for Bank
        // Actually, normal balance for Asset is Debit. So Balance = Debit - Credit.
        $bankCoA = \App\Models\ChartOfAccount::where('code', '1101')->first();
        $cashflow = 0;
        if ($bankCoA) {
             $debit = \App\Models\JournalDetail::where('chart_of_account_id', $bankCoA->id)->sum('debit');
             $credit = \App\Models\JournalDetail::where('chart_of_account_id', $bankCoA->id)->sum('credit');
             $cashflow = $debit - $credit;
        }

        // 2. Pax Departure (Next 30 Days)
        $paxDeparture = \App\Models\TransactionDetail::whereHas('transaction.packet', function ($query) {
            $query->whereBetween('start_date', [now(), now()->addDays(30)]);
        })->count();

        // 3. Inventory Alert (Mocked for now)
        $inventoryAlert = 5; // Mock value

        return [
            Stat::make('Real-time Cashflow', 'IDR ' . number_format($cashflow, 0))
                ->description('Bank Balance')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Pax Departure', $paxDeparture . ' Pax')
                ->description('Next 30 Days')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
            Stat::make('Inventory Alert', $inventoryAlert . ' Items')
                ->description('Low Stock (< 50)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
