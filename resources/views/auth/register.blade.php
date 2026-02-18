@extends(auth()->check() ? 'layouts.admin' : 'layouts.guest')

@section('content')
<div class="{{ auth()->check() ? 'max-w-4xl mx-auto' : 'flex flex-col items-center justify-center min-h-screen bg-emerald-50' }}">
    
    @if(auth()->check())
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Register New Admin</h1>
        <p class="text-gray-500">Create a new account for staff or administrators.</p>
    </div>
    @endif

    <div class="bg-white p-8 rounded-[2rem] shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50 w-full {{ auth()->check() ? '' : 'max-w-md' }}">
        
        @if(!auth()->check())
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-emerald-800 tracking-wide">FSI BOARD</h2>
            <p class="text-gray-500 mt-2">Sign up to get started</p>
        </div>
        @endif

        <form method="POST" action="{{ auth()->check() ? route('register.admin.store') : route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus
                    class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300">
            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Username -->
            <div class="mb-5">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input id="username" type="text" name="username" :value="old('username')" required
                    class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300" placeholder="Username">
                @error('username') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input id="email" type="email" name="email" :value="old('email')" required
                    class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300">
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Role Selection (Only if Super Admin) -->
            @if(auth()->check() && auth()->user()->role === 'super_admin')
            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select id="role" name="role" class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-700">
                    <option value="admin_agent">Admin Agent (Standard)</option>
                    <option value="finance">Finance Staff</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            @else
                <input type="hidden" name="role" value="admin_agent">
            @endif

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300">
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300">
            </div>

            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-[5px_5px_10px_#bebebe,-5px_-5px_10px_#ffffff] active:shadow-inner transform transition hover:-translate-y-0.5">
                {{ auth()->check() ? 'Create Account' : 'Register' }}
            </button>
            
            @if(!auth()->check())
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">Already registered? Log in</a>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
