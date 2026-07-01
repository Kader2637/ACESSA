@extends('layouts.admin.app')

@section('title', 'Pusat Persetujuan — Panel Admin')
@section('page_title', 'Pusat Persetujuan')

@section('style')
<style>
    /* Desain Tabel Audit Premium */
    .audit-table th { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; color: #64748b; padding: 1.25rem 1rem; border: none; background: #f8fafc; }
    .audit-table td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: #1e293b; font-size: 12px; }
    .table-container { background: white; border-radius: 1.5rem; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    
    /* Kartu Kelas Modern (Solid Accents) */
    .course-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; background: white; border: 1px solid #e2e8f0; padding: 1.5rem; display: flex; flex-direction: column; }
    .course-card:hover { transform: translateY(-4px); border-color: #cbd5e1; box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05); }
    .thumb-container { aspect-ratio: 16/9; overflow: hidden; border-radius: 1rem; position: relative; margin-bottom: 1.25rem; border: 1px solid #f1f5f9; }
</style>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Pusat <span class="text-indigo-600">Persetujuan &amp; Aktivasi</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Tinjau permohonan akun dosen/guru baru dan aktivasi kelas baru</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Verifikasi Gerbang</span>
    </div>
</div>

<div class="space-y-8 mb-20">
    {{-- Antrean Dosen --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-2.5 mb-4">
            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Antrean Verifikasi Guru &amp; Dosen</h4>
        </div>
        
        <div class="table-container">
            <div class="overflow-x-auto">
                <table class="w-full text-left audit-table" id="data-table">
                    <thead>
                        <tr>
                            <th class="text-center w-20">No</th>
                            <th>Profil Instruktur</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Kontak</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" class="py-20 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest animate-pulse" id="teacher-loader">Memindai Database Pengajar...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Antrean Kelas --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-2.5 mb-6">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Persetujuan Aktivasi Kelas Baru</h4>
        </div>
        
        <div id="project-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
            <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">Menghubungkan database kelas...</p>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Setujui Kelas --}}
<div id="modal-accept" class="fixed inset-0 z-[1000] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('accept')"></div>
    <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-8 text-center border border-slate-200 animate-zoom-in">
        <div class="w-16 h-16 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-5">
            <i data-feather="check-circle" class="w-8 h-8"></i>
        </div>
        <h3 class="text-lg font-extrabold text-slate-900 mb-1">Setujui &amp; Aktifkan Kelas?</h3>
        <p class="text-slate-500 font-semibold mb-6 text-xs leading-relaxed">Materi kelas akan langsung dipublikasikan dan dapat diakses mahasiswa.</p>
        <form id="form-accept">
            @csrf <input type="hidden" id="AcceptClassId">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('accept')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">Aktifkan Kelas</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Tolak Kelas --}}
<div id="modal-reject" class="fixed inset-0 z-[1000] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('reject')"></div>
    <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-8 text-center border border-slate-200 animate-zoom-in">
        <div class="w-16 h-16 bg-red-50 border border-red-100 text-red-500 rounded-xl flex items-center justify-center mx-auto mb-5">
            <i data-feather="x-circle" class="w-8 h-8"></i>
        </div>
        <h3 class="text-lg font-extrabold text-slate-900 mb-1">Tolak Aktivasi Kelas?</h3>
        <p class="text-slate-500 font-semibold mb-6 text-xs leading-relaxed">Pengajar harus melengkapi detail materi sebelum mengajukan kembali.</p>
        <form id="form-tolak">
            @csrf <input type="hidden" id="RejectClassId">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('reject')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        window.openModal = function(type, id) {
            $(`#AcceptClassId, #RejectClassId`).val(id);
            $(`#modal-${type}`).removeClass('hidden').addClass('flex');
        };

        window.closeModal = function(type) {
            $(`#modal-${type}`).removeClass('flex').addClass('hidden');
        };

        // Ambil Data Guru
        function fetchInstructors() {
            $.ajax({
                url: '/api/teacher/pending',
                method: 'GET',
                success: function(res) {
                    let tbody = $('#data-table tbody');
                    tbody.empty();
                    if (res.data.length === 0) {
                        tbody.append('<tr><td colspan="5" class="py-20 text-center text-slate-400 font-bold uppercase text-[10px]">Antrean bersih, tidak ada pengajar tertunda.</td></tr>');
                    } else {
                        res.data.forEach((item, i) => {
                            const img = item.image ? `/storage/${item.image}` : `/user.png';
                            tbody.append(`
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="text-center text-slate-400 font-bold">#${i + 1}</td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <img class="w-9 h-9 rounded-lg object-cover border border-slate-100 shrink-0" src="${img}" onerror="this.src='/user.png'">
                                            <div>
                                                <p class="font-extrabold text-slate-900 leading-none">${item.name}</p>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">ID Pengguna: #${item.id}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded-md text-[9px] font-bold text-slate-600 uppercase">${item.gender || '—'}</span></td>
                                    <td class="text-center text-[11px] font-mono font-medium text-slate-500">${item.email}</td>
                                    <td class="text-center">
                                        <a href="/admin/teacher/detail/${item.id}" class="px-4 py-2 bg-slate-900 hover:bg-indigo-650 text-white rounded-xl font-bold text-xs transition-all">Audit Profil</a>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function() { $('#teacher-loader').text('Gagal memuat data.'); }
            });
        }

        // Ambil Data Kelas
        function fetchClassrooms() {
            $.ajax({
                url: '/api/approval/classroom',
                method: 'GET',
                success: function(res) {
                    const container = $('#project-container');
                    container.empty();
                    if (res.data.length === 0) {
                        container.html('<div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Antrean bersih, tidak ada kelas tertunda.</p></div>');
                    } else {
                        res.data.forEach(item => {
                            const thumb = item.thumbnail ? `/storage/${item.thumbnail}` : '/classaccesa.png';
                            container.append(`
                                <div class="course-card group animate-fade-in text-left">
                                    <div class="thumb-container">
                                        <img src="${thumb}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300" onerror="this.src='/classaccesa.png'">
                                        <span class="absolute top-3 right-3 px-2 py-1 bg-amber-500 text-white font-bold text-[8px] uppercase tracking-wider rounded-lg shadow-sm">Menunggu</span>
                                    </div>
                                    <h3 class="text-sm font-extrabold text-slate-900 mb-1 truncate leading-tight">${item.name}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-4">Pengajar: ${item.user}</p>
                                    <div class="mt-auto flex gap-2">
                                        <a href="/admin/classroom/detail/${item.id}" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl flex items-center justify-center transition-all"><i data-feather="info" class="w-4 h-4"></i></a>
                                        <button onclick="openModal('reject', ${item.id})" class="flex-1 py-2 bg-red-50 hover:bg-red-500 text-red-650 hover:text-white rounded-xl flex items-center justify-center transition-all"><i data-feather="x" class="w-4 h-4"></i></button>
                                        <button onclick="openModal('accept', ${item.id})" class="flex-[2] py-2 bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all"><i data-feather="check" class="w-4 h-4"></i> Aktifkan</button>
                                    </div>
                                </div>
                            `);
                        });
                        feather.replace();
                    }
                }
            });
        }

        // Aksi Submit
        $('#form-accept').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: `/api/acceptClass/${$('#AcceptClassId').val()}`,
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    toastr.success('Kelas Berhasil Diaktifkan');
                    closeModal('accept');
                    fetchClassrooms();
                }
            });
        });

        $('#form-tolak').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: `/api/rejectClass/${$('#RejectClassId').val()}`,
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    toastr.success('Aktivasi Kelas Ditolak');
                    closeModal('reject');
                    fetchClassrooms();
                }
            });
        });

        fetchInstructors();
        fetchClassrooms();
    });
</script>
@endsection