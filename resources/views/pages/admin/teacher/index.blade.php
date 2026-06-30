@extends('layouts.admin.app')

@section('title', 'Kelola Pengajar — Panel Admin')
@section('page_title', 'Database Guru &amp; Dosen')

@section('style')
<style>
    .teacher-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; padding: 1.5rem; flex-direction: column; display: flex; align-items: center; text-align: center; }
    .teacher-card:hover { transform: translateY(-4px); border-color: #cbd5e1; box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05); }
    .profile-frame { position: relative; width: 80px; height: 80px; margin-bottom: 1.25rem; }
    .profile-frame img { width: 100%; height: 100%; object-fit: cover; border-radius: 1rem; border: 2px solid #e2e8f0; }
    .status-badge { position: absolute; bottom: -2px; right: -2px; width: 18px; height: 18px; background: #10b981; border: 3px solid #fff; border-radius: 50%; }
</style>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Database <span class="text-indigo-600">Guru &amp; Dosen</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5 font-sans">Manajemen akun verifikasi, hak akses, dan audit profil pengajar platform</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Platform Mentors</span>
    </div>
</div>

<div id="teacher-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-20" data-aos="fade-up">
    <div class="col-span-full py-24 text-center" id="loading-state">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-650 rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">Memuat database pengajar...</p>
    </div>
</div>

<div id="no-data-message" class="hidden py-24 flex flex-col items-center justify-center bg-white rounded-3xl border border-dashed border-slate-200 animate-fade-in">
    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 mb-4">
        <i data-feather="users" class="w-6 h-6"></i>
    </div>
    <h4 class="font-bold text-slate-800 text-sm">Database Kosong</h4>
    <p class="text-slate-400 text-xs mt-1">Tidak ada instruktur yang terdaftar dalam sistem.</p>
</div>

@include('components.modal-delete')
@endsection

@section('script')
<script>
    $(document).ready(function() {
        const fetchTeachers = () => {
            $('#loading-state').removeClass('hidden');
            $.ajax({
                url: '/api/teacher',
                type: 'GET',
                success: function(res) {
                    $('#loading-state').addClass('hidden');
                    const container = $('#teacher-container');
                    container.empty();

                    if (res.status === 'success' && res.data.length > 0) {
                        $('#no-data-message').addClass('hidden');
                        res.data.forEach(t => {
                            const img = t.image ? `/storage/${t.image}` : '/user.png';
                            container.append(`
                                <div class="teacher-card group animate-fade-in">
                                    <div class="profile-frame">
                                        <img src="${img}" alt="${t.name}" onerror="this.src='/user.png'">
                                        <div class="status-badge shadow-sm"></div>
                                    </div>
                                    
                                    <h5 class="text-sm font-extrabold text-slate-900 leading-snug mb-1 truncate w-full px-2 group-hover:text-indigo-650 transition-colors">${t.name}</h5>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-6 truncate w-full px-2">${t.email}</p>
                                    
                                    <div class="w-full flex gap-2 mt-auto">
                                        <a href="/admin/teacher/detail/${t.id}" class="flex-[2] py-2 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-indigo-650 transition-all flex items-center justify-center gap-1.5 active:scale-[0.98]">
                                            <i data-feather="eye" class="w-3.5 h-3.5"></i> Detail
                                        </a>
                                        <button onclick="openDeleteModal(${t.id})" class="flex-1 py-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white border border-red-100 hover:border-red-500 transition-all flex items-center justify-center active:scale-[0.98]">
                                            <i data-feather="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </div>
                            `);
                        });
                        feather.replace();
                    } else {
                        $('#no-data-message').removeClass('hidden');
                        feather.replace();
                    }
                }
            });
        };

        window.openDeleteModal = function(userId) {
            $('#deleteClassId').val(userId);
            $('#modal-delete').removeClass('hidden').addClass('flex');
        };

        window.closeModal = function(type) {
            if(type === 'delete') {
                $('#modal-delete').removeClass('flex').addClass('hidden');
            }
        };

        $('#form-delete').on('submit', function(e) {
            e.preventDefault();
            const id = $('#deleteClassId').val();
            
            $.ajax({
                url: `/api/user/delete/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    toastr.success('Dosen Berhasil Dihapus');
                    closeModal('delete');
                    fetchTeachers();
                },
                error: function() {
                    toastr.error('Gagal menghapus pengajar.');
                }
            });
        });

        fetchTeachers();
    });
</script>
@endsection