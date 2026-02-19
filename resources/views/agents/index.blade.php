@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Agent Management</h1>
        <p class="text-gray-500 text-sm">Performance tracking and commission management.</p>
    </div>
    <div class="flex space-x-2">
        <!-- Export Button -->
        <a href="{{ route('exports.database') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg flex items-center shadow-lg transition-transform transform hover:scale-105">
             <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
             Export Data
        </a>
        
        <!-- Import Button (Triggers Modal) -->
        <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg flex items-center shadow-lg transition-transform transform hover:scale-105">
             <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
             Import CSV
        </button>

        <a href="{{ route('agents.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg flex items-center shadow-lg transition-transform transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Agent
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
            <tr>
                <th class="px-6 py-3">Agent Name / Contact</th>
                <th class="px-6 py-3">Performance (Pax)</th>
                <th class="px-6 py-3">Total Revenue Brought</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($agents as $agent)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-900">{{ $agent->name }}</div>
                    <div class="text-xs text-gray-400">{{ $agent->phone ?? '-' }} | {{ $agent->email ?? '-' }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="text-lg font-bold text-emerald-600 mr-2">{{ $agent->transactions_count }}</span>
                        <span class="text-xs text-gray-400">Transactions</span>
                    </div>
                </td>
                <td class="px-6 py-4 font-mono font-medium text-gray-700">
                    IDR {{ number_format($agent->total_revenue) }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('agents.show', $agent->id) }}" class="text-emerald-600 hover:text-emerald-800 font-bold text-xs uppercase tracking-wide">View</a>
                    <a href="{{ route('agents.edit', $agent->id) }}" class="text-yellow-600 hover:text-yellow-800 font-bold text-xs uppercase tracking-wide">Edit</a>
                    <form action="{{ route('agents.destroy', $agent->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this agent?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs uppercase tracking-wide">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Import Agents (CSV)</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Upload a CSV file with columns: <br> <strong>Name, Phone, Email, Location</strong>
                </p>
                <form action="{{ route('imports.agents') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" accept=".csv, .txt" class="block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-full file:border-0
                      file:text-sm file:font-semibold
                      file:bg-emerald-50 file:text-emerald-700
                      hover:file:bg-emerald-100 mb-4
                    " required />
                    
                    <div class="flex justify-between">
                         <a href="{{ route('imports.template', 'agents') }}" class="text-xs text-blue-500 hover:underline mt-2">Download Template</a>
                    </div>

                    <div class="items-center px-4 py-3 mt-4">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                            Upload & Import
                        </button>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
