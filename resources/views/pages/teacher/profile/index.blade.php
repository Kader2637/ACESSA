@extends('layouts.teacher.app')

@section('title', 'Profil Saya — Teacher Panel')
@section('page_title', 'Profil Instruktur')

@section('content')
<div class="max-w-4xl mx-auto flex flex-col gap-8">
    
    {{-- Header Banner Card --}}
    <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm" data-aos="fade-up">
        {{-- Cover Banner --}}
        <div class="h-44 bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 relative">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-4 right-4 bg-white/10 backdrop-blur-md border border-white/10 text-white font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 rounded-xl shadow-sm">
                Verified Instructor
            </div>
        </div>

        {{-- Profile Base Info --}}
        <div class="px-8 pb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6 -mt-12 mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-end gap-6">
                    <div class="w-24 h-24 rounded-3xl border-4 border-white shadow-xl overflow-hidden bg-gradient-to-br from-indigo-500 to-cyan-500 flex-shrink-0 relative">
                        <img id="profile-avatar-display" src="{{ asset('storage/'.auth()->user()->image) }}" class="w-full h-full object-cover" alt="Profile"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full items-center justify-center text-white font-black text-3xl hidden">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="pb-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-none" id="profile-name-display">{{ auth()->user()->name }}</h2>
                            <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-600 text-[9px] font-bold uppercase rounded-md tracking-wider">Guru / Dosen</span>
                        </div>
                        <p class="text-slate-400 text-xs font-semibold mt-2">ID Pengguna: #{{ auth()->user()->id }}</p>
                    </div>
                </div>
                
                <button onclick="openEditProfileModal()" class="px-4 py-2.5 bg-white border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50/20 text-slate-700 hover:text-indigo-600 font-bold text-xs rounded-xl shadow-sm transition-all self-stretch sm:self-auto text-center">
                    ✏️ Edit Profil
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Email Instruktur</p>
                        <p class="font-bold text-slate-800 text-xs" id="profile-email-display">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                    <div class="w-10 h-10 bg-cyan-50 rounded-xl flex items-center justify-center text-cyan-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Tanggal Bergabung</p>
                        <p class="font-bold text-slate-800 text-xs">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d F Y') : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Managed Classes in Profile --}}
    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-6 md:p-8 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <h3 class="font-extrabold text-slate-900 text-base mb-6">Kelas yang Diajarkan</h3>
        
        <div id="loading-profile-classes" class="py-8 flex justify-center">
            <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-500 rounded-full animate-spin"></div>
        </div>

        <div id="profile-classes-empty" class="py-8 text-center text-slate-400 text-xs font-semibold hidden">
            Anda belum membuat kelas pengajaran apa pun saat ini.
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="profile-classes-grid"></div>
    </div>

    {{-- Danger Zone logout --}}
    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-6 md:p-8 shadow-sm" data-aos="fade-up" data-aos-delay="150">
        <h3 class="font-extrabold text-slate-900 text-base mb-4">Pengaturan Keamanan</h3>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-red-500/5 border border-red-500/10 rounded-2xl gap-4">
            <div>
                <p class="font-bold text-red-700 text-sm">Keluar dari Akun</p>
                <p class="text-red-400 text-xs mt-0.5">Mengakhiri sesi instruktur Anda di peramban ini.</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-red-600/10">
                    Keluar Sekarang
                </button>
            </form>
        </div>
    </div>

</div>

{{-- MODAL: Edit Profile --}}
<div id="editProfileModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditProfileModal()"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-8">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6">Edit Profil Saya</h3>
            <form id="frm-edit-profile" class="flex flex-col gap-4">
                @csrf
                
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-300" required>
                </div>

                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Email Instruktur</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-300" required>
                </div>

                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Kata Sandi Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-300" placeholder="Kosongkan jika tidak diganti">
                </div>

                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Foto Profil Baru (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-extrabold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeEditProfileModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        const authId = '{{ auth()->user()->id }}';
        
        // Fetch classes
        $.ajax({
            url: `/api/my/classroom/teacher/data/${authId}`,
            method: 'GET',
            success: function(res) {
                $('#loading-profile-classes').hide();
                const list = res.data || [];
                
                if (list.length === 0) {
                    $('#profile-classes-empty').removeClass('hidden');
                    return;
                }

                list.forEach(k => {
                    $('#profile-classes-grid').append(`
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-600/10 border border-indigo-600/20 text-indigo-600 font-extrabold text-sm rounded-xl flex items-center justify-center">
                                    ${k.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-800 text-xs leading-snug">${k.name}</h4>
                                    <p class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5">${k.student_count || 0} Siswa</p>
                                </div>
                            </div>
                            <a href="/teacher/classroom/course/${k.id}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                Kelola →
                            </a>
                        </div>
                    `);
                });
            },
            error: () => {
                $('#loading-profile-classes').hide();
                $('#profile-classes-empty').removeClass('hidden');
            }
        });

        // Submit Profile Update
        $('#frm-edit-profile').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Menyimpan...');

            const formData = new FormData(this);

            $.ajax({
                url: '/teacher/profile/update',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    toastr.success('Profil berhasil diperbarui!');
                    closeEditProfileModal();
                    
                    // Reload profile details instantly
                    setTimeout(() => location.reload(), 800);
                },
                error: function(err) {
                    const msg = err.responseJSON ? err.responseJSON.message : 'Gagal memperbarui profil.';
                    toastr.error(msg);
                },
                complete: () => btn.prop('disabled', false).text('Simpan Perubahan')
            });
        });
    });

    function openEditProfileModal() {
        $('#editProfileModal').removeClass('hidden').addClass('flex');
    }
    function closeEditProfileModal() {
        $('#editProfileModal').removeClass('flex').addClass('hidden');
        $('#frm-edit-profile')[0].reset();
    }
</script>
@endsection
