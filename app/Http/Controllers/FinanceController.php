<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $payments = Payment::with('transaction.package')->latest()->paginate(10);
        return view('finance.index', compact('payments'));
    }

    public function dashboard()
    {
        // 1. Total Revenue (Sum of all verified payments)
        $totalRevenue = Payment::sum('amount_paid');

        // 2. Accounts Receivable (Total Transaction Value - Total Revenue)
        // Note: distinct transactions needed if payments tracked separately, but sum is easier
        $totalTransactionValue = Transaction::sum('total_amount');
        $accountsReceivable = $totalTransactionValue - $totalRevenue;

        // 3. Monthly Income Stats (Current Year)
        $monthlyIncome = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount_paid) as total')
            ->whereYear('payment_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $incomeStats = [];
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
            $incomeStats[] = $monthlyIncome[$i] ?? 0;
        }

        return view('finance.dashboard', compact('totalRevenue', 'accountsReceivable', 'incomeStats', 'months'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        Payment::create([
            'transaction_id' => $transaction->id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'status' => 'Verified', // Auto-verify for now
        ]);

        // Auto-update transaction status logic
        $totalPaid = $transaction->payments()->sum('amount_paid') + $request->amount_paid;
        
        if ($totalPaid >= $transaction->total_amount) {
            $transaction->update(['status' => 'Paid']);
        } elseif ($totalPaid > 0) {
            $transaction->update(['status' => 'Down Payment']);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }
}
