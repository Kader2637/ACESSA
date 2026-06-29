<aside class="w-72 bg-white border-r border-slate-200 flex flex-col hidden lg:flex h-full shrink-0">
    <div class="p-8 flex justify-center shrink-0">
        <a href="/teacher">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-auto">
        </a>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Management</p>
        
        <a href="{{ route('teacher') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('teacher') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <a href="{{ route('classroom.teacher') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('classroom.teacher*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Kelola Kelas
        </a>

        <div class="pt-10 shrink-0">
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Sistem</p>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm text-red-500 hover:bg-red-50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar Panel
            </a>
        </div>
    </nav>

    <div class="p-6 mt-auto shrink-0">
        <div class="bg-indigo-600 rounded-3xl p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase opacity-60">Teacher Mode</p>
                <p class="text-xs font-bold mt-1">ACESSA Platform</p>
            </div>
            <svg class="absolute -bottom-2 -right-2 w-16 h-16 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z"></path></svg>
        </div>
    </div>
</aside>