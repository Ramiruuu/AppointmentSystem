<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BookEase — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f6fb] antialiased" x-data="{ sidebarOpen: false }">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-[220px] bg-[#0f1117] flex flex-col flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-200"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 py-6 border-b border-white/5">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V9h14v12zM7 11h5v5H7z"/></svg>
            </div>
            <div>
                <div class="font-display font-bold text-white text-[15px] tracking-tight">BookEase</div>
                <div class="text-[10px] text-white/30 font-light">Appointment System</div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-[10px] text-white/25 font-semibold tracking-widest uppercase px-2 mb-2 mt-2">Main</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-lg text-[13px] font-medium transition-all group
                      {{ request()->routeIs('dashboard') ? 'bg-violet-500/20 text-white' : 'text-white/45 hover:text-white/80 hover:bg-white/5' }}">
                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                             {{ request()->routeIs('dashboard') ? 'bg-violet-400' : 'bg-white/20 group-hover:bg-white/40' }}"></span>
                Dashboard
            </a>

            <a href="{{ route('appointments.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-lg text-[13px] font-medium transition-all group
                      {{ request()->routeIs('appointments.*') ? 'bg-violet-500/20 text-white' : 'text-white/45 hover:text-white/80 hover:bg-white/5' }}">
                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                             {{ request()->routeIs('appointments.*') ? 'bg-violet-400' : 'bg-white/20 group-hover:bg-white/40' }}"></span>
                My Appointments
            </a>

            <a href="{{ route('appointments.create') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-lg text-[13px] font-medium transition-all group text-white/45 hover:text-white/80 hover:bg-white/5">
                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 bg-white/20 group-hover:bg-white/40"></span>
                Book Appointment
            </a>

            @if(Auth::user()->isAdmin())
            <p class="text-[10px] text-white/25 font-semibold tracking-widest uppercase px-2 mb-2 mt-5">Admin</p>
            <a href="{{ route('admin.appointments.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-lg text-[13px] font-medium transition-all group
                      {{ request()->routeIs('admin.appointments.*') ? 'bg-violet-500/20 text-white' : 'text-white/45 hover:text-white/80 hover:bg-white/5' }}">
                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                             {{ request()->routeIs('admin.appointments.*') ? 'bg-violet-400' : 'bg-white/20 group-hover:bg-white/40' }}"></span>
                All Appointments
            </a>
            <a href="{{ route('admin.services.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-lg text-[13px] font-medium transition-all group
                      {{ request()->routeIs('admin.services.*') ? 'bg-violet-500/20 text-white' : 'text-white/45 hover:text-white/80 hover:bg-white/5' }}">
                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                             {{ request()->routeIs('admin.services.*') ? 'bg-violet-400' : 'bg-white/20 group-hover:bg-white/40' }}"></span>
                Services
            </a>
            @endif
        </nav>

        <!-- User -->
        <div class="px-4 py-4 border-t border-white/5" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2.5 w-full text-left">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-[12px] font-medium truncate">{{ Auth::user()->name }}</div>
                    <div class="text-white/30 text-[10px]">{{ Auth::user()->isAdmin() ? 'Administrator' : 'Client' }}</div>
                </div>
                <svg class="w-3 h-3 text-white/30" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition
                 class="absolute bottom-16 left-3 right-3 bg-[#181b24] border border-white/10 rounded-xl py-1.5 shadow-xl z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-[12px] text-white/60 hover:text-white hover:bg-white/5 transition">Profile Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-[12px] text-red-400 hover:bg-red-500/10 transition">Sign Out</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 md:hidden"></div>

    <!-- Content -->
    <div class="flex-1 md:ml-[220px] flex flex-col min-h-screen">

        <!-- Topbar -->
        <header class="h-[60px] bg-white border-b border-gray-100 flex items-center justify-between px-6 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="md:hidden p-1.5 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-display font-bold text-gray-900 text-lg tracking-tight">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                @if(session('success'))
                <div class="hidden sm:flex items-center gap-1.5 bg-green-50 border border-green-100 text-green-700 text-xs px-3 py-1.5 rounded-full font-medium">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
                @endif
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-1.5 bg-violet-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-violet-700 transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/></svg>
                    Book
                </a>
            </div>
        </header>

        <!-- Flash on mobile -->
        @if(session('success') || session('error'))
        <div class="sm:hidden px-6 pt-4">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">{{ session('error') }}</div>
            @endif
        </div>
        @endif

        <!-- Page content -->
        <main class="flex-1 px-6 py-8">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>