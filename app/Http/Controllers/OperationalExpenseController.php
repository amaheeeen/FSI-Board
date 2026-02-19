<?php

namespace App\Http\Controllers;

use App\Models\OperationalExpense;
use App\Models\OperationalBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OperationalExpenseController extends Controller
{
    public function index(Request $request)
    {
        // Filter Parameters
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        if ($request->ajax()) {
            return response()->json($this->getChartData($selectedMonth, $selectedYear));
        }

        $expenses = OperationalExpense::latest()->paginate(10);
        
        // Calculate Expense Totals (Monthly & Yearly) for Cards
        // We can use the selected month/year or just current date for the initial view cards.
        // User requirements imply the cards might also need to update, or at least the Chart.
        // Let's keep the cards as "Current Month" and "Current Year" for simplicity unless requested otherwise,
        // BUT the chart needs filtered data.
        
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyTotalExpenses = OperationalExpense::whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->sum('amount');

        $yearlyTotalExpenses = OperationalExpense::whereYear('expense_date', $currentYear)
            ->sum('amount');
        
        // Initial Chart Data (Current Month)
        $chartData = $this->getChartData($currentMonth, $currentYear);
        
        return view('operational.index', array_merge(compact('expenses', 'monthlyTotalExpenses', 'yearlyTotalExpenses'), $chartData));
    }

    private function getChartData($month, $year)
    {
        // Cost Distribution by Category
        $distribution = OperationalExpense::whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->select('category', \Illuminate\Support\Facades\DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();
            
        $categories = $distribution->pluck('category');
        $amounts = $distribution->pluck('total');
        
        return [
            'chartCategories' => $categories,
            'chartSeries' => $amounts,
            'selectedMonth' => $month,
            'selectedYear' => $year
        ];
    }

    public function create()
    {
        return view('operational.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        $path = null;
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
        }

        OperationalExpense::create([
            'expense_date' => $request->expense_date,
            'title' => $request->title,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'receipt_path' => $path,
        ]);

        return redirect()->route('operational-costs.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(OperationalExpense $operational)
    {
        return view('operational.edit', compact('operational'));
    }

    public function update(Request $request, OperationalExpense $operational)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $operational->receipt_path;
        if ($request->hasFile('receipt')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('receipt')->store('receipts', 'public');
        }

        $operational->update([
            'expense_date' => $request->expense_date,
            'title' => $request->title,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'receipt_path' => $path,
        ]);

        return redirect()->route('operational-costs.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(OperationalExpense $operational)
    {
        if ($operational->receipt_path && Storage::disk('public')->exists($operational->receipt_path)) {
            Storage::disk('public')->delete($operational->receipt_path);
        }
        
        $operational->delete();
        
        return redirect()->route('operational-costs.index')->with('success', 'Expense deleted successfully.');
    }
}
