<header class="h-20 bg-white border-b border-slate-200 sticky top-0 z-30 px-6 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        {{-- Mobile Hamburger Toggle --}}
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-slate-50 border border-slate-200/85 text-slate-700 active:scale-95 transition-all shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <h1 class="text-xs font-black tracking-wider text-slate-900 uppercase">
            @yield('page_title', 'Menu Utama')
        </h1>
    </div>

    <div class="relative">
        <button onclick="toggleProfileDropdown()" class="flex items-center gap-4 focus:outline-none hover:opacity-90 active:scale-98 transition-all">
            <div class="text-right hidden sm:block border-r border-slate-200 pr-4 text-left">
                <p class="text-xs font-extrabold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[9px] font-bold text-slate-500 uppercase mt-1 tracking-widest">Akun Mahasiswa</p>
            </div>
            <div class="w-9 h-9 rounded-lg border border-slate-200 overflow-hidden shrink-0">
                @php
                    $avatar = auth()->user()->image && auth()->user()->image !== 'user.png' ? asset('storage/'.auth()->user()->image) : '/user.png';
                @endphp
                <img src="{{ $avatar }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/user.png';">
            </div>
        </button>

        {{-- Dropdown Menu --}}
        <div id="profile-dropdown" class="absolute right-0 mt-3 w-48 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 hidden animate-zoom-in z-[1100] text-left">
            <div class="px-4 py-2 border-b border-slate-100 sm:hidden">
                <p class="text-xs font-extrabold text-slate-900 leading-none truncate">{{ auth()->user()->name }}</p>
                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Mahasiswa</p>
            </div>
            <a href="/student/dashboard" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-all">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <form id="dropdownLogoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <button onclick="document.getElementById('dropdownLogoutForm').submit();" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition-all text-left">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar
            </button>
        </div>
    </div>
</header>