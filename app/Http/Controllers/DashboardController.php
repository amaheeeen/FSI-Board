<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Package;
use App\Models\Pilgrim;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter Parameters
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        if ($request->ajax()) {
            return response()->json($this->getDashboardData($selectedMonth, $selectedYear));
        }

        // Initial Load (Pass data to view)
        $data = $this->getDashboardData($selectedMonth, $selectedYear);
        $recentTransactions = Transaction::with(['user', 'package', 'agent', 'pilgrims'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', array_merge($data, compact('recentTransactions')));
    }

    private function getDashboardData($selectedMonth, $selectedYear)
    {
        // 1. Monthly Revenue
        $monthlyRevenue = \App\Models\Payment::whereMonth('payment_date', $selectedMonth)
            ->whereYear('payment_date', $selectedYear)
            ->sum('amount_paid');

        // 2. Monthly Ops Cost
        $opsCost = \App\Models\OperationalExpense::whereMonth('expense_date', $selectedMonth)
            ->whereYear('expense_date', $selectedYear)
            ->sum('amount');

        // 3. Budget Progress
        $firstDayOfMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('Y-m-d');
        $monthlyBudget = \App\Models\OperationalBudget::whereDate('start_date', '<=', $firstDayOfMonth)
            ->whereDate('end_date', '>=', $firstDayOfMonth)
            ->sum('allocated_amount');
        $budgetProgress = $monthlyBudget > 0 ? ($opsCost / max($monthlyBudget, 1)) * 100 : 0;

        // 4. Operating Profit & Margin
        $operatingProfit = $monthlyRevenue - $opsCost;
        $profitMargin = $monthlyRevenue > 0 ? ($operatingProfit / max($monthlyRevenue, 1)) * 100 : 0;

        // 5. Active Pilgrims (Snapshot - Independent of month filter usually, but kept for context)
        // Using "Selected Month" to check for pilgrims active *during* that month might be more accurate for historical view.
        // For now, let's keep it "Currently Active" if no historical logic required, 
        // BUT user asked for "Filter Waktu". If I select last year, showing "Current Active" is weird.
        // Let's make it "Active during selected month".
        $startOfMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
        
        $activePilgrims = Pilgrim::whereHas('transaction.package', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where(function($q) use ($startOfMonth, $endOfMonth) {
                // Overlap logic: Package Start <= Month End AND Package End >= Month Start
                $q->whereDate('departure_date', '<=', $endOfMonth)
                  ->whereDate('return_date', '>=', $startOfMonth);
            });
        })->count();

        // 6. Upcoming Package (Future relative to now, unrelated to filter?)
        // Let's keep it real-time "Upcoming" for the widget.
        $upcomingPackage = Package::where('status', 'Open')
            ->orderBy('departure_date', 'asc')
            ->first();
        $remainingQuota = $upcomingPackage ? $upcomingPackage->available_quota : 0;

        // 7. Chart Data: Revenue vs Ops Cost & Registrations (Yearly View for Selected Year)
        $months = [];
        $revenueData = [];
        $expenseData = [];
        $registrations = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
            
            // Revenue for Month $i
            $revenueData[] = \App\Models\Payment::whereMonth('payment_date', $i)
                ->whereYear('payment_date', $selectedYear)
                ->sum('amount_paid');
            
            // Expense for Month $i
            $expenseData[] = \App\Models\OperationalExpense::whereMonth('expense_date', $i)
                ->whereYear('expense_date', $selectedYear)
                ->sum('amount');

            // Registrations for Month $i (Pilgrims Created)
            $registrations[] = Pilgrim::whereMonth('created_at', $i)
                ->whereYear('created_at', $selectedYear)
                ->count();
        }
        
        // Gender stats (Pie Chart) - All time or selected year? Usually Demographics are all time or per departure.
        // Let's make it filtered by transactions in that year/month to be dynamic.
        $genderStats = [
            Pilgrim::whereHas('transaction', function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);
            })->where('gender', 'Male')->count(),
            Pilgrim::whereHas('transaction', function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);
            })->where('gender', 'Female')->count(),
        ];

        return [
            'monthlyRevenue' => $monthlyRevenue,
            'opsCost' => $opsCost,
            'budgetProgress' => $budgetProgress,
            'operatingProfit' => $operatingProfit,
            'profitMargin' => $profitMargin,
            'activePilgrims' => $activePilgrims,
            'upcomingPackage' => $upcomingPackage,
            'remainingQuota' => $remainingQuota,
            'months' => $months,
            'revenueData' => $revenueData,
            'expenseData' => $expenseData,
            'registrations' => $registrations,
            'selectedMonth' => $selectedMonth, // Echo back
            'selectedYear' => $selectedYear,   // Echo back
            'genderStats' => $genderStats
        ];
    }
}
