@extends('layouts.teacher.app')

@section('page_title', 'Manajemen Unit Materi')

@section('style')
<style>
    .nav-tab-active { border-bottom: 3px solid #4f46e5; color: #4f46e5; font-weight: 800; }
    .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 2.5rem; background: #000; shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
    #pdf-canvas { max-width: 100%; height: auto; border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .glass-control { background: rgba(15, 23, 42, 0.8); backdrop-blur: 16px; border: 1px solid rgba(255, 255, 255, 0.1); }
    .animate-zoom-in { transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out; }
    .modal-open .animate-zoom-in { transform: scale(1); opacity: 1; }
    .custom-input { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.25rem; padding: 0.85rem 1.25rem; font-weight: 600; transition: all 0.2s; width: 100%; color: #0f172a; }
    .custom-input:focus { outline: none; border-color: #4f46e5; background: white; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
</style>
@endsection

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-4 text-left">
        <a id="back-button" href="#" class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-all shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] leading-none mb-1">Preview & Assignment</p>
            <h4 class="text-2xl font-black text-slate-900 tracking-tight" id="class-name1">Memuat Unit...</h4>
        </div>
    </div>
</div>

<div class="mb-10 flex border-b border-slate-200 gap-10">
    <button onclick="switchTab('materi')" id="btn-materi" class="pb-4 text-sm font-bold text-slate-400 nav-tab-active transition-all">Konten Materi</button>
    <button onclick="switchTab('zoom')" id="btn-zoom" class="pb-4 text-sm font-bold text-slate-400 transition-all">Pertemuan Zoom</button>
    <button onclick="switchTab('tugas')" id="btn-tugas" class="pb-4 text-sm font-bold text-slate-400 transition-all">Manajemen Tugas</button>
</div>

<div id="content-materi" class="tab-pane block animate-fade-in">
    <div id="link" class="hidden mb-12"></div>
    
    <div id="document" class="hidden relative w-full mb-12 group min-h-[600px]">
        <div class="flex justify-center bg-slate-100 p-6 md:p-12 rounded-[3rem] border border-slate-200 overflow-x-auto shadow-inner">
            <canvas id="pdf-canvas"></canvas>
        </div>
        
        <div id="pdf-controls" class="absolute right-8 top-1/2 -translate-y-1/2 flex flex-col items-center gap-4 z-50 opacity-0 group-hover:opacity-100 transition-all duration-500 translate-x-4 group-hover:translate-x-0">
            <button id="prev" class="w-14 h-14 glass-control text-white rounded-2xl flex items-center justify-center shadow-2xl hover:bg-indigo-600 transition-all active:scale-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <div class="py-5 px-3 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-100 flex flex-col items-center gap-1 min-w-[60px]">
                <span id="current_page" class="text-sm font-black text-indigo-600">1</span>
                <div class="w-4 h-[1px] bg-slate-200"></div>
                <span id="total_pages" class="text-[10px] font-bold text-slate-400">0</span>
            </div>
            <button id="next" class="w-14 h-14 glass-control text-white rounded-2xl flex items-center justify-center shadow-2xl hover:bg-indigo-600 transition-all active:scale-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    <div id="text" class="bg-white p-10 md:p-16 rounded-[3rem] border border-slate-100 shadow-sm prose prose-indigo max-w-none prose-headings:font-black"></div>
</div>

<div id="content-zoom" class="tab-pane hidden animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Riwayat & Jadwal Pertemuan Zoom</h3>
            <p class="text-slate-400 text-xs font-medium">Buat, kelola, dan mulai pertemuan Zoom virtual untuk materi ini.</p>
        </div>
        <button onclick="openModal('add-zoom')" class="inline-flex items-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pertemuan Zoom
        </button>
    </div>

    <div id="zoom-loading-message" class="py-20 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest animate-pulse">Memuat jadwal pertemuan...</div>
    <div id="zoom-meetings-container" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
</div>

<!-- Modal Tambah Pertemuan Zoom -->
<div id="modal-add-zoom" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('add-zoom')"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in p-10 md:p-14">
        <h3 class="text-2xl font-black text-slate-900 mb-8">Tambah Pertemuan Zoom Baru</h3>
        <form id="addZoomForm" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
            <input type="hidden" name="course_id" value="{{ $id }}">
            <div class="col-span-full">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Judul Pertemuan</label>
                <input type="text" name="title" class="custom-input" placeholder="E.g. Pertemuan 1 - Pengenalan Framework" required>
            </div>
            <div class="col-span-full">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Deskripsi Pertemuan (Opsional)</label>
                <textarea name="description" rows="3" class="custom-input font-medium" placeholder="E.g. Menjelaskan materi dasar dan sesi tanya jawab"></textarea>
            </div>
            <div class="col-span-full">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Waktu Pertemuan</label>
                <input type="datetime-local" name="meeting_time" class="custom-input font-bold" required>
            </div>
            <div class="col-span-full">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Tautan Rapat Zoom Manual (Opsional)</label>
                <input type="url" name="zoom_link" class="custom-input font-medium" placeholder="E.g. https://us02web.zoom.us/j/1234567890?pwd=xxxx">
                <span class="text-[9px] text-slate-400 font-medium block mt-1.5 ml-1 leading-normal">Kosongkan jika kredensial `.env` (`ZOOM_ACCOUNT_ID`) Anda sudah lengkap untuk membuat link otomatis.</span>
            </div>
            <div class="col-span-full">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Passcode Rapat Manual (Wajib jika link manual diisi)</label>
                <input type="text" name="manual_passcode" class="custom-input font-medium" placeholder="E.g. 123456">
                <span class="text-[9px] text-slate-400 font-medium block mt-1.5 ml-1 leading-normal">Masukkan sandi rapat asli (angka/huruf pendek) agar SDK Zoom tidak mengalami error sandi salah.</span>
            </div>
            <div class="col-span-full flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeModal('add-zoom')" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl uppercase text-[10px]">Batal</button>
                <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-black rounded-2xl uppercase text-[10px] tracking-widest hover:bg-indigo-600">Simpan Pertemuan</button>
            </div>
        </form>
    </div>
</div>

<div id="content-tugas" class="tab-pane hidden animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Penugasan</h3>
            <p class="text-slate-400 text-xs font-medium">Buat dan kelola instruksi tugas untuk siswa di unit ini.</p>
        </div>
        <button onclick="openModal('add-task')" id="createTask" class="inline-flex items-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Tugas
        </button>
    </div>

    <div id="loading-message" class="py-20 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest animate-pulse">Menghubungkan ke server...</div>
    <div id="tasks-container" class="grid grid-cols-1 gap-6"></div>
</div>

<div id="modal-add-task" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('add-task')"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in p-10 md:p-14">
        <h3 class="text-2xl font-black text-slate-900 mb-8">Buat Tugas Baru</h3>
        <form id="addTaskForm" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
            <input type="hidden" name="course_id" value="{{ $id }}">
            <div class="col-span-full">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Judul Tugas</label>
                <input type="text" name="name" class="custom-input" placeholder="E.g. Final Project Frontend" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Tenggat Waktu</label>
                <input type="datetime-local" name="deadline" class="custom-input font-bold" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Tipe Pengumpulan</label>
                <select name="type" class="custom-input appearance-none font-bold" required>
                    <option value="file">📁 Berkas (ZIP/PDF)</option>
                    <option value="link">🔗 Tautan (URL)</option>
                </select>
            </div>
            <div class="col-span-full">
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Instruksi Tugas</label>
                <textarea name="description" rows="3" class="custom-input font-medium"></textarea>
            </div>
            <div class="col-span-full flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeModal('add-task')" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl uppercase text-[10px]">Batal</button>
                <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-black rounded-2xl uppercase text-[10px] tracking-widest hover:bg-indigo-600">Terbitkan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-update-task" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('update-task')"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in p-10 md:p-14 text-left">
        <h3 class="text-2xl font-black text-slate-900 mb-8">Update Penugasan</h3>
        <form id="updateTaskForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @method('PUT')
            <input type="hidden" name="course_id" value="{{ $id }}">
            <input type="hidden" id="update-task-id" name="task_id">
            <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Judul</label><input type="text" id="update-task-title" name="name" class="custom-input font-bold" required></div>
            <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 text-red-500">Deadline</label><input type="datetime-local" id="update-task-deadline" name="deadline" class="custom-input font-bold text-red-500" required></div>
            <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Tipe</label><select id="update-task-type" name="type" class="custom-input font-bold" required><option value="file">File</option><option value="link">Link</option></select></div>
            <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Instruksi</label><textarea id="update-task-description" name="description" rows="3" class="custom-input font-medium"></textarea></div>
            <div class="col-span-full flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeModal('update-task')" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl uppercase text-[10px]">Batal</button>
                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white font-black rounded-2xl uppercase text-[10px] tracking-widest">Update Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-delete" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 text-left">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete')"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl animate-zoom-in p-10 text-center">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <h3 class="text-xl font-black text-slate-900 mb-2 tracking-tight">Hapus Tugas?</h3>
        <p class="text-slate-500 font-medium mb-10 text-sm">Aksi ini akan menghapus tugas dan semua pengumpulan siswa di dalamnya secara permanen.</p>
        <form id="form-delete-manual">
            <input type="hidden" id="deleteClassId">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('delete')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-black uppercase text-[10px] tracking-widest rounded-2xl">Batal</button>
                <button type="submit" class="flex-1 py-4 bg-red-500 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-red-600 shadow-lg shadow-red-200">Ya, Hapus!</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    const courseId = {{ $id }};
    const loadingMessage = document.getElementById('loading-message');
    const tasksContainer = $('#tasks-container');

    // FUNGSI GLOBAL MODAL
    function openModal(type) {
        $(`#modal-${type}`).removeClass('hidden').addClass('flex');
        setTimeout(() => $(`#modal-${type}`).addClass('modal-open'), 10);
    }
    function closeModal(type) {
        $(`#modal-${type}`).removeClass('modal-open');
        setTimeout(() => $(`#modal-${type}`).removeClass('flex').addClass('hidden'), 200);
    }

    // FUNGSI TABS
    function switchTab(tab) {
        $('.tab-pane').addClass('hidden');
        $(`#content-${tab}`).removeClass('hidden');
        $('[id^="btn-"]').removeClass('nav-tab-active text-indigo-600').addClass('text-slate-400');
        $(`#btn-${tab}`).addClass('nav-tab-active text-indigo-600').removeClass('text-slate-400');
        
        if (tab === 'zoom') {
            fetchZoomMeetings();
        }
    }

    // LOAD DAFTAR TUGAS
    function fetchTasks() {
        loadingMessage.style.display = 'block';
        tasksContainer.empty();
        $.ajax({
            url: `/api/task/course/${courseId}`,
            method: 'GET',
            success: function(res) {
                loadingMessage.style.display = 'none';
                if (res.status === 'success' && res.data.length > 0) {
                    $('#createTask').addClass('hidden'); // Sembunyikan tombol tambah jika sudah ada tugas
                    res.data.forEach(task => {
                        tasksContainer.append(`
                        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group animate-fade-in">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-slate-50 text-2xl flex items-center justify-center rounded-3xl group-hover:bg-indigo-50 transition-colors">${task.type === 'file' ? '📁' : '🔗'}</div>
                                <div>
                                    <h5 class="text-xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">${task.name}</h5>
                                    <p class="text-xs font-bold text-red-500 mt-1 uppercase tracking-widest">Deadline: ${task.deadline_format}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="/teacher/detailTask/${task.id}" class="px-6 py-3 bg-slate-900 text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-indigo-600 transition-all shadow-lg">Pantau Siswa</a>
                                <button onclick="prepareEditTask('${task.id}', '${task.name}', '${task.description || ''}', '${task.deadline}', '${task.type}')" class="p-3 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <button onclick="prepareDeleteTask('${task.id}')" class="p-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        </div>`);
                    });
                } else {
                    $('#createTask').removeClass('hidden');
                    tasksContainer.html(`<div class="py-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200"><h4 class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Belum ada tugas dibuat</h4></div>`);
                }
            }
        });
    }

    // PERSIPAPAN AKSI TUGAS
    function prepareEditTask(id, name, desc, deadline, type) {
        $('#update-task-id').val(id); $('#update-task-title').val(name); $('#update-task-deadline').val(deadline);
        $('#update-task-type').val(type); $('#update-task-description').val(desc); openModal('update-task');
    }
    function prepareDeleteTask(id) { $('#deleteClassId').val(id); openModal('delete'); }

    // AJAX SUBMISSIONS
    $('#addTaskForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/task/course/post', method: 'POST', data: new FormData(this), processData: false, contentType: false,
            success: function() { toastr.success('Tugas diterbitkan!'); closeModal('add-task'); fetchTasks(); }
        });
    });

    $('#updateTaskForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/task/course/update/${$('#update-task-id').val()}`, method: 'POST', data: new FormData(this), processData: false, contentType: false,
            success: function() { toastr.success('Tugas diperbarui!'); closeModal('update-task'); fetchTasks(); }
        });
    });

    $('#form-delete-manual').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/task/course/delete/${$('#deleteClassId').val()}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
            success: function() { toastr.success('Tugas dihapus!'); closeModal('delete'); fetchTasks(); }
        });
    });

    // LOAD DATA MATERI (VIDEO/PDF/TEXT)
    $(document).ready(function() {
        fetchTasks();
        fetch(`/api/teacher/course/show/${courseId}`).then(r => r.json()).then(res => {
            if (res.success) {
                const c = res.data;
                $('#class-name1').text(c.name);
                $('#back-button').attr('href', `/teacher/classroom/course/${c.classroom_id}`);

                if (c.type === 'link' && c.link) {
                    const isYoutube = c.link.includes('youtube.com') || c.link.includes('youtu.be');
                    const isZoom = c.link.includes('zoom.us') || c.link.includes('zoom.com');
                    
                    if (isZoom) {
                        try {
                            const urlObj = new URL(c.link);
                            const pathParts = urlObj.pathname.split('/');
                            const jIndex = pathParts.indexOf('j');
                            if (jIndex !== -1 && pathParts[jIndex + 1]) {
                                meetingNumber = pathParts[jIndex + 1];
                            } else {
                                meetingNumber = c.link.match(/\/j\/(\d+)/)[1];
                            }
                            passcode = urlObj.searchParams.get('pwd') || '';
                        } catch (e) {
                            console.error('Failed to parse Zoom URL', e);
                        }
                        
                        $('#link').removeClass('hidden').html(`
                            <div id="zoom-meeting-container" class="relative bg-slate-950 rounded-[3rem] p-8 text-white min-h-[450px] flex flex-col items-center justify-center overflow-hidden shadow-2xl border border-slate-900">
                                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(79,70,229,0.1),transparent)] pointer-events-none"></div>
                                
                                <div id="zoom-lobby" class="z-10 text-center flex flex-col items-center gap-6 max-w-md">
                                    <div class="w-20 h-20 bg-indigo-500/10 rounded-[2rem] flex items-center justify-center text-4xl shadow-inner animate-pulse">👨‍🏫</div>
                                    <div>
                                        <h3 class="text-2xl font-black tracking-tight mb-2 uppercase">Mulai Kelas Zoom (Host)</h3>
                                        <p class="text-slate-400 text-sm font-medium leading-relaxed">Mulai kelas video konferensi langsung dari web. Anda bertindak sebagai Host.</p>
                                    </div>
                                    <button id="btn-join-zoom" class="px-8 py-4.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-2xl shadow-xl shadow-indigo-600/30 transition-all active:scale-95">
                                        Mulai Kelas Rapat (Host)
                                    </button>
                                </div>
                                
                                <div id="meetingSDKElement" class="hidden w-full h-[650px] rounded-[2rem] overflow-hidden bg-slate-900 border border-slate-800 z-10"></div>
                            </div>
                            
                            <!-- Disability Access Panel for Teacher -->
                            <div class="mt-6 bg-slate-900 border border-slate-850 rounded-[2.5rem] p-6 shadow-xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl"></div>
                                <div class="relative z-10">
                                    <div class="flex flex-wrap items-center justify-between gap-4 mb-5 pb-5 border-b border-slate-800/60">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 bg-indigo-500 rounded-full animate-ping" id="caption-status-dot"></span>
                                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-[0.2em]">Pencatat Caption Guru</span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-4 text-white">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] font-black text-slate-500 uppercase">Bahasa Anda:</span>
                                                <select id="spoken-lang" class="bg-slate-800 border border-slate-700 text-xs rounded-xl px-3 py-1.5 font-bold focus:outline-none cursor-pointer">
                                                    <option value="id-ID">Bahasa Indonesia</option>
                                                    <option value="en-US">English</option>
                                                    <option value="ja-JP">日本語</option>
                                                </select>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] font-black text-slate-500 uppercase">Preview Ukuran:</span>
                                                <select id="font-size" class="bg-slate-800 border border-slate-700 text-xs rounded-xl px-3 py-1.5 font-bold focus:outline-none cursor-pointer">
                                                    <option value="text-lg">Normal</option>
                                                    <option value="text-2xl" selected>Besar</option>
                                                    <option value="text-3xl">Sangat Besar</option>
                                                    <option value="text-4xl">Maksimal (Disabilitas)</option>
                                                </select>
                                            </div>
                                            <button id="btn-toggle-mic" class="px-5 py-2.5 bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600/30 font-black text-[9px] uppercase tracking-wider rounded-xl transition-all active:scale-95">
                                                Aktifkan Mic Caption (Mulai Bicara)
                                            </button>
                                        </div>
                                    </div>
                                    <div id="subtitles-display-box" class="min-h-[120px] max-h-[220px] overflow-y-auto bg-black/50 border border-slate-800 rounded-2xl p-5 flex flex-col justify-end text-center">
                                        <p id="active-subtitle-text" class="text-yellow-400 font-extrabold text-2xl tracking-wide leading-relaxed drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">
                                            Tekan tombol di atas lalu bicaralah untuk mentranskrip suara ke siswa disabilitas secara real-time.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `);
                    } else if (isYoutube) {
                        const videoId = new URL(c.link).searchParams.get('v') || c.link.split('/').pop();
                        $('#link').removeClass('hidden').html(`<div class="video-container shadow-2xl shadow-indigo-100"><iframe src="https://www.youtube.com/embed/${videoId}" allowfullscreen></iframe></div>`);
                    } else {
                        $('#link').removeClass('hidden').html(`<div class="bg-white p-5 rounded-[3rem] border border-slate-100 shadow-2xl overflow-hidden"><iframe src="${c.link}" class="w-full h-[700px] rounded-[2rem]" frameborder="0"></iframe></div>`);
                    }
                } else if (c.type === 'document' && c.document) {
                    $('#document').removeClass('hidden');
                    let pdfDoc = null, pageNum = 1;
                    pdfjsLib.getDocument(`/storage/${c.document}`).promise.then(doc => {
                        pdfDoc = doc; $('#total_pages').text(doc.numPages); renderPage(pageNum);
                    });
                    function renderPage(num) {
                        pdfDoc.getPage(num).then(page => {
                            const canvas = document.getElementById('pdf-canvas'), ctx = canvas.getContext('2d'), viewport = page.getViewport({ scale: 1.5 });
                            canvas.height = viewport.height; canvas.width = viewport.width;
                            page.render({ canvasContext: ctx, viewport: viewport });
                            $('#current_page').text(num);
                        });
                    }
                    $('#prev').click(() => { if (pageNum > 1) { pageNum--; renderPage(pageNum); } });
                    $('#next').click(() => { if (pageNum < pdfDoc.numPages) { pageNum++; renderPage(pageNum); } });
                } else if (c.type === 'text_course') {
                    $('#text').html(`<h1 class="text-4xl font-black text-slate-900 mb-8 uppercase tracking-tighter">${c.name}</h1><div class="text-slate-600 font-medium text-lg leading-relaxed">${c.text_course}</div>`);
                }
            }
        });
    });

    // Zoom & Disability Features Variables (Teacher/Host)
    let meetingNumber = '';
    let passcode = '';
    let zoomSignature = '';
    let zoomSdkKey = '';
    let recognition = null;
    let isRecognizing = false;
    let captionTimeout = null;

    // Join Zoom Meeting SDK as Host
    $(document).on('click', '#btn-join-zoom', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mx-auto"></div>');
        
        $.ajax({
            url: '/api/zoom/signature',
            method: 'POST',
            data: {
                meeting_number: meetingNumber,
                role: 1 // teacher = host
            },
            success: function(sigRes) {
                zoomSignature = sigRes.signature;
                zoomSdkKey = sigRes.sdkKey;
                
                $('#zoom-lobby').addClass('hidden');
                $('#meetingSDKElement').removeClass('hidden');
                
                initializeZoomSDK();
            },
            error: function() {
                toastr.error('Gagal mendapatkan signature Zoom. Kredensial tidak valid.');
                btn.prop('disabled', false).html('Mulai Kelas Rapat (Host)');
            }
        });
    });

    function initializeZoomSDK() {
        const meetingSDKElement = document.getElementById('meetingSDKElement');
        
        ZoomMtgEmbedded.createClient().init({
            zoomAppRoot: meetingSDKElement,
            language: 'en-US'
        }).then((client) => {
            client.join({
                sdkKey: zoomSdkKey,
                signature: zoomSignature,
                meetingNumber: meetingNumber,
                passCode: passcode,
                userName: '{{ auth()->user()->name }} (Dosen)',
                userEmail: '{{ auth()->user()->email }}'
            }).then(() => {
                toastr.success('Berhasil memulai kelas virtual sebagai Host!');
            }).catch((err) => {
                console.error('Failed to host Zoom meeting', err);
                toastr.error('Gagal memulai rapat Zoom.');
            });
        }).catch((err) => {
            console.error('Failed to init Zoom Web SDK', err);
            toastr.error('Inisialisasi Zoom Web SDK gagal.');
        });
    }

    // Toggle Mic Captions (Post to Server)
    $(document).on('click', '#btn-toggle-mic', function() {
        if (isRecognizing) {
            stopMic();
        } else {
            startMic();
        }
    });

    function startMic() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            toastr.error('Browser Anda tidak mendukung Web Speech API untuk caption otomatis.');
            return;
        }
        
        recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = false;
        recognition.lang = $('#spoken-lang').val();
        
        recognition.onstart = function() {
            isRecognizing = true;
            $('#btn-toggle-mic').removeClass('bg-red-600/20 text-red-400 border-red-500/30')
                .addClass('bg-red-600 text-white border-red-500')
                .text('Nonaktifkan Mic Caption');
            $('#caption-status-dot').removeClass('bg-indigo-500').addClass('bg-red-500');
            toastr.success('Mic Caption Aktif! Silakan berbicara.');
        };
        
        recognition.onresult = function(event) {
            const resultIndex = event.resultIndex;
            const transcript = event.results[resultIndex][0].transcript.trim();
            
            if (transcript) {
                // Post text transcript to server
                $.ajax({
                    url: '/api/live-captions',
                    method: 'POST',
                    data: {
                        course_id: courseId,
                        text: transcript,
                        language: $('#spoken-lang').val()
                    },
                    success: function() {
                        renderSubtitleText('Saya', transcript);
                    }
                });
            }
        };
        
        recognition.onerror = function(event) {
            console.error('Speech Recognition Error', event);
        };
        
        recognition.onend = function() {
            if (isRecognizing) {
                recognition.start(); // Restart if intended
            }
        };
        
        recognition.start();
    }

    function stopMic() {
        isRecognizing = false;
        if (recognition) {
            recognition.stop();
        }
        $('#btn-toggle-mic').addClass('bg-red-600/20 text-red-400 border-red-500/30')
            .removeClass('bg-red-600 text-white border-red-500')
            .text('Aktifkan Mic Caption (Mulai Bicara)');
        $('#caption-status-dot').addClass('bg-indigo-500').removeClass('bg-red-500');
        toastr.info('Mic Caption Dinonaktifkan.');
    }

    function renderSubtitleText(speaker, text) {
        const fontSize = $('#font-size').val();
        const displayBox = $('#subtitles-display-box');
        const textEl = $('#active-subtitle-text');
        
        textEl.removeClass('text-lg text-2xl text-3xl text-4xl').addClass(fontSize);
        textEl.html(`<span class="text-slate-400 font-medium">${speaker}:</span> ${text}`);
        
        displayBox.scrollTop(displayBox[0].scrollHeight);
        
        if (captionTimeout) clearTimeout(captionTimeout);
        captionTimeout = setTimeout(() => {
            textEl.html('<span class="text-slate-550 font-medium">Bicaralah untuk mentranskrip suara ke siswa disabilitas...</span>');
        }, 12000);
    }

    // ZOOM HISTORY TABS FUNCTIONS
    function fetchZoomMeetings() {
        $('#zoom-loading-message').show();
        $('#zoom-meetings-container').empty();
        
        $.ajax({
            url: `/api/zoom-meetings/${courseId}`,
            method: 'GET',
            success: function(res) {
                $('#zoom-loading-message').hide();
                if (res.success && res.data.length > 0) {
                    res.data.forEach(meet => {
                        const dateStr = new Date(meet.meeting_time).toLocaleString('id-ID', {
                            dateStyle: 'medium',
                            timeStyle: 'short'
                        });
                        
                        let meetNum = '';
                        let pwd = '';
                        try {
                            const urlObj = new URL(meet.zoom_link);
                            const pathParts = urlObj.pathname.split('/');
                            const jIndex = pathParts.indexOf('j');
                            if (jIndex !== -1 && pathParts[jIndex + 1]) {
                                meetNum = pathParts[jIndex + 1];
                            } else {
                                meetNum = meet.zoom_link.match(/\/j\/(\d+)/)[1];
                            }
                            pwd = meet.passcode || urlObj.searchParams.get('pwd') || '';
                        } catch (e) {}

                        const descHtml = meet.description ? `<p class="text-slate-500 text-xs font-medium mb-3 leading-relaxed">${meet.description}</p>` : '';
                        const card = `
                            <div class="bg-white border border-slate-200 rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all flex flex-col justify-between">
                                <div class="text-left">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="px-3.5 py-1.5 bg-indigo-50 text-indigo-600 font-black text-[9px] uppercase tracking-widest rounded-xl">Virtual Class</span>
                                        <span class="text-[10px] font-bold text-slate-400">${dateStr}</span>
                                    </div>
                                    <h4 class="text-base font-black text-slate-900 mb-1 leading-tight">${meet.title}</h4>
                                    ${descHtml}
                                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-6">ID: ${meetNum} &bull; Passcode: ${pwd}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button onclick="startZoomSession('${meet.zoom_link}', '${pwd}')" class="flex-1 py-3.5 bg-slate-900 text-white font-black uppercase text-[9px] tracking-wider rounded-2xl hover:bg-indigo-650 transition-all flex items-center justify-center gap-2">
                                        📹 Mulai Rapat
                                    </button>
                                    <button onclick="deleteZoomMeeting(${meet.id})" class="p-3.5 bg-red-50 text-red-600 rounded-2xl hover:bg-red-600 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        `;
                        $('#zoom-meetings-container').append(card);
                    });
                } else {
                    $('#zoom-meetings-container').html(`
                        <div class="col-span-full py-16 bg-white border border-dashed border-slate-200 rounded-[3rem] text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                            Belum ada pertemuan Zoom terjadwal
                        </div>
                    `);
                }
            },
            error: function() {
                $('#zoom-loading-message').hide();
                toastr.error('Gagal mengambil data pertemuan Zoom.');
            }
        });
    }

    function startZoomSession(link, rawPasscode) {
        try {
            const urlObj = new URL(link);
            const pathParts = urlObj.pathname.split('/');
            const jIndex = pathParts.indexOf('j');
            let meetingNumber = '';
            if (jIndex !== -1 && pathParts[jIndex + 1]) {
                meetingNumber = pathParts[jIndex + 1];
            } else {
                meetingNumber = link.match(/\/j\/(\d+)/)[1];
            }
            const passcode = rawPasscode || urlObj.searchParams.get('pwd') || '';
            
            // Redirect to standalone zoom-session view in a new window/tab
            const zoomUrl = `/zoom-session?meeting_number=${meetingNumber}&passcode=${encodeURIComponent(passcode)}&role=1&course_id=${courseId}`;
            window.open(zoomUrl, '_blank');
        } catch (e) {
            toastr.error('Format tautan Zoom tidak valid.');
        }
    }

    // Add zoom meeting submit handler
    $('#addZoomForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('Menyimpan...');

        $.ajax({
            url: '/api/zoom-meetings',
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                toastr.success('Pertemuan Zoom berhasil dijadwalkan!');
                closeModal('add-zoom');
                form[0].reset();
                fetchZoomMeetings();
            },
            error: function(err) {
                const msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Gagal menjadwalkan pertemuan Zoom. Periksa isian Anda.';
                toastr.error(msg);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('Simpan Pertemuan');
            }
        });
    });

    function deleteZoomMeeting(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal pertemuan Zoom ini?')) {
            $.ajax({
                url: `/api/zoom-meetings/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    toastr.success('Jadwal pertemuan Zoom berhasil dihapus.');
                    fetchZoomMeetings();
                },
                error: function() {
                    toastr.error('Gagal menghapus jadwal pertemuan.');
                }
            });
        }
    }
    // END OF ZOOM HISTORY FUNCTIONS
</script>
@endsection