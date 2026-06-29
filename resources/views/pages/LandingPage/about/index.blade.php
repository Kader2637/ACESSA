@extends('layouts.landingpage.app')

@section('title', 'Tentang Kami - ACESSA')

@section('style')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #fafafa;
        overflow-x: hidden;
    }

    /* Gradient Text Animation */
    .text-gradient-flow {
        background: linear-gradient(to right, #4f46e5, #06b6d4, #4f46e5);
        background-size: 200% auto;
        color: transparent;
        -webkit-background-clip: text;
        background-clip: text;
        animation: textFlow 5s linear infinite;
    }
    @keyframes textFlow {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    /* Glow Elements */
    .glow-blob {
        position: absolute;
        filter: blur(90px);
        z-index: 0;
        border-radius: 50%;
        animation: pulseBlob 8s infinite alternate;
    }
    @keyframes pulseBlob {
        0% { transform: scale(1) translate(0, 0); opacity: 0.3; }
        100% { transform: scale(1.1) translate(20px, -20px); opacity: 0.6; }
    }

    /* Team Card Hover Effect */
    .dev-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .dev-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.8), transparent);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .dev-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
    }
    .dev-card:hover::before {
        opacity: 1;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection

@section('content')

{{-- ==================== HERO SECTION ==================== --}}
<section class="relative pt-32 pb-24 md:pt-48 md:pb-32 overflow-hidden bg-white border-b border-slate-100">
    <div class="glow-blob bg-indigo-200 w-[600px] h-[600px] top-[-10%] left-[-10%]"></div>
    <div class="glow-blob bg-cyan-100 w-[500px] h-[500px] bottom-[-10%] right-[-5%]" style="animation-delay: -3s;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div data-aos="fade-down" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs uppercase tracking-widest mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                UNIVERSITAS MERDEKA MALANG
            </div>
            
            <h1 data-aos="fade-up" data-aos-delay="100" class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-8">
                Dibangun oleh Developer, <br />
                <span class="text-gradient-flow">Untuk Calon Developer.</span>
            </h1>
            
            <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed mb-10 max-w-3xl mx-auto">
                ACESSA adalah platform edukasi digital generasi baru. Kami memadukan arsitektur sistem yang canggih dengan kurikulum praktis untuk mencetak talenta IT berstandar industri.
            </p>

            <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#tim" class="px-8 py-4 rounded-full bg-slate-900 text-white font-bold text-lg hover:bg-indigo-600 transition-all duration-300 shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    Temui Tim Developer Kami
                </a>
                <a href="#visi" class="px-8 py-4 rounded-full bg-white border border-slate-200 text-slate-800 font-bold text-lg hover:bg-slate-50 transition-all shadow-sm flex items-center justify-center">
                    Visi & Metodologi
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ==================== VISI & METODOLOGI ==================== --}}
<section id="visi" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            {{-- Bagian Visi --}}
            <div data-aos="fade-right">
                <h2 class="text-indigo-600 font-bold tracking-widest uppercase text-sm mb-3">Visi Kami</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">
                    Menjembatani Jarak Antara Teori dan Praktik Industri.
                </h3>
                <p class="text-slate-600 text-lg mb-6 leading-relaxed">
                    Kami membangun platform ini dengan satu tujuan: memastikan setiap baris kode yang Anda pelajari relevan dengan kebutuhan perusahaan teknologi dunia nyata.
                </p>
                <div class="flex gap-4 items-start mb-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">Kurikulum Terstruktur</h4>
                        <p class="text-slate-500 text-sm">Disusun berurutan dari fundamental hingga tahap *deployment*.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">Fokus Project-Based</h4>
                        <p class="text-slate-500 text-sm">Ujian akhirnya adalah membangun portofolio produk nyata yang berfungsi.</p>
                    </div>
                </div>
            </div>

            {{-- Bagian Alur (Metodologi) --}}
            <div data-aos="fade-left" class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-200 shadow-lg relative">
                <h4 class="text-xl font-extrabold text-slate-900 mb-8">Alur Pembelajaran</h4>
                <div class="relative border-l-2 border-slate-100 ml-4 space-y-8">
                    
                    <div class="relative pl-8">
                        <div class="absolute -left-[11px] top-0.5 w-5 h-5 rounded-full bg-slate-900 border-4 border-white shadow-sm"></div>
                        <h5 class="font-bold text-slate-900">1. Pilih Tech-Stack</h5>
                        <p class="text-slate-500 text-sm mt-1">Tentukan arah keahlian (Front-End, Back-End, atau Data).</p>
                    </div>
                    
                    <div class="relative pl-8">
                        <div class="absolute -left-[11px] top-0.5 w-5 h-5 rounded-full bg-indigo-600 border-4 border-white shadow-sm"></div>
                        <h5 class="font-bold text-slate-900">2. Sesi Interaktif & Code Review</h5>
                        <p class="text-slate-500 text-sm mt-1">Pelajari modul, kerjakan kuis, dan terima umpan balik teknis.</p>
                    </div>
                    
                    <div class="relative pl-8">
                        <div class="absolute -left-[11px] top-0.5 w-5 h-5 rounded-full bg-cyan-500 border-4 border-white shadow-sm"></div>
                        <h5 class="font-bold text-slate-900">3. Build, Deploy, Lulus</h5>
                        <p class="text-slate-500 text-sm mt-1">Selesaikan proyek akhir, dapatkan sertifikat, dan bersiap untuk karier profesional.</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ==================== TIM DEVELOPER (DARK TECH THEME) ==================== --}}
