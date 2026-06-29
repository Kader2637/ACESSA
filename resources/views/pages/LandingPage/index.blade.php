@extends('layouts.landingpage.app')

@section('title', 'ACESSA — Platform Belajar Coding Generasi Baru')

@section('style')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    /* =============================================
       GLOBAL TOKENS
    ============================================= */
    :root {
        --indigo: #4f46e5;
        --cyan:   #06b6d4;
        --slate:  #0f172a;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa;
        overflow-x: hidden;
    }

    /* =============================================
       GRADIENT UTILITIES
    ============================================= */
    .text-gradient {
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 60%, #8b5cf6 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradShift 6s linear infinite;
    }
    @keyframes gradShift {
        0%   { background-position: 0%   center; }
        100% { background-position: 200% center; }
    }

    /* =============================================
       HERO ANIMATIONS
    ============================================= */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33%       { transform: translateY(-12px) rotate(1deg); }
        66%       { transform: translateY(-6px) rotate(-1deg); }
    }
    @keyframes floatB {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50%       { transform: translateY(-18px) rotate(-2deg); }
    }
    @keyframes shimmer {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(79,70,229,0.3), 0 0 40px rgba(6,182,212,0.1); }
        50%       { box-shadow: 0 0 40px rgba(79,70,229,0.6), 0 0 80px rgba(6,182,212,0.2); }
    }
    @keyframes typeLine {
        from { width: 0; }
        to   { width: 100%; }
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0; }
    }

    .animate-float   { animation: float  6s ease-in-out infinite; }
    .animate-float-b { animation: floatB 8s ease-in-out infinite; }

    /* Code editor card */
    .code-card {
        background: #0d0d1a;
        border: 1px solid rgba(79,70,229,0.3);
        border-radius: 16px;
        box-shadow: 0 30px 80px -20px rgba(79,70,229,0.35), 0 0 0 1px rgba(79,70,229,0.1);
        animation: pulseGlow 4s ease-in-out infinite;
        overflow: hidden;
    }
    .code-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(79,70,229,0.05), transparent 50%, rgba(6,182,212,0.05));
        pointer-events: none;
    }
    .code-token-purple { color: #c084fc; }
    .code-token-cyan   { color: #67e8f9; }
    .code-token-green  { color: #4ade80; }
    .code-token-blue   { color: #93c5fd; }
    .code-token-yellow { color: #fde68a; }
    .code-token-white  { color: #e2e8f0; }

    /* Badge pill */
    .badge-live {
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16,185,129,0.25);
        color: #059669;
    }

    /* Floating stat card */
    .stat-card {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.8);
        border-radius: 20px;
        box-shadow: 0 20px 50px -15px rgba(79,70,229,0.12), 0 2px 8px rgba(0,0,0,0.06);
    }

    /* Section dark */
    .section-dark {
        background: #080812;
        background-image:
            radial-gradient(ellipse at 20% 50%, rgba(79,70,229,0.12) 0%, transparent 60%),
            radial-gradient(ellipse at 80% 20%, rgba(6,182,212,0.08) 0%, transparent 60%),
            linear-gradient(to right, #ffffff08 1px, transparent 1px),
            linear-gradient(to bottom, #ffffff08 1px, transparent 1px);
        background-size: auto, auto, 40px 40px, 40px 40px;
    }

    /* Path cards */
    .path-card {
        background: linear-gradient(135deg, #0f0f1a 0%, #111827 100%);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .path-card:hover {
        transform: translateY(-8px) scale(1.01);
        border-color: rgba(79,70,229,0.4);
        box-shadow: 0 30px 60px -20px rgba(79,70,229,0.3), 0 0 0 1px rgba(79,70,229,0.2);
    }
    .path-card .icon-wrap {
        transition: all 0.3s ease;
    }
    .path-card:hover .icon-wrap {
        transform: scale(1.15) rotate(-5deg);
    }

    /* Class cards */
    .class-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .class-card:hover {
        transform: translateY(-10px);
        border-color: rgba(79,70,229,0.3);
        box-shadow: 0 30px 60px -20px rgba(79,70,229,0.18), 0 8px 25px rgba(0,0,0,0.08);
    }

    /* CTA glow */
    .cta-section {
        background: radial-gradient(ellipse at 50% 0%, rgba(79,70,229,0.25) 0%, transparent 70%), #080812;
    }
    .cta-glow-btn {
        position: relative;
        overflow: hidden;
    }
    .cta-glow-btn::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -60%;
        width: 40%;
        height: 200%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        transform: skewX(-20deg);
        animation: shimmer 3s ease-in-out infinite;
    }

    /* Testimonials */
    .testimonial-card {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .testimonial-card:hover {
        border-color: #e0e7ff;
        box-shadow: 0 20px 40px -15px rgba(79,70,229,0.12);
        transform: translateY(-4px);
    }

    /* Tech stack tags */
    .tech-tag {
        background: rgba(79,70,229,0.06);
        border: 1px solid rgba(79,70,229,0.12);
        color: #4338ca;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.03em;
        transition: all 0.2s;
    }
    .tech-tag:hover {
        background: rgba(79,70,229,0.12);
        border-color: rgba(79,70,229,0.3);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f8fafc; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(#4f46e5, #06b6d4); border-radius: 6px; }
</style>
@endsection

@section('content')

{{-- ========================================================
     HERO SECTION
======================================================== --}}
<section id="home" class="relative min-h-screen flex items-center overflow-hidden bg-white">
    
    {{-- Background grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e8ebf0_1px,transparent_1px),linear-gradient(to_bottom,#e8ebf0_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_65%_65%_at_50%_40%,#000_60%,transparent_100%)] opacity-60"></div>

    {{-- Ambient blobs --}}
    <div class="absolute top-[-15%] left-[-10%] w-[700px] h-[700px] bg-indigo-200/40 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-8%] w-[500px] h-[500px] bg-cyan-200/30 rounded-full blur-[100px] pointer-events-none" style="animation: float 10s ease-in-out infinite;"></div>
    <div class="absolute top-[30%] right-[20%] w-[300px] h-[300px] bg-violet-200/20 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full pt-28 pb-20 md:pt-36 md:pb-28">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            {{-- LEFT: Copy --}}
            <div class="lg:col-span-6 text-center lg:text-left">

                {{-- Badge --}}
                <div data-aos="fade-down" class="inline-flex items-center gap-2.5 badge-live px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest mb-7">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Platform Aktif · 3.2K+ Developer
                </div>

                {{-- H1 --}}
                <h1 data-aos="fade-up" data-aos-delay="80" class="text-5xl md:text-6xl xl:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.08] mb-6">
                    Kuasai Coding.<br/>
                    <span class="text-gradient">Build Masa Depan.</span>
                </h1>

                {{-- Desc --}}
                <p data-aos="fade-up" data-aos-delay="160" class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                    Belajar pemrograman modern dengan kurikulum berbasis <em>real project</em>. Dari front-end hingga back-end, mulai nol hingga siap industri.
                </p>

                {{-- Tech Stack Tags --}}
                <div data-aos="fade-up" data-aos-delay="220" class="flex flex-wrap gap-2 justify-center lg:justify-start mb-10">
                    @foreach(['JavaScript', 'Python', 'PHP', 'React', 'Laravel', 'Node.js', 'SQL', 'Docker'] as $tech)
                    <span class="tech-tag">{{ $tech }}</span>
                    @endforeach
                </div>

                {{-- CTAs --}}
                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ url('/register/student') }}" class="cta-glow-btn inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold text-base shadow-[0_8px_30px_rgba(79,70,229,0.4)] hover:shadow-[0_12px_40px_rgba(79,70,229,0.6)] hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Mulai Gratis Sekarang
                    </a>
                    <a href="{{ url('/classroom') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-white border border-slate-200 text-slate-700 font-bold text-base hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50/50 hover:-translate-y-1 transition-all duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Lihat Katalog Kelas
                    </a>
                </div>

                {{-- Mini trust --}}
                <div data-aos="fade-up" data-aos-delay="400" class="mt-10 flex items-center gap-4 justify-center lg:justify-start">
                    <div class="flex -space-x-2">
                        @foreach(['4f46e5','06b6d4','8b5cf6','f59e0b','10b981'] as $color)
                        <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center text-white text-xs font-bold" style="background:#{{ $color }}">{{ chr(65 + $loop->index) }}</div>
                        @endforeach
                    </div>
                    <div class="text-sm text-slate-500 font-medium">
                        <span class="font-extrabold text-slate-900">3.2K+</span> developer sudah bergabung
                    </div>
                </div>
            </div>

            {{-- RIGHT: Code Editor Visual --}}
            <div class="lg:col-span-6 hidden lg:flex items-center justify-center relative" data-aos="fade-left" data-aos-delay="200">
                
                {{-- Decorative floating cards --}}
                <div class="absolute -top-8 -right-4 z-20 animate-float-b">
                    <div class="bg-white border border-slate-100 rounded-2xl shadow-lg px-4 py-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-900 leading-none">Project Selesai</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">E-commerce App</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-6 -left-8 z-20 animate-float">
                    <div class="bg-white border border-slate-100 rounded-2xl shadow-lg px-4 py-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-900 leading-none">Skill Progress</p>
                            <div class="mt-1.5 w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full w-[72%] bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Code Card --}}
                <div class="code-card relative w-full max-w-md">
                    {{-- Title bar --}}
                    <div class="flex items-center px-4 py-3.5 bg-[#0a0a18] border-b border-white/5">
                        <div class="flex space-x-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                        </div>
                        <div class="mx-auto flex items-center gap-2">
                            <span class="text-[11px] text-slate-500 font-mono">acessa</span>
                            <span class="text-slate-700">/</span>
                            <span class="text-[11px] text-indigo-400 font-mono font-bold">main.js</span>
                        </div>
                        <div class="w-14 h-1.5 bg-white/5 rounded-full">
                            <div class="h-full w-2/3 bg-gradient-to-r from-indigo-500 to-cyan-400 rounded-full"></div>
                        </div>
                    </div>
                    {{-- Code --}}
                    <div class="p-6 font-mono text-[13px] leading-[2] select-none">
                        <div><span class="code-token-purple">import</span> <span class="code-token-white">{ LearningEngine }</span> <span class="code-token-purple">from</span> <span class="code-token-green">'@acessa/core'</span><span class="code-token-white">;</span></div>
                        <div class="h-4"></div>
                        <div><span class="code-token-blue">const</span> <span class="code-token-cyan">dev</span> <span class="code-token-white">=</span> <span class="code-token-purple">new</span> <span class="code-token-yellow">LearningEngine</span><span class="code-token-white">({</span></div>
                        <div class="pl-5"><span class="code-token-cyan">stack</span><span class="code-token-white">:</span> <span class="code-token-green">'Full-Stack'</span><span class="code-token-white">,</span></div>
                        <div class="pl-5"><span class="code-token-cyan">mode</span><span class="code-token-white">:</span> <span class="code-token-green">'Project-Based'</span><span class="code-token-white">,</span></div>
                        <div class="pl-5"><span class="code-token-cyan">mentor</span><span class="code-token-white">:</span> <span class="code-token-green">'Expert'</span><span class="code-token-white">,</span></div>
                        <div><span class="code-token-white">});</span></div>
                        <div class="h-4"></div>
                        <div><span class="code-token-cyan">dev</span><span class="code-token-white">.</span><span class="code-token-yellow">launch</span><span class="code-token-white">().</span><span class="code-token-yellow">then</span><span class="code-token-white">((</span><span class="code-token-cyan">result</span><span class="code-token-white">) => {</span></div>
                        <div class="pl-5"><span class="code-token-blue">console</span><span class="code-token-white">.</span><span class="code-token-yellow">log</span><span class="code-token-white">(</span><span class="code-token-green">`Career: ${</span><span class="code-token-cyan">result</span><span class="code-token-green">.level}`</span><span class="code-token-white">);</span></div>
                        <div><span class="code-token-white">});</span></div>
                        <div class="flex items-center mt-1">
                            <span class="code-token-white opacity-50">// </span>
                            <span class="code-token-green ml-1">✓ Career Accelerated!</span>
                            <span class="inline-block w-2 h-4 bg-indigo-400 ml-1 animate-[blink_1s_step-end_infinite]"></span>
                        </div>
                    </div>
                    {{-- Status bar --}}
                    <div class="flex items-center justify-between px-5 py-2.5 bg-indigo-600/20 border-t border-indigo-500/20 text-[10px] font-mono">
                        <span class="flex items-center gap-1.5 text-indigo-400">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            ACESSA · Engine v3.0
                        </span>
                        <span class="text-slate-500">JavaScript · UTF-8</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ========================================================
     STATS ROW
======================================================== --}}
<div class="relative z-20 bg-white border-y border-slate-100" data-aos="fade-up">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-slate-100">
            @php
            $stats = [
                ['value' => '3.2K+', 'label' => 'Peserta Aktif', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>', 'from' => 'indigo', 'to' => 'violet'],
                ['value' => '65+', 'label' => 'Modul Kelas', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>', 'from' => 'cyan', 'to' => 'sky'],
                ['value' => '4.9', 'label' => 'Rating Ulasan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>', 'from' => 'amber', 'to' => 'yellow'],
                ['value' => '98%', 'label' => 'Kepuasan User', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>', 'from' => 'emerald', 'to' => 'teal'],
            ];
            @endphp
            @foreach($stats as $i => $stat)
            <div class="py-10 px-6 text-center group cursor-default">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl mb-3
                    @if($i==0) bg-indigo-50 @elseif($i==1) bg-cyan-50 @elseif($i==2) bg-amber-50 @else bg-emerald-50 @endif">
                    <svg class="w-5 h-5 @if($i==0) text-indigo-600 @elseif($i==1) text-cyan-600 @elseif($i==2) text-amber-500 @else text-emerald-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
                </div>
                <div class="text-3xl font-extrabold text-slate-900 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stat['value'] }}</div>
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ========================================================
     TRUSTED BY / PARTNERS
======================================================== --}}
<section class="py-14 bg-slate-50/50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <p data-aos="fade-up" class="text-center text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase mb-10">Dipercaya Institusi &amp; Komunitas Terkemuka</p>
        <div class="flex flex-wrap justify-center items-center gap-10 md:gap-20" data-aos="fade-up" data-aos-delay="100">
            
            <div class="flex items-center gap-2.5 opacity-50 hover:opacity-100 transition-all duration-300 grayscale hover:grayscale-0 cursor-pointer group">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 7.5l-6-3v5.5l6 3 6-3v-5.5l-6 3z"/></svg>
                </div>
                <div><div class="font-extrabold text-lg text-slate-900 leading-none">UNMER</div><div class="text-[9px] text-green-700 font-bold tracking-widest uppercase mt-0.5">Universitas Merdeka</div></div>
            </div>

            <div class="flex items-center gap-2.5 opacity-50 hover:opacity-100 transition-all duration-300 grayscale hover:grayscale-0 cursor-pointer">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
                <div><div class="font-extrabold text-lg text-slate-900 leading-none">INNOVATE<span class="text-blue-600">OS</span></div><div class="text-[9px] text-slate-400 font-bold tracking-widest uppercase mt-0.5">Intelligence System</div></div>
            </div>

            <div class="flex items-center gap-2 opacity-50 hover:opacity-100 transition-all duration-300 grayscale hover:grayscale-0 cursor-pointer">
                <svg class="w-7 h-7 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/></svg>
                <span class="font-extrabold text-xl text-slate-800 tracking-tight">TechStartup.</span>
            </div>

            <div class="flex items-center gap-2 opacity-50 hover:opacity-100 transition-all duration-300 grayscale hover:grayscale-0 cursor-pointer">
                <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="font-extrabold text-xl text-slate-800 tracking-tight">DevCore</span>
            </div>

        </div>
    </div>
</section>

{{-- ========================================================
     LEARNING PATHS (DARK BENTO GRID)
======================================================== --}}
<section id="path" class="py-28 section-dark relative overflow-hidden">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Heading --}}
        <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
            <span class="inline-block text-indigo-400 text-xs font-bold tracking-[0.25em] uppercase mb-4 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20">Engineering Paths</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-5 tracking-tight leading-tight">
                Pilih Jalur<br/><span class="text-gradient">Spesialisasi Anda</span>
            </h2>
            <p class="text-slate-400 text-lg font-medium leading-relaxed">Jalur kurikulum yang diracik oleh developer berpengalaman untuk memastikan fondasi kode Anda sekuat baja.</p>
        </div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Front-End --}}
            <div data-aos="fade-up" data-aos-delay="100" class="path-card p-8 flex flex-col">
                <div class="icon-wrap w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <span class="text-[10px] font-bold tracking-widest uppercase text-indigo-400 mb-3">Track 01</span>
                <h3 class="text-2xl font-bold text-white mb-3">Front-End Dev</h3>
                <p class="text-slate-400 text-sm leading-relaxed flex-grow mb-6">Kuasai HTML, CSS modern, JavaScript interaktif, dan framework React untuk membangun UI responsif & memukau.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['HTML5', 'CSS3', 'JS', 'React', 'Tailwind'] as $tag)
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Back-End --}}
            <div data-aos="fade-up" data-aos-delay="200" class="path-card p-8 flex flex-col" style="border-color:rgba(6,182,212,0.15);">
                <div class="icon-wrap w-14 h-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                </div>
                <span class="text-[10px] font-bold tracking-widest uppercase text-cyan-400 mb-3">Track 02</span>
                <h3 class="text-2xl font-bold text-white mb-3">Back-End & API</h3>
                <p class="text-slate-400 text-sm leading-relaxed flex-grow mb-6">Arsitekturkan aplikasi tangguh. Kuasai routing, database, manajemen API, dan logika bisnis tingkat lanjut.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['PHP', 'Laravel', 'MySQL', 'Node.js', 'REST API'] as $tag)
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Data & Fullstack --}}
            <div data-aos="fade-up" data-aos-delay="300" class="path-card p-8 flex flex-col" style="border-color:rgba(139,92,246,0.15);">
                <div class="icon-wrap w-14 h-14 rounded-2xl bg-violet-500/10 border border-violet-500/20 text-violet-400 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <span class="text-[10px] font-bold tracking-widest uppercase text-violet-400 mb-3">Track 03</span>
                <h3 class="text-2xl font-bold text-white mb-3">Data & Analitik</h3>
                <p class="text-slate-400 text-sm leading-relaxed flex-grow mb-6">Ubah data mentah menjadi insight cerdas. Visualisasi, machine learning, dan pengambilan keputusan berbasis data.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Python', 'SQL', 'Pandas', 'Matplotlib', 'ML'] as $tag)
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-violet-500/10 text-violet-400 border border-violet-500/20">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- CTA inside dark section --}}
        <div class="text-center mt-14" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ url('/classroom') }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-bold text-sm transition-colors group">
                Lihat Semua Jalur Belajar
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

    </div>
