@extends('layouts.landingpage.app')

@section('title', 'ACESSA - Master Your Tech Stack')

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

    /* Text Gradient Flow */
    .text-gradient-flow {
        background: linear-gradient(to right, #4f46e5, #06b6d4, #8b5cf6, #4f46e5);
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

    /* Ambient Glow Blobs */
    .glow-blob {
        position: absolute;
        filter: blur(90px);
        z-index: 0;
        border-radius: 50%;
        animation: pulseBlob 8s infinite alternate;
        pointer-events: none;
    }
    @keyframes pulseBlob {
        0% { transform: scale(1) translate(0, 0); opacity: 0.3; }
        100% { transform: scale(1.1) translate(20px, -20px); opacity: 0.5; }
    }

    /* Floating Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    .animate-float { animation: float 5s ease-in-out infinite; }
    .animate-float-delayed { animation: float 6s ease-in-out infinite; animation-delay: 2.5s; }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection

@section('content')

{{-- ==================== HERO SECTION (ULTRA TECH) ==================== --}}
<section id="home" class="relative pt-32 pb-32 md:pt-48 md:pb-40 overflow-hidden bg-white flex items-center">
    <div class="glow-blob bg-indigo-300 w-[600px] h-[600px] top-[-10%] left-[-10%]"></div>
    <div class="glow-blob bg-cyan-200 w-[500px] h-[500px] bottom-[-10%] right-[-5%]" style="animation-delay: -3s;"></div>
    
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e5e7eb_1px,transparent_1px),linear-gradient(to_bottom,#e5e7eb_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-40"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 text-center lg:text-left">
                <div data-aos="fade-right" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs uppercase tracking-widest mb-8 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Belajar Generasi Baru
                </div>
                
                <h1 data-aos="fade-up" data-aos-delay="100" class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6">
                    Kuasai Tech Stack. <br />
                    Bangun Sistem <br class="hidden lg:block"/>
                    <span class="text-gradient-flow">Skala Global.</span>
                </h1>
                
                <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed mb-10 max-w-2xl mx-auto lg:mx-0">
                    Pelajari bahasa pemrograman modern, kuasai arsitektur *front-end* hingga *back-end*, dan jadilah talenta *engineering* yang diandalkan industri teknologi.
                </p>

                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="{{ url('/classroom') }}" class="px-8 py-4 rounded-full bg-slate-900 text-white font-bold text-lg hover:bg-indigo-600 transition-all duration-300 shadow-xl hover:shadow-indigo-500/40 flex items-center justify-center gap-2 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        Mulai Ngoding Sekarang
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 hidden lg:block relative" data-aos="fade-left" data-aos-delay="400">
                <div class="relative w-full aspect-square animate-float">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-cyan-500/20 rounded-full blur-3xl"></div>
                    
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[110%] bg-[#0a0a0a] rounded-2xl border border-slate-700 shadow-2xl overflow-hidden z-20">
                        <div class="flex items-center px-4 py-3 bg-slate-900 border-b border-slate-800">
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            </div>
                            <div class="mx-auto text-xs font-mono text-slate-400">index.js — ACESSA_Core</div>
                        </div>
                        <div class="p-5 font-mono text-sm leading-relaxed text-slate-300">
                            <p><span class="text-fuchsia-400">import</span> { LearningSystem } <span class="text-fuchsia-400">from</span> <span class="text-emerald-400">'@acessa/core'</span>;</p>
                            <br/>
                            <p><span class="text-indigo-400">const</span> <span class="text-blue-400">student</span> = <span class="text-fuchsia-400">new</span> <span class="text-yellow-300">LearningSystem</span>();</p>
                            <p><span class="text-blue-400">student</span>.<span class="text-yellow-300">initialize</span>({</p>
                            <p class="pl-4"><span class="text-cyan-300">track</span>: <span class="text-emerald-400">'Full-Stack Engineering'</span>,</p>
                            <p class="pl-4"><span class="text-cyan-300">mode</span>: <span class="text-emerald-400">'Project Based'</span>,</p>
                            <p>});</p>
                            <br/>
                            <p><span class="text-blue-400">student</span>.<span class="text-yellow-300">buildFuture</span>().<span class="text-yellow-300">then</span>(() <span class="text-fuchsia-400">=></span> {</p>
                            <p class="pl-4"><span class="text-blue-400">console</span>.<span class="text-yellow-300">log</span>(<span class="text-emerald-400">"Career Accelerated!"</span>);</p>
                            <p>});</p>
                            <div class="w-2 h-4 bg-slate-400 animate-pulse mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== FLOATING STATS PANEL (PREMIUM LOOK) ==================== --}}
<div class="relative z-30 -mt-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up" data-aos-delay="500">
    <div class="bg-white/80 backdrop-blur-xl border border-slate-200 shadow-2xl rounded-3xl p-8 md:p-10 flex flex-col md:flex-row justify-between items-center gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-200">
        
        <div class="w-full pt-4 md:pt-0 group cursor-default">
            <h4 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400 mb-2 group-hover:scale-110 transition-transform">3.2K+</h4>
            <p class="text-slate-500 font-extrabold uppercase tracking-widest text-xs">Peserta Aktif</p>
        </div>

        <div class="w-full pt-6 md:pt-0 group cursor-default">
            <h4 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-cyan-400 mb-2 group-hover:scale-110 transition-transform">65+</h4>
            <p class="text-slate-500 font-extrabold uppercase tracking-widest text-xs">Modul Kelas</p>
        </div>

        <div class="w-full pt-6 md:pt-0 group cursor-default">
            <h4 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400 mb-2 group-hover:scale-110 transition-transform">4.9<span class="text-2xl text-slate-400">/5</span></h4>
            <p class="text-slate-500 font-extrabold uppercase tracking-widest text-xs">Rating Ulasan</p>
        </div>

    </div>
</div>

{{-- ==================== TRUSTED BY / PARTNERS (CUSTOMIZED LOGOS) ==================== --}}
<section class="pt-24 pb-16 bg-white relative z-20 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p data-aos="fade-up" class="text-center text-xs font-bold tracking-widest text-slate-400 uppercase mb-10">Didukung & Dipercaya Oleh Institusi Terkemuka</p>
        
        <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-70 hover:opacity-100 transition-opacity duration-500">
            
            <div data-aos="fade-up" data-aos-delay="100" class="flex items-center gap-3 grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center border border-green-100 shadow-sm">
                    <svg class="w-8 h-8 text-green-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 7.5l-6-3v5.5l6 3 6-3v-5.5l-6 3z"/></svg>
                </div>
                <div class="flex flex-col text-left">
                    <span class="font-extrabold text-xl text-slate-900 leading-none">UNMER</span>
                    <span class="text-[10px] font-bold tracking-widest text-green-700 uppercase mt-1">Universitas Merdeka</span>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="200" class="flex items-center gap-3 grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100 shadow-sm">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <div class="flex flex-col text-left">
                    <span class="font-extrabold text-xl text-slate-900 leading-none">INNOVATE<span class="text-blue-600">OS</span></span>
                    <span class="text-[10px] font-bold tracking-widest text-slate-500 uppercase mt-1">Intelligence System</span>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="300" class="flex items-center gap-2 grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer">
                <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/></svg>
                <span class="font-extrabold text-2xl text-slate-800 tracking-tight">TechStartup.</span>
            </div>

            <div data-aos="fade-up" data-aos-delay="400" class="flex items-center gap-2 grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="font-extrabold text-2xl text-slate-800 tracking-tight">DevCore</span>
            </div>

        </div>
    </div>
</section>

{{-- ==================== LEARNING PATHS (DARK BENTO GRID) ==================== --}}
<section id="path" class="py-24 bg-[#0a0a0a] relative overflow-hidden">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:32px_32px]"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div class="max-w-2xl" data-aos="fade-right">
                <span class="text-indigo-400 font-extrabold tracking-widest uppercase text-xs mb-3 block">Engineering Paths</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Pilih Spesialisasi Anda</h2>
                <p class="text-slate-400 text-lg font-medium">Jalur belajar yang diarsiteki langsung oleh *developer* untuk memastikan Anda memiliki fondasi kode yang kuat.</p>
            </div>
            <div data-aos="fade-left">
                <a href="{{ url('/classroom') }}" class="group inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-full px-6 py-3.5 shadow-[0_0_20px_rgba(79,70,229,0.3)] transition-all hover:-translate-y-1">
                    Semua Katalog
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Path 1: Front End --}}
            <div data-aos="fade-up" data-aos-delay="100" class="bg-[#111111] border border-slate-800 rounded-[2rem] p-8 hover:border-indigo-500/50 transition-colors shadow-2xl relative overflow-hidden group flex flex-col h-full">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 blur-3xl rounded-full"></div>
                <div class="w-14 h-14 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-2xl flex items-center justify-center mb-6 relative z-10 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3 relative z-10">Front-End Dev</h3>
                <p class="text-slate-400 mb-8 flex-grow relative z-10 text-sm leading-relaxed">Kuasai HTML, CSS modern (Tailwind), dan JavaScript interaktif untuk membangun UI yang responsif dan ciamik.</p>
            </div>

            {{-- Path 2: Back End --}}
            <div data-aos="fade-up" data-aos-delay="200" class="bg-[#111111] border border-slate-800 rounded-[2rem] p-8 hover:border-cyan-500/50 transition-colors shadow-2xl relative overflow-hidden group flex flex-col h-full">
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 blur-3xl rounded-full"></div>
                <div class="w-14 h-14 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 rounded-2xl flex items-center justify-center mb-6 relative z-10 group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3 relative z-10">Back-End & API</h3>
                <p class="text-slate-400 mb-8 flex-grow relative z-10 text-sm leading-relaxed">Arsitekturnya aplikasi. Pelajari *database routing*, manajemen API, hingga logika bisnis tingkat lanjut.</p>
            </div>

            {{-- Path 3: Data / Fullstack --}}
            <div data-aos="fade-up" data-aos-delay="300" class="bg-[#111111] border border-slate-800 rounded-[2rem] p-8 hover:border-fuchsia-500/50 transition-colors shadow-2xl relative overflow-hidden group flex flex-col h-full">
                <div class="absolute top-0 right-0 w-32 h-32 bg-fuchsia-500/10 blur-3xl rounded-full"></div>
                <div class="w-14 h-14 bg-fuchsia-500/10 border border-fuchsia-500/30 text-fuchsia-400 rounded-2xl flex items-center justify-center mb-6 relative z-10 group-hover:bg-fuchsia-500 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3 relative z-10">Data & Analitik</h3>
                <p class="text-slate-400 mb-8 flex-grow relative z-10 text-sm leading-relaxed">Ubah data mentah dari *database* menjadi *insight* cerdas yang divisualisasikan dengan rapi dan terukur.</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== SECTION KELAS DINAMIS (AJAX) ========== --}}
<section class="relative py-24 bg-white overflow-hidden border-b border-slate-100">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">Akses Kelas Premium</h2>
            <p class="text-slate-500 text-lg font-medium">Buktikan komitmen Anda. Pilih modul, tulis baris kode pertama Anda hari ini, dan hasilkan aplikasi *real-world*.</p>
        </div>

        <div id="home-classroom-row" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-10">
            {{-- Loading State --}}
            <div id="loading-state" class="col-span-full flex flex-col items-center justify-center py-24">
                <div class="relative w-16 h-16">
                    <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
                </div>
                <p class="text-slate-500 font-bold mt-6 tracking-wide animate-pulse uppercase text-xs">Memuat Database Kelas...</p>
            </div>
        </div>
        
        <div class="mt-16 text-center" data-aos="fade-up">
            <a href="{{ url('/classroom') }}" class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold rounded-full px-8 py-4 transition-all duration-300">
                Lihat Seluruh Katalog
            </a>
        </div>
    </div>
</section>

{{-- CTA AKHIR (TECH GLOW) --}}
<section class="py-24 bg-white relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div data-aos="zoom-in" data-aos-duration="1000" class="bg-slate-900 rounded-[3rem] p-10 md:p-20 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 via-slate-900 to-cyan-600/20 z-0"></div>
            
            <div class="relative z-10">
                <h2 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight">Ketik Baris Kode Pertama Anda.</h2>
                <p class="text-slate-400 text-lg md:text-xl mb-12 max-w-2xl mx-auto leading-relaxed font-medium">
                    Gabung dengan ekosistem developer kami. Registrasi sekarang, mulai dari 0, dan capai *level* keahlian *engineering* tertinggi Anda.
                </p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <a href="{{ url('/register/student') }}" class="bg-white text-slate-900 hover:bg-indigo-50 px-10 py-5 rounded-full font-extrabold text-lg transition-all hover:scale-105 shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                        Buat Akun Developer
                    </a>
                </div>
            </div>
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

        const listContainer = $('#home-classroom-row');
        const loadingState = $('#loading-state');

        function renderHomeClassrooms(data) {
            listContainer.empty();

            if (!data || data.length === 0) {
                listContainer.append(`
                    <div data-aos="fade-up" class="col-span-full bg-white border border-slate-200 border-dashed rounded-[2rem] p-16 text-center shadow-sm">
                        <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Repository Kosong</h3>
                        <p class="text-slate-500 text-lg font-medium">Modul sedang dalam proses penyusunan (WIP). Pantau terus *update* kami!</p>
                    </div>
                `);
                AOS.refresh();
                return;
            }

            const sliceData = data.slice(0, 3);
            let delay = 100;

            sliceData.forEach(c => {
                const thumb = c.thumbnail ? `/storage/${c.thumbnail}` : `https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=600&auto=format&fit=crop`; 
                const desc = c.description
                    ? (c.description.length > 85 ? c.description.substring(0, 85) + '...' : c.description)
                    : 'Modul komprehensif yang dioptimasi untuk arsitektur pengembangan web modern.';

                const mentor = c.user_name || 'System Engineer';

                // TAMPILAN CARD AJAX
                const card = `
                    <div data-aos="fade-up" data-aos-delay="${delay}" class="group bg-white rounded-[2rem] overflow-hidden border border-slate-200 flex flex-col h-full cursor-pointer relative transition-all duration-300 hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] hover:-translate-y-2 hover:border-indigo-300" onclick="window.location.href='/classroom'">
                        
                        <div class="relative aspect-[16/9] w-full overflow-hidden bg-slate-900">
                            <img src="${thumb}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100" alt="${c.name}">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
                            
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-[10px] font-bold tracking-widest text-indigo-400 bg-slate-900/80 backdrop-blur border border-indigo-500/30 rounded-md uppercase">Tech Module</span>
                            </div>
                        </div>
                        
                        <div class="p-6 md:p-8 flex flex-col flex-grow relative bg-white">
                            <h3 class="text-xl md:text-2xl font-extrabold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors leading-snug line-clamp-2">${c.name}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-8 flex-grow line-clamp-3 font-medium">${desc}</p>
                            
                            <div class="pt-5 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-0.5">Author</p>
                                    <p class="text-sm font-extrabold text-slate-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        ${mentor}
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all duration-300 shadow-sm">
                                    <svg class="w-5 h-5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                listContainer.append(card);
                delay += 150;
            });

            setTimeout(() => { AOS.refresh(); }, 100);
        }

        $.ajax({
            url: '/api/classroom',
            method: 'GET',
            success: function (res) {
                loadingState.remove(); 
                const data = res.data || [];
                renderHomeClassrooms(data);
            },
            error: function () {
                loadingState.remove();
                renderHomeClassrooms([]);
            }
        });
    });
</script>
@endsection