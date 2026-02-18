<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = Agent::withCount('transactions')->latest()->get()->map(function ($agent) {
            // Calculate total revenue brought by this agent
            $totalRevenue = $agent->transactions->sum('total_amount');
            
            // Calculate total paid (commission base?)
            // For now just show revenue and pilgrim count
            $agent->total_revenue = $totalRevenue;
            
            return $agent;
        });

        return view('agents.index', ['agents' => $agents]); // Pass collection, not pagination for now or paginate manually
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Agent::create($validated);

        return redirect()->route('agents.index')->with('success', 'Agent created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        $agent->load(['pilgrims.transaction.package']);
        return view('agents.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
