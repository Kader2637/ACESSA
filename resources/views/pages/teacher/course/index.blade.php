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
                <span class="px-3 py-1 bg-indigo-600 text-white font-black text-[9px] uppercase tracking-widest rounded-lg mb-3 inline-block shadow-lg">Management Mode</span>
                <h2 id="class-name" class="text-3xl font-black text-white tracking-tight leading-none"></h2>
            </div>
            <a href="{{ route('classroom.teacher') }}" class="px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-white hover:text-slate-900 transition-all">Kembali</a>
        </div>
    </div>
    <div class="p-8 bg-slate-50/50">
        <p id="class-description" class="text-slate-500 font-medium leading-relaxed max-w-4xl"></p>
    </div>
</div>

<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 border-b border-slate-200 overflow-x-auto">
    <div class="flex gap-8">
        <button onclick="switchTab('course')" id="tab-course" class="pb-4 text-sm font-bold text-slate-400 nav-tab-active transition-all whitespace-nowrap">Daftar Materi</button>
        <button onclick="switchTab('student')" id="tab-student" class="pb-4 text-sm font-bold text-slate-400 transition-all whitespace-nowrap">Siswa Aktif</button>
        <button onclick="switchTab('approval')" id="tab-approval" class="pb-4 text-sm font-bold text-slate-400 transition-all whitespace-nowrap flex items-center gap-2 text-slate-400">Approval <span id="pending-count" class="px-2 py-0.5 bg-red-500 text-white text-[10px] rounded-full hidden">0</span></button>
        <button onclick="switchTab('discussion')" id="tab-discussion" class="pb-4 text-sm font-bold text-slate-400 transition-all whitespace-nowrap">Diskusi Room</button>
        <button onclick="switchTab('absensi')" id="tab-absensi" class="pb-4 text-sm font-bold text-slate-400 transition-all whitespace-nowrap">Absensi QR</button>
    </div>
    <div class="pb-4">
        <button onclick="openModal('addMaterial')" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Materi
        </button>
    </div>
</div>

<div id="content-course" class="tab-pane block animate-fade-in">
    <div id="materialCards" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"></div>
</div>

<div id="content-student" class="tab-pane hidden animate-fade-in">
    <div id="student-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"></div>
</div>

<div id="content-approval" class="tab-pane hidden animate-fade-in">
    <div id="student-pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"></div>
</div>

<div id="content-discussion" class="tab-pane hidden animate-fade-in">
    <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden flex flex-col shadow-xl">
        <div id="kotak-pesan" class="chat-container overflow-y-auto p-6 md:p-10 space-y-6 bg-slate-50/30"></div>
        <form id="form-pesan" class="p-6 bg-white border-t border-slate-100 flex items-center gap-4">
            <input type="text" name="message" id="input-pesan" class="flex-1 px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-indigo-600 font-medium transition-all" placeholder="Tulis pesan diskusi...">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="classroom_id" value="{{ $id }}">
            <button type="submit" class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center hover:bg-indigo-700 shadow-lg transition-all active:scale-90"><svg class="w-6 h-6 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg></button>
        </form>
    </div>
</div>

