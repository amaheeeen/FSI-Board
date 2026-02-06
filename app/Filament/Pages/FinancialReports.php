<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\JournalDetail;
use App\Models\ChartOfAccount; // Assuming existing model
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Enums\Alignment;

class FinancialReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $title = 'Financial Reports';
    protected static string $view = 'filament.pages.financial-reports';

    public $activeTab = 'profit-loss';

    public function getViewData(): array
    {
        return [
            'pl' => $this->getProfitLossData(),
            'bs' => $this->getBalanceSheetData(),
        ];
    }

    protected function getProfitLossData()
    {
        // Revenue (Type: equity/revenue check normal balance credit)
        // Expense (Type: expense check debit)
        
        // Simple logic for Demo/MVP:
        // Revenue: Code starts with '4'
        // Expense: Code starts with '5'
        
        $revenueDetails = JournalDetail::whereHas('coa', function ($q) {
            $q->where('code', 'like', '4%');
        })->get();
        
        $expenseDetails = JournalDetail::whereHas('coa', function ($q) {
            $q->where('code', 'like', '5%');
        })->get();

        $totalRevenue = $revenueDetails->sum('credit') - $revenueDetails->sum('debit');
        $totalExpense = $expenseDetails->sum('debit') - $expenseDetails->sum('credit');
        
        return [
            'revenue' => $totalRevenue,
            'expense' => $totalExpense,
            'net_profit' => $totalRevenue - $totalExpense,
        ];
    }

    protected function getBalanceSheetData()
    {
        // Assets: Code '1%'
        // Liabilities: Code '2%'
        // Equity: Code '3%' + Net Profit
        
        $assets = JournalDetail::whereHas('coa', fn($q) => $q->where('code', 'like', '1%'))->get();
        $liabilities = JournalDetail::whereHas('coa', fn($q) => $q->where('code', 'like', '2%'))->get();
        $equity = JournalDetail::whereHas('coa', fn($q) => $q->where('code', 'like', '3%'))->get();
        
        $totalAssets = $assets->sum('debit') - $assets->sum('credit');
        $totalLiabilities = $liabilities->sum('credit') - $liabilities->sum('debit');
        $totalEquity = $equity->sum('credit') - $equity->sum('debit');
        
        // Add Net Profit to Equity (Simplification)
        $pl = $this->getProfitLossData();
        $totalEquity += $pl['net_profit'];
        
        return [
            'assets' => $totalAssets,
            'liabilities' => $totalLiabilities,
            'equity' => $totalEquity,
        ];
    }
}
