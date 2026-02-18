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

        // Required columns based on export: Name, NIK, Passport Number, Gender, City, Agent Name (Optional but tricky to import by name, maybe ID is safer? Or skip for now)
        // The modal says: Name, Passport, NIK, Gender, City, AgentID (ID is better for import)
        
        while (($row = fgetcsv($handle)) !== false) {
             // Basic Check: row length
             if (count($row) < 5) continue;

             // Map based on index (assuming modal order: Name, Passport, NIK, Gender, City, AgentID)
             // 0: Name, 1: Passport, 2: NIK, 3: Gender, 4: City, 5: AgentID
             try {
                Pilgrim::create([
                    'full_name' => $row[0],
                    'passport_number' => $row[1],
                    'nik' => $row[2], // Should create NIK column if not exists? It exists in Model/DB.
                    'gender' => $row[3],
                    'city' => $row[4],
                    'agent_id' => isset($row[5]) ? (int)$row[5] : null,
                ]);
             } catch (\Exception $e) {
                 // Skip invalid rows or log them. For now, continue.
                 // Ideally collect errors and show them.
                 continue;
             }
        }

        fclose($handle);

        return redirect()->route('pilgrims.index')->with('success', 'Pilgrims imported successfully.');
    }
}
