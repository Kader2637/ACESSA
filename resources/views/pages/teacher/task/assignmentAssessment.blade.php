@extends('layouts.teacher.app')

@section('title', 'Penilaian Tugas — Teacher Panel')
@section('page_title', 'Penilaian Tugas')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Penilaian <span class="text-indigo-600">Tugas Siswa</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Berikan penilaian &amp; feedback untuk tugas yang dikumpulkan siswa</p>
    </div>
    <div class="flex gap-2">
        <button type="button" onclick="openCreateTaskModal()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all shadow-md">
            + Buat Tugas Baru
        </button>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-center" data-aos="fade-up">
    <div class="flex-1 min-w-48">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input id="search-task" type="text" placeholder="Cari berdasarkan judul atau deskripsi..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>
    </div>
    <select id="filter-task-status" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 cursor-pointer">
        <option value="all">Semua Status</option>
        <option value="needs_grading">Butuh Penilaian</option>
        <option value="graded">Selesai Dinilai</option>
    </select>
    <select id="filter-task-class" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 cursor-pointer">
        <option value="all">Semua Kelas</option>
    </select>
</div>

{{-- Tasks Assessment Table Card --}}
<div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm" data-aos="fade-up" data-aos-delay="50">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h3 class="font-extrabold text-slate-900 text-sm">Daftar Pengumpulan Tugas</h3>
        <span id="results-count" class="text-xs text-slate-400 font-semibold">0 tugas ditemukan</span>
    </div>

    {{-- Loader --}}
    <div id="tasks-loader" class="py-20 flex flex-col items-center gap-3">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest animate-pulse">Memuat tugas...</p>
    </div>

    {{-- Empty State --}}
    <div id="tasks-empty" class="hidden py-16 text-center">
        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Tidak Ada Tugas yang Perlu Dinilai</h4>
        <p class="text-slate-400 text-xs mt-1">Semua tugas siswa telah selesai dinilai atau belum ada tugas yang dikumpulkan.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider hidden" id="table-header">
                <tr>
                    <th class="px-6 py-3.5 w-16 text-center">No</th>
                    <th class="px-6 py-3.5">Judul Tugas</th>
                    <th class="px-6 py-3.5">Kelas</th>
                    <th class="px-6 py-3.5">Siswa</th>
                    <th class="px-6 py-3.5">Tanggal Batas</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right px-8">Tindakan</th>
                </tr>
            </thead>
            <tbody id="tasks-list-body" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
        </table>
    </div>
</div>