</section>

{{-- ========================================================
     SECTION KELAS DINAMIS (AJAX)
======================================================== --}}
<section class="py-28 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-cyan-400 to-violet-500"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Heading --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div data-aos="fade-right">
                <span class="text-indigo-500 font-bold text-xs tracking-[0.2em] uppercase mb-3 block">Kelas Premium</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">Akses Kelas<br/><span class="text-gradient">Real-World</span></h2>
            </div>
            <div data-aos="fade-left">
                <a href="{{ url('/classroom') }}" class="group inline-flex items-center gap-2 border border-slate-200 hover:border-indigo-300 bg-white hover:bg-indigo-50/50 text-slate-700 hover:text-indigo-600 font-bold rounded-full px-6 py-3 text-sm transition-all duration-300">
                    Lihat Semua
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        {{-- Cards Container --}}
        <div id="home-classroom-row" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Loading State --}}
            <div id="loading-state" class="col-span-full flex flex-col items-center justify-center py-24 gap-4">
                <div class="relative w-14 h-14">
                    <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-t-indigo-600 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                </div>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] animate-pulse">Memuat Katalog Kelas...</p>
            </div>
        </div>

        <div class="mt-14 text-center" data-aos="fade-up">
            <a href="{{ url('/classroom') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-full px-10 py-4 text-sm shadow-[0_8px_30px_rgba(79,70,229,0.3)] hover:shadow-[0_12px_40px_rgba(79,70,229,0.5)] hover:-translate-y-1 transition-all duration-300">
                Jelajahi Seluruh Katalog →
            </a>
        </div>
    </div>
