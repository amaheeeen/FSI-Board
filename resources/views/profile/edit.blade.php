@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
        <p class="text-gray-500">Manage your account settings and preferences.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="md:col-span-1">
            <div class="bg-white p-8 rounded-[2rem] shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50 text-center">
                <div class="relative inline-block mb-4">
                    <img class="h-24 w-24 rounded-full mx-auto shadow-lg border-4 border-white object-cover" 
                         src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}">
                    <div class="absolute bottom-1 right-1 bg-emerald-500 w-5 h-5 rounded-full border-2 border-white"></div>
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-700 tracking-wider">
                    {{ ucfirst($user->role ?? 'Staff') }}
                </span>
                <p class="text-gray-400 text-sm mt-4">
                    Member since {{ $user->created_at->format('M Y') }}
                </p>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="md:col-span-2">
            <div class="bg-white p-8 rounded-[2rem] shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50">
                <h3 class="text-lg font-bold text-gray-700 mb-6 border-b border-gray-100 pb-2">Edit Details</h3>
                
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Avatar Upload -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                                <div class="relative w-20 h-20">
                                    <img src="{{ $user->avatar_url }}" alt="Profile" class="w-full h-full rounded-full object-cover shadow-sm border border-gray-200">
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Change Photo</label>
                                <input type="file" name="avatar" accept="image/*"
                                    class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-emerald-50 file:text-emerald-700
                                      hover:file:bg-emerald-100
                                    "/>
                                @error('avatar') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                                class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                            @error('username') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <hr class="border-gray-100">

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password <span class="text-gray-400 font-normal">(Leave blank to keep current)</span></label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-[5px_5px_10px_#bebebe,-5px_-5px_10px_#ffffff] active:shadow-inner transform transition hover:-translate-y-0.5">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
