<header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-8 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        <h1 class="text-xl font-black tracking-tight text-slate-900 uppercase">
            Panel <span class="text-indigo-600">Instruktur</span>
        </h1>
    </div>

    <div class="flex items-center gap-6">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-black text-slate-900 leading-none">{{ auth()->user()->name }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Instructor Account</p>
        </div>
        <div class="w-12 h-12 rounded-2xl border-2 border-slate-100 p-0.5 overflow-hidden shadow-sm">
            <img src="{{ auth()->user()->image && auth()->user()->image !== 'user.png' ? asset('storage/'.auth()->user()->image) : '/user.png' }}" class="w-full h-full object-cover rounded-[0.85rem]" alt="Profile" onerror="this.onerror=null; this.src='/user.png';">
        </div>
    </div>
</header>