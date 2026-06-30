@extends('layouts.admin.app')

@section('title', 'Tahun & Semester — Admin Panel')
@section('page_title', 'Tahun Akademik & Semester')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Pengaturan <span class="text-indigo-600">Tahun &amp; Semester</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Mengelola semester aktif dan kalender akademik platform pembelajaran</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Academic Calendar</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start" data-aos="fade-up">
    
    {{-- Left Form: Add Semester --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <h3 class="font-extrabold text-slate-900 text-sm mb-4">Tambah Tahun Akademik</h3>
        <form id="frm-add-semester" class="flex flex-col gap-4">
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Nama Semester / Tahun</label>
                <input type="text" id="semester-name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-300" placeholder="Contoh: Ganjil 2026/2027" required>
            </div>
            
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Status Keaktifan</label>
                <select id="semester-status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md transition-all active:scale-[0.98]">
                Simpan Semester Baru
            </button>
        </form>
    </div>

    {{-- Right Table: Semesters List --}}
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-slate-900 text-sm">Kalender Semester Terdaftar</h4>
            <span class="text-xs text-slate-400 font-semibold" id="semester-count">2 Semester</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Tahun Akademik / Semester</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right px-8">Tindakan</th>
                    </tr>
                </thead>
                <tbody id="semester-list-body" class="divide-y divide-slate-50 text-slate-700 font-semibold">
                    <tr>
                        <td class="px-6 py-4 text-center text-slate-400 font-bold">1</td>
                        <td class="px-6 py-4 font-extrabold text-slate-900">Genap 2025/2026</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-bold rounded-md">Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-right px-8">
                            <button class="text-slate-400 hover:text-indigo-600 font-bold text-[10px] uppercase transition-colors mr-3">Nonaktifkan</button>
                            <button class="text-red-500 hover:text-red-700 font-bold text-[10px] uppercase transition-colors">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-center text-slate-400 font-bold">2</td>
                        <td class="px-6 py-4 font-extrabold text-slate-900">Ganjil 2025/2026</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-500 text-[10px] font-bold rounded-md">Tidak Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-right px-8">
                            <button class="text-indigo-600 hover:text-indigo-800 font-bold text-[10px] uppercase transition-colors mr-3">Aktifkan</button>
                            <button class="text-red-500 hover:text-red-700 font-bold text-[10px] uppercase transition-colors">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#frm-add-semester').on('submit', function(e) {
            e.preventDefault();
            const name = $('#semester-name').val();
            const status = $('#semester-status').val();

            // Append to table dynamically for mock demo
            const count = $('#semester-list-body tr').length + 1;
            const statusBadge = status === "1" ? '<span class="px-2 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-bold rounded-md">Aktif</span>' : '<span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-500 text-[10px] font-bold rounded-md">Tidak Aktif</span>';
            const actionBtn = status === "1" ? '<button class="text-slate-400 hover:text-indigo-600 font-bold text-[10px] uppercase transition-colors mr-3">Nonaktifkan</button>' : '<button class="text-indigo-600 hover:text-indigo-800 font-bold text-[10px] uppercase transition-colors mr-3">Aktifkan</button>';

            $('#semester-list-body').append(`
                <tr class="animate-fade-in">
                    <td class="px-6 py-4 text-center text-slate-400 font-bold">${count}</td>
                    <td class="px-6 py-4 font-extrabold text-slate-900">${name}</td>
                    <td class="px-6 py-4">${statusBadge}</td>
                    <td class="px-6 py-4 text-right px-8">
                        ${actionBtn}
                        <button class="text-red-500 hover:text-red-700 font-bold text-[10px] uppercase transition-colors" onclick="$(this).closest('tr').remove(); updateCount();">Hapus</button>
                    </td>
                </tr>
            `);

            $('#semester-name').val('');
            toastr.success('Semester baru berhasil disimpan!');
            updateCount();
        });
    });

    function updateCount() {
        const count = $('#semester-list-body tr').length;
        $('#semester-count').text(`${count} Semester`);
    }
</script>
@endsection
