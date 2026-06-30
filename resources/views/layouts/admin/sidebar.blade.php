<aside id="admin-sidebar" class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-slate-200 flex flex-col z-[1000] transition-transform duration-300 transform lg:translate-x-0 -translate-x-full">
    
    {{-- Brand Logo (Solid Academic Navy, No Gradients) --}}
    <div class="px-6 py-6 flex items-center gap-3 flex-shrink-0 border-b border-slate-200 relative z-10">
        <a href="/admin/dashboard" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-extrabold text-lg shadow-sm">
                A
            </div>
            <div>
                <span class="font-extrabold text-[15px] text-slate-900 tracking-tight">ACESSA</span>
                <div class="text-[9px] text-indigo-600 font-bold uppercase tracking-widest leading-none">Portal Admin</div>
            </div>
        </a>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-5 relative z-10 scrollbar-thin">
        
        {{-- Section 1: Ringkasan --}}
        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-indigo-600/70 uppercase tracking-[0.18em]">Kontrol Utama</p>
            <div class="space-y-1">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="home" class="w-4 h-4"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/approval" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/approval*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="check-circle" class="w-4 h-4"></i>
                    <span>Pusat Persetujuan</span>
                </a>
            </div>
        </div>

        {{-- Section 2: Pengguna --}}
        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-emerald-650 uppercase tracking-[0.18em]">Manajemen Pengguna</p>
            <div class="space-y-1">
                <a href="/admin/teacher" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/teacher*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="users" class="w-4 h-4"></i>
                    <span>Kelola Guru &amp; Dosen</span>
                </a>
                <a href="/admin/student" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/student*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="user" class="w-4 h-4"></i>
                    <span>Kelola Mahasiswa</span>
                </a>
            </div>
        </div>

        {{-- Section 3: Akademik --}}
        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-amber-600 uppercase tracking-[0.18em]">Akademik &amp; Kelas</p>
            <div class="space-y-1">
                <a href="/admin/classroom" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/classroom*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="layers" class="w-4 h-4"></i>
                    <span>Kelola Kelas</span>
                </a>
                <a href="/admin/task" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/task*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="file-text" class="w-4 h-4"></i>
                    <span>Materi &amp; Tugas</span>
                </a>
            </div>
        </div>

        {{-- Section 4: Audit & Aktivitas --}}
        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-purple-600 uppercase tracking-[0.18em]">Audit &amp; Aktivitas</p>
            <div class="space-y-1">
                <a href="/admin/zoom" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/zoom*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="video" class="w-4 h-4"></i>
                    <span>Jadwal Zoom</span>
                </a>
                <a href="/admin/forum" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/forum*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="message-square" class="w-4 h-4"></i>
                    <span>Moderasi Forum</span>
                </a>
            </div>
        </div>

        {{-- Section 5: Konfigurasi --}}
        <div>
            <p class="px-3 pb-2 text-[9px] font-black text-rose-600/80 uppercase tracking-[0.18em]">Pengaturan Sistem</p>
            <div class="space-y-1">
                <a href="/admin/semester" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-[11px] transition-all duration-150 {{ request()->is('admin/semester*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-feather="calendar" class="w-4 h-4"></i>
                    <span>Tahun &amp; Semester</span>
                </a>
            </div>
        </div>

    </nav>

    {{-- System Profil Card (Solid Colors, No Gradients) --}}
    <div class="p-4 bg-slate-50 flex-shrink-0 border-t border-slate-200 relative z-10">
        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 mb-3 shadow-sm">
            <div class="w-9 h-9 bg-indigo-650 rounded-lg flex items-center justify-center text-white font-extrabold text-sm flex-shrink-0">
                A
            </div>
            <div class="overflow-hidden flex-grow">
                <p class="text-[12px] font-bold text-slate-800 leading-tight truncate">Administrator</p>
                <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Hak Akses Penuh</p>
            </div>
        </div>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        <button onclick="document.getElementById('logoutForm').submit();" class="flex items-center justify-center gap-2.5 w-full py-3 bg-red-50 hover:bg-red-600 text-red-650 hover:text-white border border-red-100 hover:border-red-600 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all active:scale-[0.98]">
            <i data-feather="log-out" class="w-3.5 h-3.5"></i>
            Keluar Sistem
        </button>
    </div>
</aside>