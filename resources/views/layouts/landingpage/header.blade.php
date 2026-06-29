<div class="fixed z-50 top-4 left-0 w-full flex justify-center px-4 sm:px-6 lg:px-8 pointer-events-none">
    
    <div class="w-full max-w-7xl pointer-events-auto transition-all duration-300" data-aos="fade-down" data-aos-duration="800">
        
        <nav id="mainNavbar" class="relative bg-white/90 backdrop-blur-2xl border border-slate-200/80 shadow-[0_8px_32px_-8px_rgba(79,70,229,0.12)] rounded-2xl md:rounded-full px-5 lg:px-8 flex justify-between items-center h-16 md:h-18">

            {{-- LOGO SVG INLINE (Modern, No Image Dependency) --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group flex-shrink-0">
                {{-- Icon "A" dengan circuit / code bracket --}}
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                    <defs>
                        <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#4f46e5"/>
                            <stop offset="100%" style="stop-color:#06b6d4"/>
                        </linearGradient>
                    </defs>
                    {{-- Background rounded square --}}
                    <rect width="36" height="36" rx="10" fill="url(#logoGrad)"/>
                    {{-- Letter A shape --}}
                    <path d="M18 7L28 27H22.5L21 23H15L13.5 27H8L18 7Z" fill="white" fill-opacity="0.15"/>
                    <path d="M18 10L26 28H21L19.5 24H16.5L15 28H10L18 10Z" fill="white"/>
                    <rect x="15.5" y="20" width="5" height="2" rx="1" fill="url(#logoGrad)"/>
                    {{-- Circuit dots --}}
                    <circle cx="7" cy="18" r="1.5" fill="white" fill-opacity="0.6"/>
                    <circle cx="29" cy="18" r="1.5" fill="white" fill-opacity="0.6"/>
                    <line x1="8.5" y1="18" x2="10" y2="18" stroke="white" stroke-opacity="0.4" stroke-width="1"/>
                    <line x1="26" y1="18" x2="27.5" y2="18" stroke="white" stroke-opacity="0.4" stroke-width="1"/>
                </svg>
                {{-- Brand Name --}}
                <span class="font-extrabold text-xl tracking-tight leading-none">
                    <span class="text-slate-900">A</span><span class="bg-gradient-to-r from-indigo-600 to-cyan-500 bg-clip-text text-transparent">CESSA</span>
                </span>
            </a>

            {{-- DESKTOP MENU (Tengah) --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ url('/') }}" class="relative px-4 py-2 rounded-xl font-semibold text-[14px] transition-all duration-200 hover:text-indigo-600 hover:bg-indigo-50/70 {{ request()->is('/') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600' }}">
                    Home
                    @if(request()->is('/')) <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></span> @endif
                </a>
                <a href="{{ url('/about') }}" class="relative px-4 py-2 rounded-xl font-semibold text-[14px] transition-all duration-200 hover:text-indigo-600 hover:bg-indigo-50/70 {{ request()->is('about') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600' }}">
                    Tentang Kami
                    @if(request()->is('about')) <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></span> @endif
                </a>
                <a href="{{ url('/classroom') }}" class="relative px-4 py-2 rounded-xl font-semibold text-[14px] transition-all duration-200 hover:text-indigo-600 hover:bg-indigo-50/70 {{ request()->is('classroom*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600' }}">
                    Katalog Kelas
                    @if(request()->is('classroom*')) <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></span> @endif
                </a>
            </div>

            {{-- DESKTOP ACTION (Kanan) --}}
            <div class="hidden md:flex items-center gap-3 flex-shrink-0">
                <a href="{{ url('/register/student') }}" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 px-4 py-2 rounded-xl font-semibold text-[14px] transition-all duration-200 hover:bg-indigo-50/70">
                    Daftar
                </a>
                <a href="{{ url('/login') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-cyan-600 text-white px-6 py-2.5 rounded-full font-bold text-[14px] transition-all duration-300 shadow-md hover:shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Masuk
                </a>
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-slate-700 hover:text-indigo-600 focus:outline-none p-2 bg-slate-100 hover:bg-indigo-50 rounded-xl transition-all duration-200">
                    <svg id="menu-icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                    <svg id="menu-icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- MOBILE MENU PANEL --}}
            <div id="mobile-menu-panel" class="hidden absolute top-[calc(100%+12px)] left-0 w-full bg-white/97 backdrop-blur-2xl border border-slate-200/80 shadow-2xl rounded-2xl overflow-hidden">
                <div class="p-4 flex flex-col gap-1">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold text-sm transition-colors {{ request()->is('/') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    <a href="{{ url('/about') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold text-sm transition-colors {{ request()->is('about') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tentang Kami
                    </a>
                    <a href="{{ url('/classroom') }}" class="flex items-center gap-3 p-3 rounded-xl font-semibold text-sm transition-colors {{ request()->is('classroom*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Katalog Kelas
                    </a>
                    <div class="w-full h-px bg-slate-100 my-2"></div>
                    <div class="flex gap-3">
                        <a href="{{ url('/register/student') }}" class="flex-1 text-center py-3 rounded-xl font-bold text-sm border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                            Daftar
                        </a>
                        <a href="{{ url('/login') }}" class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-3 rounded-xl font-bold text-sm shadow-md">
                            Masuk
                        </a>
                    </div>
                </div>
            </div>

        </nav>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobile-menu-btn');
        const panel = document.getElementById('mobile-menu-panel');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');

        if (btn && panel) {
            btn.addEventListener('click', function () {
                const isHidden = panel.classList.contains('hidden');
                if (isHidden) {
                    panel.classList.remove('hidden');
                    iconOpen.classList.add('hidden');
                    iconClose.classList.remove('hidden');
                } else {
                    panel.classList.add('hidden');
                    iconOpen.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                }
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !panel.contains(e.target)) {
                    panel.classList.add('hidden');
                    iconOpen.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                }
            });
        }
    });
</script>

<style>
    #mainNavbar { height: 68px; }
    @media (min-width: 768px) { #mainNavbar { height: 72px; } }
</style>