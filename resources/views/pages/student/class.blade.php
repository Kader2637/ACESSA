@extends('layouts.student.app')

@section('title', 'Katalog Kelas — Portal Mahasiswa')
@section('page_title', 'Katalog Kelas')

@section('style')
<style>
    .course-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; display: flex; flex-direction: column; }
    .course-card:hover { transform: translateY(-4px); border-color: #cbd5e1; box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05); }
    .image-container { aspect-ratio: 16/9; overflow: hidden; border-radius: 1rem; position: relative; border: 1px solid #f1f5f9; }
</style>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm animate-fade-in">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Katalog <span class="text-indigo-650">Kelas Kuliah</span></h2>
        <p class="text-slate-500 text-xs font-semibold mt-0.5">Akses semua materi ajar, kurikulum, dan kolaborasi dalam satu tempat.</p>
    </div>
    <button id="joinClassButton" class="px-4 py-2 bg-slate-900 hover:bg-indigo-650 text-white font-bold text-xs rounded-xl transition-all shadow-sm active:scale-[0.98] shrink-0 flex items-center gap-1.5">
        <i data-feather="plus" class="w-4 h-4"></i> Gabung Kelas Baru
    </button>
</div>

<div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-20" data-aos="fade-up">
    <div id="loading-state" class="col-span-full py-32 flex flex-col items-center justify-center">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-650 rounded-full animate-spin mb-3"></div>
        <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest animate-pulse">Menghubungkan data kelas...</p>
    </div>
</div>

<div id="no-data" class="hidden py-24 flex-col items-center justify-center text-center bg-white border border-dashed border-slate-200 rounded-3xl" data-aos="zoom-in">
    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 text-slate-450">
        <i data-feather="folder-open" class="w-6 h-6"></i>
    </div>
    <h3 class="font-bold text-slate-800 text-sm">Belum Ada Kelas</h3>
    <p class="mt-1 text-slate-405 text-xs">Gunakan kode kelas dari dosen pengampu Anda untuk mulai bergabung dan belajar.</p>
</div>

{{-- MODAL: Join Classroom --}}
<div id="joinModal" class="fixed inset-0 z-[1000] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeJoinModal()"></div>
    <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-8 text-center border border-slate-200 animate-zoom-in">
        <div class="w-16 h-16 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-5">
            <i data-feather="book-open" class="w-8 h-8"></i>
        </div>
        <h3 class="text-lg font-extrabold text-slate-900 mb-1">Gabung ke Kelas</h3>
        <p class="text-slate-550 font-semibold mb-6 text-xs leading-relaxed">Masukkan kode akses kelas yang Anda terima dari dosen pengampu untuk bergabung.</p>
        
        <div>
            <input type="text" id="classCodeInput" 
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all text-center tracking-widest text-sm" 
                placeholder="CONTOH: ABCDEF">
        </div>

        <div class="flex gap-3 mt-6">
            <button onclick="closeJoinModal()" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">Batal</button>
            <button id="confirmJoinBtn" class="flex-[2] py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5">
                <span id="btnText">Gabung Kelas</span>
                <div id="btnSpinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            </button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function openJoinModal() {
        $('#joinModal').removeClass('hidden').addClass('flex');
    }

    function closeJoinModal() {
        $('#joinModal').removeClass('flex').addClass('hidden');
        $('#classCodeInput').val('');
    }

    function loadClassroomData(userId) {
        $.ajax({
            url: `/api/student/classroom/data/${userId}`,
            method: 'GET',
            success: function(response) {
                $('#loading-state').remove();
                const container = $('#courses-container');
                container.find('.course-card').remove();

                if (response.status === "success" && response.StudentClassroomRelations.length === 0) {
                    $('#no-data').removeClass('hidden').addClass('flex');
                    container.addClass('hidden');
                    feather.replace();
                } else {
                    $('#no-data').addClass('hidden');
                    container.removeClass('hidden').addClass('grid');

                    response.StudentClassroomRelations.forEach(relation => {
                        const course = relation.course;
                        const user = relation.user;
                        const courseThumbnail = (course.thumbnail && course.thumbnail !== 'default.png') ? `/storage/${course.thumbnail}` : '/classaccesa.png';
                        const authorImage = user.profile ? `/storage/${user.profile}` : '/user.png';
                        const desc = course.description.length > 80 ? course.description.substring(0, 80) + '...' : course.description;

                        const html = `
                            <div class="course-card group">
                                <div class="image-container relative mb-4">
                                    <img src="${courseThumbnail}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300" onerror="this.onerror=null; this.src='/classaccesa.png';">
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-1 bg-white border border-slate-200 text-indigo-600 font-bold text-[8px] uppercase tracking-wider rounded-lg shadow-sm">
                                            ${course.statusClass || 'Umum'}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-5 pb-5 flex flex-col flex-grow text-left">
                                    <h5 class="text-sm font-extrabold text-slate-900 leading-snug line-clamp-1 group-hover:text-indigo-600 transition-colors">${course.name}</h5>
                                    <p class="mt-2 text-slate-500 text-xs font-semibold leading-relaxed line-clamp-2">${desc}</p>
                                    <div class="mt-auto pt-4 border-t border-slate-100 mt-5">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-2">
                                                <img src="${authorImage}" class="w-5 h-5 rounded-md border border-slate-100 object-cover" onerror="this.onerror=null; this.src='/user.png';">
                                                <span class="text-[10px] font-bold text-slate-500 truncate max-w-[110px]">${course.teacher}</span>
                                            </div>
                                            <div class="flex items-center gap-1 text-slate-400">
                                                <i data-feather="users" class="w-3.5 h-3.5"></i>
                                                <span class="text-[9px] font-extrabold text-slate-700">${course.total_user}</span>
                                            </div>
                                        </div>
                                        <a href="/student/classroom/course/${course.id}" class="w-full py-2.5 bg-slate-900 hover:bg-indigo-650 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all active:scale-[0.98]">
                                            Masuk Kelas <i data-feather="arrow-right" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>`;
                        container.append(html);
                    });
                    feather.replace();
                }
            }
        });
    }

    $(document).ready(function() {
        loadClassroomData({{ auth()->user()->id }});

        $('#joinClassButton').on('click', openJoinModal);

        $('#confirmJoinBtn').on('click', function() {
            const code = $('#classCodeInput').val();
            const btn = $(this);
            const spinner = $('#btnSpinner');
            const text = $('#btnText');

            if (!code) {
                toastr.warning("Silakan masukkan kode kelas!");
                return;
            }

            btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
            text.addClass('hidden');
            spinner.removeClass('hidden');

            $.ajax({
                url: '/api/classroom/join',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: JSON.stringify({ 
                    user_id: {{ auth()->user()->id }}, 
                    classroom_code: code 
                }),
                contentType: 'application/json',
                success: function(res) {
                    toastr.success(res.message || 'Berhasil bergabung!');
                    closeJoinModal();
                    loadClassroomData({{ auth()->user()->id }});
                },
                error: function(xhr) {
                    let errorMsg = "Terjadi kesalahan sistem.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);
                },
                complete: function() {
                    btn.prop('disabled', false).removeClass('opacity-70 cursor-not-allowed');
                    text.removeClass('hidden');
                    spinner.addClass('hidden');
                }
            });
        });
    });
</script>
@endsection