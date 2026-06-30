<header class="h-20 glass-header sticky top-0 z-[900] px-6 md:px-8 flex items-center justify-between transition-all duration-300 shrink-0">
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all active:scale-95">
            <i data-feather="menu" class="w-5 h-5"></i>
        </button>
        
        <h1 class="text-xs font-extrabold text-slate-900 tracking-wider uppercase">
            @yield('page_title', 'Panel Kontrol')
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block border-r border-slate-200 pr-4">
            <p class="text-[11px] font-extrabold text-slate-900 leading-none">Administrator Utama</p>
            <p class="text-[9px] font-bold text-slate-500 uppercase mt-1 tracking-widest">Sesi Aktif</p>
        </div>
        
        <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center border border-slate-200 shadow-sm text-white font-extrabold text-sm shrink-0">
            A
        </div>
    </div>
</header>