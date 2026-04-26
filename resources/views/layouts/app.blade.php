<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookEase — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-52 shrink-0 bg-white border-r border-gray-200 flex flex-col fixed top-0 left-0 h-screen z-30">

            <!-- Logo -->
            <div class="px-5 py-5 border-b border-gray-100">
                <div class="text-base font-semibold text-gray-900">BookEase</div>
                <div class="text-xs text-gray-400 mt-0.5">Appointment System</div>
            </div>

            <!-- Nav -->
            <nav class="flex flex-col gap-1 px-3 py-4 flex-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-800 font-medium' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('appointments.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('appointments.index') ? 'bg-indigo-50 text-indigo-800 font-medium' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    My Appointments
                </a>

                <a href="{{ route('appointments.create') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('appointments.create') ? 'bg-indigo-50 text-indigo-800 font-medium' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Book Appointment
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-800 font-medium' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a>

                @if(Auth::user()->isAdmin())
                    <div class="mt-3 mb-1 px-3">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Admin</span>
                    </div>
                    <a href="{{ route('admin.appointments.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition text-gray-500 hover:bg-gray-100 hover:text-gray-800">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        All Appointments
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition text-gray-500 hover:bg-gray-100 hover:text-gray-800">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Services
                    </a>
                @endif
            </nav>

            <!-- User + Logout -->
            <div class="px-4 py-4 border-t border-gray-100">
                <div class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-400 mb-3">Patient</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition">Sign out</button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 ml-52 min-h-screen">
            <div class="max-w-5xl mx-auto px-8 py-8">
                @yield('content')
            </div>
        </main>

    </div>

</body>

</html>