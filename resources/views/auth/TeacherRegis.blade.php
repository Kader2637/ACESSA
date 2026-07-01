@extends('layouts.landingpage.app')

@section('title', 'Daftar Instruktur – ACESSA')

@section('style')
<style>
    body {
        background-color: #ffffff;
        overflow-x: hidden;
    }

    /* Subtle Grid Background - Konsisten dengan halaman lain */
    .bg-grid-pattern {
        background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px),
                          linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Wizard Step Display */
    .wizard-step { display: none; }
    .wizard-step.active { 
        display: block; 
        animation: slideUp 0.4s ease-out; 
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Custom Input Focus */
    .clean-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        background-color: #ffffff;
    }

    /* Radio Button Styling */
    .gender-option:checked + label {
        border-color: #4f46e5;
        background-color: #f5f3ff;
        color: #4f46e5;
    }

    /* Stepper Line */
    .step-line {
        position: absolute;
        top: 20px;
        left: 0;
        height: 2px;
        background: #e2e8f0;
        z-index: 0;
    }
</style>
@endsection

@section('content')

{{-- ==================== HERO BREADCRUMB ==================== --}}
<section class="relative pt-32 pb-12 md:pt-40 md:pb-16 bg-white bg-grid-pattern border-b border-slate-200">
    <div class="absolute inset-0 bg-gradient-to-b from-white/40 via-white/80 to-white pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">Pendaftaran Instruktur</h3>
        <nav class="flex items-center gap-2 text-sm font-bold text-slate-500 uppercase tracking-widest">
            <a href="/" class="hover:text-indigo-600 transition-colors">Home</a>
            <span class="text-slate-300">/</span>
            <span class="text-indigo-600">Register Teacher</span>
        </nav>
    </div>
</section>

{{-- ==================== REGISTRATION FORM (WIZARD) ==================== --}}
<section class="py-20 bg-slate-50/50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">Bagikan Ilmu Anda</h2>
                <p class="text-slate-500 font-medium leading-relaxed max-w-lg mx-auto">Bergabunglah sebagai mentor dan bantu bentuk masa depan generasi baru dengan cara mengajar yang inovatif.</p>
            </div>

            <div class="relative mb-16 px-4" data-aos="fade-up" data-aos-delay="100">
                <div class="step-line w-full"></div>
                <div id="progress-bar" class="absolute top-[20px] left-0 h-[2px] bg-indigo-600 transition-all duration-500 z-0" style="width: 0%;"></div>

                <div class="relative z-10 flex justify-between">
                    <div class="flex flex-col items-center">
                        <div id="stepIndicator1" class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-indigo-600 text-white shadow-lg shadow-indigo-200">1</div>
                        <span class="text-[10px] font-black uppercase tracking-tighter mt-2 text-indigo-600">Personal</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="stepIndicator2" class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-white border-2 border-slate-200 text-slate-400">2</div>
                        <span class="text-[10px] font-black uppercase tracking-tighter mt-2 text-slate-400">Profesi</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="stepIndicator3" class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-white border-2 border-slate-200 text-slate-400">3</div>
                        <span class="text-[10px] font-black uppercase tracking-tighter mt-2 text-slate-400">Keamanan</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[2rem] shadow-xl overflow-hidden p-8 md:p-12" data-aos="fade-up" data-aos-delay="200">
                <form id="registrationForm" enctype="multipart/form-data">
                    @csrf

                    {{-- STEP 1: PERSONAL INFO --}}
                    <div id="step1" class="wizard-step active">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Username</label>
                                    <input type="text" name="name" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all" placeholder="Contoh: budisudono">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Email Instansi/Pribadi</label>
                                    <input type="email" name="email" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all" placeholder="nama@email.com">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">No. Telepon</label>
                                    <input type="number" name="no_telephone" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all" placeholder="08xxxxxxxxxx">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Jenis Kelamin</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="radio" name="gender" value="male" id="male" class="gender-option hidden">
                                        <label for="male" class="flex items-center justify-center py-3.5 border-2 border-slate-100 rounded-xl cursor-pointer font-bold text-slate-500 hover:bg-slate-50 transition-all">Laki-laki</label>
                                        
                                        <input type="radio" name="gender" value="female" id="female" class="gender-option hidden">
                                        <label for="female" class="flex items-center justify-center py-3.5 border-2 border-slate-100 rounded-xl cursor-pointer font-bold text-slate-500 hover:bg-slate-50 transition-all">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-10">
                            <button type="button" onclick="validateStep(1)" class="w-full py-4 bg-slate-900 text-white font-extrabold rounded-xl shadow-lg hover:bg-indigo-600 transition-all duration-300 flex justify-center items-center gap-3">
                                Selanjutnya: Data Profesi
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2: PROFESSIONAL INFO --}}
                    <div id="step2" class="wizard-step">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Asal Yayasan / Sekolah</label>
                                    <input type="text" name="school" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all" placeholder="Nama instansi pengajar">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">NIP / ID Pegawai</label>
                                    <input type="number" name="nip" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all" placeholder="Masukkan nomor identitas">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Alamat Lengkap</label>
                                <textarea name="address" rows="3" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 transition-all" placeholder="Alamat domisili saat ini"></textarea>
                            </div>
                        </div>
                        <div class="mt-10 flex gap-4">
                            <button type="button" onclick="goToStep(1)" class="w-1/3 py-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all">Kembali</button>
                            <button type="button" onclick="validateStep(2)" class="w-2/3 py-4 bg-slate-900 text-white font-extrabold rounded-xl shadow-lg hover:bg-indigo-600 transition-all duration-300 flex justify-center items-center gap-3">
                                Langkah Terakhir
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 3: PHOTO & PASSWORD --}}
                    <div id="step3" class="wizard-step">
                        <div class="space-y-8">
                            <div class="flex flex-col items-center">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 w-full text-center">Foto Profil Profesional</label>
                                <div class="relative group">
                                    <div id="profileImageContainer" class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl bg-slate-100 flex items-center justify-center relative z-10">
                                        <img id="profileImage" src="/user.png" class="w-full h-full object-cover">
                                    </div>
                                    <label for="image" class="absolute bottom-0 right-0 w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-indigo-700 transition-colors z-20 border-2 border-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </label>
                                    <input type="file" id="image" name="image" class="hidden" onchange="previewImage(event)">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Password</label>
                                    <input type="password" name="password" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold transition-all" placeholder="Min. 8 Karakter">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="clean-input w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold transition-all" placeholder="Ulangi password">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-10 flex gap-4">
                            <button type="button" onclick="goToStep(2)" class="w-1/3 py-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all">Kembali</button>
                            <button type="submit" id="submitButton" class="w-2/3 py-4 bg-emerald-600 text-white font-extrabold rounded-xl shadow-lg hover:bg-emerald-700 transition-all duration-300 flex justify-center items-center gap-3">
                                <div id="loadingSpinner" class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                Selesaikan Pendaftaran
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <div class="mt-8 text-center" data-aos="fade-up" data-aos-delay="300">
                <p class="text-slate-500 font-medium">Sudah menjadi bagian dari kami? <a href="/login" class="text-indigo-600 font-black uppercase text-xs tracking-widest ml-1 hover:text-indigo-800 transition-colors">Masuk</a></p>
            </div>

        </div>
    </div>
