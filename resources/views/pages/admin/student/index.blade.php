@extends('layouts.admin.app')

@section('title', 'Kelola Mahasiswa — Admin Panel')
@section('page_title', 'Database Mahasiswa')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Database <span class="text-indigo-600">Siswa &amp; Mahasiswa</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Lihat dan moderasi seluruh siswa terdaftar di platform ACESSA</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Platform Users</span>
    </div>
</div>

{{-- Students Card Grid/Table --}}
<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm" data-aos="fade-up">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-900 text-sm">Daftar Akun Siswa</h3>
        <span id="student-count" class="text-xs text-slate-400 font-semibold">0 mahasiswa</span>
    </div>

    {{-- Loading --}}
    <div id="student-loader" class="py-20 flex flex-col items-center gap-3">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest animate-pulse">Memuat database...</p>
    </div>

    {{-- Empty state --}}
    <div id="student-empty" class="hidden py-16 text-center">
        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Belum Ada Mahasiswa</h4>
        <p class="text-slate-400 text-xs mt-1">Tidak ada akun mahasiswa yang terdaftar dalam database.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider hidden" id="student-table-header">
                <tr>
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Tanggal Daftar</th>
                    <th class="px-6 py-4 text-right px-8">Tindakan</th>
                </tr>
            </thead>
            <tbody id="student-list-body" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        loadStudents();
    });

    function loadStudents() {
        $('#student-loader').show();
        $('#student-table-header').addClass('hidden');
        $('#student-empty').addClass('hidden');
        $('#student-list-body').empty();

        $.ajax({
            url: '/api/student',
            method: 'GET',
            success: function(res) {
                $('#student-loader').hide();
                const list = res.data || [];
                $('#student-count').text(`${list.length} mahasiswa`);

                if (list.length === 0) {
                    $('#student-empty').removeClass('hidden');
                    return;
                }

                $('#student-table-header').removeClass('hidden');
                
                list.forEach((s, index) => {
                    const joinedDate = s.created_at ? new Date(s.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) : '—';
                    const avatar = s.image ? `/storage/${s.image}` : '/user.png';

                    $('#student-list-body').append(`
                        <tr id="student-row-${s.id}">
                            <td class="px-6 py-4 text-center text-slate-400 font-bold">${index + 1}</td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-indigo-50 border border-slate-100 shrink-0">
                                    <img src="${avatar}" class="w-full h-full object-cover" onerror="this.src='/user.png'">
                                </div>
                                <span class="font-extrabold text-slate-900">${s.name}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-mono font-medium">${s.email}</td>
                            <td class="px-6 py-4 text-slate-400 font-semibold">${joinedDate}</td>
                            <td class="px-6 py-4 text-right px-8">
                                <button onclick="deleteStudent(${s.id})" class="px-3 py-1.5 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white rounded-lg text-[10px] font-bold transition-all">
                                    Hapus Akun
                                </button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: () => {
                $('#student-loader').hide();
                $('#student-empty').removeClass('hidden');
            }
        });
    }

    function deleteStudent(studentId) {
        Swal.fire({
            title: 'Hapus Mahasiswa?',
            text: "Akun ini akan dihapus secara permanen dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/user/delete/${studentId}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Siswa berhasil dihapus!');
                        $(`#student-row-${studentId}`).remove();
                        // recalculate count
                        const currentCount = parseInt($('#student-count').text());
                        $('#student-count').text(`${currentCount - 1} mahasiswa`);
                    },
                    error: () => toastr.error('Gagal menghapus siswa.')
                });
            }
        });
    }
</script>
@endsection
