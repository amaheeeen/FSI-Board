@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Add New Agent</h1>
        <p class="text-gray-500">Register a new partner agent to the system.</p>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50">
        <form method="POST" action="{{ route('agents.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Agent Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="e.g. PT Berkah Travel">
                </div>

                <!-- Phone -->
                <div class="col-span-1">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" id="phone" name="phone" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="+62 812...">
                </div>

                <!-- Email -->
                <div class="col-span-1">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="agent@example.com">
                </div>

                <!-- Location -->
                <div class="col-span-1">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location/City</label>
                    <input type="text" id="location" name="location"
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="Jakarta">
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('agents.index') }}" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 font-bold transition-all">Cancel</a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-[5px_5px_10px_#bebebe,-5px_-5px_10px_#ffffff] active:shadow-inner transform transition hover:-translate-y-0.5">
                    Save Agent
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