</section>

{{-- ========================================================
     TESTIMONIALS
======================================================== --}}
<section class="py-28 bg-slate-50/60 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(79,70,229,0.05)_0%,transparent_60%)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-block text-indigo-500 text-xs font-bold tracking-[0.2em] uppercase mb-4 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100">Testimoni</span>
            <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Kata Mereka yang<br/><span class="text-gradient">Sudah Bertransformasi</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $testimonials = [
                ['name' => 'Rizky Pratama', 'role' => 'Junior Developer @ Tokopedia', 'quote' => 'ACESSA benar-benar mengubah cara belajar saya. Kurikulumnya langsung ke inti, tanpa basa-basi. Sekarang saya sudah kerja di startup impian!', 'rating' => 5, 'color' => 'indigo'],
                ['name' => 'Siti Nuraini', 'role' => 'Data Analyst @ Gojek', 'quote' => 'Modul Data Analytics-nya sangat komprehensif. Dari Python dasar hingga visualisasi data — semua dijelaskan dengan super jelas dan ada project nyata.', 'rating' => 5, 'color' => 'cyan'],
                ['name' => 'Budi Santoso', 'role' => 'Fullstack Dev @ Freelance', 'quote' => 'Saya bisa freelance dengan confidence setelah tamat Full-Stack track di ACESSA. ROI-nya luar biasa — bayar kursus, balik dalam sebulan!', 'rating' => 5, 'color' => 'violet'],
            ];
            @endphp
            @foreach($testimonials as $i => $t)
            <div data-aos="fade-up" data-aos-delay="{{ ($i+1)*100 }}" class="testimonial-card p-8">
                {{-- Stars --}}
                <div class="flex gap-1 mb-5">
                    @for($s=0; $s<$t['rating']; $s++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                {{-- Quote --}}
                <p class="text-slate-600 text-sm leading-relaxed mb-6 italic">"{{ $t['quote'] }}"</p>
                {{-- Author --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm
                        @if($t['color']=='indigo') bg-indigo-600 @elseif($t['color']=='cyan') bg-cyan-600 @else bg-violet-600 @endif">
                        {{ substr($t['name'],0,1) }}
                    </div>
                    <div>
                        <p class="font-extrabold text-sm text-slate-900 leading-none">{{ $t['name'] }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">{{ $t['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
     CTA FINAL
======================================================== --}}
<section class="py-28 cta-section relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div data-aos="zoom-in" data-aos-duration="800">
            <span class="inline-block text-indigo-400 text-xs font-bold tracking-[0.25em] uppercase mb-6 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20">Mulai Sekarang</span>
            <h2 class="text-5xl md:text-7xl font-extrabold text-white mb-6 tracking-tight leading-tight">
                Ketik Baris Kode<br/><span class="text-gradient">Pertama Anda.</span>
            </h2>
            <p class="text-slate-400 text-lg md:text-xl mb-12 max-w-2xl mx-auto leading-relaxed font-medium">
                Bergabung bersama ribuan developer yang sudah membuktikan. Registrasi gratis, mulai dari nol, dan capai puncak skill engineering-mu.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/register/student') }}" class="cta-glow-btn inline-flex items-center justify-center gap-2 bg-white text-slate-900 hover:bg-indigo-50 px-10 py-5 rounded-full font-extrabold text-base transition-all hover:scale-105 shadow-[0_0_40px_rgba(255,255,255,0.1)]">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Buat Akun Gratis
                </a>
                <a href="{{ url('/classroom') }}" class="inline-flex items-center justify-center gap-2 border border-white/20 text-white hover:bg-white/10 px-10 py-5 rounded-full font-bold text-base transition-all hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Tonton Demo
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    $(document).ready(function () {
        AOS.init({ once: true, offset: 60, duration: 800, easing: 'ease-out-cubic' });

        const listContainer = $('#home-classroom-row');
        const loadingState  = $('#loading-state');

        function renderHomeClassrooms(data) {
            listContainer.empty();

            if (!data || data.length === 0) {
                listContainer.append(`
                    <div data-aos="fade-up" class="col-span-full bg-white border-2 border-dashed border-slate-200 rounded-[24px] p-16 text-center">
                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Modul Segera Hadir</h3>
                        <p class="text-slate-400 font-medium">Tim kami sedang menyiapkan konten kelas terbaik. Stay tuned!</p>
                    </div>
                `);
                AOS.refresh();
                return;
            }

            const sliceData = data.slice(0, 3);
            let delay = 100;

            sliceData.forEach(c => {
                const thumb = c.thumbnail
                    ? `/storage/${c.thumbnail}`
                    : `https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=700&auto=format&fit=crop`;

                const desc = c.description
                    ? (c.description.length > 90 ? c.description.substring(0, 90) + '…' : c.description)
                    : 'Modul komprehensif untuk penguasaan teknologi web modern secara mendalam.';

                const mentor = c.user_name || 'ACESSA Expert';

                const card = `
                    <div data-aos="fade-up" data-aos-delay="${delay}" class="class-card flex flex-col h-full cursor-pointer" onclick="window.location.href='/classroom'">
                        <div class="relative aspect-video w-full overflow-hidden bg-slate-900">
                            <img src="${thumb}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90" loading="lazy" alt="${c.name}">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 text-[10px] font-bold tracking-wider text-indigo-400 bg-slate-900/80 backdrop-blur-sm border border-indigo-500/30 rounded-lg uppercase">Tech Module</span>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-extrabold text-slate-900 mb-2.5 leading-snug line-clamp-2 hover:text-indigo-600 transition-colors">${c.name}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow line-clamp-3 font-medium">${desc}</p>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center text-white text-[10px] font-bold">${mentor.charAt(0)}</div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Instructor</p>
                                        <p class="text-xs font-bold text-slate-900">${mentor}</p>
                                    </div>
                                </div>
                                <div class="w-9 h-9 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                listContainer.append(card);
                delay += 120;
            });

            setTimeout(() => { AOS.refresh(); }, 100);
        }

        $.ajax({
            url: '/api/classroom',
            method: 'GET',
            success: function (res) {
                loadingState.remove();
                renderHomeClassrooms(res.data || []);
            },
            error: function () {
                loadingState.remove();
                renderHomeClassrooms([]);
            }
        });
    });
</script>
@endsection