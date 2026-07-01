<header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-8 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        <h1 class="text-xl font-black tracking-tight text-slate-900 uppercase">
            Panel <span class="text-indigo-600">Instruktur</span>
        </h1>
    </div>

    <div class="relative">
        <button onclick="toggleProfileDropdown()" class="flex items-center gap-4 focus:outline-none hover:opacity-90 active:scale-98 transition-all">
            <div class="text-right hidden sm:block text-left">
                <p class="text-sm font-black text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Instructor Account</p>
            </div>
            <div class="w-12 h-12 rounded-2xl border-2 border-slate-100 p-0.5 overflow-hidden shadow-sm shrink-0">
                <img src="{{ auth()->user()->image && auth()->user()->image !== 'user.png' ? asset('storage/'.auth()->user()->image) : '/user.png' }}" class="w-full h-full object-cover rounded-[0.85rem]" alt="Profile" onerror="this.onerror=null; this.src='/user.png';">
            </div>
        </button>

        {{-- Dropdown Menu --}}
        <div id="profile-dropdown" class="absolute right-0 mt-3 w-48 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 hidden animate-zoom-in z-[1100] text-left">
            <div class="px-4 py-2 border-b border-slate-100 sm:hidden">
                <p class="text-xs font-extrabold text-slate-900 leading-none truncate">{{ auth()->user()->name }}</p>
                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 tracking-wider">Instructor</p>
            </div>
            <a href="/teacher" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-all">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <form id="dropdownLogoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <button onclick="document.getElementById('dropdownLogoutForm').submit();" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-red-650 hover:bg-red-50 transition-all text-left">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar
            </button>
        </div>
    </div>
</header>