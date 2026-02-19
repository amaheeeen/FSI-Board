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
    public function index(Request $request)
    {
        // Filter Parameters
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        if ($request->ajax()) {
            return response()->json($this->getChartData($selectedMonth, $selectedYear));
        }

        $transactions = Transaction::with(['user', 'agent', 'package'])
            ->latest()
            ->paginate(10);
            
        $chartData = $this->getChartData($selectedMonth, $selectedYear);

        return view('transactions.index', array_merge(compact('transactions'), $chartData));
    }

    private function getChartData($month, $year)
    {
        // Transaction Trend (Daily for Month view, or Monthly for Year view?)
        // Let's stick to "Yearly View" = Monthly Breakdown, "Monthly View" = Daily breakdown?
        // User just said "Time Filter".
        // Let's implement logic: 
        // If specific month is selected (standard view), show DAILY trend for that month.
        // If "All Months"? No, filter is always set to something.
        
        // $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $labels = [];
        $data = [];
        
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = \Carbon\Carbon::createFromDate($year, $month, $d)->format('Y-m-d');
            $labels[] = $d; // Day number
            
            $dailyTotal = Transaction::whereDate('transaction_date', $date)
                ->sum('total_amount');
            $data[] = $dailyTotal;
        }

        return [
            'chartLabels' => $labels,
            'chartData' => $data,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'chartTitle' => 'Daily Transaction Trend (' . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . ')'
        ];
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
        $packages = Package::where('status', 'Open')->get();
        // Include the current package even if it's not open, so it doesn't disappear
        if (!$packages->contains($transaction->package)) {
            $packages->push($transaction->package);
        }
        $agents = Agent::all();
        return view('transactions.edit', compact('transaction', 'packages', 'agents'));
    }

    public function editPilgrims(Transaction $transaction)
    {
        $transaction->load('pilgrims.agent');
        $agents = Agent::all();
        return view('transactions.pilgrims-bulk-edit', compact('transaction', 'agents'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Handle Bulk Pilgrim Update
        if ($request->has('pilgrims')) {
            $request->validate([
                'pilgrims' => 'required|array',
                'pilgrims.*.full_name' => 'required|string|max:255',
                // Add other validations as needed, similar to PilgrimController
            ]);

            try {
                DB::beginTransaction();

                if (is_array($request->pilgrims)) {
                    foreach ($request->pilgrims as $id => $data) {
                        // Securely find pilgrim belonging to this transaction
                        $pilgrim = Pilgrim::where('id', $id)
                                          ->where('transaction_id', $transaction->id)
                                          ->first();
                        
                        if ($pilgrim) {
                            unset($data['id']); // Remove ID from data if present
                            $pilgrim->update($data);
                        }
                    }
                }

                DB::commit();

                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Pilgrims data updated successfully.'
                    ], 200);
                }

                return back()->with('success', 'Pilgrims updated successfully.');

            } catch (\Exception $e) {
                DB::rollBack();
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to update pilgrims: ' . $e->getMessage()
                    ], 500);
                }
                
                return back()->with('error', 'Failed to update pilgrims.');
            }
        }

        // Handle Standard Transaction Details Update
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'agent_id' => 'nullable|exists:agents,id',
            'transaction_date' => 'required|date',
            'status' => 'required|in:Pending,Down Payment,Paid,Cancelled'
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Transaksi Utama
            $transaction->update($validated);

            // 2. Cascade Update ke seluruh Jamaah terkait (Business Rule: Satu Transaksi = Satu Agen)
            if ($request->has('agent_id')) {
                Pilgrim::where('transaction_id', $transaction->id)->update([
                    'agent_id' => $request->agent_id
                ]);
            }

            DB::commit();
            return redirect()->route('transactions.show', $transaction)->with('success', 'Transaksi dan data agen jamaah berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
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
