<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Pilgrim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function importAgents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle);

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                // Expected format: Name, Phone, Email, Location
                Agent::create([
                    'name' => $row[0],
                    'phone' => $row[1] ?? '',
                    'email' => $row[2] ?? null,
                    'location' => $row[3] ?? null,
                ]);
            }
            DB::commit();
            return back()->with('success', 'Agents imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error: ' . $e->getMessage());
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        } finally {
            fclose($handle);
        }
    }
    
    // Placeholder for Pilgrims Import (Complex due to relationships)
    public function importPilgrims(Request $request)
    {
        return back()->with('error', 'Pilgrim import via CSV is currently disabled due to complex relationship requirements (Transaction/Package). Please input manually.');
    }

    public function downloadTemplate($type)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$type}_template.csv\"",
        ];

        $columns = match($type) {
            'agents' => ['Name', 'Phone', 'Email', 'Location'],
            'pilgrims' => ['Name', 'Passport Number', 'Gender (Male/Female)', 'Phone'],
            default => []
        };

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportDatabase()
    {
        // Simple export of Agents for now as proof of concept
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"agents_backup_" . date('Y-m-d') . ".csv\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Phone', 'Email', 'Location', 'Created At']);
            
            Agent::cursor()->each(function ($agent) use ($file) {
                fputcsv($file, [
                    $agent->id,
                    $agent->name,
                    $agent->phone,
                    $agent->email,
                    $agent->location,
                    $agent->created_at
                ]);
            });
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
