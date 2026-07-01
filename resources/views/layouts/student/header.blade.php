<header class="h-20 bg-white border-b border-slate-200 sticky top-0 z-30 px-6 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        <h1 class="text-xs font-black tracking-wider text-slate-900 uppercase">
            @yield('page_title', 'Menu Utama')
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block border-r border-slate-200 pr-4">
            <p class="text-xs font-extrabold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
            <p class="text-[9px] font-bold text-slate-500 uppercase mt-1 tracking-widest">Akun Mahasiswa</p>
        </div>
        <div class="w-9 h-9 rounded-lg border border-slate-200 overflow-hidden shrink-0">
            @php
                $avatar = auth()->user()->image && auth()->user()->image !== 'user.png' ? asset('storage/'.auth()->user()->image) : '/user.png';
            @endphp
            <img src="{{ $avatar }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/user.png';">
        </div>
    </div>
</header>