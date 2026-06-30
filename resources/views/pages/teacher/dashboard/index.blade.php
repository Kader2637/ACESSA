@extends('layouts.teacher.app')

@section('page_title', 'Dashboard Overview')

@section('style')
<style>
    .course-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .course-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.15);
    }
    .quick-action-btn {
        transition: all 0.3s ease;
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
{{-- Welcome Banner --}}
<div class="mb-8 bg-slate-900 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl" data-aos="fade-up">
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-500/20 to-cyan-500/20 blur-[80px] rounded-full -mr-16 -mt-16"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-violet-500/10 blur-[60px] rounded-full -ml-8 -mb-8"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-indigo-300 font-bold text-[10px] uppercase tracking-[0.2em] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                Panel Instruktur
            </div>
            <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight leading-tight">
                Selamat Datang kembali,<br> 
                <span class="bg-gradient-to-r from-indigo-400 to-cyan-300 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>
            </h2>
            <p class="mt-2 text-slate-400 font-medium max-w-md text-sm">
                Kelola ruang kelas Anda, buat materi, nilai tugas siswa, dan mulai sesi pembelajaran interaktif dengan mudah.
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('zoom.session') }}" class="quick-action-btn flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-3 rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Mulai Meeting
            </a>
            <a href="{{ route('task.assignmentAssessment') }}" class="quick-action-btn flex items-center gap-2 bg-white/10 hover:bg-white/15 text-white px-5 py-3 rounded-2xl font-bold text-xs border border-white/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Tugas Baru
            </a>
        </div>
    </div>
</div>

