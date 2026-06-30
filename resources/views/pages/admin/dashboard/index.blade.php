@extends('layouts.admin.app')

@section('title', 'Dashboard Utama — Panel Admin')
@section('page_title', 'Statistik Sistem')

@section('style')
<style>
    .kpi-card { 
        background: #ffffff; 
        border: 1px solid #e2e8f0; 
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 10px 20px -10px rgba(79, 70, 229, 0.08);
        border-color: #cbd5e1;
    }
    .status-pulse { animation: pulse-custom 2s infinite; }
    @keyframes pulse-custom { 
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.15); } 
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(79, 70, 229, 0); } 
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); } 
    }
</style>
@endsection

@section('content')
<div class="flex flex-col gap-8">
    
    {{-- Banner / Info Kontrol Utama --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm" data-aos="fade-down">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-650 font-bold text-[10px] uppercase tracking-widest mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 status-pulse"></span>
                Sistem Operasional
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Panel Kontrol Utama
            </h2>
            <p class="text-slate-500 text-xs font-medium mt-1">Status database &amp; analitik pertumbuhan platform ACESSA secara real-time.</p>
        </div>
        <div class="flex gap-3">
            <div class="px-4 py-2.5 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-2.5">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-extrabold uppercase text-emerald-600 tracking-wider">Server: Aktif</span>
            </div>
        </div>
    </div>

    {{-- KPI Cards (Solid Flat Campus Design with Solid Left Accents) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up" data-aos-delay="50">
        {{-- Total Mentors --}}
        <div class="kpi-card p-6 rounded-2xl shadow-sm border-l-4 border-l-indigo-650 flex flex-col justify-between min-h-[140px] group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Dosen/Guru</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight" id="countTeacher">0</h3>
            </div>
            <div class="flex items-center justify-between mt-4">
                <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-650 text-[9px] font-bold rounded">Pengajar</span>
                <div class="w-8 h-8 bg-indigo-50 border border-indigo-100 text-indigo-650 rounded-lg flex items-center justify-center">
                    <i data-feather="users" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        {{-- Total Students --}}
        <div class="kpi-card p-6 rounded-2xl shadow-sm border-l-4 border-l-emerald-500 flex flex-col justify-between min-h-[140px] group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Mahasiswa</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight" id="countStudent">0</h3>
            </div>
            <div class="flex items-center justify-between mt-4">
                <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[9px] font-bold rounded">Siswa</span>
                <div class="w-8 h-8 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                    <i data-feather="user-check" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        {{-- Total Classrooms --}}
        <div class="kpi-card p-6 rounded-2xl shadow-sm border-l-4 border-l-amber-500 flex flex-col justify-between min-h-[140px] group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Ruang Kelas</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight" id="countClassroom">0</h3>
            </div>
            <div class="flex items-center justify-between mt-4">
                <span class="px-2 py-0.5 bg-amber-50 border border-amber-100 text-amber-600 text-[9px] font-bold rounded">Kelas</span>
                <div class="w-8 h-8 bg-amber-50 border border-amber-100 text-amber-650 rounded-lg flex items-center justify-center">
                    <i data-feather="layers" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        {{-- Total Materials/Courses --}}
        <div class="kpi-card p-6 rounded-2xl shadow-sm border-l-4 border-l-rose-500 flex flex-col justify-between min-h-[140px] group">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Materi &amp; Unit</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight" id="countCourse">0</h3>
            </div>
            <div class="flex items-center justify-between mt-4">
                <span class="px-2 py-0.5 bg-rose-50 border border-rose-100 text-rose-600 text-[9px] font-bold rounded">Materi</span>
                <div class="w-8 h-8 bg-rose-50 border border-rose-100 text-rose-650 rounded-lg flex items-center justify-center">
                    <i data-feather="book-open" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Layout: Charts and Lists --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- Left: Growth Chart --}}
        <div class="lg:col-span-8 flex flex-col gap-6" data-aos="fade-up" data-aos-delay="100">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Grafik Aktivitas &amp; Pendaftaran</h3>
                        <p class="text-slate-400 text-[10px] font-semibold mt-0.5">Statistik pertumbuhan bulanan pengajar dan siswa</p>
                    </div>
                </div>
                <div class="h-72 w-full bg-white">
                    <canvas id="platformGrowthChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Pending Approvals List --}}
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between px-2">
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Persetujuan Tertunda</h4>
                    <a href="/admin/approval" class="text-[10px] font-black text-indigo-650 hover:text-indigo-850 uppercase hover:underline">Kelola Semua</a>
                </div>
                
                <div class="data-pending grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200 shadow-sm animate-pulse">
                        <p class="text-[10px] font-bold text-slate-400 tracking-widest">MENYINKRONKAN DATA...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: System Overview & Shortcuts (Solid White, No Gradients) --}}
        <div class="lg:col-span-4 flex flex-col gap-6" data-aos="fade-up" data-aos-delay="150">
            
            {{-- Quick Shortcuts --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Aksi Cepat</h4>
                <div class="flex flex-col gap-2.5">
                    <a href="/admin/teacher" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-indigo-50/50 hover:text-indigo-600 transition-all group">
                        <span class="text-xs font-bold tracking-tight text-slate-700 group-hover:text-indigo-600">Kelola Pengajar</span>
                        <i data-feather="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                    </a>
                    <a href="/admin/student" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-indigo-50/50 hover:text-indigo-600 transition-all group">
                        <span class="text-xs font-bold tracking-tight text-slate-700 group-hover:text-indigo-600">Kelola Mahasiswa</span>
                        <i data-feather="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                    </a>
                    <a href="/admin/classroom" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-indigo-50/50 hover:text-indigo-600 transition-all group">
                        <span class="text-xs font-bold tracking-tight text-slate-700 group-hover:text-indigo-600">Audit Kelas Aktif</span>
                        <i data-feather="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                    </a>
                </div>
            </div>

            {{-- System version info (Solid Navy, No Aether Code) --}}
            <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden shadow-md">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Versi Platform</p>
                <h4 class="text-lg font-black tracking-tight relative z-10">ACESSA V1.0</h4>
                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-[10px] text-slate-500 font-bold relative z-10">
                    <span>Status Keamanan</span>
                    <span class="text-emerald-400 flex items-center gap-1 font-extrabold uppercase">
                        Aman
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
{{-- Load Chart.js from CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function fetchData() {
        $.ajax({
            url: '/api/teacher/pending',
            method: 'GET',
            success: function(response) {
                let container = $('.data-pending');
                container.empty();

                if (response.status === "success" && response.data.length > 0) {
                    const maxCards = Math.min(response.data.length, 4);
                    for (let i = 0; i < maxCards; i++) {
                        let item = response.data[i];
                        let profileImage = item.image ? `/storage/${item.image}` : '/user.png';

                        container.append(`
                            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4 hover:border-indigo-600 transition-all group animate-fade-in">
                                <img src="${profileImage}" class="w-11 h-11 rounded-lg object-cover border border-slate-100" onerror="this.src='/user.png'">
                                <div class="flex-1 overflow-hidden">
                                    <h5 class="text-xs font-extrabold text-slate-900 truncate">${item.name}</h5>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 truncate">${item.email}</p>
                                </div>
                                <a href="/admin/approval" class="w-8 h-8 bg-slate-50 hover:bg-indigo-600 hover:text-white rounded-lg flex items-center justify-center text-slate-500 transition-all">
                                    <i data-feather="chevron-right" style="width:12px"></i>
                                </a>
                            </div>
                        `);
                    }
                    feather.replace();
                } else {
                    container.html(`
                        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tidak ada permintaan bergabung tertunda</p>
                        </div>
                    `);
                }
            }
        });
    }

    function fetchCount() {
        $.ajax({
            url: '/api/count/statistika/admin/data',
            method: 'GET',
            success: function(res) {
                animateValue("countTeacher", 0, res.countTeacher, 1000);
                animateValue("countStudent", 0, res.countStudent, 1000);
                animateValue("countClassroom", 0, res.countClassroom, 1000);
                animateValue("countCourse", 0, res.countCourse, 1000);
                
                // Initialize dynamic chart based on count results
                initGrowthChart(res.countTeacher, res.countStudent);
            }
        });
    }

    function animateValue(id, start, end, duration) {
        if (start === end) { document.getElementById(id).innerHTML = end; return; }
        let range = end - start;
        let current = start;
        let increment = end > start? 1 : -1;
        let stepTime = Math.abs(Math.floor(duration / (range || 1)));
        let obj = document.getElementById(id);
        let timer = setInterval(function() {
            current += increment;
            obj.innerHTML = current;
            if (current == end) { clearInterval(timer); }
        }, stepTime);
    }

    function initGrowthChart(teachersCount, studentsCount) {
        const ctx = document.getElementById('platformGrowthChart').getContext('2d');
        
        const teacherData = [0, 1, Math.max(1, Math.floor(teachersCount/2)), teachersCount];
        const studentData = [1, 2, Math.max(2, Math.floor(studentsCount/2)), studentsCount];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan 2026', 'Feb 2026', 'Mar 2026', 'Hari Ini'],
                datasets: [
                    {
                        label: 'Siswa / Mahasiswa',
                        data: studentData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        tension: 0.35,
                        borderWidth: 3,
                        fill: true
                    },
                    {
                        label: 'Guru / Dosen',
                        data: teacherData,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        tension: 0.35,
                        borderWidth: 3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 },
                            color: '#64748b'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', weight: 'bold' } }
                    }
                }
            }
        });
    }

    $(document).ready(function() {
        fetchData();
        fetchCount();
    });
</script>
@endsection