<div id="content-absensi" class="tab-pane hidden animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-left">
        
        {{-- Form Buat Absensi --}}
        <div class="lg:col-span-1 bg-white border border-slate-200/60 rounded-2xl p-6 h-fit shadow-sm">
            <h3 class="font-extrabold text-slate-900 text-sm mb-4">Buat Sesi Absensi Baru</h3>
            <form id="form-create-attendance" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Judul Sesi / Pertemuan</label>
                    <input type="text" id="attendance-title" class="custom-input" placeholder="Contoh: Pertemuan 1 - Pengenalan" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Durasi Aktif (Menit)</label>
                    <select id="attendance-duration" class="custom-input appearance-none" required>
                        <option value="5">5 Menit (Sangat Cepat)</option>
                        <option value="10" selected>10 Menit (Direkomendasikan)</option>
                        <option value="15">15 Menit</option>
                        <option value="30">30 Menit</option>
                        <option value="60">60 Menit</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-4 bg-indigo-650 hover:bg-indigo-700 text-white font-black uppercase text-[10px] tracking-widest rounded-xl transition-all shadow-sm">
                    ⚡ Buat Sesi & Tampilkan QR
                </button>
            </form>
        </div>

        {{-- Riwayat Absensi --}}
        <div class="lg:col-span-2 bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-extrabold text-slate-900 text-sm">Riwayat Sesi Absensi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 w-16 text-center">No</th>
                            <th class="px-6 py-3.5">Nama Sesi</th>
                            <th class="px-6 py-3.5 text-center">Kode</th>
                            <th class="px-6 py-3.5">Batas Waktu</th>
                            <th class="px-6 py-3.5 text-right px-8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-list-table" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
                </table>
            </div>
            <div id="attendance-empty" class="py-12 text-center text-slate-400 text-xs font-semibold">
                Belum ada sesi absensi yang dibuat untuk kelas ini.
            </div>
        </div>

    </div>
</div>