{{-- KPI Summary Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8" data-aos="fade-up" data-aos-delay="50">
    <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Kelas Dikelola</p>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900" id="countClassroom">0</span>
            <span class="text-xs text-slate-400 font-bold">Ruang</span>
        </div>
    </div>
    <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Total Siswa</p>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900" id="totalStudents">0</span>
            <span class="text-xs text-slate-400 font-bold">Siswa</span>
        </div>
    </div>
    <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Tugas Aktif</p>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900" id="totalTasks">0</span>
            <span class="text-xs text-slate-400 font-bold">Tugas</span>
        </div>
    </div>
    <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Total Guru/Dosen</p>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold text-slate-900" id="countTeacher">0</span>
            <span class="text-xs text-slate-400 font-bold">Rekan</span>
        </div>
    </div>
</div>

{{-- Main Dashboard Layout --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    {{-- Left Side: Managed Classes (8 columns) --}}
    <div class="lg:col-span-8 flex flex-col gap-6">
        
        <div class="flex items-center justify-between px-2">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Manajemen <span class="text-indigo-600">Kelas Aktif</span></h3>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Daftar kelas yang sedang berjalan</p>
            </div>
            <a href="{{ route('classroom.teacher') }}" class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>

        {{-- Loading Spinner --}}
        <div id="loading" class="py-20 flex flex-col items-center justify-center text-center bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] animate-pulse">Menghubungkan Database...</p>
        </div>

        {{-- Classes Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="data-teacher"></div>

        {{-- No Data Placeholder --}}
        <div id="no-data-message" class="hidden py-16 flex-col items-center justify-center text-center bg-white border border-dashed border-slate-200 rounded-3xl" data-aos="zoom-in">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-base">Belum Ada Kelas</h3>
            <p class="text-slate-400 text-xs font-medium mt-1 max-w-xs mx-auto">Silakan buat ruang kelas Anda terlebih dahulu untuk memulai pembelajaran.</p>
            <a href="{{ route('classroom.teacher') }}" class="mt-6 px-6 py-3 bg-indigo-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-md">Buat Kelas Baru</a>
        </div>

    </div>

    {{-- Right Side: Quick Widgets (4 columns) --}}
    <div class="lg:col-span-4 flex flex-col gap-6">
        
        {{-- Quick Actions --}}
        <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm" data-aos="fade-left" data-aos-delay="100">
            <h4 class="font-extrabold text-slate-900 text-sm mb-4">Aksi Cepat</h4>
            <div class="grid grid-cols-1 gap-2.5">
                <a href="{{ route('classroom.teacher') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-indigo-50/50 rounded-xl text-slate-700 hover:text-indigo-600 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs font-bold">Buat Kelas Baru</span>
                </a>
                <a href="{{ route('task.assignmentAssessment') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-indigo-50/50 rounded-xl text-slate-700 hover:text-indigo-600 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    </div>
                    <span class="text-xs font-bold">Buat & Nilai Tugas</span>
                </a>
                <a href="{{ route('zoom.session') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-indigo-50/50 rounded-xl text-slate-700 hover:text-indigo-600 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-xs font-bold">Mulai Zoom Virtual</span>
                </a>
                <a href="{{ route('teacher.statistics') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-indigo-50/50 rounded-xl text-slate-700 hover:text-indigo-600 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                    </div>
                    <span class="text-xs font-bold">Lihat Statistik & Analytics</span>
                </a>
            </div>
        </div>

        {{-- Active Session Info --}}
        <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm" data-aos="fade-left" data-aos-delay="150">
            <h4 class="font-extrabold text-slate-900 text-sm mb-4">Informasi Sesi</h4>
            <div class="flex flex-col gap-4 text-xs font-medium text-slate-500">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span>Browser OS</span>
                    <span class="font-bold text-slate-800">Windows</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span>Status Server</span>
                    <span class="flex items-center gap-1.5 text-emerald-600 font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Aktif / Normal
                    </span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span>Terakhir Update</span>
                    <span class="font-bold text-slate-800" id="lastUpdateTime">Hari Ini</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('script')
<script>
    const fetchClassData = () => {
        const authId = '{{ auth()->user()->id }}';
        $('#loading').show();
        $('#data-teacher').empty();

        $.ajax({
            url: `/api/my/classroom/teacher/data/${authId}`,
            method: 'GET',
            success: function(res) {
                $('#loading').hide();
                const data = res.data || [];
                
                // Calculate total students enrolled across all managed classes
                const totalStudents = data.reduce((sum, k) => sum + (k.student_count || 0), 0);
                $('#totalStudents').text(totalStudents);

                if (data.length > 0) {
                    data.forEach((kelas) => {
                        const thumb = kelas.thumbnail ? `{{ asset('storage') }}/${kelas.thumbnail}` : '/user.png';
                        const desc = kelas.description.length > 85 ? kelas.description.substring(0, 85) + '...' : kelas.description;
                        const students = kelas.student_count || 0;

                        $('#data-teacher').append(`
                            <div class="course-card bg-white border border-slate-200/60 rounded-3xl overflow-hidden flex flex-col h-full shadow-sm">
                                <div class="relative aspect-[16/10] overflow-hidden bg-slate-900">
                                    <img src="${thumb}" class="w-full h-full object-cover opacity-80" onerror="this.onerror=null; this.src='/user.png'">
                                    <div class="absolute top-4 left-4">
                                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-white/90 backdrop-blur-md rounded-lg shadow-sm border border-slate-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span class="text-[9px] font-extrabold text-slate-900 uppercase tracking-widest">Active</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-6 flex flex-col flex-grow">
                                    <h5 class="text-lg font-extrabold text-slate-900 leading-tight mb-2 line-clamp-1">${kelas.name}</h5>
                                    <p class="text-slate-500 text-xs font-semibold leading-relaxed mb-6 flex-grow line-clamp-2">${desc}</p>
                                    
                                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                                        <span class="text-xs font-bold text-slate-400">${students} Siswa</span>
                                        <a href="/teacher/classroom/course/${kelas.id}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs rounded-xl transition-all">
                                            Manage
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    $('#no-data-message').removeClass('hidden').addClass('flex');
                }
            },
            error: () => {
                $('#loading').hide();
                $('#no-data-message').removeClass('hidden').addClass('flex');
            }
        });
    };

    function fetchStats() {
        const authId = '{{ auth()->user()->id }}';
        
        // Fetch statistika (guru, kelas count)
        $.ajax({
            url: `/api/count/statistika/${authId}`,
            method: 'GET',
            success: function(res) {
                $('#countTeacher').text(res.countTeacher || 0);
                $('#countClassroom').text(res.countClassroom || 0);
            }
        });

        // Fetch task counts
        $.when(
            $.ajax({ url: `/api/done/assigment/task/${authId}`, method: 'GET' }),
            $.ajax({ url: `/api/not/assigment/task/${authId}`, method: 'GET' })
        ).done(function(doneRes, pendingRes) {
            const totalDone = (doneRes[0].data || []).length;
            const totalPending = (pendingRes[0].data || []).length;
            $('#totalTasks').text(totalDone + totalPending);
        });

        // Set last updated time to local time formatted beautifully
        const now = new Date();
        $('#lastUpdateTime').text(now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}) + ' WIB');
    }

    $(document).ready(function() {
        fetchClassData();
        fetchStats();
    });
</script>
@endsection