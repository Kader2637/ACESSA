@extends('layouts.admin.app')

@section('title', 'Audit Forum Diskusi — Admin Panel')
@section('page_title', 'Moderasi Forum Diskusi')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Moderasi <span class="text-indigo-600">Forum Diskusi</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Memantau dan menyaring seluruh pesan diskusi antar pengguna platform</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Forum Moderator</span>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm" data-aos="fade-up">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-900 text-sm">Semua Pesan Forum</h3>
        <span id="forum-count" class="text-xs text-slate-400 font-semibold">0 pesan</span>
    </div>

    {{-- Loader --}}
    <div id="forum-loader" class="py-20 flex flex-col items-center gap-3">
        <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest animate-pulse">Mengambil data forum...</p>
    </div>

    {{-- Empty --}}
    <div id="forum-empty" class="hidden py-16 text-center">
        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
            <i data-feather="message-square" class="w-6 h-6"></i>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Belum Ada Pesan</h4>
        <p class="text-slate-400 text-xs mt-1">Belum ada aktivitas pesan di forum diskusi saat ini.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider hidden" id="forum-table-header">
                <tr>
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Pengirim</th>
                    <th class="px-6 py-4">Nama Kelas</th>
                    <th class="px-6 py-4">Isi Pesan</th>
                    <th class="px-6 py-4">Tanggal Kirim</th>
                    <th class="px-6 py-4 text-right px-8">Aksi</th>
                </tr>
            </thead>
            <tbody id="forum-list-body" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        loadForumDiscussions();
    });

    function loadForumDiscussions() {
        $('#forum-loader').show();
        $('#forum-table-header').addClass('hidden');
        $('#forum-empty').addClass('hidden');
        $('#forum-list-body').empty();

        $.ajax({
            url: '/api/forum/discussions',
            method: 'GET',
            success: function(res) {
                $('#forum-loader').hide();
                const list = res.data || [];
                $('#forum-count').text(`${list.length} pesan`);

                if (list.length === 0) {
                    $('#forum-empty').removeClass('hidden');
                    feather.replace();
                    return;
                }

                $('#forum-table-header').removeClass('hidden');
                
                list.forEach((d, index) => {
                    const postTime = d.created_at ? new Date(d.created_at).toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'}) : '—';

                    $('#forum-list-body').append(`
                        <tr id="forum-row-${d.id}">
                            <td class="px-6 py-4 text-center text-slate-400 font-bold">${index + 1}</td>
                            <td class="px-6 py-4 font-extrabold text-slate-900">${d.user_name}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-lg font-bold">${d.classroom_name}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-[320px] font-medium leading-relaxed break-words">${d.message}</td>
                            <td class="px-6 py-4 text-slate-450 font-semibold">${postTime} WIB</td>
                            <td class="px-6 py-4 text-right px-8">
                                <button onclick="deleteForumMessage(${d.id})" class="px-3 py-1.5 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white rounded-lg text-[10px] font-bold transition-all">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `);
                });
                feather.replace();
            },
            error: () => {
                $('#forum-loader').hide();
                $('#forum-empty').removeClass('hidden');
                feather.replace();
            }
        });
    }

    function deleteForumMessage(id) {
        Swal.fire({
            title: 'Hapus Pesan?',
            text: "Pesan forum diskusi ini akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/forum/discussion/delete/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Pesan forum berhasil dihapus!');
                        $(`#forum-row-${id}`).remove();
                        const currentCount = parseInt($('#forum-count').text());
                        $('#forum-count').text(`${currentCount - 1} pesan`);
                    },
                    error: () => toastr.error('Gagal menghapus pesan forum.')
                });
            }
        });
    }
</script>
@endsection
