<aside id="student-sidebar" class="fixed top-0 left-0 h-screen w-64 bg-slate-900 text-slate-350 flex flex-col z-[1000] border-r border-slate-800 transition-transform duration-300 transform lg:translate-x-0 -translate-x-full">
    {{-- Brand Logo (Yellow/Gold Campus Theme) --}}
    <div class="px-6 py-6 flex items-center gap-3 flex-shrink-0 border-b border-slate-800">
        <a href="/student/dashboard" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 bg-yellow-500 text-slate-950 rounded-lg flex items-center justify-center font-black text-lg shadow-md shadow-yellow-500/20">
                A
            </div>
            <div>
                <span class="font-extrabold text-[15px] text-white tracking-tight">ACESSA</span>
                <div class="text-[9px] text-yellow-500 font-black uppercase tracking-widest leading-none">Portal Mahasiswa</div>
            </div>
        </a>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 px-4 py-6 space-y-5 overflow-y-auto scrollbar-thin">
        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-yellow-500 uppercase tracking-[0.18em]">Kontrol Utama</p>
            <div class="space-y-1">
                <a href="/student/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('student/dashboard') ? 'bg-yellow-500 text-slate-950 shadow-md shadow-yellow-500/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-feather="home" class="w-4 h-4"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/student/classroom" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('student/classroom*') ? 'bg-yellow-500 text-slate-950 shadow-md shadow-yellow-500/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-feather="book-open" class="w-4 h-4"></i>
                    <span>Katalog Kelas</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-yellow-500 uppercase tracking-[0.18em]">Akademik &amp; Registrasi</p>
            <div class="space-y-1">
                <a href="{{ route('join.classroom') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->routeIs('join.classroom') ? 'bg-yellow-500 text-slate-950 shadow-md shadow-yellow-500/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-feather="plus-circle" class="w-4 h-4"></i>
                    <span>Bergabung Kelas</span>
                </a>
                <a href="{{ route('student.khs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->routeIs('student.khs') ? 'bg-yellow-500 text-slate-950 shadow-md shadow-yellow-500/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-feather="file-text" class="w-4 h-4"></i>
                    <span>KHS / Nilai Akademik</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-yellow-500 uppercase tracking-[0.18em]">Tugas &amp; Diskusi</p>
            <div class="space-y-1">
                <a href="/student/taskColection" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('student/taskColection*') ? 'bg-yellow-500 text-slate-950 shadow-md shadow-yellow-500/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-feather="clipboard" class="w-4 h-4"></i>
                    <span>Daftar Tugas Saya</span>
                </a>
                <a href="/student/diskusi" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('student/diskusi*') ? 'bg-yellow-500 text-slate-950 shadow-md shadow-yellow-500/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-feather="message-square" class="w-4 h-4"></i>
                    <span>Forum Diskusi Global</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-yellow-500 uppercase tracking-[0.18em]">Sesi Virtual</p>
            <div class="space-y-1">
                <a href="/zoom-session" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('zoom-session*') ? 'bg-yellow-500 text-slate-950 shadow-md shadow-yellow-500/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-feather="video" class="w-4 h-4"></i>
                    <span>Jadwal Zoom Sesi</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Student profile info card --}}
    <div class="p-4 bg-slate-950 flex-shrink-0 border-t border-slate-800">
        <div class="flex items-center gap-3 p-3 bg-slate-900 rounded-xl border border-slate-800 mb-3 shadow-sm">
            <div class="w-9 h-9 bg-yellow-500 text-slate-950 rounded-lg flex items-center justify-center font-extrabold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden flex-grow text-left">
                <p class="text-[12px] font-bold text-white leading-tight truncate">{{ auth()->user()->name }}</p>
                <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Mahasiswa Aktif</p>
            </div>
        </div>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        <button onclick="document.getElementById('logoutForm').submit();" class="flex items-center justify-center gap-2.5 w-full py-3 bg-red-500/10 hover:bg-red-650 text-red-400 hover:text-white border border-red-500/20 hover:border-red-600 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all active:scale-[0.98]">
            <i data-feather="log-out" class="w-3.5 h-3.5"></i>
            Keluar Panel
        </button>
    </div>
</aside>