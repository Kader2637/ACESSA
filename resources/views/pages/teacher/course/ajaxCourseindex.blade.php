@extends('layouts.teacher.app')

@section('page_title', 'Detail Kurikulum')

@section('style')
<style>
    .nav-tab-active { border-bottom: 3px solid #4f46e5; color: #4f46e5; font-weight: 800; }
    .chat-container { height: calc(100vh - 450px); min-height: 550px; }
    .animate-zoom-in { transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out; }
    .modal-open .animate-zoom-in { transform: scale(1); opacity: 1; }
    .custom-input { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.25rem; padding: 0.85rem 1.25rem; font-weight: 600; transition: all 0.2s; width: 100%; color: #0f172a; }
    .custom-input:focus { outline: none; border-color: #4f46e5; background: white; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
</style>
@endsection

@section('content')
<div class="mb-10 bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm" data-aos="fade-up">
    <div class="relative h-48 md:h-64 overflow-hidden">
        <img id="class-thumbnail" class="w-full h-full object-cover" alt="Cover">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
        <div class="absolute bottom-6 left-8 right-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="px-3 py-1 bg-indigo-600 text-white font-black text-[9px] uppercase tracking-widest rounded-lg mb-3 inline-block shadow-lg">Instructor Mode</span>
                <h2 id="class-name-header" class="text-3xl font-black text-white tracking-tight leading-none uppercase"></h2>
            </div>
            <a href="{{ route('classroom.teacher') }}" class="px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-white hover:text-slate-900 transition-all shadow-xl">Kembali</a>
        </div>
    </div>
    <div class="p-8 bg-slate-50/50">
        <p id="class-description" class="text-slate-500 font-medium leading-relaxed max-w-4xl italic"></p>
    </div>
</div>

<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 border-b border-slate-200 overflow-x-auto">
    <div class="flex gap-8">
        <button onclick="switchTab('course')" id="tab-course" class="pb-4 text-sm font-bold text-slate-400 nav-tab-active transition-all whitespace-nowrap">Daftar Materi</button>
        <button onclick="switchTab('student')" id="tab-student" class="pb-4 text-sm font-bold text-slate-400 transition-all whitespace-nowrap">Siswa Aktif</button>
        <button onclick="switchTab('approval')" id="tab-approval" class="pb-4 text-sm font-bold text-slate-400 transition-all whitespace-nowrap flex items-center gap-2">Approval <span id="pending-count" class="px-2 py-0.5 bg-red-500 text-white text-[10px] rounded-full hidden">0</span></button>
        <button onclick="switchTab('discussion')" id="tab-discussion" class="pb-4 text-sm font-bold text-slate-400 transition-all whitespace-nowrap">Diskusi Room</button>
    </div>
    <div class="pb-4">
        <button onclick="openModal('addMaterial')" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Materi
        </button>
    </div>
</div>

<div id="content-course" class="tab-pane block animate-fade-in">
    <div id="materialCards" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8"></div>
</div>

<div id="content-student" class="tab-pane hidden animate-fade-in">
    <div id="student-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"></div>
</div>

<div id="content-approval" class="tab-pane hidden animate-fade-in">
    <div id="student-pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"></div>
</div>

<div id="content-discussion" class="tab-pane hidden animate-fade-in">
    <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden flex flex-col shadow-xl">
        <div id="kotak-pesan" class="chat-container overflow-y-auto p-8 md:p-12 space-y-6 bg-slate-50/30 shadow-inner"></div>
        <form id="form-pesan" class="p-6 bg-white border-t border-slate-100 flex items-center gap-4">
            <input type="text" name="message" id="input-pesan" class="flex-1 px-6 py-5 bg-slate-50 border border-slate-200 rounded-3xl focus:outline-none focus:border-indigo-600 font-medium transition-all" placeholder="Tulis pesan diskusi ke kelas...">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="classroom_id" value="{{ $id }}">
            <button type="submit" class="w-16 h-16 bg-indigo-600 text-white rounded-3xl flex items-center justify-center hover:bg-indigo-700 shadow-lg active:scale-90 transition-all">
                <svg class="w-6 h-6 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>
</div>

<div id="modal-addMaterial" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('addMaterial')"></div>
    <div class="relative bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-10 md:p-12">
            <h3 class="text-2xl font-black text-slate-900 mb-8">Tambah Materi Baru</h3>
            <form id="materialForm" class="space-y-6">
                <input type="hidden" name="classroom_id" value="{{ $id }}">
                <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Nama Materi</label><input type="text" name="name" class="custom-input" required></div>
                <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Deskripsi</label><textarea name="description" class="custom-input" rows="2" required></textarea></div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Tipe Materi</label>
                    <select id="materialType" name="type" class="custom-input appearance-none" required>
                        <option value="">Pilih Tipe</option>
                        <option value="document">📄 Dokumen (PDF)</option>
                        <option value="link">🎥 Link Youtube / Video</option>
                        <option value="text_course">📝 Modul Teks</option>
                    </select>
                </div>
                <div id="additionalInput" class="animate-fade-in"></div>
                <div class="flex gap-3 pt-6">
                    <button type="button" onclick="closeModal('addMaterial')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-600">Simpan Materi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-editMaterial" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('editMaterial')"></div>
    <div class="relative bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-10 md:p-12">
            <h3 class="text-2xl font-black text-slate-900 mb-8">Update Materi</h3>
            <form id="editMaterialForm" class="space-y-6">
                @method('PUT')
                <input type="hidden" name="classroom_id" value="{{ $id }}">
                <input type="hidden" id="editMaterialId" name="id">
                <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Judul Materi</label><input type="text" id="editMaterialName" name="name" class="custom-input" required></div>
                <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Deskripsi</label><textarea id="editMaterialDescription" name="description" class="custom-input" rows="2" required></textarea></div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Tipe Materi</label>
                    <select id="editMaterialType" name="type" class="custom-input appearance-none" required>
                        <option value="document">Dokumen</option>
                        <option value="link">Link</option>
                        <option value="text_course">Text Course</option>
                    </select>
                </div>
                <div id="editAdditionalInput" class="animate-fade-in"></div>
                <div class="flex gap-3 pt-6">
                    <button type="button" onclick="closeModal('editMaterial')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-delete" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete')"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-zoom-in p-10 text-center">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <h3 class="text-xl font-black text-slate-900 mb-2 tracking-tight">Hapus Materi?</h3>
        <p class="text-slate-500 font-medium mb-10 text-sm">Data materi akan dihapus permanen. Siswa tidak akan bisa lagi mengakses konten ini.</p>
        <form id="form-delete-materi-manual">
            <input type="hidden" id="deleteIdMateri">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('delete')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-black uppercase text-[10px] rounded-2xl">Batal</button>
                <button type="submit" class="flex-1 py-4 bg-red-500 text-white font-black uppercase text-[10px] rounded-2xl shadow-lg shadow-red-200 hover:bg-red-600">Hapus!</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-kick" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('kick')"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-zoom-in p-10 text-center">
        <h3 class="text-xl font-black text-slate-900 mb-2">Keluarkan Siswa?</h3>
        <p class="text-slate-500 font-medium mb-10 text-sm italic leading-relaxed">Setelah dikeluarkan, siswa harus meminta akses kembali untuk masuk ke kelas ini.</p>
        <form id="form-kick-student-manual">
            <input type="hidden" id="kickIdStudent">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('kick')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-black text-[10px] rounded-2xl uppercase">Batal</button>
                <button type="submit" class="flex-1 py-4 bg-slate-900 text-white font-black text-[10px] rounded-2xl uppercase hover:bg-red-500 transition-all">Keluarkan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-approval" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('approval')"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl animate-zoom-in p-10 text-center">
        <h3 id="approval-title" class="text-xl font-black text-slate-900 mb-10 uppercase tracking-tighter"></h3>
        <form id="form-approval-manual">
            <input type="hidden" id="approvalIdStudent">
            <input type="hidden" id="approvalType">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('approval')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-black text-[10px] rounded-2xl uppercase">Batal</button>
                <button id="approval-submit-btn" type="submit" class="flex-1 py-4 text-white font-black text-[10px] rounded-2xl uppercase shadow-lg"></button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    const classId = '{{ $id }}';
    const userId = {{ auth()->user()->id }};

    // FUNGSI MODAL GLOBAL
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
        $('[id^="tab-"]').removeClass('nav-tab-active text-indigo-600').addClass('text-slate-400');
        $(`#tab-${tab}`).addClass('nav-tab-active text-indigo-600').removeClass('text-slate-400');
        if (tab === 'absensi') loadAttendanceSessions(classId);
    }

    // LOAD DATA KELAS
    const fetchClassData = () => {
        $.ajax({
            url: `/api/teacher/classroom/show/${classId}`, method: 'GET',
            success: function(res) {
                if (res.status === "success") {
                    $('#class-name-header, #class-name-title').text(res.data.name);
                    $('#class-description').text(res.data.description);
                    $('#class-thumbnail').attr('src', (res.data.thumbnail && res.data.thumbnail !== 'default.png') ? `/storage/${res.data.thumbnail}` : '/classaccesa.png');
                    fetchMaterials(); ambilDataSiswa(); ambilDataPending();
                }
            }
        });
    };

    // LOAD DAFTAR MATERI
    const fetchMaterials = () => {
        $.ajax({
            url: `/api/teacher/course/data/${classId}`, method: 'GET',
            success: function(res) {
                const list = $('#materialCards'); list.empty();
                if (res.status && res.data.length > 0) {
                    res.data.forEach(m => {
                        const icon = m.type === 'link' ? '📹' : m.type === 'document' ? '📄' : '📝';
                        list.append(`
                        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group animate-fade-in">
                            <div class="flex items-start justify-between mb-6">
                                <div class="w-14 h-14 bg-slate-50 text-3xl flex items-center justify-center rounded-2xl group-hover:bg-indigo-50 transition-colors">${icon}</div>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="prepareEditMaterial('${m.id}', '${m.name}', '${m.description.replace(/'/g, "\\'")}', '${m.type}', '${m.link}', '${m.text_course}')" class="p-2 text-slate-400 hover:text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                    <button onclick="prepareDeleteMateri('${m.id}')" class="p-2 text-slate-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                            <h5 class="text-slate-900 font-black text-xl mb-2 truncate">${m.name}</h5>
                            <p class="text-slate-400 text-sm font-medium line-clamp-2 mb-8 h-10 leading-relaxed">${m.description}</p>
                            <a href="/teacher/course/detail/${m.id}" class="block w-full py-4 bg-slate-900 text-white text-center font-black uppercase text-[10px] tracking-[0.2em] rounded-2xl hover:bg-indigo-600 shadow-lg shadow-indigo-100 transition-all">Kelola Unit & Tugas</a>
                        </div>`);
                    });
                } else {
                    list.html(`<div class="col-span-full py-24 text-center bg-white rounded-[3rem] border border-dashed border-slate-200 animate-fade-in"><img src="{{ asset('no-data.png') }}" class="w-32 mx-auto opacity-30 mb-4"><h4 class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Belum ada materi pengajaran</h4></div>`);
                }
            }
        });
    };

    // LOGIKA DINAMIS INPUT MATERI (ADD)
    $('#materialType').on('change', function() {
        const area = $('#additionalInput'); area.empty();
        const type = $(this).val();
        if (type === 'document') area.append(`<div class="animate-fade-in"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Upload PDF</label><input type="file" name="document" class="custom-input" accept=".pdf" required></div>`);
        else if (type === 'link') area.append(`<div class="animate-fade-in"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">URL Video</label><input type="url" name="link" class="custom-input" placeholder="https://youtube.com/..." required></div>`);
        else if (type === 'text_course') area.append(`<div class="animate-fade-in"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Konten Artikel</label><textarea name="text_course" rows="6" class="custom-input" required></textarea></div>`);
    });

    // LOGIKA DINAMIS INPUT MATERI (EDIT)
    function prepareEditMaterial(id, name, desc, type, link, text) {
        $('#editMaterialId').val(id); $('#editMaterialName').val(name); $('#editMaterialDescription').val(desc); $('#editMaterialType').val(type);
        const area = $('#editAdditionalInput'); area.empty();
        if (type === 'link') area.append(`<div class="animate-fade-in"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">URL Video</label><input type="url" name="link" class="custom-input" value="${link}" required></div>`);
        else if (type === 'document') area.append(`<div class="animate-fade-in"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Ganti PDF (Opsional)</label><input type="file" name="document" class="custom-input" accept=".pdf"></div>`);
        else if (type === 'text_course') area.append(`<div class="animate-fade-in"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Konten Artikel</label><textarea name="text_course" rows="6" class="custom-input" required>${text}</textarea></div>`);
        openModal('editMaterial');
    }

    // KICK & APPROVAL HELPERS
    function prepareDeleteMateri(id) { $('#deleteIdMateri').val(id); openModal('delete'); }
    function prepareKick(id) { $('#kickIdStudent').val(id); openModal('kick'); }
    function prepareApproval(id, type) {
        $('#approvalIdStudent').val(id); $('#approvalType').val(type);
        $('#approval-title').text(type === 'accept' ? 'Terima Siswa?' : 'Tolak Siswa?');
        $('#approval-submit-btn').text(type === 'accept' ? 'Ya, Terima' : 'Ya, Tolak').removeClass('bg-green-500 bg-red-500').addClass(type === 'accept' ? 'bg-green-500 shadow-green-100' : 'bg-red-500 shadow-red-100');
        openModal('approval');
    }

    // AJAX SUBMISSIONS
    $('#materialForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/teacher/course/create', method: 'POST', data: new FormData(this), processData: false, contentType: false,
            success: function() { toastr.success('Berhasil ditambah!'); closeModal('addMaterial'); fetchMaterials(); }
        });
    });

    $('#editMaterialForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/teacher/course/update/${$('#editMaterialId').val()}`, method: 'POST', data: new FormData(this), processData: false, contentType: false,
            success: function() { toastr.success('Materi diperbarui!'); closeModal('editMaterial'); fetchMaterials(); }
        });
    });

    $('#form-delete-materi-manual').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/teacher/course/delete/${$('#deleteIdMateri').val()}`, method: 'DELETE',
            success: function() { toastr.success('Materi dihapus!'); closeModal('delete'); fetchMaterials(); }
        });
    });

    $('#form-kick-student-manual').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/kick/student/${$('#kickIdStudent').val()}`, method: 'DELETE',
            success: function() { toastr.success('Siswa dikeluarkan'); closeModal('kick'); ambilDataSiswa(); }
        });
    });

    $('#form-approval-manual').on('submit', function(e) {
        e.preventDefault();
        const sid = $('#approvalIdStudent').val();
        const type = $('#approvalType').val();
        const url = type === 'accept' ? `/api/accept/teacher/${sid}` : `/api/reject/teacher/${sid}`;
        $.ajax({
            url: url, method: 'POST', data: { _token: '{{ csrf_token() }}' },
            success: function() { toastr.success('Berhasil diproses!'); closeModal('approval'); ambilDataPending(); ambilDataSiswa(); }
        });
    });

    // SISWA & PENDING FETCHERS
    const ambilDataSiswa = () => {
        $.ajax({
            url: `/api/teacher/data/classroom/${classId}`, method: 'GET',
            success: function(res) {
                const list = $('#student-list'); list.empty();
                if (res.data.length > 0) {
                    res.data.forEach(s => {
                        list.append(`<div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex flex-col items-center text-center shadow-sm animate-fade-in group">
                            <img src="${s.profile && s.profile !== 'user.png' && s.profile !== 'default.png' ? '/storage/'+s.profile : '/user.png'}" class="w-20 h-20 rounded-3xl object-cover mb-4 border-4 border-slate-50 shadow-sm" onerror="this.onerror=null; this.src='/user.png';">
                            <h6 class="text-slate-900 font-black mb-1 truncate w-full">${s.name}</h6>
                            <p class="text-[9px] text-slate-400 font-black uppercase mb-6 tracking-widest">${s.email}</p>
                            <button onclick="prepareKick('${s.id_relation}')" class="text-red-500 font-black text-[9px] uppercase tracking-widest hover:underline">Kick Out</button>
                        </div>`);
                    });
                } else { list.html(`<div class="col-span-full py-20 text-center text-slate-400 font-bold uppercase text-[10px]">Belum ada siswa aktif</div>`); }
            }
        });
    };

    const ambilDataPending = () => {
        $.ajax({
            url: `/api/pending/teacher/${classId}`, method: 'GET',
            success: function(res) {
                const list = $('#student-pending'); list.empty();
                if (res.status && res.data.length > 0) {
                    $('#pending-count').text(res.data.length).removeClass('hidden');
                    res.data.forEach(s => {
                        list.append(`<div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex flex-col items-center text-center shadow-sm animate-fade-in">
                            <img src="${s.profile && s.profile !== 'user.png' && s.profile !== 'default.png' ? '/storage/'+s.profile : '/user.png'}" class="w-20 h-20 rounded-3xl object-cover mb-4 border shadow-sm" onerror="this.onerror=null; this.src='/user.png';">
                            <h6 class="text-slate-900 font-black mb-6 truncate w-full">${s.name}</h6>
                            <div class="flex gap-2 w-full">
                                <button onclick="prepareApproval('${s.id_relation}', 'accept')" class="flex-1 py-3 bg-green-500 text-white rounded-xl font-black text-[9px] uppercase shadow-lg shadow-green-100">Accept</button>
                                <button onclick="prepareApproval('${s.id_relation}', 'reject')" class="flex-1 py-3 bg-red-50 text-red-500 rounded-xl font-black text-[9px] uppercase hover:bg-red-500 hover:text-white transition-all">Reject</button>
                            </div>
                        </div>`);
                    });
                } else { 
                    $('#pending-count').addClass('hidden'); 
                    list.html(`<div class="col-span-full py-24 text-center bg-white rounded-[3rem] border border-dashed border-slate-200 animate-fade-in"><h4 class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Antrean Approval Bersih</h4></div>`);
                }
            }
        });
    };

    // LOGIKA DISKUSI (REAL-TIME POLLING)
    function ambilPesan() {
        const box = $('#kotak-pesan');
        const shouldScroll = box.scrollTop() + box.innerHeight() >= box[0].scrollHeight;
        $.ajax({
            url: `/api/forum/discussion/${classId}?last_message_id=${lastMessageId}`, type: 'GET',
            success: function(res) {
                if (res.status === "success" && res.data.length > 0) {
                    res.data.forEach(msg => {
                        if ($(`[data-message-id="${msg.id}"]`).length) return;
                        const isMe = msg.user_id == userId;
                        box.append(`
                        <div class="flex ${isMe ? 'flex-row-reverse' : 'flex-row'} items-end gap-3 animate-fade-in" data-message-id="${msg.id}">
                            <img src="${msg.user_image && msg.user_image !== 'user.png' && msg.user_image !== 'default.png' ? '/storage/'+msg.user_image : '/user.png'}" class="w-10 h-10 rounded-full object-cover mb-1 shadow-sm" onerror="this.onerror=null; this.src='/user.png';">
                            <div class="max-w-[70%]">
                                <div class="px-6 py-4 rounded-3xl ${isMe ? 'bg-indigo-600 text-white rounded-br-none shadow-indigo-100' : 'bg-white text-slate-900 rounded-bl-none shadow-sm border border-slate-100'}">
                                    <p class="text-[9px] font-black uppercase tracking-widest mb-1 opacity-60">${isMe ? 'Anda' : msg.user_name}</p>
                                    <p class="text-sm font-medium leading-relaxed">${msg.message}</p>
                                </div>
                                <p class="text-[8px] font-black text-slate-400 mt-1.5 mx-2 uppercase">${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                            </div>
                        </div>`);
                        lastMessageId = msg.id;
                    });
                    if (shouldScroll) box.scrollTop(box[0].scrollHeight);
                }
            }
        });
    }

    $('#form-pesan').submit(function(e) {
        e.preventDefault();
        if(!$('#input-pesan').val()) return;
        $.ajax({
            url: `/api/forum/discussion`, type: 'POST', data: $(this).serialize(),
            success: function() { $('#input-pesan').val(''); ambilPesan(); }
        });
    });

    let pollInterval = null;
    let countdownInterval = null;

    function loadAttendanceSessions(classId) {
        const table = $('#attendance-list-table');
        table.empty();
        $('#attendance-empty').addClass('hidden');

        $.ajax({
            url: `/api/classroom/${classId}/attendance/sessions`,
            method: 'GET',
            success: function(res) {
                const sessions = res.data || [];
                if (sessions.length === 0) {
                    $('#attendance-empty').removeClass('hidden');
                    return;
                }

                sessions.forEach((s, index) => {
                    const validUntil = new Date(s.valid_until);
                    const now = new Date();
                    const isExpired = validUntil < now;
                    
                    const actionBtn = `
                        <div class="flex justify-end gap-2">
                            <button onclick="openQRModal(${s.id})" class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-600 rounded-lg text-[10px] font-bold transition-all flex items-center gap-1">⚡ QR</button>
                            <button onclick="openDetailModal(${s.id})" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold transition-all">📊 Detail</button>
                        </div>
                    `;

                    const statusBadge = isExpired
                        ? `<span class="text-red-500 font-bold">Kedaluwarsa</span>`
                        : `<span class="text-emerald-500 font-bold animate-pulse">Aktif</span>`;

                    const dateString = validUntil.toLocaleString('id-ID', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: 'short' });

                    table.append(`
                        <tr>
                            <td class="px-6 py-4 text-center text-slate-400">${index + 1}</td>
                            <td class="px-6 py-4 text-slate-900">${s.title}</td>
                            <td class="px-6 py-4 text-center font-mono font-bold text-slate-700 bg-slate-50 rounded-lg">${s.code}</td>
                            <td class="px-6 py-4 text-slate-500">${dateString} (${statusBadge})</td>
                            <td class="px-6 py-4 text-right px-8">${actionBtn}</td>
                        </tr>
                    `);
                });
            },
            error: () => $('#attendance-empty').removeClass('hidden')
        });
    }

    let activeSessionIdForDetail = null;

    function openDetailModal(sessionId) {
        activeSessionIdForDetail = sessionId;
        closeQRModal();
        $('#modal-detail-attendance').removeClass('hidden').addClass('flex');
        fetchAttendanceDetail(sessionId);
    }

    function fetchAttendanceDetail(sessionId) {
        const tableBody = $('#detail-attendance-table-body');
        tableBody.empty();
        $('#detail-attendance-empty').addClass('hidden');

        $.ajax({
            url: `/api/attendance/session/${sessionId}/records`,
            method: 'GET',
            success: function(res) {
                const s = res.session;
                $('#detail-session-title').text(`Detail Kehadiran: ${s.title}`);
                $('#detail-session-code').text(`Kode: ${s.code} | Batas Waktu: ${new Date(s.valid_until).toLocaleString('id-ID')}`);
                
                $('#btn-show-qr-from-detail').off('click').on('click', function() {
                    closeDetailModal();
                    openQRModal(sessionId);
                });

                const data = res.data || [];
                const presentCount = data.filter(st => st.status === 'Hadir').length;
                const absentCount = data.filter(st => st.status !== 'Hadir').length;

                $('#detail-count-present').text(presentCount);
                $('#detail-count-absent').text(absentCount);
                $('#detail-count-total').text(data.length);

                if (data.length === 0) {
                    $('#detail-attendance-empty').removeClass('hidden');
                    return;
                }

                data.forEach((st, index) => {
                    const statusBadge = st.status === 'Hadir'
                        ? `<span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[10px] font-black uppercase">Hadir</span>`
                        : `<span class="px-2 py-0.5 bg-red-50 text-red-500 rounded-md text-[10px] font-black uppercase">Tidak Hadir</span>`;

                    const timeStr = st.scanned_at ? st.scanned_at : '—';
                    const avatar = st.profile ? `/storage/${st.profile}` : '/user.png';

                    tableBody.append(`
                        <tr>
                            <td class="px-6 py-4 text-center text-slate-400">${index + 1}</td>
                            <td class="px-6 py-4 flex items-center gap-2">
                                <img src="${avatar}" class="w-6 h-6 rounded-full object-cover border" onerror="this.onerror=null; this.src='/user.png';">
                                <span class="text-slate-900">${st.name}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-550">${st.email}</td>
                            <td class="px-6 py-4 text-slate-500">${timeStr}</td>
                            <td class="px-6 py-4 text-center">${statusBadge}</td>
                        </tr>
                    `);
                });
            }
        });
    }

    function closeDetailModal() {
        $('#modal-detail-attendance').removeClass('flex').addClass('hidden');
        activeSessionIdForDetail = null;
    }

    // Submit handler for creating new attendance session
    $('#form-create-attendance').submit(function(e) {
        e.preventDefault();
        const title = $('#attendance-title').val();
        const duration = $('#attendance-duration').val();

        $.ajax({
            url: '/api/attendance/create',
            method: 'POST',
            data: {
                classroom_id: classId,
                title: title,
                duration: duration
            },
            success: function(res) {
                toastr.success(res.message);
                $('#attendance-title').val('');
                loadAttendanceSessions(classId);
                openQRModal(res.data.id);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal membuat sesi absensi.';
                toastr.error(msg);
            }
        });
    });

    let activeSessionIdForQR = null;

    function openQRModal(sessionId) {
        activeSessionIdForQR = sessionId;
        stopAttendancePoll();
        $('#modal-qr-attendance').removeClass('hidden').addClass('flex');
        fetchQRModalDetails(sessionId);
        pollAttendees(sessionId);
        pollInterval = setInterval(() => pollAttendees(sessionId), 3000);
    }

    $('#extend-session-select').on('change', function() {
        const minutes = $(this).val();
        if (!minutes || !activeSessionIdForQR) return;

        $.ajax({
            url: `/api/attendance/session/${activeSessionIdForQR}/extend`,
            method: 'POST',
            data: { duration: minutes },
            success: function(res) {
                toastr.success(res.message);
                $('#extend-session-select').val('');
                fetchQRModalDetails(activeSessionIdForQR);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal memperpanjang sesi.';
                toastr.error(msg);
                $('#extend-session-select').val('');
            }
        });
    });

    function fetchQRModalDetails(sessionId) {
        $.ajax({
            url: `/api/attendance/session/${sessionId}/records`,
            method: 'GET',
            success: function(res) {
                const s = res.session;
                $('#qr-session-title').text(s.title);
                $('#qr-session-code').text(s.code);
                
                const scanUrl = window.location.origin + `/attendance/scan/${s.token}`;
                const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=${encodeURIComponent(scanUrl)}`;
                $('#attendance-qr-img').attr('src', qrApiUrl);

                if (s.is_expired) {
                    $('#qr-expired-overlay').removeClass('hidden');
                    $('#qr-countdown').text('Selesai').removeClass('text-yellow-400').addClass('text-red-500');
                    $('#qr-progress-bar').css('width', '0%');
                } else {
                    $('#qr-expired-overlay').addClass('hidden');
                    $('#qr-countdown').removeClass('text-red-500').addClass('text-yellow-400');
                    startCountdown(s.valid_until);
                }
            }
        });
    }

    function startCountdown(validUntilStr) {
        if (countdownInterval) clearInterval(countdownInterval);

        const validUntil = new Date(validUntilStr).getTime();

        const updateClock = () => {
            const now = new Date().getTime();
            const distance = validUntil - now;

            if (distance <= 0) {
                clearInterval(countdownInterval);
                $('#qr-countdown').text('Waktu Habis').removeClass('text-yellow-400').addClass('text-red-500');
                $('#qr-expired-overlay').removeClass('hidden');
                $('#qr-progress-bar').css('width', '0%');
                
                loadAttendanceSessions(classId);
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const minStr = String(minutes).padStart(2, '0');
            const secStr = String(seconds).padStart(2, '0');
            $('#qr-countdown').text(`${minStr}:${secStr}`);
            $('#qr-progress-bar').css('width', `100%`);
        };

        updateClock();
        countdownInterval = setInterval(updateClock, 1000);
    }

    function pollAttendees(sessionId) {
        $.ajax({
            url: `/api/attendance/session/${sessionId}/records`,
            method: 'GET',
            success: function(res) {
                const data = res.data || [];
                const attended = data.filter(st => st.status === 'Hadir');
                
                $('#qr-attendee-count').text(`${attended.length} / ${data.length}`);

                const list = $('#qr-student-list');
                list.empty();

                if (attended.length === 0) {
                    $('#qr-student-empty').removeClass('hidden');
                    return;
                }

                $('#qr-student-empty').addClass('hidden');
                attended.forEach(st => {
                    const avatar = st.profile ? `/storage/${st.profile}` : '/user.png';
                    list.append(`
                        <div class="py-3 flex items-center gap-3">
                            <img src="${avatar}" class="w-8 h-8 rounded-lg object-cover border border-slate-100" onerror="this.onerror=null; this.src='/user.png';">
                            <div class="flex-grow">
                                <h5 class="text-xs font-extrabold text-slate-800">${st.name}</h5>
                                <p class="text-[9px] text-emerald-600 font-bold">Hadir pukul ${st.scanned_at}</p>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                    `);
                });
            }
        });
    }

    function closeQRModal() {
        $('#modal-qr-attendance').removeClass('flex').addClass('hidden');
        stopAttendancePoll();
        loadAttendanceSessions(classId);
    }

    function stopAttendancePoll() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }

    $(document).ready(function() {
        fetchClassData();
        setInterval(ambilPesan, 3000);
    });

    let lastMessageId = 0;
</script>
@endsection