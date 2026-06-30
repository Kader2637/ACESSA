@extends('layouts.student.app')

@section('title', 'Daftar Tugas Saya — Portal Mahasiswa')
@section('page_title', 'Daftar Tugas')

@section('style')
<style>
    .task-row { transition: background-color 0.2s ease; }
    .task-row:hover { background-color: #fffbeb; }
</style>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up">
    {{-- KPI Cards --}}
    <div class="bg-white border border-slate-200 p-5 rounded-2xl flex items-center justify-between shadow-sm">
        <div class="text-left">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Tugas</p>
            <h4 class="text-2xl font-black text-slate-800" id="stat-total">0</h4>
        </div>
        <div class="w-9 h-9 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center border border-yellow-100 shrink-0">
            <i data-feather="book-open" class="w-4 h-4"></i>
        </div>
    </div>
    
    <div class="bg-white border border-slate-200 p-5 rounded-2xl flex items-center justify-between shadow-sm">
        <div class="text-left">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tugas Selesai</p>
            <h4 class="text-2xl font-black text-slate-800" id="stat-done">0</h4>
        </div>
        <div class="w-9 h-9 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center border border-emerald-100 shrink-0">
            <i data-feather="check-circle" class="w-4 h-4"></i>
        </div>
    </div>

    <div class="bg-white border border-slate-200 p-5 rounded-2xl flex items-center justify-between shadow-sm">
        <div class="text-left">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Belum Dikumpul</p>
            <h4 class="text-2xl font-black text-slate-800" id="stat-pending">0</h4>
        </div>
        <div class="w-9 h-9 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center border border-rose-100 shrink-0">
            <i data-feather="clock" class="w-4 h-4"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="50">
    {{-- Tasks List --}}
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider">Daftar Tugas Aktif</h3>
        </div>

        <div id="tasks-loading" class="py-16 flex flex-col items-center justify-center">
            <div class="w-6 h-6 border-2 border-slate-100 border-t-yellow-500 rounded-full animate-spin mb-2"></div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Memuat daftar tugas...</p>
        </div>

        <div id="tasks-empty" class="hidden py-16 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest border border-dashed border-slate-200 rounded-2xl">
            Tidak ada tugas terdaftar saat ini.
        </div>

        <div class="overflow-x-auto hidden" id="tasks-table-container">
            <table class="w-full text-left text-xs font-semibold">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400">
                        <th class="pb-3 font-black uppercase tracking-wider">Tugas / Modul</th>
                        <th class="pb-3 font-black uppercase tracking-wider">Kelas</th>
                        <th class="pb-3 font-black uppercase tracking-wider">Tenggat Waktu</th>
                        <th class="pb-3 font-black uppercase tracking-wider text-center">Status</th>
                        <th class="pb-3 font-black uppercase tracking-wider text-center">Nilai</th>
                    </tr>
                </thead>
                <tbody id="tasks-list"></tbody>
            </table>
        </div>
    </div>

    {{-- Progress Chart --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider mb-4">Grafik Penyelesaian</h3>
            <div class="relative w-full h-[220px] flex items-center justify-center">
                <canvas id="assignmentChart"></canvas>
            </div>
        </div>
        <div class="border-t border-slate-100 pt-4 mt-6">
            <div class="flex justify-between items-center text-xs font-bold text-slate-650">
                <span>Rasio Kepatuhan:</span>
                <span class="text-yellow-600" id="stat-ratio">0%</span>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-2">
                <div id="ratio-progress-bar" class="bg-yellow-500 h-full transition-all duration-500" style="width: 0%"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        const authId = '{{ auth()->user()->id }}';
        let totalCount = 0;
        let doneCount = 0;
        let pendingCount = 0;

        // Init Chart
        const ctx = document.getElementById('assignmentChart').getContext('2d');
        const assignmentChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Belum Selesai'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['#eab308', '#f1f5f9'],
                    borderColor: ['#d97706', '#cbd5e1'],
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 10, weight: 'bold' },
                            color: '#475569'
                        }
                    }
                },
                cutout: '70%'
            }
        });

        // 1. Fetch classroom list
        $.ajax({
            url: `/api/student/classroom/data/${authId}`,
            method: 'GET',
            success: function(res) {
                const relations = res.StudentClassroomRelations || [];
                const classes = relations.map(r => r.course);

                if (classes.length === 0) {
                    $('#tasks-loading').hide();
                    $('#tasks-empty').removeClass('hidden');
                    return;
                }

                let fetched = 0;
                let allTasks = [];

                classes.forEach(c => {
                    if (!c || !c.id) return;
                    $.ajax({
                        url: `/api/task/course/${c.id}`,
                        method: 'GET',
                        success: function(tRes) {
                            const list = (tRes.data || tRes || []).map(t => ({
                                ...t,
                                classroom_name: c.name,
                                classroom_id: c.id
                            }));
                            allTasks = [...allTasks, ...list];
                        },
                        complete: function() {
                            fetched++;
                            if (fetched === classes.length) {
                                processAndRenderTasks(allTasks);
                            }
                        }
                    });
                });
            },
            error: () => {
                $('#tasks-loading').hide();
                $('#tasks-empty').removeClass('hidden');
            }
        });

        function processAndRenderTasks(tasks) {
            $('#tasks-loading').hide();
            if (tasks.length === 0) {
                $('#tasks-empty').removeClass('hidden');
                return;
            }

            $('#tasks-table-container').removeClass('hidden');
            const tbody = $('#tasks-list');
            tbody.empty();

            totalCount = tasks.length;
            let loadedSubmissions = 0;

            tasks.forEach(t => {
                // Fetch submission status for this task
                $.ajax({
                    url: `/api/Apiassigment/${t.id}`,
                    method: 'GET',
                    success: function(sRes) {
                        const subs = sRes.data || [];
                        const mySub = subs.find(s => s.user_id == authId);

                        const isSubmitted = !!mySub;
                        const grade = isSubmitted && mySub.grade !== null ? mySub.grade : '—';
                        
                        if (isSubmitted) {
                            doneCount++;
                        } else {
                            pendingCount++;
                        }

                        let statusBadge = '';
                        if (isSubmitted) {
                            statusBadge = `<span class="px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 font-bold text-[8px] uppercase tracking-wider rounded-lg">Dikumpul</span>`;
                        } else {
                            const now = new Date();
                            const deadline = new Date(t.deadline);
                            if (now > deadline) {
                                statusBadge = `<span class="px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-600 font-bold text-[8px] uppercase tracking-wider rounded-lg">Terlambat</span>`;
                            } else {
                                statusBadge = `<span class="px-2.5 py-1 bg-amber-50 border border-amber-100 text-amber-600 font-bold text-[8px] uppercase tracking-wider rounded-lg font-black animate-pulse">Menunggu</span>`;
                            }
                        }

                        const deadlineStr = new Date(t.deadline).toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
                        }) + ' WIB';

                        tbody.append(`
                            <tr class="task-row border-b border-slate-100 text-slate-700">
                                <td class="py-4 pr-3">
                                    <p class="font-extrabold text-slate-800">${t.name}</p>
                                    <p class="text-[9px] text-slate-400 font-bold mt-0.5 truncate max-w-[150px]">${t.description || 'Tidak ada deskripsi'}</p>
                                </td>
                                <td class="py-4 text-slate-500 font-semibold">${t.classroom_name}</td>
                                <td class="py-4 text-slate-450">${deadlineStr}</td>
                                <td class="py-4 text-center">${statusBadge}</td>
                                <td class="py-4 text-center font-black text-slate-800">${grade}</td>
                            </tr>
                        `);
                    },
                    complete: function() {
                        loadedSubmissions++;
                        if (loadedSubmissions === tasks.length) {
                            // Update KPIs
                            $('#stat-total').text(totalCount);
                            $('#stat-done').text(doneCount);
                            $('#stat-pending').text(pendingCount);

                            // Update ratio
                            const ratio = totalCount > 0 ? Math.round((doneCount / totalCount) * 100) : 0;
                            $('#stat-ratio').text(ratio + '%');
                            $('#ratio-progress-bar').css('width', ratio + '%');

                            // Update Chart
                            assignmentChart.data.datasets[0].data = [doneCount, pendingCount];
                            assignmentChart.update();
                        }
                    }
                });
            });
        }
    });
</script>
@endsection
