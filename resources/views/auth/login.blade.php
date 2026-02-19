<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-emerald-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - FSI Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .clay-card {
            background-color: white;
            border-radius: 2rem;
            box-shadow: 12px 12px 24px #d1d5db, -12px -12px 24px #ffffff;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center">
    <div class="clay-card p-10 w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-emerald-800 tracking-wide">FSI BOARD</h2>
            <p class="text-gray-500 mt-2">Sign in to manage your Umrah & Hajj</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email or Username -->
            <div class="mb-5">
                <label for="login" class="block text-sm font-medium text-gray-700 mb-2">Email or Username</label>
                <input id="login" type="text" name="login" :value="old('login')" required autofocus
                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300"
                    placeholder="admin@umrah.com or admin">
                @error('login') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div x-data="{ show: false }" class="relative w-full">
                    <input :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                        class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300 pr-12"
                        :placeholder="show ? 'Enter your password' : '••••••••'">
                    
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                        <!-- Eye Icon (Show) - Visible when password is HIDDEN (!show) -->
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Off Icon (Hide) - Visible when password is SHOWN (show) -->
                        <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mb-8">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">Forgot password?</a>
            </div>

            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>
