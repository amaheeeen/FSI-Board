<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Package;
use App\Models\Pilgrim;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'agent', 'package'])
            ->latest()
            ->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $packages = Package::where('status', 'Open')->get();
        $agents = Agent::all();
        return view('transactions.create', compact('packages', 'agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'agent_id' => 'nullable|exists:agents,id',
            'room_type' => 'required|in:quad,triple,double',
            'pilgrims' => 'required|array|min:1',
            'pilgrims.*.full_name' => 'required|string',
            'pilgrims.*.passport_number' => 'required|string|unique:pilgrims,passport_number',
            'pilgrims.*.gender' => 'required|in:Male,Female',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $package = Package::findOrFail($request->package_id);
                $paxCount = count($request->pilgrims);
                
                // Check Quota (Double check inside transaction for better integrity)
                // Reload package to get latest data
                if ($package->fresh()->available_quota < $paxCount) {
                    throw new \Exception('Not enough quota available for ' . $paxCount . ' pilgrims.');
                }
    
                // Calculate Total Amount
                $pricePerPax = match($request->room_type) {
                    'double' => $package->price_double,
                    'triple' => $package->price_triple,
                    default => $package->price_quad,
                };
                $totalAmount = $pricePerPax * $paxCount;
    
                // Create Transaction
                $transaction = Transaction::create([
                    'user_id' => auth()->id() ?? 1, // Use auth user if available
                    'agent_id' => $request->agent_id,
                    'package_id' => $package->id,
                    'total_pax' => $paxCount,
                    'total_amount' => $totalAmount,
                    'status' => 'Pending',
                    'transaction_date' => now(),
                ]);
    
                // Create Pilgrims
                foreach ($request->pilgrims as $pilgrimData) {
                    Pilgrim::create([
                        'transaction_id' => $transaction->id,
                        'full_name' => $pilgrimData['full_name'],
                        'passport_number' => $pilgrimData['passport_number'],
                        'gender' => $pilgrimData['gender'],
                    ]);
                }
            });

            // Retrieve the last transaction to show success message (not ideal but works for now as transaction object inside closure is local)
            // Or better, just redirect to index. valid transaction code generation is handled by model boot method usually or we can rely on latest.
            $transaction = Transaction::latest()->first(); 
    
            return redirect()->route('transactions.index')->with('success', 'Booking created successfully! Code: ' . $transaction->transaction_code);
    
        } catch (\Exception $e) {
            return back()->with('error', 'Booking failed: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['pilgrims', 'payments', 'package', 'agent']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        // Simple edit for status only for now
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:Pending,Down Payment,Paid,Cancelled'
        ]);

        $transaction->update(['status' => $request->status]);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaction status updated.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction cancelled and deleted.');
    }
}
