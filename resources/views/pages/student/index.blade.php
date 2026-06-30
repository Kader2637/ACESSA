@extends('layouts.student.app')

@section('title', 'Dashboard Mahasiswa — Portal Mahasiswa')
@section('page_title', 'Ringkasan Aktivitas')

@section('style')
<style>
    .kpi-card { border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; }
    .course-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; display: flex; flex-direction: column; }
    .course-card:hover { transform: translateY(-4px); border-color: #cbd5e1; box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05); }
    .image-container { aspect-ratio: 16/10; overflow: hidden; border-radius: 1rem; position: relative; border: 1px solid #f1f5f9; }
</style>
@endsection

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm animate-fade-in">
    <div>
        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-yellow-50 border border-yellow-100 text-yellow-650 font-bold text-[10px] uppercase tracking-widest mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
            Status: Mahasiswa Aktif
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
            Selamat Datang, <span class="text-yellow-600">{{ auth()->user()->name }}</span> 👋
        </h2>
        <p class="text-slate-500 text-xs font-semibold mt-1">Pantau perkembangan kurikulum, statistik tugas, dan jadwal kuliah Anda.</p>
    </div>
    
    <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 p-4 rounded-2xl min-w-[220px] shrink-0 shadow-sm">
        <div class="w-10 h-10 bg-yellow-500 text-slate-950 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
            <i data-feather="book" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">Kelas Diikuti</p>
            <p class="text-xl font-black text-slate-800 mt-1"><span id="count">0</span></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    {{-- Left: Classrooms List --}}
    <div class="lg:col-span-8 flex flex-col gap-5">
        <div class="flex items-center gap-2 px-2">
            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Daftar Kelas Saya</h4>
        </div>

        <div id="loading-state" class="py-24 flex flex-col items-center justify-center bg-white border border-slate-200 rounded-3xl">
            <div class="w-8 h-8 border-3 border-slate-100 border-t-yellow-500 rounded-full animate-spin mb-3"></div>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest animate-pulse">Menghubungkan data kelas...</p>
        </div>

        <div id="no-data" class="hidden py-24 flex-col items-center justify-center text-center bg-white border border-dashed border-slate-200 rounded-3xl">
            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                <i data-feather="folder-open" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Belum Bergabung Kelas</h3>
            <p class="mt-1 text-slate-400 text-xs">Anda belum terdaftar dalam kelas pengajaran apa pun saat ini.</p>
            <a href="/student/classroom" class="mt-6 px-5 py-2.5 bg-slate-900 hover:bg-yellow-500 hover:text-slate-950 text-white font-bold text-xs rounded-xl transition-all shadow-sm">Katalog Kelas</a>
        </div>

        <div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-20 hidden"></div>
    </div>

    {{-- Right: Weekly Learning Graphic & Stats --}}
    <div class="lg:col-span-4 flex flex-col gap-6" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider">Aktivitas Belajar Mingguan</h3>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            
            <div class="relative w-full h-[200px]">
                <canvas id="weeklyActivityChart"></canvas>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/50">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Rata-rata Harian</p>
                    <p class="text-sm font-black text-slate-800">45 Menit</p>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/50">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Status Keaktifan</p>
                    <p class="text-sm font-black text-emerald-600">Sangat Baik</p>
                </div>
            </div>
        </div>

        {{-- Academic Shortcuts Card --}}
        <div class="bg-slate-900 text-white rounded-3xl p-6 relative overflow-hidden shadow-sm">
            <p class="text-[8px] font-bold text-yellow-500 uppercase tracking-widest mb-1.5">Info Akademik</p>
            <h4 class="text-base font-extrabold tracking-tight">Kartu Hasil Studi (KHS)</h4>
            <p class="text-slate-400 text-xs font-semibold mt-1 leading-relaxed">Lihat nilai semester berjalan &amp; IPS Anda di KHS Portal.</p>
            <a href="/student/khs" class="mt-6 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-slate-950 font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all shadow-sm active:scale-[0.98]">
                Buka KHS Portal <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function loadClassroomData(userId) {
        $.ajax({
            url: `/api/student/classroom/data/${userId}`,
            method: 'GET',
            success: function(response) {
                $('#loading-state').remove();
                const container = $('#courses-container');
                container.empty();

                if (response.status === "success" && response.StudentClassroomRelations.length === 0) {
                    $('#no-data').removeClass('hidden').addClass('flex');
                    container.addClass('hidden');
                } else {
                    $('#no-data').addClass('hidden');
                    container.removeClass('hidden').addClass('grid');

                    response.StudentClassroomRelations.forEach(relation => {
                        const course = relation.course;
                        const user = relation.user;

                        const courseThumbnail = course.thumbnail ? `/storage/${course.thumbnail}` : '/user.png';
                        const authorImage = user.profile ? `/storage/${user.profile}` : '/user.png';
                        const desc = course.description.length > 60 ? course.description.substring(0, 60) + '...' : course.description;

                        const html = `
                            <div class="course-card group text-left">
                                <div class="image-container relative mb-4">
                                    <img src="${courseThumbnail}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300" onerror="this.onerror=null; this.src='/user.png';">
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-1 bg-white border border-slate-200 text-yellow-650 font-bold text-[8px] uppercase tracking-wider rounded-lg shadow-sm">
                                            ${course.statusClass || 'Aktif'}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-5 pb-5 flex flex-col flex-grow">
                                    <h5 class="text-xs font-extrabold text-slate-900 leading-snug line-clamp-1 group-hover:text-yellow-600 transition-colors">${course.name}</h5>
                                    <p class="mt-2 text-slate-500 text-[11px] font-semibold leading-relaxed line-clamp-2">${desc}</p>
                                    <div class="mt-auto pt-4 border-t border-slate-100 mt-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-1.5">
                                                <img src="${authorImage}" class="w-4 h-4 rounded-md border border-slate-100 object-cover" onerror="this.onerror=null; this.src='/user.png';">
                                                <span class="text-[9px] font-bold text-slate-550 truncate max-w-[100px]">${course.teacher}</span>
                                            </div>
                                            <div class="flex items-center gap-1 text-slate-400">
                                                <i data-feather="users" class="w-3 h-3"></i>
                                                <span class="text-[9px] font-extrabold text-slate-700">${course.total_user}</span>
                                            </div>
                                        </div>
                                        <a href="/student/classroom/course/${course.id}" class="w-full py-2 bg-slate-900 hover:bg-yellow-500 hover:text-slate-950 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all active:scale-[0.98]">
                                            Masuk Kelas <i data-feather="arrow-right" class="w-4.5 h-4.5"></i>
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

    function fetchCount(userId) {
        $.ajax({
            url: `/api/count/student/${userId}`,
            method: 'GET',
            success: function(response) {
                $('#count').text(response.count);
            }
        });
    }

    function initWeeklyChart() {
        const ctx = document.getElementById('weeklyActivityChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Waktu Belajar (Menit)',
                    data: [35, 60, 45, 90, 30, 15, 40],
                    backgroundColor: '#eab308', /* tailwind yellow-500 */
                    borderColor: '#d97706',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', size: 9, weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', size: 9, weight: 'bold' } }
                    }
                }
            }
        });
    }

    $(document).ready(function() {
        const userId = {{ auth()->user()->id }};
        loadClassroomData(userId);
        fetchCount(userId);
        initWeeklyChart();
    });
</script>
@endsection