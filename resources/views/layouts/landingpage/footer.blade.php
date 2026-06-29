<footer class="bg-[#060610] border-t border-white/5 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        
        {{-- Main Footer Content --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-12">
            
            {{-- Brand Column --}}
            <div class="md:col-span-5">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-5 group w-fit">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="footerLogoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#4f46e5"/>
                                <stop offset="100%" style="stop-color:#06b6d4"/>
                            </linearGradient>
                        </defs>
                        <rect width="36" height="36" rx="10" fill="url(#footerLogoGrad)"/>
                        <path d="M18 10L26 28H21L19.5 24H16.5L15 28H10L18 10Z" fill="white"/>
                        <rect x="15.5" y="20" width="5" height="2" rx="1" fill="url(#footerLogoGrad)"/>
                        <circle cx="7" cy="18" r="1.5" fill="white" fill-opacity="0.6"/>
                        <circle cx="29" cy="18" r="1.5" fill="white" fill-opacity="0.6"/>
                    </svg>
                    <span class="font-extrabold text-xl tracking-tight text-white">A<span class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">CESSA</span></span>
                </a>
                <p class="text-slate-400 text-sm leading-relaxed font-medium mb-6 max-w-xs">
                    Platform belajar coding generasi baru. Kuasai tech stack modern dan bangun karier engineering yang solid bersama komunitas kami.
                </p>
                {{-- Social Icons --}}
                <div class="flex items-center gap-3">
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:bg-indigo-600/20 hover:text-indigo-400 hover:border-indigo-500/40 flex items-center justify-center transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:bg-indigo-600/20 hover:text-indigo-400 hover:border-indigo-500/40 flex items-center justify-center transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:bg-indigo-600/20 hover:text-indigo-400 hover:border-indigo-500/40 flex items-center justify-center transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0 1 12 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:bg-indigo-600/20 hover:text-indigo-400 hover:border-indigo-500/40 flex items-center justify-center transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Links Columns --}}
            <div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-8">
                
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-[0.15em] mb-5">Platform</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Beranda</a></li>
                        <li><a href="{{ url('/classroom') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Katalog Kelas</a></li>
                        <li><a href="{{ url('/about') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ url('/login') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Masuk</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-[0.15em] mb-5">Learning Path</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/classroom') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Front-End Dev</a></li>
                        <li><a href="{{ url('/classroom') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Back-End & API</a></li>
                        <li><a href="{{ url('/classroom') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Data & Analytics</a></li>
                        <li><a href="{{ url('/classroom') }}" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Full-Stack Path</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-[0.15em] mb-5">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm font-medium transition-colors">Cookie Policy</a></li>
                    </ul>
                    {{-- Trust Badge --}}
                    <div class="mt-6 inline-flex items-center gap-1.5 text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Trusted Platform
                    </div>
                </div>

            </div>
        </div>

        {{-- Divider --}}
        <div class="border-t border-white/5 pt-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-xs font-medium">
                    &copy; {{ date('Y') }} <span class="text-white font-bold">ACESSA</span>. All rights reserved. Built with ❤️ for developers.
                </p>
                <div class="flex items-center gap-1.5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-emerald-400 text-xs font-bold">All systems operational</span>
                </div>
            </div>
        </div>

    </div>
</footer>