</section>

@endsection

@section('script')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = { "progressBar": true, "closeButton": true, "positionClass": "toast-top-right" };

    function goToStep(step) {
        $(".wizard-step").removeClass("active");
        $("#step" + step).addClass("active");

        $(".step-indicator").removeClass("bg-indigo-600 text-white shadow-lg shadow-indigo-200 border-indigo-600")
                           .addClass("bg-white border-2 border-slate-200 text-slate-400");
        
        for(let i=1; i<=step; i++) {
            $("#stepIndicator" + i).removeClass("border-slate-200 text-slate-400 bg-white")
                                   .addClass("bg-indigo-600 text-white shadow-lg shadow-indigo-200");
        }

        let progress = ((step - 1) / 2) * 100;
        $("#progress-bar").css("width", progress + "%");
    }

    function validateStep(step) {
        let valid = true;
        let fields = {
            1: ["name", "email", "no_telephone", "gender"],
            2: ["school", "nip", "address"]
        };

        fields[step].forEach(f => {
            let field = $(`[name='${f}']`);
            if (field.attr("type") === "radio") {
                if ($(`input[name='${f}']:checked`).length === 0) valid = false;
            } else {
                if (field.val().trim() === "") valid = false;
            }
        });

        if (!valid) {
            toastr.warning("Lengkapi semua kolom sebelum melanjutkan.");
            return;
        }

        goToStep(step + 1);
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            $("#profileImage").attr("src", reader.result);
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    $("#registrationForm").on('submit', function(e) {
        e.preventDefault();
        
        let pass = $("[name='password']").val();
        let conf = $("[name='password_confirmation']").val();
        
        if (pass.length < 8) {
            toastr.error("Password minimal 8 karakter.");
            return;
        }
        if (pass !== conf) {
            toastr.error("Konfirmasi password tidak cocok!");
            return;
        }

        const btn = $("#submitButton");
        const spinner = $("#loadingSpinner");

        btn.prop("disabled", true).addClass('opacity-50');
        spinner.removeClass('hidden');

        let formData = new FormData(this);

        $.ajax({
            url: "/api/Apiregister/teacher",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'Accept': 'application/json' },
            success: function(res) {
                toastr.success('Pendaftaran berhasil! Silahkan menunggu konfirmasi admin.', 'Selamat!');
                setTimeout(() => window.location.href = "/login", 2000);
            },
            error: function(xhr) {
                let errorMsg = "Terjadi kesalahan sistem.";
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                    } else if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                }
                toastr.error(errorMsg, "Registrasi Gagal");
            },
            complete: function() {
                btn.prop("disabled", false).removeClass('opacity-50');
                spinner.addClass('hidden');
            }
        });
    });
</script>
@endsection