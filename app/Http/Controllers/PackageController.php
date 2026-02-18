<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ... (lines 14-17)
        $packages = Package::with(['transactions'])->latest()->get();
        return view('packages.index', compact('packages'));
    }

    // ... (lines 20-26)
    
    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch distinct history for autocomplete
        $hotelsMakkah = Package::select('hotel_makkah')->distinct()->whereNotNull('hotel_makkah')->pluck('hotel_makkah');
        $hotelsMadinah = Package::select('hotel_madinah')->distinct()->whereNotNull('hotel_madinah')->pluck('hotel_madinah');
        $airlines = Package::select('airlines')->distinct()->whereNotNull('airlines')->pluck('airlines');

        return view('packages.create', compact('hotelsMakkah', 'hotelsMadinah', 'airlines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_quad' => 'required|numeric',
            'price_triple' => 'required|numeric',
            'price_double' => 'required|numeric',
            'duration_days' => 'required|integer',
            'departure_date' => 'required|date',
            'quota' => 'required|integer',
            'hotel_makkah' => 'nullable|string|max:255',
            'hotel_madinah' => 'nullable|string|max:255',
            'airlines' => 'nullable|string|max:255',
        ]);

        // Calculate return_date
        $departure = Carbon::parse($validated['departure_date']);
        $days = (int) $validated['duration_days'];
        // Standard usage: 9 Days Package -> Return on 9th day.
        $validated['return_date'] = $departure->copy()->addDays($days - 1);
        
        Package::create($validated);

        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        // Fetch distinct history for autocomplete
        $hotelsMakkah = Package::select('hotel_makkah')->distinct()->whereNotNull('hotel_makkah')->pluck('hotel_makkah');
        $hotelsMadinah = Package::select('hotel_madinah')->distinct()->whereNotNull('hotel_madinah')->pluck('hotel_madinah');
        $airlines = Package::select('airlines')->distinct()->whereNotNull('airlines')->pluck('airlines');

        return view('packages.edit', compact('package', 'hotelsMakkah', 'hotelsMadinah', 'airlines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_quad' => 'required|numeric',
            'price_triple' => 'required|numeric',
            'price_double' => 'required|numeric',
            'duration_days' => 'required|integer',
            'departure_date' => 'required|date',
            'quota' => 'required|integer',
            'hotel_makkah' => 'nullable|string|max:255',
            'hotel_madinah' => 'nullable|string|max:255',
            'airlines' => 'nullable|string|max:255',
        ]);

        // Calculate return_date
        $departure = Carbon::parse($validated['departure_date']);
        $days = (int) $validated['duration_days'];
        $validated['return_date'] = $departure->copy()->addDays($days - 1);

        $package->update($validated);

        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        // Note: Pilgrims don't have direct agent anymore, agent is on transaction
        $package->load(['transactions.pilgrims', 'transactions.agent']);
        return view('packages.show', compact('package'));
    }

    /**
     * Export Manifest to Excel (CSV for now).
     */
    public function exportManifest(Package $package)
    {
        $fileName = 'Manifest-' . $package->name . '.csv';
        // Get all transactions for this package, and all pilgrims for those transactions
        $transactions = $package->transactions()->with(['pilgrims', 'agent'])->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Full Name', 'Passport Number', 'Gender', 'Ref Agent', 'Transaction Code');

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            $no = 1;
            foreach ($transactions as $transaction) {
                foreach ($transaction->pilgrims as $pilgrim) {
                    $row['No']  = $no++;
                    $row['Full Name']    = $pilgrim->full_name;
                    $row['Passport Number']    = $pilgrim->passport_number;
                    $row['Gender']  = $pilgrim->gender;
                    $row['Ref Agent']  = $transaction->agent->name ?? '-';
                    $row['Transaction Code']  = $transaction->transaction_code;
    
                    fputcsv($file, array($row['No'], $row['Full Name'], $row['Passport Number'], $row['Gender'], $row['Ref Agent'], $row['Transaction Code']));
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
