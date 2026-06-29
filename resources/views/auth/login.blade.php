@extends('layouts.landingpage.app')

@section('title', 'Masuk – ACESSA')

@section('style')
<style>
    body {
        background-color: #ffffff;
        overflow-x: hidden;
    }

    /* Subtle Grid Background - Sama dengan Home & About agar selaras */
    .bg-grid-pattern {
        background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px),
                          linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Modern Input Focus */
    .auth-input:focus {
        outline: none;
        border-color: #4f46e5; /* Indigo 600 */
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        background-color: #ffffff;
    }

    /* Shake Animation untuk Error */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.4s ease-in-out; }
</style>
@endsection

@section('content')

{{-- ==================== AUTH PAGE WRAPPER ==================== --}}
<section class="min-h-screen pt-32 pb-20 bg-white bg-grid-pattern relative flex items-center justify-center">
    <div class="absolute inset-0 bg-gradient-to-b from-white/40 via-white/80 to-white pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-[650px]" data-aos="zoom-in">
            
            {{-- SISI KIRI: BRAND INFO (DARK THEME) --}}
            <div class="lg:w-1/2 bg-slate-900 p-10 md:p-16 flex flex-col justify-between relative overflow-hidden hidden lg:flex">
                <div class="absolute top-[-10%] left-[-10%] w-80 h-80 bg-indigo-600/20 blur-[100px] rounded-full"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-80 h-80 bg-cyan-500/20 blur-[100px] rounded-full"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-white/80 font-bold text-xs uppercase tracking-widest backdrop-blur-md mb-12">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Platform Engineering
                    </div>
                    
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tighter leading-[1.1] mb-6">
                        Selamat Datang <br />
                        <span class="text-indigo-400">Kembali.</span>
                    </h2>
                    <p class="text-slate-400 text-lg font-medium leading-relaxed max-w-sm">
                        Lanjutkan pengembangan portofolio Anda dan kuasai standar teknologi industri hari ini.
                    </p>
                </div>

                <div class="relative z-10 border-t border-slate-800 pt-10 flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Didukung Oleh</span>
                        <span class="text-white font-extrabold text-xl tracking-tight">UNMER <span class="text-slate-500">MALANG</span></span>
                    </div>
                    <img src="{{ asset('logo.png') }}" class="h-10 opacity-80" alt="Logo">
                </div>
            </div>

            {{-- SISI KANAN: FORM LOGIN (CLEAN THEME) --}}
            <div class="lg:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-white">
                <div class="max-w-sm w-full mx-auto">
                    
                    <div class="mb-10">
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Masuk Akun</h3>
                        <p class="text-slate-500 font-medium mt-2">Gunakan kredensial pengembang Anda.</p>
                    </div>

                    {{-- TEMPAT NOTIFIKASI ERROR (PASSWORD SALAH DLL) --}}
                    <div id="responseMessage" class="mb-6">
                        {{-- Handle Flash Session (Jika ada redirect dari Controller) --}}
                        @if(session('error') || $errors->any())
                            <div class="flex items-start gap-3 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 animate-shake">
                                <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-sm font-bold leading-tight">
                                    @if(session('error')) {{ session('error') }} @endif
                                    @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- FORM LOGIN --}}
                    <form action="/post/login" method="POST" id="loginForm" class="space-y-6">
                        @csrf

                        <div class="group">
                            <label for="email" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-indigo-600">Email Address</label>
                            <input id="email" name="email" type="email" required 
                                class="auth-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all"
                                placeholder="name@email.com">
                        </div>

                        <div class="group">
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-xs font-black text-slate-400 uppercase tracking-widest transition-colors group-focus-within:text-indigo-600">Password</label>
                                <a href="#" class="text-[11px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors">Lupa Sandi?</a>
                            </div>
                            <div class="relative">
                                <input id="password" name="password" type="password" required 
                                    class="auth-input w-full pl-4 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all"
                                    placeholder="••••••••">
                                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-indigo-600 transition-colors">
                                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit" class="w-full py-4 bg-slate-900 text-white font-extrabold rounded-xl shadow-lg hover:bg-indigo-600 transition-all duration-300 flex justify-center items-center gap-3 active:scale-[0.98]">
                            Masuk ke Dashboard
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>

                    <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                        <p class="text-slate-500 text-sm font-semibold">Belum memiliki akun?</p>
                        <button onclick="openModal()" class="mt-2 inline-flex items-center gap-1 text-indigo-600 font-black uppercase text-[11px] tracking-[0.15em] hover:text-indigo-800 transition-colors">
                            Buat Akun Gratis
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== MODAL PILIH ROLE (BENTO STYLE) ==================== --}}
<div id="registerModalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[60] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div id="registerModalContent" class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
        
        <div class="px-10 pt-10 pb-6 text-center">
            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pilih Identitas</h3>
            <p class="text-slate-500 font-medium mt-2">Bagaimana Anda akan menggunakan platform ini?</p>
        </div>

        <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Opsi Peserta --}}
            <a href="{{ url('/register/student') }}" class="role-card group bg-slate-50 border-2 border-transparent rounded-3xl p-8 flex flex-col h-full hover:bg-white">
                <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Saya Ingin Belajar</h4>
                <p class="text-slate-500 text-sm font-medium leading-relaxed mb-6">Akses kurikulum teknologi terbaru, kerjakan proyek, dan raih sertifikat.</p>
                <div class="mt-auto font-black text-indigo-600 text-[10px] uppercase tracking-widest flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    Pilih Peserta <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            {{-- Opsi Instruktur --}}
            <a href="{{ url('/register/teacher') }}" class="role-card group bg-slate-50 border-2 border-transparent rounded-3xl p-8 flex flex-col h-full hover:bg-white">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Saya Ingin Mengajar</h4>
                <p class="text-slate-500 text-sm font-medium leading-relaxed mb-6">Bagikan ilmu Anda, kelola modul kelas, dan bantu lahirkan talenta baru.</p>
                <div class="mt-auto font-black text-emerald-600 text-[10px] uppercase tracking-widest flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    Pilih Instruktur <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        </div>

        <div class="px-10 pb-10 text-center">
            <button onclick="closeModal()" class="text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-slate-900 transition-colors">Batal</button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#btnSubmit');
            const msgBox = $('#responseMessage');
            
            btn.prop('disabled', true).addClass('opacity-50').html('Memverifikasi...');
            msgBox.empty();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'Accept': 'application/json'
                },
                success: function(res) {
                    window.location.href = res.redirect;
                },
                error: function(xhr) {
                    btn.prop('disabled', false).removeClass('opacity-50').html('Masuk ke Dashboard <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>');
                    
                    let errorContent = "Terjadi kesalahan sistem.";
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorContent = xhr.responseJSON.message;
                    }

                    msgBox.html(`
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 animate-shake shadow-sm">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-bold">${errorContent}</p>
                        </div>
                    `);
                }
            });
        });

        $('#toggle-password').click(function() {
            const input = $('#password');
            const icon = $('#eye-icon');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.html(`<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>`);
            } else {
                input.attr('type', 'password');
                icon.html(`<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`);
            }
        });
    });

    function openModal() {
        $('#registerModalBackdrop').removeClass('hidden').addClass('flex');
        setTimeout(() => {
            $('#registerModalBackdrop').removeClass('opacity-0');
            $('#registerModalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
    }

    function closeModal() {
        $('#registerModalBackdrop').addClass('opacity-0');
        $('#registerModalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => { $('#registerModalBackdrop').addClass('hidden').removeClass('flex'); }, 300);
    }
</script>
@endsection