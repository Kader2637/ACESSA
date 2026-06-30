@extends('layouts.admin.app')

@section('title', 'Audit Profil Pengajar — Panel Admin')
@section('page_title', 'Audit Profil Pengajar')

@section('style')
<style>
    .bio-card { background: white; border-radius: 2rem; border: 1px solid #e2e8f0; position: relative; overflow: hidden; }
    .profile-large-frame { width: 110px; height: 110px; border-radius: 1.5rem; border: 4px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); object-fit: cover; flex-shrink: 0; }
    .info-pill { background: #f8fafc; padding: 1rem; border-radius: 1rem; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
    .course-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; padding: 1.5rem; flex-direction: column; display: flex; }
    .course-card:hover { transform: translateY(-4px); border-color: #cbd5e1; box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05); }
    .status-banner { padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
</style>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm animate-fade-in">
    <div class="overflow-hidden">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Audit Profil: <span class="text-indigo-650">{{ $user->name }}</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Verifikasi identitas, biodata, dan performa pengajar dalam sistem</p>
    </div>
    
    <div class="flex items-center gap-2 shrink-0">
        @if ($user->status == 'pending')
            <button class="px-4 py-2 bg-red-50 hover:bg-red-500 border border-red-200 hover:border-red-500 text-red-650 hover:text-white font-bold text-xs rounded-xl transition-all active:scale-[0.98] reject-button-user" data-id="{{ $user->id }}">Tolak Pengajuan</button>
            <button class="px-5 py-2 bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-all active:scale-[0.98] accept-button-user" data-id="{{ $user->id }}">Terima &amp; Verifikasi</button>
        @else
            <a href="/admin/teacher" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs transition-all flex items-center gap-1.5 active:scale-[0.98]">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
            </a>
        @endif
    </div>
</div>

<div class="bio-card p-6 md:p-8 mb-8 shadow-sm">
    <div class="flex flex-col md:flex-row items-center md:items-start gap-8 min-w-0">
        <div class="shrink-0 text-center">
            <img src="{{ asset('storage/' . $user->image) }}" class="profile-large-frame mb-4 mx-auto border border-slate-200" onerror="this.src='/user.png'">
            <div class="flex justify-center">
                @if ($user->status == 'accept')
                    <span class="status-banner bg-emerald-50 border border-emerald-100 text-emerald-700">Dosen Terverifikasi</span>
                @elseif ($user->status == 'pending')
                    <span class="status-banner bg-amber-50 border border-amber-100 text-amber-700">Menunggu Verifikasi</span>
                @else
                    <span class="status-banner bg-red-50 border border-red-100 text-red-700">Akses Ditolak</span>
                @endif
            </div>
        </div>

        <div class="flex-1 w-full min-w-0 text-left">
            <div class="mb-6">
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-1 break-words">{{ $user->name }}</h3>
                <p class="text-indigo-650 font-bold uppercase text-[10px] tracking-widest">{{ $user->role === 'teacher' ? 'Dosen / Pengajar' : $user->role }} • Anggota Akademik ACESSA</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="info-pill">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-400 border border-slate-200 shrink-0"><i data-feather="mail" class="w-4 h-4"></i></div>
                    <div class="min-w-0 flex-grow">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Alamat Email</p>
                        <p class="text-xs font-bold text-slate-700 truncate leading-snug">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="info-pill">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-400 border border-slate-200 shrink-0"><i data-feather="user" class="w-4 h-4"></i></div>
                    <div class="min-w-0 flex-grow">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Jenis Kelamin</p>
                        <p class="text-xs font-bold text-slate-700 truncate leading-snug">{{ $user->gender || '—' }}</p>
                    </div>
                </div>
                <div class="info-pill">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-400 border border-slate-200 shrink-0"><i data-feather="phone" class="w-4 h-4"></i></div>
                    <div class="min-w-0 flex-grow">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Nomor Telepon</p>
                        <p class="text-xs font-bold text-slate-700 truncate leading-snug">{{ $user->no_telephone || '—' }}</p>
                    </div>
                </div>
                <div class="info-pill">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-400 border border-slate-200 shrink-0"><i data-feather="map-pin" class="w-4 h-4"></i></div>
                    <div class="min-w-0 flex-grow">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Alamat Tinggal</p>
                        <p class="text-xs font-bold text-slate-700 truncate leading-snug">{{ $user->address || '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-4 flex items-center gap-2.5 px-2">
    <span class="w-2 h-2 rounded-full bg-indigo-650"></span>
    <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Daftar Kelas yang Diampu</h4>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-20 text-left" id="data-teacher">
    <div class="col-span-full py-20 text-center" id="loading">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-650 rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">Menyelaraskan data kelas...</p>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        const authId = '{{ $user->id }}';

        const fetchClassData = () => {
            $.ajax({
                url: `/api/classroom/teacher/data/${authId}`,
                method: 'GET',
                success: function(response) {
                    $('#loading').hide();
                    let container = $('#data-teacher');
                    container.empty();

                    if (response.data.length > 0) {
                        response.data.forEach((kelas) => {
                            let statusBadge = kelas.status === 'accept' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : (kelas.status === 'reject' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-amber-50 text-amber-700 border-amber-100');
                            
                            container.append(`
                                <div class="course-card group animate-fade-in">
                                    <div class="flex justify-between items-start mb-6 shrink-0">
                                        <div class="bg-indigo-50 border border-indigo-100 text-indigo-650 p-3 rounded-xl"><i data-feather="layers" class="w-5 h-5"></i></div>
                                        <span class="px-2 py-0.5 ${statusBadge} border rounded-lg font-bold text-[8px] uppercase tracking-wider">${kelas.status === 'accept' ? 'Aktif' : (kelas.status === 'reject' ? 'Ditolak' : 'Menunggu')}</span>
                                    </div>
                                    <h3 class="text-sm font-extrabold text-slate-900 leading-snug mb-1 group-hover:text-indigo-650 transition-colors truncate">${kelas.name}</h3>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-4">Kode Kelas: ${kelas.codeClass}</p>
                                    <div class="grid grid-cols-2 gap-4 mt-auto pt-4 border-t border-slate-100">
                                        <div class="min-w-0"><p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Siswa</p><p class="text-xs font-bold text-slate-700 truncate leading-snug">${kelas.total_user} / ${kelas.limit}</p></div>
                                        <div class="text-right min-w-0"><p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Visibilitas</p><p class="text-xs font-bold text-slate-700 uppercase truncate leading-snug">${kelas.statusClass || 'Umum'}</p></div>
                                    </div>
                                    <a href="/admin/classroom/detail/${kelas.id}" class="mt-5 block w-full py-2 bg-slate-900 text-white text-center font-bold text-xs rounded-xl hover:bg-indigo-650 transition-all active:scale-[0.98]">Inspeksi Kelas</a>
                                </div>
                            `);
                        });
                        feather.replace();
                    } else {
                        container.html('<div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-slate-400 font-bold text-xs">Pengajar belum membuat kelas apa pun.</div>');
                    }
                }
            });
        };

        const handleUserStatus = (userId, type) => {
            const isAccept = type === 'accept';
            Swal.fire({
                title: isAccept ? 'Verifikasi Dosen?' : 'Tolak Dosen?',
                text: isAccept ? "Pengajar akan diberikan akses masuk ke platform." : "Pengajuan dosen akan ditolak.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isAccept ? '#4f46e5' : '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: isAccept ? 'Ya, Terima' : 'Ya, Tolak',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold text-xs py-2.5 px-6 mx-2',
                    cancelButton: 'rounded-xl font-bold text-xs py-2.5 px-6 mx-2 text-slate-700'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: isAccept ? `/api/accept/${userId}` : `/api/reject/${userId}`,
                        method: 'POST',
                        success: function() {
                            Swal.fire({ title: 'Berhasil!', text: 'Status berhasil diperbarui.', icon: 'success', customClass: { popup: 'rounded-2xl' } })
                            .then(() => location.reload());
                        }
                    });
                }
            });
        };

        $('.accept-button-user').click(function() { handleUserStatus($(this).data('id'), 'accept'); });
        $('.reject-button-user').click(function() { handleUserStatus($(this).data('id'), 'reject'); });

        fetchClassData();
    });
</script>
@endsection