@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Jamaah Management</h1>
        <p class="text-gray-500 text-sm">Database of all registered pilgrims.</p>
    </div>
    <div class="flex space-x-3">
        <!-- Export Button -->
        <a href="{{ route('pilgrims.export') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl shadow-[4px_4px_8px_#d1d5db,-4px_-4px_8px_#ffffff] flex items-center transition-transform transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export Data
        </a>

        <!-- Import Button -->
        <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow-[4px_4px_8px_#d1d5db,-4px_-4px_8px_#ffffff] flex items-center transition-transform transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m-4-4v12"></path></svg>
            Import Data
        </button>

        <!-- New Pilgrim -->
        <a href="{{ route('pilgrims.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-xl shadow-lg flex items-center transition-transform transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Jamaah
        </a>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Import Pilgrims</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Upload CSV file.<br>
                    Format: Name, Passport, NIK, Gender, City, AgentID
                </p>
                <form action="{{ route('pilgrims.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" accept=".csv, .txt" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 mb-4
                    "/>
                    <div class="items-center px-4 py-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-xl w-full shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Upload Data
                        </button>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-xl w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
    <p class="font-bold">Success</p>
    <p>{{ session('success') }}</p>
</div>
@endif

<div x-data="{ 
    selectedIds: [],
    toggleAll() {
        if (this.selectedIds.length === {{ $pilgrims->count() }}) {
            this.selectedIds = [];
        } else {
            this.selectedIds = [{{ $pilgrims->pluck('id')->implode(',') }}];
        }
    }
}" class="relative">

    <!-- Floating Bulk Edit Button -->
    <div x-show="selectedIds.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         class="fixed bottom-6 right-6 z-50">
        <form action="{{ route('pilgrims.bulk-edit-selection') }}" method="POST">
            @csrf
            <!-- Hidden inputs for each selected pilgrim -->
            <template x-for="id in selectedIds">
                <input type="hidden" name="selected_pilgrims[]" :value="id">
            </template>
            
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-2xl shadow-[4px_4px_10px_rgba(0,0,0,0.2)] flex items-center space-x-2 transform transition hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span x-text="'Edit ' + selectedIds.length + ' Selected'"></span>
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 w-10">
                            <input type="checkbox" 
                                   @click="toggleAll()"
                                   :checked="selectedIds.length === {{ $pilgrims->count() }} && {{ $pilgrims->count() }} > 0"
                                   class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 shadow-sm">
                        </th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">NIK</th>
                        <th class="px-6 py-3">Passport</th>
                        <th class="px-6 py-3">Agent</th>
                        <th class="px-6 py-3">City</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pilgrims as $pilgrim)
                    <tr class="hover:bg-gray-50 transition-colors" :class="selectedIds.includes({{ $pilgrim->id }}) ? 'bg-emerald-50/50' : ''">
                        <td class="px-6 py-4">
                            <input type="checkbox" value="{{ $pilgrim->id }}" x-model="selectedIds" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 shadow-sm">
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $pilgrim->full_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $pilgrim->nik }}</td>
                        <td class="px-6 py-4 font-mono text-emerald-700">{{ $pilgrim->passport_number }}</td>
                        <td class="px-6 py-4">
                            {{ $pilgrim->agent->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $pilgrim->city }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $pilgrim->status == 'departed' ? 'bg-purple-100 text-purple-700' : 
                                   ($pilgrim->status == 'visa_issued' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst(str_replace('_', ' ', $pilgrim->status ?? 'Active')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 flex justify-end items-center">
                            <a href="{{ route('pilgrims.show', $pilgrim->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Detail</a>
                            <a href="{{ route('pilgrims.edit', $pilgrim->id) }}" class="text-yellow-600 hover:text-yellow-900 font-medium ml-2">Edit</a>
                            <form action="{{ route('pilgrims.destroy', $pilgrim->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Apakah anda yakin ingin menghapus data jamaah ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $pilgrims->links() }}
    </div>
</div>
@endsection
