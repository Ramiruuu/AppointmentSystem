<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookEase — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { font-family: ui-sans-serif, system-ui, -apple-system, sans-serif; background: #F7F7FB; color: #1a1a2e; }

        .sidebar {
            width: 200px;
            flex-shrink: 0;
            background: #26215C;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 40;
        }

        .main-content {
            margin-left: 200px;
            min-height: 100vh;
            padding: 32px 36px;
            width: calc(100% - 200px);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.15s;
        }
        .nav-link:hover { background: rgba(255,255,255,0.06); }
        .nav-link.active { background: #3C3489; }
        .nav-label { font-size: 13px; color: #7F77DD; white-space: nowrap; }
        .nav-link.active .nav-label { color: #CECBF6; font-weight: 500; }

        .mobile-topbar { display: none; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.25s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; padding: 16px; }
            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 16px;
                background: #26215C;
                position: sticky;
                top: 0;
                z-index: 30;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.45);
                z-index: 35;
            }
            .sidebar-overlay.open { display: block; }
        }
    </style>
</head>
<body>

<!-- Mobile Top Bar -->
<div class="mobile-topbar">
    <div>
        <div style="font-size:15px; font-weight:500; color:#EEEDFE;">BookEase</div>
        <div style="font-size:10px; color:#7F77DD; margin-top:1px;">Appointment System</div>
    </div>
    <button onclick="toggleSidebar()" style="background:none; border:none; cursor:pointer; padding:4px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#AFA9EC" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div style="display:flex;">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <div style="padding:22px 20px 16px; flex-shrink:0;">
            <div style="font-size:16px; font-weight:500; color:#EEEDFE; letter-spacing:-0.01em;">BookEase</div>
            <div style="font-size:11px; color:#7F77DD; margin-top:2px;">Appointment System</div>
        </div>
        <div style="height:1px; background:#3C3489; flex-shrink:0;"></div>

        <nav style="padding:12px 10px; display:flex; flex-direction:column; gap:2px; flex:1; overflow-y:auto;">

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ request()->routeIs('dashboard') ? '#AFA9EC' : '#534AB7' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.index') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ request()->routeIs('appointments.index') ? '#AFA9EC' : '#534AB7' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="nav-label">My Appointments</span>
            </a>

            <a href="{{ route('appointments.create') }}" class="nav-link {{ request()->routeIs('appointments.create') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ request()->routeIs('appointments.create') ? '#AFA9EC' : '#534AB7' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                <span class="nav-label">Book Appointment</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ request()->routeIs('profile.*') ? '#AFA9EC' : '#534AB7' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="nav-label">Profile</span>
            </a>

            @if(Auth::user()->isAdmin())
                <div style="margin:10px 12px 4px; font-size:10px; font-weight:500; color:#534AB7; text-transform:uppercase; letter-spacing:0.08em;">Admin</div>
                <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ request()->routeIs('admin.appointments.*') ? '#AFA9EC' : '#534AB7' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="nav-label">All Appointments</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ request()->routeIs('admin.services.*') ? '#AFA9EC' : '#534AB7' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="nav-label">Services</span>
                </a>
            @endif

        </nav>

        <div style="padding:14px 18px; border-top:1px solid #3C3489; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                <div style="width:32px; height:32px; border-radius:50%; background:#3C3489; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:500; color:#CECBF6; flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="font-size:12px; font-weight:500; color:#CECBF6; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Auth::user()->name }}</div>
                    <div style="font-size:10px; color:#534AB7; margin-top:1px;">Patient</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="font-size:11px; color:#534AB7; background:none; border:none; cursor:pointer; padding:0;"
                    onmouseover="this.style.color='#E24B4A'" onmouseout="this.style.color='#534AB7'">Sign out</button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        @yield('content')
    </main>

</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }
</script>
</body>
</html>