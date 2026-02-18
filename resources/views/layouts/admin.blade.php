<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FSI Board - Umrah & Hajj Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        /* Claymorphism Utilities */
        .clay-card {
            background-color: white;
            border-radius: 1.5rem; /* rounded-3xl */
            box-shadow: 8px 8px 16px #d1d5db, -8px -8px 16px #ffffff;
        }
        .clay-sidebar {
            box-shadow: 5px 5px 10px #d1d5db, -5px -5px 10px #ffffff;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-700">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar (Floating Clay) -->
        <aside class="w-full md:w-64 flex-shrink-0 flex flex-col transition-all duration-300 md:h-[calc(100vh-2rem)] md:m-4 bg-white rounded-3xl clay-sidebar sticky top-4 z-50 transform"
               :class="{ '-ml-[17rem]': !sidebarOpen && window.innerWidth < 768 }">
            
            <div class="h-20 flex items-center justify-center border-b border-gray-100 px-4">
                <span class="text-2xl font-bold text-emerald-600 tracking-wider">FSI BOARD</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <div class="pt-4 pb-2 px-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Operations
                </div>

                <a href="{{ route('pilgrims.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('pilgrims*') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Jamaah
                </a>

                <a href="{{ route('packages.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('packages*') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Paket
                </a>

                <a href="{{ route('agents.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('agents*') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Agen
                </a>

                <div class="pt-4 pb-2 px-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Finance
                </div>

                <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('transactions*') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Transaksi
                </a>
                
                <a href="{{ route('finance.dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('finance.dashboard') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Keuangan
                </a>
                
                <div class="pt-4 pb-2 px-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                    System
                </div>

                <a href="{{ route('logs.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('logs*') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Audit Logs
                </a>
                
                <a href="{{ route('manual') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('manual') ? 'bg-emerald-50 text-emerald-700 shadow-inner font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50' }}">
                   <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                   User Manual
                </a>
            </nav>
            
            <div class="p-6">
                <!-- Profile Link -->
                <a href="{{ route('profile.edit') }}" class="block">
                    <div class="flex items-center p-3 bg-gray-50 rounded-2xl cursor-pointer hover:bg-emerald-50 transition-colors">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full shadow-md object-cover" src="{{ auth()->user()->avatar_url }}" alt="">
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-sm font-bold text-gray-700 truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ '@' . (auth()->user()->username ?? 'admin') }}</p>
                        </div>
                    </div>
                </a>

                <!-- Register New Admin (Super Admin Only) -->
                @if(auth()->user() && auth()->user()->role === 'super_admin')
                <div class="mt-4">
                    <a href="{{ route('register.admin') }}" class="flex items-center justify-center w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-all">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Register New Admin
                    </a>
                </div>
                @endif
                
                <div class="mt-4 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-h-screen transition-all duration-300">
            <!-- Mobile Header -->
            <div class="md:hidden flex items-center justify-between bg-white border-b p-4 shadow-sm">
                <span class="text-xl font-bold text-emerald-600">FSI BOARD</span>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <div class="p-6 md:p-8 flex-1 overflow-y-auto">
                @yield('content')
            </div>
        </main>

    </div>
</body>
</html>
