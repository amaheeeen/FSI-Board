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
        $pilgrims = Pilgrim::with('transaction.package')->latest()->paginate(10);
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
            'city' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'agent_id' => 'required|exists:agents,id',
            'mahram_id' => 'nullable|exists:pilgrims,id',
        ]);

        Pilgrim::create($validated);

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
}
