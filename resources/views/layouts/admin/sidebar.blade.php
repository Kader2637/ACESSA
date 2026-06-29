<aside id="admin-sidebar" class="fixed top-0 left-0 h-screen w-[280px] bg-white border-r border-slate-100 flex flex-col z-[1000] transition-transform duration-300 transform lg:translate-x-0 -translate-x-full shadow-2xl lg:shadow-none">
    
    <div class="p-8 flex justify-center border-b border-slate-50 shrink-0">
        <a href="/admin/dashboard" class="transition-transform active:scale-95">
            <img src="{{ asset('logo.png') }}" class="w-32" alt="Logo">
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar">
        <p class="px-4 py-3 text-[9px] font-black text-slate-300 uppercase tracking-widest">Management Center</p>
        
        <a href="/admin/dashboard" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ request()->is('admin/dashboard') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50' }}">
            <i data-feather="home" class="w-5 h-5"></i>
            <span class="text-sm font-bold tracking-tight">Dashboard</span>
        </a>

        <a href="/admin/classroom" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ request()->is('admin/classroom*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50' }}">
            <i data-feather="layers" class="w-5 h-5"></i>
            <span class="text-sm font-bold tracking-tight">Kelas</span>
        </a>

        <a href="/admin/teacher" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ request()->is('admin/teacher*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50' }}">
            <i data-feather="user" class="w-5 h-5"></i>
            <span class="text-sm font-bold tracking-tight">Guru</span>
        </a>

        <a href="/admin/approval" class="flex items-center justify-between px-4 py-4 rounded-2xl transition-all {{ request()->is('admin/approval*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50' }}">
            <div class="flex items-center gap-4">
                <i data-feather="check-circle" class="w-5 h-5"></i>
                <span class="text-sm font-bold tracking-tight">Approval</span>
            </div>
        </a>
    </nav>

    <div class="p-6 bg-slate-50/50 border-t border-slate-100 mt-auto shrink-0">
        <div class="flex items-center gap-3 p-3 bg-white rounded-2xl border border-slate-100 shadow-sm mb-4">
            <img src="{{ asset('favicon.png') }}" class="w-9 h-9 rounded-xl object-cover" alt="">
            <div class="overflow-hidden">
                <p class="text-[11px] font-black text-slate-900 truncate">System Management</p>
                <p class="text-[8px] font-bold text-indigo-500 uppercase tracking-tighter">ACESSA System</p>
            </div>
        </div>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        <button onclick="document.getElementById('logoutForm').submit();" class="flex items-center justify-center gap-3 w-full py-4 bg-white border-2 border-red-50 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white hover:border-red-500 transition-all active:scale-95 group">
            <i data-feather="log-out" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
            Log Out System
        </button>
    </div>
</aside>