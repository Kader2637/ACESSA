@extends('layouts.teacher.app')

@section('page_title', 'Manajemen Kelas')

@section('style')
<style>
    .animate-zoom-in { transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out; }
    .modal-open .animate-zoom-in { transform: scale(1); opacity: 1; }
    .table-container { background: white; border-radius: 2.5rem; border: 1px solid #f1f5f9; overflow: hidden; }
    .custom-table th { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; padding: 1.5rem 1rem; border: none; background: #f8fafc; }
    .custom-table td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: #1e293b; font-size: 13px; }
    .custom-input { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.25rem; padding: 0.85rem 1.25rem; font-weight: 600; transition: all 0.2s; width: 100%; color: #0f172a; }
    .custom-input:focus { outline: none; border-color: #4f46e5; background: white; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
</style>
@endsection

@section('content')
<div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Daftar <span class="text-indigo-600">Kelas</span></h2>
        <p class="text-slate-500 font-medium mt-1 uppercase text-[10px] tracking-widest text-indigo-500">Pusat Kendali Pengajaran</p>
    </div>
    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
        <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2 shrink-0">
            <label class="text-[10px] font-black uppercase tracking-wider text-slate-400 shrink-0">Filter Semester:</label>
            <select id="filter-semester" class="bg-transparent text-xs font-bold text-slate-700 outline-none cursor-pointer">
                <option value="all">Semua Semester</option>
            </select>
        </div>
        <button onclick="openModal('create')" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 text-white font-black uppercase text-[11px] tracking-widest rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95 group shrink-0">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            Buat Kelas Baru
        </button>
    </div>
</div>

<div class="table-container shadow-xl shadow-slate-200/50 mb-12">
    <div class="overflow-x-auto">
        <table class="w-full text-left custom-table">
            <thead>
                <tr>
                    <th class="text-center w-20">No</th>
                    <th>Thumbnail</th>
                    <th>Informasi Kelas</th>
                    <th class="text-center">Limit</th>
                    <th class="text-center">Status</th>
                    <th class="text-right px-8">Aksi</th>
                </tr>
            </thead>
            <tbody id="classroom-data"></tbody>
        </table>
    </div>
</div>

<div id="modal-create" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('create')"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-10 md:p-14">
            <h3 class="text-2xl font-black text-slate-900 mb-8">Buat Ruang Kelas</h3>
            <form id="createClassForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
                <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Nama Kelas</label><input type="text" name="name" class="custom-input"></div>
                <div class="col-span-full">
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Visibilitas</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="statusClass" value="public" class="w-4 h-4 text-indigo-600" checked><span class="text-sm font-bold text-slate-700">Public</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="statusClass" value="private" class="w-4 h-4 text-indigo-600"><span class="text-sm font-bold text-slate-700">Private</span></label>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Kode Kelas</label>
                    <div class="flex gap-2">
                        <input type="text" id="codeClass" name="codeClass" class="custom-input text-indigo-600 font-black">
                        <div class="flex items-center gap-2 bg-slate-50 px-3 rounded-xl border border-slate-200 shrink-0"><input type="checkbox" id="autoGenerateCode" class="w-4 h-4 text-indigo-600 rounded cursor-pointer"><span class="text-[9px] font-black text-slate-500 uppercase">Auto</span></div>
                    </div>
                </div>
                <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Limit Siswa</label><input type="number" name="limit" class="custom-input" required></div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Semester</label>
                    <select name="semester_id" id="create-semester-select" class="custom-input font-bold" required>
                        <option value="">Pilih Semester</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Bobot SKS</label>
                    <input type="number" name="sks" min="1" max="8" value="3" class="custom-input" required>
                </div>
                <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Thumbnail</label><input type="file" name="thumbnail" class="custom-input" required></div>
                <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Deskripsi</label><textarea name="description" rows="3" class="custom-input" required></textarea></div>
                <div class="col-span-full flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal('create')" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl">Batal</button>
                    <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-600">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-edit" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('edit')"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-zoom-in">
        <div class="p-10 md:p-14">
            <h3 class="text-2xl font-black text-slate-900 mb-8">Update Kelas</h3>
            <form id="updateClassForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @method('PUT')
                <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
                <input type="hidden" id="editClassId" name="class_id">
                <div class="col-span-full flex justify-center"><img src="" id="img-thumbnail-edit" class="w-full max-h-40 object-cover rounded-2xl mb-4 border"></div>
                <div class="col-span-full">
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Visibilitas</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="statusClass" value="public" id="edit-status-public" class="w-4 h-4 text-indigo-600"><span class="text-sm font-bold text-slate-700">Public</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="statusClass" value="private" id="edit-status-private" class="w-4 h-4 text-indigo-600"><span class="text-sm font-bold text-slate-700">Private</span></label>
                    </div>
                </div>
                <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Nama Kelas</label><input type="text" id="edit-nama" name="name" class="custom-input font-bold"></div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Kode Kelas</label>
                    <div class="flex gap-2">
                        <input type="text" id="codeClass-edit" name="codeClass" class="custom-input text-indigo-600 font-black tracking-widest">
                        <div class="flex items-center gap-2 bg-slate-50 px-3 rounded-xl border border-slate-200 shrink-0"><input type="checkbox" id="autoGenerateCodeEdit" class="w-4 h-4 text-indigo-600 rounded cursor-pointer"><span class="text-[9px] font-black text-slate-500 uppercase">Auto</span></div>
                    </div>
                </div>
                <div><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Limit</label><input type="number" id="edit-limit" name="limit" class="custom-input" required></div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Semester</label>
                    <select name="semester_id" id="edit-semester-select" class="custom-input font-bold" required>
                        <option value="">Pilih Semester</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block ml-1 tracking-widest">Bobot SKS</label>
                    <input type="number" name="sks" id="edit-sks" min="1" max="8" class="custom-input" required>
                </div>
                <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Thumbnail (Opsional)</label><input type="file" name="thumbnail" class="custom-input"></div>
                <div class="col-span-full"><label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Deskripsi</label><textarea id="edit-description" name="description" rows="3" class="custom-input" required></textarea></div>
                <div class="col-span-full flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal('edit')" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl">Batal</button>
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl shadow-lg">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-delete" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete')"></div>
    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl animate-zoom-in">
        <div class="p-10 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2">Hapus Kelas?</h3>
            <p class="text-slate-500 font-medium mb-10">Data tidak dapat dikembalikan setelah dihapus.</p>
            <form id="form-delete-manual">
                <input type="hidden" id="deleteClassId">
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('delete')" class="flex-1 py-4 bg-slate-100 text-slate-600 font-black uppercase text-[10px] tracking-widest rounded-2xl">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-red-500 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-red-600">Ya, Hapus!</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function openModal(type) {
        $(`#modal-${type}`).removeClass('hidden').addClass('flex');
        setTimeout(() => $(`#modal-${type}`).addClass('modal-open'), 10);
    }
    function closeModal(type) {
        $(`#modal-${type}`).removeClass('modal-open');
        setTimeout(() => $(`#modal-${type}`).removeClass('flex').addClass('hidden'), 200);
    }

    function generateClassCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let res = '';
        for (let i = 0; i < 6; i++) res += chars.charAt(Math.floor(Math.random() * chars.length));
        return res;
    }

    $('#autoGenerateCode').on('change', function() {
        $('#codeClass').val(this.checked ? generateClassCode() : '');
    });

    $('#autoGenerateCodeEdit').on('change', function() {
        $('#codeClass-edit').val(this.checked ? generateClassCode() : '');
    });

    let allClassrooms = [];
    
    // Load semesters for selectors
    const loadSemesters = () => {
        fetch('/api/semesters')
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    const filterSelect = $('#filter-semester');
                    const createSelect = $('#create-semester-select');
                    const editSelect = $('#edit-semester-select');
                    
                    filterSelect.empty().append('<option value="all">Semua Semester</option>');
                    createSelect.empty().append('<option value="">Pilih Semester</option>');
                    editSelect.empty().append('<option value="">Pilih Semester</option>');
                    
                    res.data.forEach(sem => {
                        filterSelect.append(`<option value="${sem.id}">${sem.name}</option>`);
                        createSelect.append(`<option value="${sem.id}" ${sem.is_active ? 'selected' : ''}>${sem.name}</option>`);
                        editSelect.append(`<option value="${sem.id}">${sem.name}</option>`);
                    });
                }
            });
    };

    const fetchClassData = () => {
        $.ajax({
            url: `/api/classroom/teacher/data/{{ auth()->user()->id }}`,
            method: 'GET',
            success: function(res) {
                if (res.status === 'success') {
                    allClassrooms = res.data;
                    renderClassrooms();
                }
            }
        });
    };

    const renderClassrooms = () => {
        const selectedSemester = $('#filter-semester').val();
        let filtered = allClassrooms;
        
        if (selectedSemester && selectedSemester !== 'all') {
            filtered = allClassrooms.filter(k => k.semester_id == selectedSemester);
        }
        
        let rows = '';
        if (filtered.length > 0) {
            filtered.forEach((k, i) => {
                const safeDesc = k.description.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                const semesterName = k.semester ? k.semester.name : 'Belum ditentukan';
                rows += `
                <tr class="hover:bg-slate-50/80 transition-all duration-300">
                    <td class="px-8 py-6 text-center text-slate-400 font-black">${i + 1}</td>
                    <td class="px-6 py-6"><img src="/storage/${k.thumbnail}" class="w-24 h-14 object-cover rounded-xl border shadow-sm"></td>
                    <td class="px-6 py-6">
                        <div class="font-black text-slate-900 text-sm tracking-tight">${k.name}</div>
                        <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest mt-0.5">${k.codeClass} &bull; ${k.sks} SKS</div>
                        <div class="text-[9px] text-slate-400 font-medium mt-1">${semesterName}</div>
                    </td>
                    <td class="px-6 py-6 text-center text-slate-500 font-bold">${k.limit} Siswa</td>
                    <td class="px-6 py-6 text-center"><span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase ${k.status === 'accept' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600'}">${k.status}</span></td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex justify-end gap-2.5">
                            <a href="/teacher/classroom/course/${k.id}" class="p-3 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <button onclick="prepareEdit('${k.id}', '${k.codeClass}', '${k.name}', '${k.limit}', '${k.thumbnail}', '${safeDesc}', '${k.statusClass}', '${k.semester_id || ''}', '${k.sks || 3}')" class="p-3 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button onclick="prepareDelete('${k.id}')" class="p-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
        } else {
            rows = `<tr><td colspan="6" class="py-24 text-center text-slate-400 font-bold">Belum Ada Data Kelas</td></tr>`;
        }
        $('#classroom-data').html(rows);
    };

    $('#filter-semester').on('change', renderClassrooms);

    function prepareEdit(id, code, name, limit, thumb, desc, statusClass, semesterId, sks) {
        $('#editClassId').val(id); 
        $('#codeClass-edit').val(code); 
        $('#edit-nama').val(name); 
        $('#edit-limit').val(limit); 
        $('#edit-description').val(desc);
        $('#edit-semester-select').val(semesterId);
        $('#edit-sks').val(sks);
        $('#img-thumbnail-edit').attr('src', `/storage/${thumb}`);
        if (statusClass === 'public') $('#edit-status-public').prop('checked', true); else $('#edit-status-private').prop('checked', true);
        openModal('edit');
    }

    function prepareDelete(id) { $('#deleteClassId').val(id); openModal('delete'); }

    $('#createClassForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/classroom/teacher', type: 'POST', data: new FormData(this), processData: false, contentType: false,
            success: function() { toastr.success('Berhasil!'); closeModal('create'); $('#createClassForm')[0].reset(); fetchClassData(); }
        });
    });

    $('#updateClassForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/classroom/update/${$('#editClassId').val()}`, type: 'POST', data: new FormData(this), processData: false, contentType: false,
            success: function() { toastr.success('Diperbarui!'); closeModal('edit'); fetchClassData(); }
        });
    });

    $('#form-delete-manual').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/classroom/delete/${$('#deleteClassId').val()}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
            success: function() { toastr.success('Dihapus!'); closeModal('delete'); fetchClassData(); }
        });
    });

    $(document).ready(function() {
        loadSemesters();
        fetchClassData();
    });
</script>
@endsection