@extends('layouts.landingpage.app')

@section('title', 'Katalog Kelas – ACESSA')

@section('style')
<style>
    body { 
        background-color: #fafafa;
        overflow-x: hidden;
    }

    /* Subtle Background Pattern (Konsisten dengan halaman lain) */
    .bg-grid-pattern {
        background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px),
                          linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Animasi Ambient di Header */
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.05); }
    }
    .glow-blob {
        position: absolute;
        filter: blur(80px);
        border-radius: 50%;
        animation: pulse-slow 6s infinite alternate;
        pointer-events: none;
    }

    /* Search Input Clean Focus */
    .search-input:focus {
        outline: none;
        border-color: #6366f1; /* Indigo 500 */
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
</style>
@endsection

@section('content')

{{-- ==================== HEADER PENCARIAN (CLEAN) ==================== --}}
<section class="relative pt-32 pb-16 md:pt-40 md:pb-24 bg-white bg-grid-pattern border-b border-slate-200 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-white/60 via-white/90 to-white pointer-events-none"></div>
    
    <div class="glow-blob bg-indigo-200 w-[400px] h-[400px] top-0 left-[20%]"></div>
    <div class="glow-blob bg-cyan-100 w-[300px] h-[300px] bottom-0 right-[20%]" style="animation-delay: -2s;"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 data-aos="fade-up" data-aos-duration="800" class="text-4xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] mb-6">
            Eksplorasi Katalog <span class="text-indigo-600">Modul</span>
        </h1>
        <p data-aos="fade-up" data-aos-duration="800" data-aos-delay="100" class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed mb-10 max-w-2xl mx-auto">
            Temukan kurikulum terbaik yang disusun oleh para *engineer* ahli. Pilih kelas, pelajari materinya, dan bangun portofolio Anda.
        </p>

        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="200" class="max-w-2xl mx-auto relative group">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="search-class" class="search-input w-full pl-14 pr-6 py-4 bg-white border-2 border-slate-200 rounded-2xl text-slate-900 text-lg font-medium placeholder-slate-400 transition-all shadow-sm hover:shadow-md" placeholder="Cari nama kelas atau instruktur...">
        </div>
    </div>
</section>

{{-- ==================== LIST KELAS (AJAX CONTAINER) ==================== --}}
<section class="py-16 md:py-24 bg-slate-50 min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div id="classroom-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-10">
            <div class="col-span-full flex flex-col items-center justify-center py-20" id="loading-state">
                <div class="relative w-16 h-16 mb-4">
                    <div class="absolute inset-0 border-4 border-slate-200 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
                </div>
                <p class="text-slate-500 font-bold uppercase tracking-widest text-sm animate-pulse">Menghubungkan ke Server...</p>
            </div>
        </div>

        <div id="no-data" class="hidden flex-col items-center justify-center py-20 text-center col-span-full w-full">
            <div class="w-32 h-32 bg-white rounded-full shadow-sm border border-slate-100 flex items-center justify-center mb-6">
                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">Data Tidak Ditemukan</h3>
            <p class="text-slate-500 text-lg font-medium max-w-md">Kata kunci yang Anda cari tidak cocok dengan kelas maupun instruktur manapun. Coba gunakan kata lain.</p>
        </div>

    </div>
</section>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        const container   = $('#classroom-container');
        const noData      = $('#no-data');
        const searchInput = $('#search-class');

        let allClassrooms = [];

        function render(list) {
            // Hapus isi container (kecuali kalau mau pertahankan skeleton loading)
            container.empty();

            if (!list || list.length === 0) {
                // Tampilkan pesan kosong, hilangkan style grid sementara biar posisinya center
                container.removeClass('grid').addClass('hidden');
                noData.removeClass('hidden').addClass('flex');
                return;
            }

            // Sembunyikan pesan kosong, kembalikan style grid
            noData.removeClass('flex').addClass('hidden');
            container.removeClass('hidden').addClass('grid');

            let delay = 0; // Untuk animasi bertahap (cascade)

            list.forEach(c => {
                const thumbnail = c.thumbnail ? `/storage/${c.thumbnail}` : `https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=600&auto=format&fit=crop`;
                const desc = c.description
                    ? (c.description.length > 90 ? c.description.substring(0, 90) + '...' : c.description)
                    : 'Modul komprehensif yang dirancang untuk mempercepat karir teknologi Anda.';
                const mentor = c.user_name || "Instruktur ACESSA";
                const initials = mentor.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

                // UI CARD (SUPER CLEAN TAILWIND)
                const html = `
                    <div data-aos="fade-up" data-aos-delay="${delay}" class="group bg-white rounded-3xl overflow-hidden border border-slate-200 flex flex-col h-full hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] hover:-translate-y-2 hover:border-indigo-300 transition-all duration-300">
                        
                        <div class="relative w-full aspect-[16/9] overflow-hidden bg-slate-900">
                            <img src="${thumbnail}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100" alt="${c.name}">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                            
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-[10px] font-bold tracking-widest text-indigo-400 bg-slate-900/80 backdrop-blur border border-indigo-500/30 rounded-lg uppercase">Materi Kelas</span>
                            </div>
                        </div>
                        
                        <div class="p-6 md:p-8 flex flex-col flex-grow relative bg-white">
                            <h3 class="text-xl font-extrabold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors leading-snug line-clamp-2">${c.name}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow line-clamp-3 font-medium">${desc}</p>
                            
                            <div class="flex flex-col gap-4 mt-auto">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-0.5">Author</p>
                                        <p class="text-sm font-extrabold text-slate-900">${mentor}</p>
                                    </div>
                                </div>
                                
                                <div class="w-full border-t border-dashed border-slate-200"></div>

                                <a href="/login" class="flex items-center justify-center gap-2 w-full py-3 bg-slate-50 text-indigo-600 font-bold rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    Mulai Akses
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                container.append(html);
                delay += 50; // Jeda animasi per card
            });
        }

        // Ambil Data Awal via AJAX
        $.ajax({
            url: '/api/classroom',
            method: 'GET',
            success: function (res) {
                $('#loading-state').remove(); // Hapus spinner bawaan
                allClassrooms = res.data || [];
                render(allClassrooms);
                // Refresh AOS untuk animasi konten yang baru datang dari AJAX
                setTimeout(() => { AOS.refresh(); }, 100);
            },
            error: function () {
                $('#loading-state').remove();
                render([]);
            }
        });

        // Search live filter
        searchInput.on('input', function () {
            const key = searchInput.val().toLowerCase();
            const filtered = allClassrooms.filter(c => {
                return (c.name || '').toLowerCase().includes(key) ||
                       (c.user_name || '').toLowerCase().includes(key);
            });
            render(filtered);
            
            // Re-trigger AOS on search
            setTimeout(() => { AOS.refresh(); }, 100);
        });

    });
</script>
@endsection