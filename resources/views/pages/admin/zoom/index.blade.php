@extends('layouts.admin.app')

@section('title', 'Audit Sesi Zoom — Admin Panel')
@section('page_title', 'Sesi Zoom Virtual')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Audit Sesi <span class="text-indigo-600">Zoom Virtual</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Memantau dan mengelola seluruh jadwal tatap muka virtual di platform</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Zoom Auditor</span>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm" data-aos="fade-up">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-900 text-sm">Daftar Jadwal Sesi Pertemuan</h3>
        <span id="zoom-count" class="text-xs text-slate-400 font-semibold">0 sesi</span>
    </div>

    {{-- Loader --}}
    <div id="zoom-loader" class="py-20 flex flex-col items-center gap-3">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest animate-pulse">Menghubungkan data...</p>
    </div>

    {{-- Empty --}}
    <div id="zoom-empty" class="hidden py-16 text-center">
        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
            <i data-feather="video" class="w-6 h-6"></i>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Belum Ada Sesi Rapat</h4>
        <p class="text-slate-400 text-xs mt-1">Tidak ada jadwal pertemuan Zoom yang terdaftar di database.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider hidden" id="zoom-table-header">
                <tr>
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Nama Sesi / Topik</th>
                    <th class="px-6 py-4">Materi Pembelajaran</th>
                    <th class="px-6 py-4">Waktu Mulai</th>
                    <th class="px-6 py-4 text-right px-8">Aksi</th>
                </tr>
            </thead>
            <tbody id="zoom-list-body" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        loadZoomMeetings();
    });

    function loadZoomMeetings() {
        $('#zoom-loader').show();
        $('#zoom-table-header').addClass('hidden');
        $('#zoom-empty').addClass('hidden');
        $('#zoom-list-body').empty();

        $.ajax({
            url: '/api/zoom/meetings',
            method: 'GET',
            success: function(res) {
                $('#zoom-loader').hide();
                const list = res.data || [];
                $('#zoom-count').text(`${list.length} sesi`);

                if (list.length === 0) {
                    $('#zoom-empty').removeClass('hidden');
                    feather.replace();
                    return;
                }

                $('#zoom-table-header').removeClass('hidden');
                
                list.forEach((m, index) => {
                    const scheduledTime = m.meeting_time ? new Date(m.meeting_time).toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'}) : '—';
                    const courseName = m.course ? m.course.name : '—';

                    $('#zoom-list-body').append(`
                        <tr id="zoom-row-${m.id}">
                            <td class="px-6 py-4 text-center text-slate-400 font-bold">${index + 1}</td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900 text-xs">${m.title}</div>
                                <div class="text-slate-400 text-[10px] font-semibold mt-0.5 max-w-[280px] truncate">${m.description || 'Tanpa deskripsi.'}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-lg font-bold">${courseName}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-semibold">${scheduledTime} WIB</td>
                            <td class="px-6 py-4 text-right px-8 flex items-center justify-end gap-2">
                                <a href="${m.zoom_link}" target="_blank" class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[10px] font-bold transition-all">
                                    Gabung Sesi
                                </a>
                                <button onclick="deleteZoomMeeting(${m.id})" class="px-2.5 py-1.5 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white rounded-lg text-[10px] font-bold transition-all">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `);
                });
                feather.replace();
            },
            error: () => {
                $('#zoom-loader').hide();
                $('#zoom-empty').removeClass('hidden');
                feather.replace();
            }
        });
    }

    function deleteZoomMeeting(id) {
        Swal.fire({
            title: 'Hapus Rapat?',
            text: "Jadwal kelas virtual zoom ini akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/zoom/session/delete/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Pertemuan Zoom berhasil dihapus!');
                        $(`#zoom-row-${id}`).remove();
                        const currentCount = parseInt($('#zoom-count').text());
                        $('#zoom-count').text(`${currentCount - 1} sesi`);
                    },
                    error: () => toastr.error('Gagal menghapus pertemuan.')
                });
            }
        });
    }
</script>
@endsection
