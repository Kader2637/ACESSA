<aside class="w-72 bg-white border-r border-slate-200 flex flex-col hidden lg:flex h-full shrink-0">
    <div class="p-8 flex justify-center shrink-0">
        <a href="/">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-auto">
        </a>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Menu Utama</p>
        
        <a href="/student/dashboard" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->is('student/dashboard') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <a href="/student/classroom" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->is('student/classroom*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Katalog Kelas
        </a>

        <a href="{{ route('join.classroom') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('join.classroom') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Bergabung Kelas
        </a>

        <a href="{{ route('student.khs') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('student.khs') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            KHS / Nilai Akademik
        </a>

        <div class="pt-10 shrink-0">
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Sistem</p>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold text-sm text-red-500 hover:bg-red-50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar
            </a>
        </div>
    </nav>
</aside>