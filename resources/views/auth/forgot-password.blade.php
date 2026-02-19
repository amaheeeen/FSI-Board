<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-emerald-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - FSI Board</title>
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
            <h2 class="text-2xl font-bold text-emerald-800 tracking-wide">Reset Password</h2>
            <p class="text-gray-500 mt-2 text-sm">Enter your email to receive a reset link.</p>
        </div>

        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="fixed top-5 right-5 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center mb-4 transition transform duration-500 ease-in-out z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300"
                    placeholder="admin@umrah.com">
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                    Email Password Reset Link
                </button>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-emerald-600">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>
