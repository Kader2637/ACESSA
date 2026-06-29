@extends('layouts.student.app')

@section('page_title', 'Dashboard Overview')

@section('content')
<div class="mb-10 bg-slate-900 rounded-[2.5rem] p-8 md:p-12 text-white relative overflow-hidden shadow-2xl shadow-indigo-100" data-aos="fade-up">
    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/20 blur-[80px] rounded-full -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-500/10 blur-[60px] rounded-full -ml-10 -mb-10"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-indigo-300 font-bold text-[10px] uppercase tracking-widest mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                Status: Aktif
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight leading-tight">
                Selamat Datang, <br class="md:hidden"> 
                <span class="text-indigo-400">{{ auth()->user()->name }}</span> 👋
            </h2>
            <p class="mt-3 text-slate-400 font-medium max-w-md leading-relaxed">
                Lanjutkan perjalanan belajarmu di ACESSA. Selesaikan setiap modul untuk memperkuat portofolio teknismu.
            </p>
        </div>

        <div class="flex items-center gap-5 bg-white/5 border border-white/10 p-6 rounded-[2rem] backdrop-blur-md min-w-[200px]">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-500/20">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Kelas Diikuti</p>
                <p class="text-3xl font-black mt-1"><span id="count">0</span></p>
            </div>
        </div>
    </div>
</div>

<div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-20">
    <div id="loading-state" class="col-span-full py-32 flex flex-col items-center justify-center text-center">
        <div class="w-12 h-12 border-4 border-slate-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
        <p class="text-slate-400 font-black text-[10px] uppercase tracking-widest animate-pulse">Menghubungkan ke Server...</p>
    </div>
</div>

<div id="no-data" class="hidden py-32 flex-col items-center justify-center text-center bg-white border-2 border-dashed border-slate-200 rounded-[3rem]" data-aos="zoom-in">
    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
    </div>
    <h3 class="text-xl font-black text-slate-900 tracking-tight">Belum Ada Kelas</h3>
    <p class="mt-2 text-slate-500 font-medium max-w-xs mx-auto text-sm">Anda belum bergabung dalam kelas manapun.</p>
    <a href="/student/classroom" class="mt-8 px-8 py-3 bg-slate-900 text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-indigo-600 transition-all shadow-lg">Eksplorasi Katalog</a>
</div>
@endsection

@section('script')
<script>
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
                    container.addClass('hidden');
                } else {
                    $('#no-data').addClass('hidden');
                    container.removeClass('hidden').addClass('grid');

                    response.StudentClassroomRelations.forEach(relation => {
                        const course = relation.course;
                        const user = relation.user;

                        const courseThumbnail = course.thumbnail ? `/storage/${course.thumbnail}` : '/assets/user.png';
                        const authorImage = user.profile ? `/storage/${user.profile}` : '/user.png';
                        const desc = course.description.length > 80 ? course.description.substring(0, 80) + '...' : course.description;

                        const html = `
                            <div class="course-item group bg-white border border-slate-200 rounded-[2rem] overflow-hidden flex flex-col h-full hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:-translate-y-1">
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
                                        <a href="/student/classroom/course/${course.id}" class="flex items-center justify-center gap-2 w-full py-4 bg-slate-900 text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-2xl hover:bg-indigo-600 shadow-lg hover:shadow-indigo-500/30 transition-all duration-300">
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

    function fetchCount(userId) {
        $.ajax({
            url: `/api/count/student/${userId}`,
            method: 'GET',
            success: function(response) {
                $('#count').text(response.count);
            }
        });
    }

    $(document).ready(function() {
        const userId = {{ auth()->user()->id }};
        loadClassroomData(userId);
        fetchCount(userId);
    });
</script>
@endsection