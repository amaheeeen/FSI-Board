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
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-300"
                    placeholder="••••••••">
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
