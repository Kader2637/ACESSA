@extends('layouts.admin.app')

@section('title', 'Audit Tugas Kuliah — Panel Admin')
@section('page_title', 'Audit Pengumpulan Tugas')

@section('style')
<style>
    .custom-table th { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; padding: 1.25rem 1rem; border: none; background: #f8fafc; }
    .custom-table td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: #1e293b; font-size: 12px; }
    .table-container { background: white; border-radius: 1.5rem; border: 1px solid #e2e8f0; overflow: hidden; }
</style>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm animate-fade-in">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Audit Tugas: <span class="text-indigo-650">{{ $taskCourse->name }}</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Memantau progres pengumpulan tugas mahasiswa secara real-time</p>
    </div>
    <a href="/admin/classroom/detail/course/{{ $taskCourse->course_id }}" class="px-4 py-2 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-slate-800 transition-all shadow-sm active:scale-[0.98] shrink-0 flex items-center gap-1.5">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Materi
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up">
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-left">
        <h5 class="text-[9px] font-black uppercase text-indigo-650 tracking-widest mb-3">Deskripsi &amp; Lampiran Tugas</h5>
        <p class="text-slate-650 font-medium leading-relaxed italic text-sm">"{{ $taskCourse->description || 'Tidak ada deskripsi instruksi.' }}"</p>
    </div>

    <div class="bg-slate-900 p-6 rounded-2xl text-white flex flex-col justify-center relative overflow-hidden shadow-sm">
        <h5 class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-3">Status Batas Waktu</h5>
        @php
            $deadline = \Carbon\Carbon::parse($taskCourse->deadline);
            $now = \Carbon\Carbon::now();
        @endphp

        @if ($now->greaterThan($deadline))
            <h3 class="text-lg font-extrabold text-red-400 tracking-tight leading-tight">Pengumpulan Selesai</h3>
            <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wide">Melewati deadline {{ $deadline->diffInDays($now) }} hari</p>
        @else
            <h3 class="text-lg font-extrabold text-emerald-400 tracking-tight leading-tight">Sedang Berlangsung</h3>
            <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wide">Sisa waktu: {{ $deadline->locale('id')->diffForHumans() }}</p>
        @endif
    </div>
</div>

<div class="space-y-8 mb-20" data-aos="fade-up" data-aos-delay="50">
    {{-- Sudah Mengumpulkan --}}
    <div>
        <div class="flex items-center gap-2 mb-3 px-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Siswa Sudah Mengumpulkan</h4>
        </div>
        <div class="table-container shadow-sm">
            <table class="w-full text-left custom-table">
                <thead>
                    <tr>
                        <th class="text-center w-20">No</th>
                        <th>Identitas Siswa</th>
                        <th class="text-center">Nilai Audit</th>
                    </tr>
                </thead>
                <tbody id="submitted-tbody">
                    <tr><td colspan="3" class="py-20 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest animate-pulse">Menghubungkan data pengumpulan...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Belum Mengumpulkan --}}
    <div>
        <div class="flex items-center gap-2 mb-3 px-2">
            <span class="w-2 h-2 rounded-full bg-slate-450"></span>
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Siswa Belum Mengumpulkan</h4>
        </div>
        <div class="table-container border border-dashed border-slate-300">
            <table class="w-full text-left custom-table">
                <thead>
                    <tr>
                        <th class="text-center w-20">No</th>
                        <th>Nama Siswa</th>
                        <th class="text-right px-10">Status Pengumpulan</th>
                    </tr>
                </thead>
                <tbody id="not-submitted-tbody">
                    <tr><td colspan="3" class="py-20 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest animate-pulse">Menghubungkan absensi kelas...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        const taskCourseId = "{{ $taskCourse->id }}";

        function loadSubmittedAssignments() {
            const tbody = $('#submitted-tbody');
            $.ajax({
                url: `/api/done/assigment/task/${taskCourseId}`,
                method: 'GET',
                success: function(res) {
                    tbody.empty();
                    if (res.data.length === 0) {
                        tbody.append('<tr><td colspan="3" class="py-16 text-center text-slate-400 font-bold uppercase text-[10px] tracking-wider">Belum ada pengumpulan tugas terdeteksi.</td></tr>');
                    } else {
                        res.data.forEach((item, i) => {
                            tbody.append(`
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="text-center text-slate-400 font-bold">${i + 1}</td>
                                    <td>
                                        <div class="font-extrabold text-slate-900">${item.name}</div>
                                        <div class="text-[9px] text-emerald-650 font-bold uppercase mt-1">Sudah Mengumpulkan</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="inline-block px-4 py-1.5 bg-slate-900 text-white rounded-lg font-bold text-xs">
                                            ${item.grade !== null ? item.grade : 'Belum Dinilai'}
                                        </div>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                }
            });
        }

        function loadNotSubmittedAssignments() {
            const tbody = $('#not-submitted-tbody');
            $.ajax({
                url: `/api/not/assigment/task/${taskCourseId}`,
                method: 'GET',
                success: function(res) {
                    tbody.empty();
                    if (res.data.length === 0) {
                        tbody.append('<tr><td colspan="3" class="py-16 text-center text-slate-400 font-bold uppercase text-[10px] tracking-wider">Seluruh siswa sudah menyelesaikan pengumpulan.</td></tr>');
                    } else {
                        res.data.forEach((item, i) => {
                            tbody.append(`
                                <tr>
                                    <td class="text-center text-slate-400 font-bold">${i + 1}</td>
                                    <td class="text-slate-500 font-bold italic">${item.name}</td>
                                    <td class="text-right px-10">
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">Menunggu</span>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                }
            });
        }

        loadSubmittedAssignments();
        loadNotSubmittedAssignments();
    });
</script>
@endsection