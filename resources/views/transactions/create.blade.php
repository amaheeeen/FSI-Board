@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">New Booking</h1>
        <p class="text-gray-500 text-sm">Create a new family/group transaction.</p>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm leading-5 font-medium text-red-800">
                    There were errors with your submission
                </h3>
                <div class="mt-2 text-sm leading-5 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST" x-data="{ 
        paxCount: 1, 
        pilgrims: [{id: 1}],
        addPilgrim() {
            this.paxCount++;
            this.pilgrims.push({id: Date.now()});
        },
        removePilgrim(index) {
            if(this.paxCount > 1) {
                this.paxCount--;
                this.pilgrims.splice(index, 1);
            }
        }
    }">
        @csrf
        
        <!-- Transaction Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Booking Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Package</label>
                    <select name="package_id" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-600">
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }} (Quota: {{ $package->available_quota }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agent (Optional)</label>
                    <select name="agent_id" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-600">
                        <option value="">Direct Booking</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                    <select name="room_type" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-600">
                        <option value="quad">Quad (Room for 4)</option>
                        <option value="triple">Triple (Room for 3)</option>
                        <option value="double">Double (Room for 2)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-bold text-gray-800">Pilgrims (Jamaah)</h2>
                <button type="button" @click="addPilgrim()" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">+ Add Pilgrim</button>
            </div>

            <template x-for="(pilgrim, index) in pilgrims" :key="pilgrim.id">
                <div class="bg-gray-50 rounded-lg p-4 mb-4 relative border border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                         <span class="text-xs font-bold text-gray-500 uppercase tracking-wide" x-text="'Pilgrim ' + (index + 1)"></span>
                         <button type="button" @click="removePilgrim(index)" x-show="paxCount > 1" class="text-red-500 hover:text-red-700 text-xs font-medium">Remove</button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Full Name</label>
                            <input type="text" :name="'pilgrims['+index+'][full_name]'" class="w-full px-4 py-3 rounded-xl text-sm border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-1 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400" placeholder="Name as per Passport" required>
                        </div>
                        
                        <!-- NIK -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">NIK (KTP)</label>
                            <input type="number" :name="'pilgrims['+index+'][nik]'" class="w-full px-4 py-3 rounded-xl text-sm border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-1 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400" placeholder="16 Digit NIK" required>
                        </div>

                        <!-- Passport -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Passport Number</label>
                            <input type="text" :name="'pilgrims['+index+'][passport_number]'" class="w-full px-4 py-3 rounded-xl text-sm border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-1 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400 uppercase" required>
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">City</label>
                            <input type="text" :name="'pilgrims['+index+'][city]'" class="w-full px-4 py-3 rounded-xl text-sm border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-1 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400" required>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Gender</label>
                            <select :name="'pilgrims['+index+'][gender]'" class="w-full px-4 py-3 rounded-xl text-sm border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-1 focus:ring-emerald-500 focus:outline-none transition-all text-gray-600">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <!-- Status (Hidden/Default) -->
                        <input type="hidden" :name="'pilgrims['+index+'][status]'" value="Registered">
                    </div>
                </div>
            </template>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('transactions.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium mr-4 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg">Create Booking</button>
        </div>
    </form>
</div>
@endsection
