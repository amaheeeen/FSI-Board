<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 1. Handle File Upload
            $path = null;
            if ($request->hasFile('proof_of_payment')) {
                $path = $request->file('proof_of_payment')->store('payments', 'public');
            }

            // 2. Create Payment Record
            Payment::create([
                'transaction_id' => $request->transaction_id,
                'amount_paid' => $request->amount_paid,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'proof_of_payment' => $path,
                'status' => 'Verified', // Assuming admin input is auto-verified
            ]);

            // 3. Update Transaction Status
            $transaction = Transaction::with('payments')->lockForUpdate()->find($request->transaction_id);
            $totalPaid = $transaction->payments->sum('amount_paid');
            
            if ($totalPaid >= $transaction->total_amount) {
                $transaction->status = 'Paid';
            } elseif ($totalPaid > 0) {
                $transaction->status = 'Down Payment';
            } else {
                $transaction->status = 'Pending';
            }
            $transaction->save();

            DB::commit();

            return back()->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }
}
