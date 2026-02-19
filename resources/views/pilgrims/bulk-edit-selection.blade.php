@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto" x-data="bulkEdit()">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Massal Jamaah</h1>
            <p class="text-gray-500 mt-1">Edit Master Data untuk {{ $pilgrims->count() }} jamaah terpilih.</p>
        </div>
        <div>
            <button @click="submitForm" class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-[4px_4px_8px_#d1d5db,-4px_-4px_8px_#ffffff] transition-transform transform hover:-translate-y-0.5 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Save All Changes
            </button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toast.visible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed top-5 right-5 z-50 p-4 rounded-xl shadow-2xl flex items-center space-x-3"
         :class="toast.type === 'success' ? 'bg-emerald-100 text-emerald-800 border-l-4 border-emerald-500' : 'bg-red-100 text-red-800 border-l-4 border-red-500'"
         style="display: none;">
        <svg x-show="toast.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <svg x-show="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <span class="font-medium" x-text="toast.message"></span>
    </div>

    <form id="bulkEditForm" @submit.prevent="submitForm">
        <div class="flex flex-col gap-6">
            
            <!-- Desktop Header (Hidden on Mobile) -->
            <div class="hidden md:grid md:grid-cols-7 gap-4 bg-gray-100 p-4 rounded-lg font-bold text-gray-600 uppercase text-xs tracking-wider">
                <div class="col-span-1">Full Name</div>
                <div class="col-span-1">Agent</div>
                <div class="col-span-1">Passport</div>
                <div class="col-span-1">NIK</div>
                <div class="col-span-1">City</div>
                <div class="col-span-1">Gender</div>
                <div class="col-span-1">Status</div>
            </div>

            @foreach($pilgrims as $pilgrim)
            <!-- Card / Row Item -->
            <div class="bg-white p-6 md:p-4 rounded-2xl md:rounded-lg shadow-[inset_4px_4px_8px_#f1f5f9,inset_-4px_-4px_8px_#ffffff] border border-gray-100 md:border-none md:shadow-none md:bg-transparent md:grid md:grid-cols-7 md:gap-4 md:items-start flex flex-col gap-4">
                
                <!-- Mobile Label -->
                <div class="md:hidden font-bold text-emerald-600 mb-2 border-b pb-2">Jamaah: {{ $pilgrim->full_name }}</div>

                <div class="col-span-1">
                    <label class="md:hidden text-xs font-bold text-gray-400 uppercase">Full Name</label>
                    <input type="hidden" name="pilgrims[{{ $pilgrim->id }}][id]" value="{{ $pilgrim->id }}">
                    <input type="text" name="pilgrims[{{ $pilgrim->id }}][full_name]" value="{{ $pilgrim->full_name }}" required
                           class="w-full px-4 py-2 rounded-lg bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                    <p class="text-red-500 text-xs mt-1" x-show="errors['pilgrims.{{ $pilgrim->id }}.full_name']" x-text="errors['pilgrims.{{ $pilgrim->id }}.full_name']"></p>
                </div>

                <div class="col-span-1">
                    <label class="md:hidden text-xs font-bold text-gray-400 uppercase">Agent</label>
                    <select name="pilgrims[{{ $pilgrim->id }}][agent_id]" class="w-full px-4 py-2 rounded-lg bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $pilgrim->agent_id == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1">
                    <label class="md:hidden text-xs font-bold text-gray-400 uppercase">Passport</label>
                    <input type="text" name="pilgrims[{{ $pilgrim->id }}][passport_number]" value="{{ $pilgrim->passport_number }}" required
                           class="w-full px-4 py-2 rounded-lg bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-mono">
                    <p class="text-red-500 text-xs mt-1" x-show="errors['pilgrims.{{ $pilgrim->id }}.passport_number']" x-text="errors['pilgrims.{{ $pilgrim->id }}.passport_number']"></p>
                </div>

                <div class="col-span-1">
                    <label class="md:hidden text-xs font-bold text-gray-400 uppercase">NIK</label>
                    <input type="text" name="pilgrims[{{ $pilgrim->id }}][nik]" value="{{ $pilgrim->nik }}" required
                           class="w-full px-4 py-2 rounded-lg bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-mono">
                    <p class="text-red-500 text-xs mt-1" x-show="errors['pilgrims.{{ $pilgrim->id }}.nik']" x-text="errors['pilgrims.{{ $pilgrim->id }}.nik']"></p>
                </div>

                <div class="col-span-1">
                    <label class="md:hidden text-xs font-bold text-gray-400 uppercase">City</label>
                    <input type="text" name="pilgrims[{{ $pilgrim->id }}][city]" value="{{ $pilgrim->city }}" required
                           class="w-full px-4 py-2 rounded-lg bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                    <p class="text-red-500 text-xs mt-1" x-show="errors['pilgrims.{{ $pilgrim->id }}.city']" x-text="errors['pilgrims.{{ $pilgrim->id }}.city']"></p>
                </div>

                <div class="col-span-1">
                    <label class="md:hidden text-xs font-bold text-gray-400 uppercase">Gender</label>
                    <select name="pilgrims[{{ $pilgrim->id }}][gender]" class="w-full px-4 py-2 rounded-lg bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                        <option value="Male" {{ $pilgrim->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $pilgrim->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label class="md:hidden text-xs font-bold text-gray-400 uppercase">Status</label>
                    <input type="text" name="pilgrims[{{ $pilgrim->id }}][status]" value="{{ $pilgrim->status }}" required
                           class="w-full px-4 py-2 rounded-lg bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                </div>
            </div>
            @endforeach
        </div>
    </form>
</div>

<script>
    function bulkEdit() {
        return {
            toast: {
                visible: false,
                message: '',
                type: 'success'
            },
            errors: {},
            showToast(message, type = 'success') {
                this.toast.message = message;
                this.toast.type = type;
                this.toast.visible = true;
                setTimeout(() => {
                    this.toast.visible = false;
                }, 3000);
            },
            async submitForm() {
                const form = document.getElementById('bulkEditForm');
                const formData = new FormData(form);
                
                // Add method spoofing for Laravel
                formData.append('_method', 'PUT');

                try {
                    const response = await fetch('{{ route("pilgrims.bulk-update-selection") }}', {
                        method: 'POST', // Use POST with _method spoofing
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData // Send FormData directly, let browser handle Content-Type
                    });

                    const result = await response.json();

                    if (response.ok) {
                        this.showToast(result.message || 'Updated successfully', 'success');
                        this.errors = {};
                        // Optional: Redirect after success
                        setTimeout(() => {
                            window.location.href = "{{ route('pilgrims.index') }}";
                        }, 1500);
                    } else {
                        if (response.status === 422) {
                            this.errors = result.errors;
                            this.showToast('Validation failed. Check inputs.', 'error');
                            console.error('Validation Errors:', result.errors);
                        } else {
                            this.showToast('Server Error: ' + (result.message || response.statusText), 'error');
                            console.error('Server Error:', result);
                        }
                    }
                } catch (error) {
                    this.showToast('Network error occured.', 'error');
                    console.error('Network Error:', error);
                }
            }

                    const result = await response.json();

                    if (response.ok) {
                        this.showToast(result.message || 'Updated successfully', 'success');
                        this.errors = {};
                        // Optional: Redirect after success
                        setTimeout(() => {
                            window.location.href = "{{ route('pilgrims.index') }}";
                        }, 1500);
                    } else {
                        if (response.status === 422) {
                            this.errors = result.errors;
                            this.showToast('Validation failed. Check inputs.', 'error');
                            console.error('Validation Errors:', result.errors);
                        } else {
                            this.showToast('Server Error: ' + (result.message || response.statusText), 'error');
                            console.error('Server Error:', result);
                        }
                    }
                } catch (error) {
                    this.showToast('Network error occured.', 'error');
                    console.error('Network Error:', error);
                }
            }
        }
    }
</script>
@endsection