{{-- MODAL: Create Task --}}
<div id="createTaskModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCreateTaskModal()"></div>
    <div class="relative bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-8">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6">Buat Tugas Baru</h3>
            <form id="frm-create-task" class="flex flex-col gap-4">
                <input type="hidden" name="type" value="tugas">
                
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Pilih Kelas</label>
                    <select id="task-class-select" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" required>
                        <option value="">Pilih Ruang Kelas</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Pilih Materi / Matakuliah</label>
                    <select name="course_id" id="task-course-select" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" required disabled>
                        <option value="">Pilih Materi</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Judul Tugas</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" placeholder="Masukkan judul tugas..." required>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Deskripsi Tugas</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" placeholder="Masukkan deskripsi tugas..." required></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Tanggal Deadline</label>
                    <input type="date" name="deadline" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none" required>
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeCreateTaskModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs">Simpan Tugas</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    const authId = '{{ auth()->user()->id }}';
    let allTaskAssessments = [];

    $(document).ready(function() {
        // Fetch classrooms first for filters & dropdown selectors
        $.ajax({
            url: `/api/my/classroom/teacher/data/${authId}`,
            method: 'GET',
            success: function(res) {
                const classes = res.data || [];
                const filterSelect = $('#filter-task-class');
                const modalClassSelect = $('#task-class-select');

                classes.forEach(k => {
                    filterSelect.append(`<option value="${k.id}">${k.name}</option>`);
                    modalClassSelect.append(`<option value="${k.id}">${k.name}</option>`);
                });

                // Load tasks
                loadTaskAssessments();
            }
        });

        // Dynamic course loading on classroom select inside createTaskModal
        $('#task-class-select').on('change', function() {
            const classId = $(this).val();
            const courseSelect = $('#task-course-select');
            courseSelect.empty().append('<option value="">Pilih Materi</option>').prop('disabled', true);

            if (!classId) return;

            $.ajax({
                url: `/api/teacher/course/data/${classId}`,
                method: 'GET',
                success: function(res) {
                    const list = res.data || res || [];
                    if (list.length > 0) {
                        courseSelect.prop('disabled', false);
                        list.forEach(c => {
                            courseSelect.append(`<option value="${c.id}">${c.name}</option>`);
                        });
                    }
                }
            });
        });

        // Form submit createTask
        $('#frm-create-task').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: '/api/task/course/post',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success('Berhasil membuat tugas baru!');
                    closeCreateTaskModal();
                    loadTaskAssessments();
                },
                error: () => toastr.error('Gagal membuat tugas.'),
                complete: () => btn.prop('disabled', false).text('Simpan Tugas')
            });
        });
    });

    function openCreateTaskModal() {
        $('#createTaskModal').removeClass('hidden').addClass('flex');
    }
    function closeCreateTaskModal() {
        $('#createTaskModal').removeClass('flex').addClass('hidden');
        $('#frm-create-task')[0].reset();
        $('#task-course-select').empty().append('<option value="">Pilih Materi</option>').prop('disabled', true);
    }

    function loadTaskAssessments() {
        $('#tasks-loader').show();
        $('#table-header').addClass('hidden');
        $('#tasks-empty').addClass('hidden');
        $('#tasks-list-body').empty();

        // Query done assignments as graded, and not assignments as needs grading
        $.ajax({
            url: `/api/done/assigment/task/${authId}`,
            method: 'GET',
            success: function(doneRes) {
                const done = (doneRes.data || []).map(t => ({...t, type_status: 'graded'}));
                
                $.ajax({
                    url: `/api/not/assigment/task/${authId}`,
                    method: 'GET',
                    success: function(notRes) {
                        const notDone = (notRes.data || []).map(t => ({...t, type_status: 'needs_grading'}));
                        allTaskAssessments = [...done, ...notDone];

                        renderTaskAssessments(allTaskAssessments);
                    },
                    error: () => renderTaskAssessments(done)
                });
            },
            error: () => {
                $('#tasks-loader').hide();
                $('#tasks-empty').removeClass('hidden');
            }
        });
    }

    function renderTaskAssessments(tasks) {
        $('#tasks-loader').hide();
        const tbody = $('#tasks-list-body');
        tbody.empty();
        $('#results-count').text(`${tasks.length} tugas ditemukan`);

        if (tasks.length === 0) {
            $('#table-header').addClass('hidden');
            $('#tasks-empty').removeClass('hidden');
            return;
        }

        $('#table-header').removeClass('hidden');
        $('#tasks-empty').addClass('hidden');

        tasks.forEach((t, i) => {
            const statusBadge = t.type_status === 'graded'
                ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg border border-emerald-100">Selesai Dinilai</span>`
                : `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-lg border border-amber-100">Butuh Penilaian</span>`;

            // DB fields mapping: name -> task title, deadline -> date
            const taskTitle = t.title || t.name || 'Tugas';
            const deadlineVal = t.due_date || t.deadline;
            const deadline = deadlineVal ? new Date(deadlineVal).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) : '—';

            tbody.append(`
                <tr class="hover:bg-slate-50/50 transition-colors task-row-item" data-status="${t.type_status}" data-class="${t.classroom_id || t.course_id}" data-search="${(taskTitle).toLowerCase()}">
                    <td class="px-6 py-4 text-center text-slate-400 font-bold">${i + 1}</td>
                    <td class="px-6 py-4">
                        <p class="font-extrabold text-slate-900 text-sm leading-snug">${taskTitle}</p>
                        <p class="text-slate-400 text-xs font-semibold mt-0.5 line-clamp-1">${t.description || '—'}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg font-bold">${t.classroom_name || t.class_name || '—'}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-800 font-bold">${t.student_name || 'Seluruh Siswa'}</td>
                    <td class="px-6 py-4 text-slate-400 font-semibold">${deadline}</td>
                    <td class="px-6 py-4">${statusBadge}</td>
                    <td class="px-6 py-4 text-right px-8">
                        ${t.id ? `
                        <a href="/teacher/detailTask/${t.id}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs rounded-xl transition-all">
                            Beri Nilai
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>` : '<span class="text-slate-300">—</span>'}
                    </td>
                </tr>
            `);
        });
    }

    // Filter Logic
    function applyTaskFilters() {
        const query = $('#search-task').val().toLowerCase();
        const status = $('#filter-task-status').val();
        const classroom = $('#filter-task-class').val();

        $('.task-row-item').each(function() {
            const rowStatus = $(this).data('status');
            const rowClass = $(this).data('class').toString();
            const rowSearch = $(this).data('search');

            const matchSearch = rowSearch.includes(query);
            const matchStatus = status === 'all' || rowStatus === status;
            const matchClass = classroom === 'all' || rowClass === classroom;

            $(this).toggle(matchSearch && matchStatus && matchClass);
        });
    }

    $('#search-task, #filter-task-status, #filter-task-class').on('input change', applyTaskFilters);
</script>
@endsection
