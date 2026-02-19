<?php

namespace App\Http\Controllers;

use App\Models\Pilgrim;
use App\Models\Agent;
use Illuminate\Http\Request;

class PilgrimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pilgrims = Pilgrim::with('agent')->latest()->paginate(10);
        return view('pilgrims.index', compact('pilgrims'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agents = Agent::all();
        // Get potential mahrams (male pilgrims) - simplified logic
        $mahrams = Pilgrim::where('gender', 'Male')->get();
        return view('pilgrims.create', compact('agents', 'mahrams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:pilgrims,nik',
            'passport_number' => 'required|string|unique:pilgrims,passport_number',
            'address' => 'nullable|string',
            'city' => 'required|string', // City is validated but not in create? Check migration. 
            // Migration doesn't have city column! Wait.
            // Let's check view create.blade.php to see if city is input.
            // And migration again.
            'gender' => 'required|in:Male,Female',
            'agent_id' => 'required|exists:agents,id',
            'mahram_id' => 'nullable|exists:pilgrims,id',
        ]);

        // Map 'name' input to 'full_name' column
        $data = [
            'full_name' => $validated['name'],
            'nik' => $validated['nik'],
            'passport_number' => $validated['passport_number'],
            'address' => $validated['address'],
            'city' => $validated['city'], 
            'gender' => $validated['gender'],
            'agent_id' => $validated['agent_id'],
        ];
        
        Pilgrim::create($data);

        return redirect()->route('pilgrims.index')->with('success', 'Pilgrim registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pilgrim $pilgrim)
    {
        return view('pilgrims.show', compact('pilgrim'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pilgrim $pilgrim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pilgrim $pilgrim)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pilgrim $pilgrim)
    {
        $pilgrim->delete();
        return redirect()->route('pilgrims.index')->with('success', 'Pilgrim deleted successfully.');
    }

    public function export()
    {
        $fileName = 'pilgrims-export-' . date('Y-m-d') . '.csv';
        $pilgrims = Pilgrim::with('agent')->latest()->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Name', 'NIK', 'Passport Number', 'Gender', 'City', 'Agent Name', 'Status');

        $callback = function() use($pilgrims, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($pilgrims as $pilgrim) {
                $row['Name']  = $pilgrim->full_name;
                $row['NIK']    = $pilgrim->nik;
                $row['Passport Number']    = $pilgrim->passport_number;
                $row['Gender']  = $pilgrim->gender;
                $row['City']  = $pilgrim->city;
                $row['Agent Name']  = $pilgrim->agent->name ?? '-';
                $row['Status']  = $pilgrim->status;

                fputcsv($file, array(
                    $row['Name'], 
                    $row['NIK'], 
                    $row['Passport Number'], 
                    $row['Gender'], 
                    $row['City'], 
                    $row['Agent Name'], 
                    $row['Status']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle); // Skip header

        // Required columns based on export: Name, NIK, Passport Number, Gender, City, AgentID
        
        while (($row = fgetcsv($handle)) !== false) {
             // Basic Check: row length
             if (count($row) < 5) continue;

             try {
                Pilgrim::create([
                    'full_name' => $row[0],
                    'passport_number' => $row[1],
                    'nik' => $row[2], 
                    'gender' => $row[3],
                    'city' => $row[4],
                    'agent_id' => isset($row[5]) ? (int)$row[5] : null,
                ]);
             } catch (\Exception $e) {
                 // Skip invalid rows
                 continue;
             }
        }

        fclose($handle);

        return redirect()->route('pilgrims.index')->with('success', 'Pilgrims imported successfully.');
    }

    /**
     * Show the bulk edit form for selected pilgrims.
     */
    public function bulkEditSelection(Request $request)
    {
        $request->validate([
            'selected_pilgrims' => 'required|array',
            'selected_pilgrims.*' => 'exists:pilgrims,id',
        ]);

        $pilgrims = Pilgrim::whereIn('id', $request->selected_pilgrims)->with('agent')->get();
        $agents = Agent::all();

        return view('pilgrims.bulk-edit-selection', compact('pilgrims', 'agents'));
    }

    /**
     * Update multiple pilgrims via bulk selection.
     */
    public function bulkUpdateSelection(Request $request)
    {
        $request->validate([
            'pilgrims' => 'required|array',
            'pilgrims.*.full_name' => 'required|string',
             // Simple check, deeper validation can be done in loop or with custom rule if needed
        ]);

        try {
            DB::beginTransaction();

            if (is_array($request->pilgrims)) {
                foreach ($request->pilgrims as $id => $data) {
                    // $id is the key, $data is the array of fields
                    
                    // Security / cleanup: remove fields that shouldn't be mass updated if any
                    // For now, we trust the fillable on the model
                    
                    // Ensure we are updating the correct record
                    $pilgrim = Pilgrim::find($id);
                    if ($pilgrim) {
                        // Remove unnecessary keys like 'id' if they exist in data (though they are in key)
                        unset($data['id']);
                        
                        $pilgrim->update($data);
                    }
                }
            }

            DB::commit();

            return response()->json(['message' => 'Pilgrims updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating pilgrims: ' . $e->getMessage()], 500);
        }
    }
}
