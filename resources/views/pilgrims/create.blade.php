@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Register New Jamaah</h1>
        <p class="text-gray-500 text-sm">Please fill in the pilgrim's details accurately.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="{{ route('pilgrims.store') }}" method="POST">
            @csrf
            
            <!-- Personal Info -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-900 border-b border-gray-100 pb-2 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="gender" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-600">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK (KTP)</label>
                        <input type="number" name="nik" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                        <input type="text" name="passport_number" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400" required>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-900 border-b border-gray-100 pb-2 mb-4">Address</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                        <textarea name="address" rows="3" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="city" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400" required>
                    </div>
                </div>
            </div>

            <!-- Relations -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-900 border-b border-gray-100 pb-2 mb-4">Relations & Agency</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referral Agent</label>
                        <select name="agent_id" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-600" required>
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mahram (Optional)</label>
                        <select name="mahram_id" class="w-full px-5 py-4 rounded-2xl text-base border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-600">
                            <option value="">None</option>
                            <!-- In real app, search via AJAX. Here we list first 50 males -->
                            @foreach($mahrams->take(50) as $mahram)
                                <option value="{{ $mahram->id }}">{{ $mahram->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <a href="{{ route('pilgrims.index') }}" class="mr-3 px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-lg shadow-md hover:bg-emerald-700 transition-transform transform hover:-translate-y-0.5">
                    Save Pilgrim
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
