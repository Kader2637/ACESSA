@extends('layouts.student.app')

@section('title', 'Eksplorasi Kelas — Portal Mahasiswa')
@section('page_title', 'Bergabung Kelas Baru')

@section('style')
<style>
    .classroom-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; display: flex; flex-direction: column; }
    .classroom-card:hover { transform: translateY(-4px); border-color: #cbd5e1; box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05); }
    .image-container { aspect-ratio: 16/9; overflow: hidden; border-radius: 1rem; position: relative; border: 1px solid #f1f5f9; }
</style>
@endsection

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm animate-fade-in">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Katalog <span class="text-indigo-650">Kelas Global</span></h2>
        <p class="text-slate-500 text-xs font-semibold mt-0.5">Eksplorasi kelas-kelas kuliah yang tersedia dan ajukan pendaftaran bergabung.</p>
    </div>
</div>

<div id="classroom-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-20" data-aos="fade-up">
    <div id="loading-spinner" class="col-span-full py-32 flex flex-col items-center justify-center">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-yellow-500 rounded-full animate-spin mb-3"></div>
        <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest animate-pulse">Menghubungkan data katalog...</p>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function loadClassrooms() {
        const userId = {{ auth()->user()->id }};
        const list = $('#classroom-list');

        $.ajax({
            url: `/api/join/classroom/${userId}`,
            type: 'GET',
            success: function(response) {
                list.empty();
                let classrooms = response.data;

                if (classrooms.length === 0) {
                    list.append(`
                        <div class="col-span-full py-24 flex flex-col items-center justify-center text-center bg-white border border-dashed border-slate-200 rounded-3xl">
                            <i data-feather="slash" class="w-12 h-12 text-slate-300 mb-4"></i>
                            <h3 class="font-bold text-slate-800 text-sm">Katalog Kosong</h3>
                            <p class="text-slate-500 text-xs mt-1">Saat ini belum ada kelas baru yang tersedia.</p>
                        </div>
                    `);
                    feather.replace();
                } else {
                    $.each(classrooms, function(index, classroom) {
                        const thumb = classroom.thumbnail ? `/storage/${classroom.thumbnail}` : '/user.png';
                        const userImg = classroom.user_image ? `/storage/${classroom.user_image}` : '/user.png';
                        const desc = classroom.description.length > 80 ? classroom.description.substring(0, 80) + '...' : classroom.description;

                        list.append(`
                            <div class="classroom-card group">
                                <div class="image-container relative mb-4">
                                    <img src="${thumb}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300" onerror="this.onerror=null; this.src='/user.png';">
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-1 bg-white border border-slate-200 text-indigo-600 font-bold text-[8px] uppercase tracking-wider rounded-lg shadow-sm">Katalog</span>
                                    </div>
                                </div>
                                <div class="px-5 pb-5 flex flex-col flex-grow text-left">
                                    <h5 class="text-sm font-extrabold text-slate-900 leading-snug line-clamp-1 group-hover:text-indigo-650 transition-colors">${classroom.name}</h5>
                                    <p class="mt-2 text-slate-500 text-xs font-semibold leading-relaxed line-clamp-2">${desc}</p>
                                    
                                    <div class="mt-auto pt-4 border-t border-slate-100 mt-5">
                                        <div class="flex items-center gap-2.5 mb-4">
                                            <img src="${userImg}" class="w-7 h-7 rounded-md border border-slate-100 object-cover" onerror="this.onerror=null; this.src='/user.png';">
                                            <div class="flex flex-col text-left">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none">Dosen Pengampu</span>
                                                <span class="text-xs font-bold text-slate-700 mt-0.5 truncate max-w-[120px]">${classroom.user_name}</span>
                                            </div>
                                        </div>
                                        <button class="join-button w-full py-2.5 bg-slate-900 hover:bg-yellow-500 hover:text-slate-950 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all active:scale-[0.98]" data-id="${classroom.id}">
                                            Daftar Sekarang <i data-feather="plus-circle" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    $('.join-button').on('click', function() {
                        const id = $(this).data('id');
                        confirmJoin(id, userId);
                    });
                    feather.replace();
                }
            }
        });
    }

    function confirmJoin(classId, userId) {
        Swal.fire({
            title: 'Konfirmasi Bergabung',
            text: 'Ajukan permintaan bergabung Anda untuk diverifikasi oleh dosen pengampu kelas.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Daftar',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-slate-950 font-bold rounded-xl mx-2 transition-all text-xs active:scale-[0.98]',
                cancelButton: 'px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-650 font-bold rounded-xl mx-2 transition-all text-xs active:scale-[0.98]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/Apiclassroom/join/${classId}`,
                    type: 'POST',
                    data: { user_id: userId, _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success(res.message || 'Pendaftaran berhasil dikirim!');
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Gagal mendaftar ke kelas.');
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        loadClassrooms();
    });
</script>
@endsection