<div id="modal-addMaterial" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('addMaterial')"></div>
    <div class="relative bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-10 md:p-12">
            <h3 class="text-2xl font-black text-slate-900 mb-8">Tambah Materi Baru</h3>
            <form id="materialForm" class="space-y-6">
                <input type="hidden" name="classroom_id" value="{{ $id }}">
                <div><label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Nama Materi</label><input type="text" name="name" class="custom-input" required></div>
                <div><label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Deskripsi</label><textarea name="description" class="custom-input" rows="2" required></textarea></div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Tipe Konten</label>
                    <select id="materialType" name="type" class="custom-input appearance-none" required>
                        <option value="">Pilih Tipe</option>
                        <option value="document">Dokumen (PDF)</option>
                        <option value="link">Link / Youtube</option>
                        <option value="text_course">Artikel Teks</option>
                    </select>
                </div>
                <div id="additionalInput"></div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal('addMaterial')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white font-black uppercase text-[10px] rounded-2xl">Simpan</button>
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
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1">Ubah Tipe</label>
                    <select id="editMaterialType" name="type" class="custom-input appearance-none" required>
                        <option value="document">Dokumen</option>
                        <option value="link">Link</option>
                        <option value="text_course">Text Course</option>
                    </select>
                </div>
                <div id="editAdditionalInput"></div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal('editMaterial')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white font-black uppercase text-[10px] rounded-2xl">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-delete" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete')"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl animate-zoom-in p-10 text-center">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <h3 class="text-xl font-black text-slate-900 mb-2">Hapus Materi?</h3>
        <p class="text-slate-500 font-medium mb-10 text-sm leading-relaxed">Data yang dihapus tidak dapat dikembalikan. Lanjutkan?</p>
        <form id="form-delete-materi">
            <input type="hidden" id="deleteIdMateri">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('delete')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-black uppercase text-[10px] rounded-2xl">Batal</button>
                <button type="submit" class="flex-1 py-4 bg-red-500 text-white font-black uppercase text-[10px] rounded-2xl">Ya, Hapus!</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-kick" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('kick')"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl animate-zoom-in p-10 text-center">
        <div class="w-20 h-20 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h3 class="text-xl font-black text-slate-900 mb-2">Keluarkan Siswa?</h3>
        <p class="text-slate-500 font-medium mb-10 text-sm">Siswa ini tidak akan bisa lagi mengakses materi di kelas ini.</p>
        <form id="form-kick-student-manual">
            <input type="hidden" id="kickIdStudent">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('kick')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-black text-[10px] rounded-2xl uppercase">Batal</button>
                <button type="submit" class="flex-1 py-4 bg-slate-900 text-white font-black text-[10px] rounded-2xl uppercase">Keluarkan</button>
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
                <button id="approval-submit-btn" type="submit" class="flex-1 py-4 text-white font-black text-[10px] rounded-2xl uppercase"></button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    const classId = '{{ $id }}';
    const userId = {{ auth()->user()->id }};

    function openModal(type) {
        $(`#modal-${type}`).removeClass('hidden').addClass('flex');
        setTimeout(() => $(`#modal-${type}`).addClass('modal-open'), 10);
    }
    function closeModal(type) {
        $(`#modal-${type}`).removeClass('modal-open');
        setTimeout(() => $(`#modal-${type}`).removeClass('flex').addClass('hidden'), 200);
    }

    function switchTab(tab) {
        $('.tab-pane').addClass('hidden');
        $(`#content-${tab}`).removeClass('hidden');
        $('[id^="tab-"]').removeClass('nav-tab-active text-indigo-600').addClass('text-slate-400');
        $(`#tab-${tab}`).addClass('nav-tab-active text-indigo-600').removeClass('text-slate-400');
    }

    const ambilDataKelas = () => {
        $.ajax({
            url: `/api/student/classroom/show/${classId}`,
            method: 'GET',
            success: function(res) {
                if (res.status === "success") {
                    const data = res.data;
                    $('#class-name').text(data.name);
                    $('#class-description').text(data.description);
                    $('#class-thumbnail').attr('src', (data.thumbnail && data.thumbnail !== 'default.png') ? `/storage/${data.thumbnail}` : '/classaccesa.png');
                    ambilDataMateri(); ambilDataSiswa(); ambilDataPending(); loadAttendanceSessions(classId);
                }
            }
        });
    };

    const ambilDataMateri = () => {
        $.ajax({
            url: `/api/student/course/data/${classId}`,
            method: 'GET',
            success: function(res) {
                const list = $('#materialCards'); list.empty();
                if (res.data.length > 0) {
                    res.data.forEach(m => {
                        const icon = m.type === 'link' ? '📹' : m.type === 'document' ? '📄' : '📝';
                        list.append(`
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all group animate-fade-in">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="w-12 h-12 bg-slate-50 text-2xl flex items-center justify-center rounded-2xl group-hover:bg-indigo-50 transition-colors">${icon}</div>
                                    <div class="flex gap-1">
                                        <button onclick="prepareEditMaterial('${m.id}', '${m.name}', '${m.description.replace(/'/g, "\\'")}', '${m.type}')" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        <button onclick="prepareDeleteMateri('${m.id}')" class="p-2 text-slate-400 hover:text-red-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </div>
                                </div>
                                <h5 class="text-slate-900 font-extrabold text-lg mb-2 truncate">${m.name}</h5>
                                <p class="text-slate-400 text-sm font-medium line-clamp-2 mb-6 h-10 leading-relaxed">${m.description}</p>
                                <a href="/teacher/course/detail/${m.id}" class="block w-full py-3 bg-slate-900 text-white text-center font-black uppercase text-[9px] tracking-widest rounded-xl hover:bg-indigo-600 transition-all">Kelola Materi</a>
                            </div>`);
                    });
                } else {
                    list.html(`<div class="col-span-full py-20 text-center text-slate-400 font-bold uppercase text-xs tracking-widest">Belum ada materi dibuat</div>`);
                }
            }
        });
    };

    function prepareEditMaterial(id, name, desc, type) {
        $('#editMaterialId').val(id); $('#editMaterialName').val(name); $('#editMaterialDescription').val(desc); $('#editMaterialType').val(type); openModal('editMaterial');
    }
    function prepareDeleteMateri(id) { $('#deleteIdMateri').val(id); openModal('delete'); }
    function prepareKick(id) { $('#kickIdStudent').val(id); openModal('kick'); }
    function prepareApproval(id, type) {
        $('#approvalIdStudent').val(id); $('#approvalType').val(type);
        $('#approval-title').text(type === 'accept' ? 'Terima Siswa?' : 'Tolak Siswa?');
        $('#approval-submit-btn').text(type === 'accept' ? 'Terima' : 'Tolak').removeClass('bg-green-500 bg-red-500').addClass(type === 'accept' ? 'bg-green-500' : 'bg-red-500');
        openModal('approval');
    }

    const ambilDataSiswa = () => {
        $.ajax({
            url: `/api/teacher/data/classroom/${classId}`, method: 'GET',
            success: function(res) {
                const list = $('#student-list'); list.empty();
                if (res.data.length > 0) {
                    res.data.forEach(s => {
                        list.append(`
                            <div class="bg-white p-5 rounded-3xl border border-slate-100 flex flex-col items-center text-center shadow-sm hover:shadow-lg transition-all animate-fade-in">
                                <img src="${s.profile && s.profile !== 'user.png' && s.profile !== 'default.png' ? '/storage/'+s.profile : '/user.png'}" class="w-16 h-16 rounded-2xl object-cover mb-4 border shadow-sm" onerror="this.onerror=null; this.src='/user.png';">
                                <h6 class="text-slate-900 font-bold mb-1 truncate w-full px-2">${s.name}</h6>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-4 truncate w-full">${s.email}</p>
                                <button onclick="prepareKick('${s.id}')" class="text-red-500 font-black text-[9px] uppercase tracking-widest hover:underline">Kick Out</button>
                            </div>`);
                    });
                } else {
                    list.html(`<div class="col-span-full py-20 text-center text-slate-400 font-bold">Kelas masih kosong</div>`);
                }
            }
        });
    };

    const ambilDataPending = () => {
        $.ajax({
            url: `/api/teacher/data/classroom/pending/${classId}`, method: 'GET',
            success: function(res) {
                const list = $('#student-pending'); list.empty();
                if (res.data && res.data.length > 0) {
                    $('#pending-count').text(res.data.length).removeClass('hidden');
                    res.data.forEach(s => {
                        list.append(`
                            <div class="bg-white p-5 rounded-3xl border border-slate-100 flex flex-col items-center text-center shadow-sm animate-fade-in">
                                <img src="${s.profile && s.profile !== 'user.png' && s.profile !== 'default.png' ? '/storage/'+s.profile : '/user.png'}" class="w-16 h-16 rounded-2xl object-cover mb-4 border shadow-sm" onerror="this.onerror=null; this.src='/user.png';">
                                <h6 class="text-slate-900 font-bold mb-4 truncate w-full">${s.name}</h6>
                                <div class="flex gap-2 w-full">
                                    <button onclick="prepareApproval('${s.id}', 'accept')" class="flex-1 py-2.5 bg-green-500 text-white rounded-xl font-black text-[9px] uppercase">Accept</button>
                                    <button onclick="prepareApproval('${s.id}', 'reject')" class="flex-1 py-2.5 bg-red-500 text-white rounded-xl font-black text-[9px] uppercase">Reject</button>
                                </div>
                            </div>`);
                    });
                } else {
                    $('#pending-count').addClass('hidden');
                    list.html(`<div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200 animate-fade-in"><h4 class="text-slate-400 font-bold">Tidak ada antrean approval</h4></div>`);
                }
            }
        });
    };

    // AJAX Form Submits
    $('#materialForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/course/teacher/post', method: 'POST', data: new FormData(this), processData: false, contentType: false,
            success: function() { toastr.success('Materi ditambah!'); closeModal('addMaterial'); ambilDataMateri(); }
        });
    });

    $('#form-delete-materi').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/course/teacher/delete/${$('#deleteIdMateri').val()}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
            success: function() { toastr.success('Dihapus!'); closeModal('delete'); ambilDataMateri(); }
        });
    });

    $('#form-kick-student-manual').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/teacher/classroom/kick/${$('#kickIdStudent').val()}/${classId}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
            success: function() { toastr.success('Siswa dikeluarkan'); closeModal('kick'); ambilDataSiswa(); }
        });
    });

    $('#form-approval-manual').on('submit', function(e) {
        e.preventDefault();
        const type = $('#approvalType').val();
        const url = type === 'accept' ? `/api/teacher/classroom/accept/${$('#approvalIdStudent').val()}/${classId}` : `/api/teacher/classroom/reject/${$('#approvalIdStudent').val()}/${classId}`;
        $.ajax({
            url: url, method: 'POST', data: { _token: '{{ csrf_token() }}' },
            success: function() { toastr.success('Siswa diproses!'); closeModal('approval'); ambilDataPending(); ambilDataSiswa(); }
        });
    });

    // Chat Logic
    const urlAmbilPesan = `/api/forum/discussion/${classId}`;
    const urlKirimPesan = `/api/forum/discussion`;
    let lastMessageId = 0;

    function ambilPesan() {
        const box = $('#kotak-pesan');
        const shouldScroll = box.scrollTop() + box.innerHeight() >= box[0].scrollHeight;
        $.ajax({
            url: `${urlAmbilPesan}?last_message_id=${lastMessageId}`, type: 'GET',
            success: function(res) {
                if (res.status === "success" && res.data.length > 0) {
                    res.data.forEach(msg => {
                        if ($(`[data-message-id="${msg.id}"]`).length) return;
                        const isMe = msg.user_id == userId;
                        const align = isMe ? 'flex-row-reverse' : 'flex-row';
                        const bg = isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-slate-900 rounded-bl-none shadow-sm border border-slate-100';
                        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const img = msg.user_image && msg.user_image !== 'user.png' && msg.user_image !== 'default.png' ? `/storage/${msg.user_image}` : '/user.png';

                        box.append(`
                            <div class="flex ${align} items-end gap-3 animate-fade-in" data-message-id="${msg.id}">
                                <img src="${img}" class="w-8 h-8 rounded-full object-cover mb-1 border shadow-sm" onerror="this.onerror=null; this.src='/user.png';">
                                <div class="max-w-[75%]">
                                    <div class="px-5 py-3 rounded-2xl ${bg}">
                                        <p class="text-[10px] font-black opacity-60 uppercase tracking-widest mb-1">${isMe ? 'Anda' : msg.user_name}</p>
                                        <p class="text-sm font-medium leading-relaxed">${msg.message}</p>
                                    </div>
                                    <p class="text-[9px] font-bold text-slate-400 mt-1 mx-2">${time}</p>
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
        const msg = $('#input-pesan').val();
        if(!msg) return;
        $.ajax({
            url: urlKirimPesan, type: 'POST', data: $(this).serialize(),
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
                    
                    const actionBtn = isExpired 
                        ? `<button onclick="openQRModal(${s.id})" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold">Lihat Kehadiran</button>`
                        : `<button onclick="openQRModal(${s.id})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[10px] font-bold flex items-center gap-1">⚡ Tampilkan QR</button>`;

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

    function openQRModal(sessionId) {
        stopAttendancePoll();
        $('#modal-qr-attendance').removeClass('hidden').addClass('flex');
        fetchQRModalDetails(sessionId);
        pollAttendees(sessionId);
        pollInterval = setInterval(() => pollAttendees(sessionId), 3000);
    }

    function fetchQRModalDetails(sessionId) {
        $.ajax({
            url: `/api/attendance/session/${sessionId}/records`,
            method: 'GET',
            success: function(res) {
                const s = res.session;
                $('#qr-session-title').text(s.title);
                $('#qr-session-code').text(s.code);
                
                const scanUrl = window.location.origin + `/attendance/scan/${s.token}`;
                const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(scanUrl)}`;
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
        ambilDataKelas();
        setInterval(ambilPesan, 3000);
    });
</script>

{{-- MODAL QR ABSENSI --}}
<div id="modal-qr-attendance" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/80 backdrop-blur-md overflow-y-auto p-4 md:p-6">
    <div class="bg-white rounded-3xl w-full max-w-4xl shadow-2xl overflow-hidden border border-slate-100 flex flex-col md:flex-row h-full max-h-[85vh] animate-scale-in text-left">
        
        {{-- Left Side: QR Code, Short Code, Countdown Timer --}}
        <div class="flex-1 p-6 md:p-8 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 bg-slate-50/50">
            <span class="px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-wider rounded-lg mb-2">SCAN BARCODE UNTUK ABSEN</span>
            <h4 id="qr-session-title" class="text-lg font-black text-slate-900 mb-6 text-center">Judul Sesi Absensi</h4>
            
            {{-- QR Frame --}}
            <div class="w-56 h-56 md:w-64 md:h-64 bg-white rounded-3xl p-4 shadow-lg border border-slate-200/60 flex items-center justify-center relative mb-4">
                <img id="attendance-qr-img" src="" class="w-full h-full object-contain" alt="QR Code Absensi">
                <div id="qr-expired-overlay" class="absolute inset-0 bg-white/95 flex flex-col items-center justify-center text-center p-4 rounded-3xl hidden">
                    <svg class="w-12 h-12 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-extrabold text-slate-800">Sesi Kedaluwarsa</span>
                    <span class="text-[10px] text-slate-400 font-bold mt-1">Siswa tidak bisa lagi melakukan absen</span>
                </div>
            </div>

            {{-- Short Code --}}
            <div class="text-center mb-6">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">KODE ABSENSI MANUAL</p>
                <span id="qr-session-code" class="text-2xl font-mono font-black text-slate-950 tracking-widest bg-slate-200 px-4 py-1.5 rounded-xl border">ABCDEF</span>
            </div>

            {{-- Countdown Timer --}}
            <div class="w-full max-w-xs bg-slate-900 rounded-2xl p-4 text-white text-center shadow-md">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1 leading-none">Sisa Waktu Validitas</p>
                <div id="qr-countdown" class="text-2xl font-mono font-black text-yellow-400 leading-none">00:00</div>
                <div class="w-full bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div id="qr-progress-bar" class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>
        </div>

        {{-- Right Side: Attendance Live List --}}
        <div class="w-full md:w-[380px] p-6 flex flex-col h-full bg-white">
            <div class="flex justify-between items-center mb-4 flex-shrink-0">
                <div>
                    <h4 class="font-extrabold text-slate-900 text-sm">Siswa Hadir</h4>
                    <p class="text-[10px] text-slate-400 font-bold mt-0.5">Live update otomatis</p>
                </div>
                <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-extrabold text-xs rounded-lg" id="qr-attendee-count">0/0</span>
            </div>
            
            {{-- Scanned Student List --}}
            <div class="flex-grow overflow-y-auto min-h-[200px]" id="qr-student-list-container">
                <div class="divide-y divide-slate-100" id="qr-student-list"></div>
                <div id="qr-student-empty" class="h-full flex flex-col items-center justify-center text-center p-8">
                    <div class="w-12 h-12 bg-slate-50 border rounded-2xl flex items-center justify-center animate-pulse mb-3">
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-800">Menunggu Kehadiran</p>
                    <p class="text-[10px] text-slate-400 font-medium mt-1 leading-relaxed">Minta siswa untuk memindai kode QR atau memasukkan kode manual.</p>
                </div>
            </div>

            {{-- Close Button --}}
            <div class="pt-4 border-t border-slate-100 flex-shrink-0 mt-4">
                <button onclick="closeQRModal()" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all">
                    Tutup Layar Absensi
                </button>
            </div>
        </div>

    </div>
</div>

@include('pages.teacher.course.ajaxCourseindex')
@endsection