<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Teacher Panel – ACESSA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --sidebar-w: 280px;
            --sidebar-bg: #0b0f1a;
            --sidebar-border: rgba(255,255,255,0.06);
            --sidebar-hover: rgba(79,70,229,0.12);
            --sidebar-active-from: #4f46e5;
            --sidebar-active-to: #6366f1;
            --header-h: 68px;
            --indigo: #4f46e5;
            --cyan: #06b6d4;
        }

        html, body { height: 100%; overflow: hidden; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f8;
            color: #0f172a;
        }

        /* ── Sidebar ──────────────────────────────────── */
        #app-sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            height: 100vh;
            flex-shrink: 0;
            overflow: hidden;
            position: relative;
            transition: width 0.3s ease, transform 0.3s ease;
        }
        #app-sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 200px;
            background: radial-gradient(ellipse at top center, rgba(79,70,229,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Nav item */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
            margin: 1px 0;
        }
        .nav-item:hover {
            background: var(--sidebar-hover);
            color: rgba(255,255,255,0.9);
        }
        .nav-item:hover .nav-icon {
            color: #818cf8;
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(79,70,229,0.25), rgba(99,102,241,0.15));
            color: white;
            border: 1px solid rgba(99,102,241,0.25);
            box-shadow: 0 4px 15px rgba(79,70,229,0.15);
        }
        .nav-item.active .nav-icon {
            color: #818cf8;
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: linear-gradient(to bottom, #4f46e5, #06b6d4);
            border-radius: 0 4px 4px 0;
        }
        .nav-icon {
            width: 18px; height: 18px;
            flex-shrink: 0;
            color: rgba(255,255,255,0.3);
            transition: color 0.2s;
        }
        .nav-badge {
            margin-left: auto;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 20px;
            background: rgba(79,70,229,0.3);
            color: #a5b4fc;
            border: 1px solid rgba(99,102,241,0.25);
        }
        .nav-badge.new {
            background: rgba(16,185,129,0.2);
            color: #34d399;
            border-color: rgba(16,185,129,0.25);
        }
        .nav-section-label {
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.2);
            padding: 16px 14px 6px;
        }

        /* ── Submenu ──────────────────────────────────── */
        .submenu { overflow: hidden; max-height: 0; transition: max-height 0.3s ease; }
        .submenu.open { max-height: 300px; }
        .submenu-item {
            display: flex; align-items: center; gap-8px;
            gap: 8px;
            padding: 8px 14px 8px 44px;
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(255,255,255,0.4);
            border-radius: 10px;
            transition: all 0.2s;
            text-decoration: none;
            margin: 1px 0;
        }
        .submenu-item:hover { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.8); }
        .submenu-item.active { color: #a5b4fc; }
        .submenu-item::before { content: '·'; margin-right: 4px; font-size: 18px; line-height: 0; vertical-align: middle; }
        .chevron { transition: transform 0.3s ease; }
        .chevron.open { transform: rotate(90deg); }

        /* ── Scrollbar ──────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* ── Main content ──────────────────────────────── */
        #main-header {
            height: var(--header-h);
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226,232,240,0.8);
        }

        /* ── Toastr override ────────────────────────────── */
        .toast { font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; border-radius: 14px !important; }

        /* Mobile sidebar overlay */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
            backdrop-filter: blur(4px);
        }
        @media (max-width: 1023px) {
            #app-sidebar { position: fixed; top: 0; left: 0; bottom: 0; z-index: 50; transform: translateX(-100%); }
            #app-sidebar.open { transform: translateX(0); }
            #sidebar-overlay.show { display: block; }
        }
    </style>
    @yield('style')
</head>
<body class="antialiased h-full">