<section id="tim" class="py-24 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
            <span class="text-indigo-600 font-bold tracking-widest uppercase text-sm mb-3 block">Engineering Team</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Kreator Sistem Ini</h2>
            <p class="text-slate-500 text-lg font-medium">Tim pengembang dedikatif dari Program Kreativitas Mahasiswa (PKM) Fakultas Sistem Informasi UNMER Malang.</p>
        </div>

        <div class="flex justify-center mb-12" data-aos="fade-up" data-aos-delay="100">
            <div class="clean-card w-full max-w-2xl bg-slate-50 border border-slate-200 rounded-3xl p-8 flex flex-col sm:flex-row items-center gap-8 text-center sm:text-left">
                <div class="w-24 h-24 rounded-2xl bg-slate-200 border border-slate-300 flex items-center justify-center flex-shrink-0">
                    <span class="text-3xl font-extrabold text-slate-600">LI</span>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-slate-900 mb-1">Luthfi Indana, S.Pd., M.Pd</h4>
                    <p class="text-indigo-600 text-xs font-bold uppercase tracking-widest mb-3">Project Advisor / Dosen</p>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Memberikan arahan strategis, validasi metodologi, serta memastikan pengembangan platform memenuhi standar capaian akademis dan industri.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="clean-card bg-white border border-slate-200 rounded-3xl p-8 flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center mb-6">
                    <span class="text-2xl font-extrabold text-indigo-600">AK</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-1">Abdul Kader</h4>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">Lead Developer & Architect</p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Merancang arsitektur utama sistem, mengelola tim *engineering*, dan memastikan seluruh kode berjalan efisien.
                </p>
            </div>

            <div class="clean-card bg-white border border-slate-200 rounded-3xl p-8 flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 rounded-2xl bg-cyan-50 border border-cyan-100 flex items-center justify-center mb-6">
                    <span class="text-2xl font-extrabold text-cyan-600">EA</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-1">Ezequiel A. T.</h4>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">Full-Stack Developer</p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Bertanggung jawab atas konektivitas *end-to-end*, mengintegrasikan fungsionalitas server ke antarmuka pengguna.
                </p>
            </div>

            <div class="clean-card bg-white border border-slate-200 rounded-3xl p-8 flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-6">
                    <span class="text-2xl font-extrabold text-emerald-600">RA</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-1">Rio Andhika</h4>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">Back-End / API Engineer</p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Membangun logika server, *database management*, dokumentasi API, dan memastikan keamanan data *platform*.
                </p>
            </div>

            <div class="clean-card bg-white border border-slate-200 rounded-3xl p-8 flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="w-20 h-20 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center mb-6">
                    <span class="text-2xl font-extrabold text-orange-600">VD</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-1">Viktorinus D. S.</h4>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">Front-End / UI Engineer</p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Menerjemahkan desain menjadi interaktivitas antarmuka (*interface*) tingkat tinggi untuk pengalaman pengguna terbaik.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ==================== CTA BOTTOM ==================== --}}
<section class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="zoom-in" data-aos-duration="1000">
        <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">Siap Untuk Ngoding?</h2>
        <p class="text-slate-500 text-lg md:text-xl mb-10 leading-relaxed font-medium">
            Jangan tunda lagi. Bergabunglah dengan platform yang dirancang oleh developer, untuk membantu Anda menjadi bagian dari masa depan industri teknologi.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/register/student') }}" class="px-8 py-4 rounded-full bg-slate-900 text-white font-bold text-lg hover:bg-indigo-600 transition-all shadow-xl hover:-translate-y-1">
                Daftar Akun Gratis
            </a>
            <a href="{{ url('/classroom') }}" class="px-8 py-4 rounded-full bg-white border border-slate-200 text-slate-800 font-bold text-lg hover:bg-slate-50 transition-all shadow-sm">
                Lihat Katalog Kelas
            </a>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    $(document).ready(function () {
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
    });
</script>
@endsection