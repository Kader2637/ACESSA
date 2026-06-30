@extends('layouts.admin.app')

@section('title', 'Materi & Tugas — Panel Admin')
@section('page_title', 'Materi &amp; Tugas Global')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm animate-fade-in">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Materi &amp; <span class="text-indigo-650">Tugas Global</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Memantau dan mengevaluasi seluruh penugasan kuliah di platform ACESSA</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Platform Tasks</span>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm" data-aos="fade-up">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-900 text-sm">Semua Daftar Tugas</h3>
        <span id="task-count" class="text-xs text-slate-400 font-semibold">0 tugas</span>
    </div>

    {{-- Loader --}}
    <div id="task-loader" class="py-20 flex flex-col items-center gap-3">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-650 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest animate-pulse">Sinkronisasi data tugas...</p>
    </div>

    {{-- Empty state --}}
    <div id="task-empty" class="hidden py-16 text-center">
        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
            <i data-feather="clipboard" class="w-6 h-6"></i>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Belum Ada Tugas Kuliah</h4>
        <p class="text-slate-400 text-xs mt-1">Belum ada tugas kuliah yang diterbitkan oleh dosen pengajar.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider hidden" id="task-table-header">
                <tr>
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Judul Tugas</th>
                    <th class="px-6 py-4">Materi Terkait</th>
                    <th class="px-6 py-4">Batas Pengumpulan</th>
                    <th class="px-6 py-4 text-right px-8">Tindakan</th>
                </tr>
            </thead>
            <tbody id="task-list-body" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        loadTasks();
    });

    function loadTasks() {
        $('#task-loader').show();
        $('#task-table-header').addClass('hidden');
        $('#task-empty').addClass('hidden');
        $('#task-list-body').empty();

        $.ajax({
            url: '/api/admin/tasks/data',
            method: 'GET',
            success: function(res) {
                $('#task-loader').hide();
                const list = res.data || [];
                $('#task-count').text(`${list.length} tugas`);

                if (list.length === 0) {
                    $('#task-empty').removeClass('hidden');
                    feather.replace();
                    return;
                }

                $('#task-table-header').removeClass('hidden');
                
                list.forEach((t, index) => {
                    const deadlineDate = t.deadline ? new Date(t.deadline).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'}) + ' WIB' : '—';

                    $('#task-list-body').append(`
                        <tr id="task-row-${t.id}">
                            <td class="px-6 py-4 text-center text-slate-400 font-bold">${index + 1}</td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900 text-xs">${t.name}</div>
                                <div class="text-slate-400 text-[10px] font-semibold mt-0.5 max-w-[320px] truncate">${t.description || 'Tidak ada instruksi detail.'}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-indigo-50 border border-indigo-100 text-indigo-650 rounded-lg font-bold">${t.course_name}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-550 font-semibold">${deadlineDate}</td>
                            <td class="px-6 py-4 text-right px-8">
                                <a href="/admin/detailTask/${t.id}" class="px-3.5 py-1.5 bg-slate-900 hover:bg-indigo-650 text-white rounded-lg text-[10px] font-bold transition-all inline-block shadow-sm">
                                    Inspeksi Tugas
                                </a>
                            </td>
                        </tr>
                    `);
                });
                feather.replace();
            },
            error: () => {
                $('#task-loader').hide();
                $('#task-empty').removeClass('hidden');
                feather.replace();
            }
        });
    }
</script>
@endsection