<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ========== SIDEBAR ========== --}}
    <aside id="app-sidebar">

        {{-- Brand Logo --}}
        <div class="px-5 py-6 flex items-center gap-3 flex-shrink-0 border-b border-white/5">
            <a href="{{ route('teacher') }}" class="flex items-center gap-2.5 group">
                <svg width="34" height="34" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="sbLogoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#4f46e5"/>
                            <stop offset="100%" style="stop-color:#06b6d4"/>
                        </linearGradient>
                    </defs>
                    <rect width="36" height="36" rx="10" fill="url(#sbLogoGrad)"/>
                    <path d="M18 10L26 28H21L19.5 24H16.5L15 28H10L18 10Z" fill="white"/>
                    <rect x="15.5" y="20" width="5" height="2" rx="1" fill="url(#sbLogoGrad)"/>
                </svg>
                <div>
                    <span class="font-extrabold text-[15px] text-white tracking-tight">A<span class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">CESSA</span></span>
                    <div class="text-[9px] text-white/30 font-bold uppercase tracking-widest leading-none">Teacher Panel</div>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4">

            {{-- OVERVIEW --}}
            <div class="nav-section-label">Overview</div>

            <a href="{{ route('teacher') }}" class="nav-item {{ request()->routeIs('teacher') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                Dashboard
            </a>

            {{-- MANAJEMEN KELAS --}}
            <div class="nav-section-label">Manajemen Kelas</div>

            <a href="{{ route('classroom.teacher') }}" class="nav-item {{ request()->routeIs('classroom.teacher*') && !request()->routeIs('classroom.detail') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Kelola Kelas
            </a>

            <a href="{{ route('classroom.detail') }}" class="nav-item {{ request()->routeIs('classroom.detail') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Detail Kelas
            </a>

            {{-- TUGAS & PENILAIAN --}}
            <div class="nav-section-label">Tugas & Penilaian</div>

            <a href="{{ route('task.assignmentAssessment') }}" class="nav-item {{ request()->routeIs('task.assignmentAssessment') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Penilaian Tugas
            </a>

            <a href="{{ route('teacher.task.history') }}" class="nav-item {{ request()->routeIs('teacher.task.history') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat Tugas
                <span class="nav-badge new">Baru</span>
            </a>

            {{-- ZOOM & SESI --}}
            <div class="nav-section-label">Sesi & Meeting</div>

            <a href="{{ route('zoom.session') }}" class="nav-item {{ request()->routeIs('zoom.session') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Mulai Sesi Zoom
            </a>

            <a href="{{ route('teacher.zoom.history') }}" class="nav-item {{ request()->routeIs('teacher.zoom.history') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                Riwayat Zoom
                <span class="nav-badge new">Baru</span>
            </a>

            {{-- STATISTIK --}}
            <div class="nav-section-label">Statistik</div>

            <a href="{{ route('teacher.statistics') }}" class="nav-item {{ request()->routeIs('teacher.statistics') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Statistik Kelas
                <span class="nav-badge new">Baru</span>
            </a>

            {{-- PROFIL & SISTEM --}}
            <div class="nav-section-label">Akun</div>

            <a href="{{ route('teacher.profile') }}" class="nav-item {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>

            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="nav-item" style="color:rgba(248,113,113,0.7)">
                <svg class="nav-icon" style="color:rgba(248,113,113,0.5)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </a>

        </nav>

        {{-- User Profile Card at Bottom --}}
        <div class="p-4 flex-shrink-0 border-t border-white/5">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/8 transition-colors cursor-pointer" onclick="window.location.href='{{ route('teacher.profile') }}'">
                <div class="w-9 h-9 rounded-xl overflow-hidden border border-white/10 flex-shrink-0">
                    <img src="{{ auth()->user()->image && auth()->user()->image !== 'user.png' ? asset('storage/'.auth()->user()->image) : '/user.png' }}" class="w-full h-full object-cover" alt="Profile" onerror="this.onerror=null; this.src='/user.png';">
                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-cyan-500 items-center justify-center text-white font-bold text-sm hidden">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-[12px] font-bold leading-tight truncate">{{ auth()->user()->name }}</p>
                    <p class="text-white/30 text-[10px] font-medium mt-0.5">Instructor</p>
                </div>
                <svg class="w-4 h-4 text-white/20 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

    </aside>

    {{-- ========== MAIN AREA ========== --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- ── Top Header ── --}}
        <header id="main-header" class="flex items-center justify-between px-6 md:px-8 flex-shrink-0 sticky top-0 z-30">
            
            {{-- Left: Mobile hamburger + Breadcrumb --}}
            <div class="flex items-center gap-4">
                {{-- Mobile menu toggle --}}
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                {{-- Breadcrumb --}}
                <div class="hidden sm:flex items-center gap-2 text-sm">
                    <span class="text-slate-400 font-medium">ACESSA</span>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-400 font-medium">Teacher</span>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-bold text-slate-900">@yield('page_title', 'Dashboard')</span>
                </div>
            </div>

            {{-- Right: Actions + Profile --}}
            <div class="flex items-center gap-3">
                {{-- Quick Zoom Button --}}
                <button onclick="openQuickZoomModal()" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs transition-all border border-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Mulai Zoom
                </button>

                {{-- Notification Bell --}}
                <div class="relative">
                    <button class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-700 transition-all relative" id="notifBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                    </button>
                </div>

                {{-- Divider --}}
                <div class="w-px h-6 bg-slate-200"></div>

                {{-- Profile --}}
                <div class="flex items-center gap-2.5 cursor-pointer group">
                    <div class="text-right hidden sm:block">
                        <p class="text-[13px] font-extrabold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Instructor</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl border-2 border-slate-100 group-hover:border-indigo-200 overflow-hidden transition-all shadow-sm">
                        <img src="{{ auth()->user()->image && auth()->user()->image !== 'user.png' ? asset('storage/'.auth()->user()->image) : '/user.png' }}" class="w-full h-full object-cover" alt="Profile" onerror="this.onerror=null; this.src='/user.png';">
                    </div>
                </div>
            </div>

        </header>

        {{-- ── Main Content ── --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-6 md:p-8 max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>
</div>

{{-- ========== GLOBAL ZOOM SCHEDULING MODAL ========== --}}
<div id="globalZoomModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeQuickZoomModal()"></div>
    <div class="relative bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        
        {{-- Header --}}
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm">Virtual Zoom Manager</h3>
                <p class="text-slate-400 text-[10px] font-semibold mt-0.5">Kelola sesi meeting virtual Anda</p>
            </div>
            <button onclick="closeQuickZoomModal()" class="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400">✕</button>
        </div>

        {{-- Modal Tabs --}}
        <div class="flex border-b border-slate-100 px-6 py-2 bg-slate-50/50 gap-2">
            <button onclick="switchGlobalZoomTab('list')" id="gzt-btn-list" class="px-4 py-2 rounded-xl text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100">Sesi Aktif Hari Ini</button>
            <button onclick="switchGlobalZoomTab('create')" id="gzt-btn-create" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-900">Tambah Jadwal Baru</button>
        </div>

        {{-- Content Area --}}
        <div class="p-6 overflow-y-auto flex-1">
            
            {{-- Tab 1: Today's Meetings --}}
            <div id="gzt-content-list" class="flex flex-col gap-3">
                <div id="gzt-list-loading" class="py-12 flex flex-col items-center justify-center gap-2">
                    <div class="w-6 h-6 border-2 border-slate-200 border-t-indigo-500 rounded-full animate-spin"></div>
                    <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest animate-pulse">Memuat jadwal...</p>
                </div>
                <div id="gzt-list-empty" class="py-12 text-center text-slate-400 text-xs font-bold hidden">
                    Tidak ada jadwal Zoom untuk hari ini.
                </div>
                <div id="gzt-list-container" class="flex flex-col gap-3"></div>
            </div>

            {{-- Tab 2: Create Schedule --}}
            <form id="gzt-create-form" class="hidden flex flex-col gap-4">
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Pilih Kelas</label>
                    <select id="gzt-classroom-select" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" required>
                        <option value="">Pilih Kelas</option>
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Pilih Materi / Course</label>
                    <select id="gzt-course-select" name="course_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" required disabled>
                        <option value="">Pilih Materi</option>
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Topik Pertemuan</label>
                    <input type="text" name="title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" placeholder="Contoh: Sesi Tanya Jawab Tugas A" required>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Deskripsi / Agenda</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" placeholder="Tulis rincian atau agenda pertemuan..."></textarea>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Waktu Pertemuan</label>
                    <input type="datetime-local" name="meeting_time" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" required>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Tautan Zoom Manual (Opsional)</label>
                    <input type="url" name="zoom_link" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" placeholder="https://zoom.us/j/...">
                    <p class="text-[9px] text-slate-400 font-semibold mt-1">Kosongkan tautan untuk membuat otomatis menggunakan API Zoom.</p>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeQuickZoomModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md">Jadwalkan Sesi</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AOS Init
    $(document).ready(function () {
        AOS.init({ once: true, duration: 750, easing: 'ease-out-cubic' });

        // Toastr config
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 4000,
        };

        // Flash messages
        @if(session('success'))
        toastr.success('{{ session('success') }}');
        @endif
        @if(session('error'))
        toastr.error('{{ session('error') }}');
        @endif
    });

    // Sidebar toggle
    function toggleSidebar() {
        const sb = document.getElementById('app-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sb.classList.toggle('open');
        overlay.classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('app-sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('show');
    }

    // ── GLOBAL ZOOM MODAL LOGIC ──
    function openQuickZoomModal() {
        $('#globalZoomModal').removeClass('hidden').addClass('flex');
        switchGlobalZoomTab('list');
        loadGlobalZoomSchedules();
        loadGlobalClassrooms();
    }
    function closeQuickZoomModal() {
        $('#globalZoomModal').removeClass('flex').addClass('hidden');
    }

    function switchGlobalZoomTab(tab) {
        if (tab === 'list') {
            $('#gzt-btn-list').addClass('bg-indigo-50 text-indigo-600 border border-indigo-100').removeClass('text-slate-500');
            $('#gzt-btn-create').removeClass('bg-indigo-50 text-indigo-600 border border-indigo-100').addClass('text-slate-500');
            $('#gzt-content-list').show();
            $('#gzt-create-form').hide();
        } else {
            $('#gzt-btn-create').addClass('bg-indigo-50 text-indigo-600 border border-indigo-100').removeClass('text-slate-500');
            $('#gzt-btn-list').removeClass('bg-indigo-50 text-indigo-600 border border-indigo-100').addClass('text-slate-500');
            $('#gzt-content-list').hide();
            $('#gzt-create-form').show();
        }
    }

    function loadGlobalClassrooms() {
        const select = $('#gzt-classroom-select');
        if (select.children('option').length > 1) return; // already loaded
        
        const teacherId = '{{ auth()->user()->id }}';
        $.ajax({
            url: `/api/my/classroom/teacher/data/${teacherId}`,
            method: 'GET',
            success: function(res) {
                const list = res.data || [];
                list.forEach(k => {
                    select.append(`<option value="${k.id}">${k.name}</option>`);
                });
            }
        });
    }

    // Dynamic classroom course loading
    $('#gzt-classroom-select').on('change', function() {
        const classId = $(this).val();
        const courseSelect = $('#gzt-course-select');
        courseSelect.empty().append('<option value="">Pilih Materi</option>').prop('disabled', true);

        if (!classId) return;

        $.ajax({
            url: `/api/teacher/course/data/${classId}`,
            method: 'GET',
            success: function(res) {
                const list = res.data || res || [];
                if (list.length > 0) {
                    courseSelect.prop('disabled', false);
                    list.forEach(c => {
                        courseSelect.append(`<option value="${c.id}">${c.name}</option>`);
                    });
                }
            }
        });
    });

    function loadGlobalZoomSchedules() {
        $('#gzt-list-loading').show();
        $('#gzt-list-empty').addClass('hidden');
        const container = $('#gzt-list-container');
        container.empty();

        const teacherId = '{{ auth()->user()->id }}';
        let items = [];

        $.ajax({
            url: `/api/my/classroom/teacher/data/${teacherId}`,
            method: 'GET',
            success: function(res) {
                const classes = res.data || [];
                if (classes.length === 0) {
                    $('#gzt-list-loading').hide();
                    $('#gzt-list-empty').removeClass('hidden');
                    return;
                }

                let completed = 0;
                classes.forEach(kelas => {
                    $.ajax({
                        url: `/api/zoom-meetings/${kelas.id}`,
                        method: 'GET',
                        success: function(mRes) {
                            const list = (mRes.data || mRes || []).map(m => ({
                                ...m, 
                                classroom_name: kelas.name,
                                classroom_id: kelas.id
                            }));
                            items = [...items, ...list];
                        },
                        complete: function() {
                            completed++;
                            if (completed === classes.length) {
                                renderGlobalSchedules(items);
                            }
                        }
                    });
                });
            },
            error: () => {
                $('#gzt-list-loading').hide();
                $('#gzt-list-empty').removeClass('hidden');
            }
        });
    }

    function renderGlobalSchedules(meetings) {
        $('#gzt-list-loading').hide();
        const container = $('#gzt-list-container');
        container.empty();

        const now = new Date();
        const todayMeetings = meetings.filter(m => {
            const d = new Date(m.meeting_time);
            return d.toDateString() === now.toDateString(); // filter for today
        });

        if (todayMeetings.length === 0) {
            $('#gzt-list-empty').removeClass('hidden');
            return;
        }

        $('#gzt-list-empty').addClass('hidden');

        todayMeetings.sort((a,b) => new Date(a.meeting_time) - new Date(b.meeting_time));

        todayMeetings.forEach(m => {
            const mTime = new Date(m.meeting_time);
            const diffMs = Math.abs(now - mTime);
            const isActiveHour = diffMs < (45 * 60 * 1000); // 45 minutes margin

            const cardBorder = isActiveHour ? 'border-emerald-500 bg-emerald-500/5 ring-1 ring-emerald-500/20' : 'border-slate-100 bg-slate-50';
            const badge = isActiveHour 
                ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[9px] font-black border border-emerald-200 animate-pulse">🔴 Jam Sesi Aktif</span>`
                : `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-200 text-slate-600 text-[9px] font-bold">Terjadwal</span>`;

            const timeStr = mTime.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}) + ' WIB';

            container.append(`
                <div class="p-4 border ${cardBorder} rounded-2xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            ${badge}
                            <span class="text-[9px] font-bold text-slate-400 truncate max-w-[150px]">${m.classroom_name}</span>
                        </div>
                        <h4 class="font-extrabold text-slate-900 text-xs">${m.topic || m.title || 'Sesi Zoom'}</h4>
                        <p class="text-slate-500 text-[10px] font-bold mt-1">Pukul ${timeStr}</p>
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100/60 pt-3">
                        <span class="text-[9px] font-mono text-slate-400">ID: ${m.meeting_number || '—'}</span>
                        <a href="/zoom-session?meeting_number=${m.meeting_number}&passcode=${m.passcode || ''}&role=1&course_id=${m.classroom_id || ''}" 
                           class="px-4 py-2 ${isActiveHour ? 'bg-emerald-600 hover:bg-emerald-500 shadow-sm' : 'bg-indigo-600 hover:bg-indigo-500'} text-white font-bold text-[10px] rounded-lg transition-all">
                            Mulai Sesi
                        </a>
                    </div>
                </div>
            `);
        });
    }

    // Submit new Zoom schedule via AJAX
    $('#gzt-create-form').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Menjadwalkan...');

        $.ajax({
            url: '/api/zoom-meetings',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                toastr.success('Pertemuan Zoom berhasil dijadwalkan!');
                $('#gzt-create-form')[0].reset();
                $('#gzt-course-select').prop('disabled', true);
                
                // Refresh list
                switchGlobalZoomTab('list');
                loadGlobalZoomSchedules();

                // If on zoom history page, refresh table
                if (typeof window.location.pathname !== 'undefined' && window.location.pathname.includes('/zoom/history')) {
                    location.reload();
                }
            },
            error: function(err) {
                const msg = err.responseJSON ? err.responseJSON.message : 'Gagal menjadwalkan meeting.';
                toastr.error(msg);
            },
            complete: () => btn.prop('disabled', false).text('Jadwalkan Sesi')
        });
    });
</script>
@yield('script')
</body>
</html>