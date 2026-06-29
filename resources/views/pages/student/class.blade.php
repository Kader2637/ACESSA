@extends('layouts.student.app')

@section('page_title', 'Katalog Kelas')

@section('style')
<style>
    .course-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .course-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
    .bg-grid-subtle {
        background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px),
                          linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
        background-size: 30px 30px;
    }
</style>
@endsection

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2rem] border border-slate-200 bg-grid-subtle shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Ruang <span class="text-indigo-600">Belajar</span></h2>
        <p class="text-slate-500 font-medium mt-1">Akses semua materi dan kolaborasi dalam satu tempat.</p>
    </div>
    <button id="joinClassButton" class="inline-flex items-center gap-2 px-6 py-3.5 bg-slate-900 text-white font-black uppercase text-[11px] tracking-widest rounded-2xl hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-500/30 transition-all active:scale-95">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        Gabung Kelas Baru
    </button>
</div>

<div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-20">
    <div id="loading-state" class="col-span-full py-32 flex flex-col items-center justify-center">
        <div class="w-12 h-12 border-4 border-slate-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
        <p class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] animate-pulse">Memuat Data Kelas...</p>
    </div>
</div>

<div id="no-data" class="hidden col-span-full py-24 flex flex-col items-center justify-center text-center bg-white border-2 border-dashed border-slate-200 rounded-[3rem]" data-aos="zoom-in">
    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
    </div>
    <h3 class="text-xl font-black text-slate-900 tracking-tight">Belum Ada Kelas</h3>
    <p class="mt-2 text-slate-500 font-medium max-w-xs mx-auto text-sm">Gunakan kode kelas dari instruktur Anda untuk mulai belajar.</p>
</div>

<div id="joinModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 transition-all duration-300">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" id="modalBackdrop"></div>
    <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl scale-95 opacity-0 transition-all duration-300 overflow-hidden" id="modalContent">
        <div class="p-10">
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Gabung ke Kelas</h3>
                <p class="text-slate-500 font-medium mt-2">Masukkan kode akses unik yang diberikan oleh instruktur Anda untuk mulai belajar.</p>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kode Akses Kelas</label>
                    <input type="text" id="classCodeInput" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 font-bold placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 transition-all text-center tracking-widest" 
                        placeholder="CONTOH: ACS-123">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-10">
                <button onclick="closeJoinModal()" class="py-4 bg-slate-100 text-slate-600 font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-slate-200 transition-all">Batal</button>
                <button id="confirmJoinBtn" class="py-4 bg-slate-900 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/20 transition-all flex items-center justify-center gap-2">
                    <span id="btnText">Gabung Sekarang</span>
                    <div id="btnSpinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const modal = $('#joinModal');
    const backdrop = $('#modalBackdrop');
    const content = $('#modalContent');

    function openJoinModal() {
        modal.removeClass('hidden').addClass('flex');
        setTimeout(() => {
            backdrop.removeClass('opacity-0');
            content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
    }

    function closeJoinModal() {
        backdrop.addClass('opacity-0');
        content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            modal.removeClass('flex').addClass('hidden');
            $('#classCodeInput').val('');
        }, 300);
    }

    function loadClassroomData(userId) {
        $.ajax({
            url: `/api/student/classroom/data/${userId}`,
            method: 'GET',
            success: function(response) {
                $('#loading-state').remove();
                const container = $('#courses-container');
                container.find('.course-item').remove();

                if (response.status === "success" && response.StudentClassroomRelations.length === 0) {
                    $('#no-data').removeClass('hidden').addClass('flex');
                } else {
                    $('#no-data').addClass('hidden');
                    response.StudentClassroomRelations.forEach(relation => {
                        const course = relation.course;
                        const user = relation.user;
                        const courseThumbnail = course.thumbnail ? `/storage/${course.thumbnail}` : '/assets/user.png';
                        const authorImage = user.profile ? `/storage/${user.profile}` : '/user.png';
                        const desc = course.description.length > 80 ? course.description.substring(0, 80) + '...' : course.description;

                        const html = `
                            <div class="course-item group bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden flex flex-col h-full hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:-translate-y-1">
                                <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                                    <img src="${courseThumbnail}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1 bg-white/90 backdrop-blur text-indigo-600 font-black text-[9px] uppercase tracking-widest rounded-lg shadow-sm">ACESSA</span>
                                    </div>
                                </div>
                                <div class="p-6 flex flex-col flex-grow">
                                    <h5 class="text-lg font-extrabold text-slate-900 leading-tight line-clamp-2 group-hover:text-indigo-600 transition-colors">${course.name}</h5>
                                    <p class="mt-3 text-slate-500 text-sm font-medium leading-relaxed line-clamp-2">${desc}</p>
                                    <div class="mt-auto pt-6">
                                        <div class="flex items-center justify-between mb-6">
                                            <div class="flex items-center gap-2">
                                                <img src="${authorImage}" class="w-7 h-7 rounded-full border border-slate-100 object-cover">
                                                <span class="text-xs font-bold text-slate-700">${course.teacher}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-slate-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                <span class="text-[10px] font-bold">${course.total_user}</span>
                                            </div>
                                        </div>
                                        <a href="/student/classroom/course/${course.id}" class="flex items-center justify-center gap-2 w-full py-4 bg-slate-900 text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-2xl hover:bg-indigo-600 shadow-lg transition-all duration-300">
                                            Masuk Kelas
                                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>`;
                        container.append(html);
                    });
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
            // Jika server kirim status 200/201
            success: function(res) {
                // Langsung ambil pesan dari server, jangan dicek manual teksnya
                toastr.success(res.message || 'Berhasil bergabung!');
                closeJoinModal();
                loadClassroomData({{ auth()->user()->id }});
            },
            // Jika server kirim status 400, 404, 500, dll
            error: function(xhr) {
                let errorMsg = "Terjadi kesalahan sistem.";
                
                // Ambil pesan dinamis dari JSON server
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