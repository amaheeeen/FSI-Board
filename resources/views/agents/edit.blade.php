@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="clay-card p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Agent</h2>

        <form action="{{ route('agents.update', $agent->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Agent Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $agent->name) }}" 
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-700 placeholder-gray-400" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $agent->phone) }}" 
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-700 placeholder-gray-400">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $agent->email) }}" 
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-700 placeholder-gray-400">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-bold text-gray-700 mb-2">Location / City</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $agent->location) }}" 
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-700 placeholder-gray-400">
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Commission Rate -->
                 <div>
                    <label for="commission_rate" class="block text-sm font-bold text-gray-700 mb-2">Commission Rate (%)</label>
                    <input type="number" step="0.01" name="commission_rate" id="commission_rate" value="{{ old('commission_rate', $agent->commission_rate) }}" 
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-700 placeholder-gray-400">
                    @error('commission_rate')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4 mt-8">
                    <a href="{{ route('agents.index') }}" class="text-gray-500 hover:text-gray-700 font-bold text-sm">Cancel</a>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform transition hover:-translate-y-0.5">
                        Update Agent
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
