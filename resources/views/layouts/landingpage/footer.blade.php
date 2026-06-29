<footer class="bg-white border-t border-slate-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            
            {{-- Bagian Kiri: Copyright --}}
            <div class="text-sm font-medium text-slate-500 text-center md:text-left">
                &copy; {{ date('Y') }} <span class="font-extrabold text-slate-900 tracking-tight">ACESSA</span>. All rights reserved.
            </div>
            
            {{-- Bagian Kanan: Link & Badge --}}
            <div class="flex flex-col sm:flex-row items-center gap-5 md:gap-8 text-sm font-medium text-slate-500">
                
                <div class="flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-3 py-1.5 rounded-md text-[11px] font-bold tracking-widest uppercase shadow-sm">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Trusted Platform
                </div>

                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-indigo-600 transition-colors">Kebijakan Privasi</a>
                    <span class="text-slate-300">|</span>
                    <a href="#" class="hover:text-indigo-600 transition-colors">Syarat & Ketentuan</a>
                </div>

            </div>
        </div>
    </div>
</footer>