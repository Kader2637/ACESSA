@extends('layouts.teacher.app')

@section('title', 'Riwayat Tugas — Teacher Panel')
@section('page_title', 'Riwayat Tugas')

@section('style')
<style>
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 700;
    }
    .badge-done   { background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.2); }
    .badge-late   { background: rgba(239,68,68,0.1);  color: #dc2626; border: 1px solid rgba(239,68,68,0.2); }
    .badge-pending { background: rgba(245,158,11,0.1); color: #d97706; border: 1px solid rgba(245,158,11,0.2); }
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4" data-aos="fade-up">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat <span class="text-indigo-600">Tugas</span></h1>
        <p class="text-slate-400 text-sm font-medium mt-1">Rekap semua tugas yang pernah diberikan ke siswa</p>
    </div>
    <a href="{{ route('task.assignmentAssessment') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-indigo-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Tugas Baru
    </a>
</div>

{{-- Filter Bar --}}
<div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-center" data-aos="fade-up" data-aos-delay="50">
    <div class="flex-1 min-w-48">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input id="searchInput" type="text" placeholder="Cari judul tugas..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-300 transition-all">
        </div>
    </div>
    <select id="filterStatus" class="px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white cursor-pointer">
        <option value="">Semua Status</option>
        <option value="done">Selesai</option>
        <option value="late">Terlambat</option>
        <option value="pending">Belum Dikumpul</option>
    </select>
    <select id="filterClass" class="px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white cursor-pointer">
        <option value="">Semua Kelas</option>
    </select>
</div>

{{-- Stats Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" data-aos="fade-up" data-aos-delay="100">
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Tugas</p>
        <p class="text-3xl font-extrabold text-slate-900" id="totalTask">—</p>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-500 mb-2">Sudah Dikumpul</p>
        <p class="text-3xl font-extrabold text-emerald-600" id="totalDone">—</p>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-red-500 mb-2">Terlambat</p>
        <p class="text-3xl font-extrabold text-red-500" id="totalLate">—</p>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-amber-500 mb-2">Belum Dikumpul</p>
        <p class="text-3xl font-extrabold text-amber-500" id="totalPending">—</p>
    </div>
</div>

{{-- Task Table --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="150">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h3 class="font-extrabold text-slate-900 text-sm">Daftar Tugas</h3>
        <span class="text-xs text-slate-400 font-medium" id="resultCount">Memuat...</span>
    </div>
    
    <div id="loadingState" class="py-20 flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Memuat riwayat tugas...</p>
    </div>

    <div id="emptyState" class="hidden py-20 text-center">
        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <p class="text-slate-700 font-extrabold">Belum Ada Riwayat Tugas</p>
        <p class="text-slate-400 text-sm mt-1">Buat tugas pertama untuk kelas Anda</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="taskTable">
            <thead class="bg-slate-50 border-b border-slate-100 hidden" id="tableHead">
                <tr>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">#</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Judul Tugas</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Kelas</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Deadline</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Dikumpul</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Aksi</th>
                </tr>
            </thead>
            <tbody id="taskBody" class="divide-y divide-slate-50"></tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    const authId = '{{ auth()->user()->id }}';
    let allTasks = [];

    // Fetch all task history from assignment assessment endpoint
    $.ajax({
        url: `/api/done/assigment/task/${authId}`,
        method: 'GET',
        success: function(res) {
            const done = res.data || [];
            $.ajax({
                url: `/api/not/assigment/task/${authId}`,
                method: 'GET',
                success: function(res2) {
                    const pending = res2.data || [];
                    allTasks = [
                        ...done.map(t => ({...t, status: 'done'})),
                        ...pending.map(t => ({...t, status: 'pending'}))
                    ];
                    renderTasks(allTasks);
                    updateStats(allTasks);
                    hideLoading();
                },
                error: () => {
                    allTasks = done.map(t => ({...t, status: 'done'}));
                    renderTasks(allTasks);
                    updateStats(allTasks);
                    hideLoading();
                }
            });
        },
        error: function() {
            hideLoading();
            $('#emptyState').removeClass('hidden');
        }
    });

    function hideLoading() {
        $('#loadingState').hide();
        if (allTasks.length === 0) {
            $('#emptyState').removeClass('hidden');
        } else {
            $('#tableHead').removeClass('hidden');
        }
    }

    function renderTasks(tasks) {
        const tbody = $('#taskBody');
        tbody.empty();
        $('#resultCount').text(`${tasks.length} tugas ditemukan`);

        if (tasks.length === 0) {
            $('#tableHead').addClass('hidden');
            $('#emptyState').removeClass('hidden');
            return;
        }

        $('#tableHead').removeClass('hidden');
        $('#emptyState').addClass('hidden');

        tasks.forEach((t, i) => {
            const statusBadge = t.status === 'done'
                ? `<span class="status-badge badge-done"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Selesai</span>`
                : `<span class="status-badge badge-pending"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Belum Dikumpul</span>`;

            const deadline = t.due_date ? new Date(t.due_date).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'}) : '—';

            tbody.append(`
                <tr class="hover:bg-slate-50/50 transition-colors task-row" data-status="${t.status}" data-name="${(t.title||t.name||'').toLowerCase()}">
                    <td class="px-6 py-4 text-slate-400 font-bold text-xs">${i+1}</td>
                    <td class="px-6 py-4">
                        <p class="font-extrabold text-slate-900 text-sm leading-tight">${t.title || t.name || 'Tugas'}</p>
                        <p class="text-slate-400 text-xs mt-0.5 line-clamp-1">${t.description || '—'}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-700 text-xs px-2.5 py-1 bg-slate-100 rounded-lg">${t.classroom_name || t.class_name || '—'}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 text-xs font-semibold">${deadline}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-slate-900">${t.student_count || '?'}</span>
                            <span class="text-slate-400 text-xs font-medium">siswa</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">${statusBadge}</td>
                    <td class="px-6 py-4">
                        ${t.id ? `<a href="/teacher/detailTask/${t.id}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs rounded-xl transition-all">
                            Detail
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>` : '<span class="text-slate-300 text-xs">—</span>'}
                    </td>
                </tr>
            `);
        });
    }

    function updateStats(tasks) {
        const done = tasks.filter(t => t.status === 'done').length;
        const pending = tasks.filter(t => t.status === 'pending').length;
        const late = tasks.filter(t => t.status === 'late').length;
        $('#totalTask').text(tasks.length);
        $('#totalDone').text(done);
        $('#totalLate').text(late);
        $('#totalPending').text(pending);
    }

    // Search + Filter
    function applyFilter() {
        const q = $('#searchInput').val().toLowerCase();
        const statusF = $('#filterStatus').val();
        const filtered = allTasks.filter(t => {
            const nameMatch = (t.title||t.name||'').toLowerCase().includes(q);
            const statusMatch = !statusF || t.status === statusF;
            return nameMatch && statusMatch;
        });
        renderTasks(filtered);
    }
    $('#searchInput, #filterStatus, #filterClass').on('input change', applyFilter);
});
</script>
@endsection
