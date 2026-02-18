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
        // 1. Validate Request
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'agent_id' => 'nullable|exists:agents,id',
            'room_type' => 'required|in:quad,triple,double',
            'pilgrims' => 'required|array|min:1',
            'pilgrims.*.full_name' => 'required|string|max:255',
            'pilgrims.*.passport_number' => 'required|string|max:20|distinct|unique:pilgrims,passport_number',
            'pilgrims.*.nik' => 'required|string|size:16|distinct|unique:pilgrims,nik',
            'pilgrims.*.city' => 'required|string|max:100',
            'pilgrims.*.gender' => 'required|in:Male,Female',
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {
                // 2. Retrieve Package & Check Quota calling fresh() to catch race conditions
                $package = Package::lockForUpdate()->findOrFail($request->package_id);
                $paxCount = count($request->pilgrims);
                
                if ($package->available_quota < $paxCount) {
                    throw new \Exception("Not enough quota available. Only {$package->available_quota} seats left.");
                }
    
                // 3. Calculate Total Amount
                $pricePerPax = match($request->room_type) {
                    'double' => $package->price_double,
                    'triple' => $package->price_triple,
                    default => $package->price_quad,
                };
                $totalAmount = $pricePerPax * $paxCount;
    
                // 4. Create Transaction Header
                $transaction = Transaction::create([
                    'user_id' => auth()->id() ?? 1,
                    'agent_id' => $request->agent_id,
                    'package_id' => $package->id,
                    'total_pax' => $paxCount,
                    'total_amount' => $totalAmount,
                    'status' => 'Pending',
                    'transaction_date' => now(),
                    // Generate code manually if observer fails or just allow DB auto-increment ID to generate it later? 
                    // Assuming Model Observer handles 'transaction_code'.
                ]);
    
                // 5. Create Pilgrim Details
                foreach ($request->pilgrims as $pilgrimData) {
                    Pilgrim::create([
                        'transaction_id' => $transaction->id,
                        'agent_id' => $request->agent_id,
                        'full_name' => $pilgrimData['full_name'],
                        'passport_number' => $pilgrimData['passport_number'],
                        'nik' => $pilgrimData['nik'],
                        'city' => $pilgrimData['city'],
                        'gender' => $pilgrimData['gender'],
                        'status' => 'Registered',
                    ]);
                }
                
                return redirect()->route('transactions.index')
                    ->with('success', 'Booking created successfully! Transaction Code: ' . $transaction->transaction_code);
            });
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Transaction Creation Failed: ' . $e->getMessage());
            
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

    public function invoice(Transaction $transaction)
    {
        $transaction->load(['user', 'agent', 'package', 'pilgrims', 'payments']);
        return view('transactions.invoice', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction cancelled and deleted.');
    }
}
