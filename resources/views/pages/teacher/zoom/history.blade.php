@extends('layouts.teacher.app')

@section('title', 'Riwayat Zoom — Teacher Panel')
@section('page_title', 'Riwayat Zoom')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4" data-aos="fade-up">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat <span class="text-indigo-600">Zoom Meeting</span></h1>
        <p class="text-slate-400 text-sm font-medium mt-1">Rekap semua sesi meeting Zoom yang pernah dilakukan</p>
    </div>
    <button onclick="openQuickZoomModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-indigo-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        Mulai Sesi Baru
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" data-aos="fade-up" data-aos-delay="50">
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Total Sesi</p>
        <p class="text-3xl font-extrabold text-slate-900" id="totalMeet">—</p>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Bulan Ini</p>
        <p class="text-3xl font-extrabold text-emerald-600" id="meetThisMonth">—</p>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <div class="w-9 h-9 bg-cyan-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Kelas Berbeda</p>
        <p class="text-3xl font-extrabold text-cyan-600" id="uniqueClass">—</p>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <div class="w-9 h-9 bg-violet-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Sesi Terbaru</p>
        <p class="text-lg font-extrabold text-violet-600 leading-tight" id="lastMeet">—</p>
    </div>
</div>

{{-- Zoom History Table --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h3 class="font-extrabold text-slate-900 text-sm">Daftar Sesi Zoom</h3>
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input id="meetSearch" type="text" placeholder="Cari meeting..." class="pl-8 pr-4 py-2 text-xs font-medium border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
        </div>
    </div>

    <div id="zoomLoading" class="py-20 flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Memuat riwayat zoom...</p>
    </div>

    <div id="zoomEmpty" class="hidden py-20 text-center">
        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-slate-700 font-extrabold">Belum Ada Riwayat Zoom</p>
        <p class="text-slate-400 text-sm mt-1">Mulai sesi Zoom pertama Anda</p>
        <a href="{{ route('zoom.session') }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 transition-all">
            Mulai Sekarang
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100 hidden" id="zoomTableHead">
                <tr>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">#</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Topik / Judul</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Kelas</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Meeting ID</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Tanggal</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                    <th class="text-left px-6 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Aksi</th>
                </tr>
            </thead>
            <tbody id="zoomBody" class="divide-y divide-slate-50"></tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    const authId = '{{ auth()->user()->id }}';
    let allMeetings = [];

    // Try to fetch zoom meetings - we'll try per classroom
    // First fetch classrooms, then fetch meetings per class
    $.ajax({
        url: `/api/my/classroom/teacher/data/${authId}`,
        method: 'GET',
        success: function(res) {
            const classes = res.data || [];
            if (classes.length === 0) {
                $('#zoomLoading').hide();
                $('#zoomEmpty').removeClass('hidden');
                return;
            }

            let fetchCount = 0;
            classes.forEach(kelas => {
                $.ajax({
                    url: `/api/zoom-meetings/${kelas.id}`,
                    method: 'GET',
                    success: function(mRes) {
                        const meetings = (mRes.data || mRes || []).map(m => ({...m, classroom_name: kelas.name, classroom_id: kelas.id}));
                        allMeetings = [...allMeetings, ...meetings];
                    },
                    complete: function() {
                        fetchCount++;
                        if (fetchCount === classes.length) {
                            allMeetings.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                            renderMeetings(allMeetings);
                            updateStats(allMeetings);
                            $('#zoomLoading').hide();
                        }
                    }
                });
            });
        },
        error: function() {
            $('#zoomLoading').hide();
            $('#zoomEmpty').removeClass('hidden');
        }
    });

    function renderMeetings(meetings) {
        const tbody = $('#zoomBody');
        tbody.empty();

        if (meetings.length === 0) {
            $('#zoomTableHead').addClass('hidden');
            $('#zoomEmpty').removeClass('hidden');
            return;
        }

        $('#zoomTableHead').removeClass('hidden');
        $('#zoomEmpty').addClass('hidden');

        meetings.forEach((m, i) => {
            const date = m.created_at ? new Date(m.created_at).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) : '—';
            const status = m.status === 'ended' || m.deleted_at
                ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Selesai</span>`
                : `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-bold border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Aktif</span>`;

            tbody.append(`
                <tr class="hover:bg-slate-50/50 transition-colors meeting-row" data-topic="${(m.topic||m.title||'').toLowerCase()}">
                    <td class="px-6 py-4 text-slate-400 font-bold text-xs">${i+1}</td>
                    <td class="px-6 py-4">
                        <p class="font-extrabold text-slate-900 text-sm">${m.topic || m.title || 'Sesi Zoom'}</p>
                        <p class="text-slate-400 text-xs mt-0.5">${m.agenda || 'Tidak ada deskripsi'}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-700 text-xs px-2.5 py-1 bg-slate-100 rounded-lg">${m.classroom_name || '—'}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs text-slate-600 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">${m.meeting_number || m.meeting_id || '—'}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs font-semibold">${date}</td>
                    <td class="px-6 py-4">${status}</td>
                    <td class="px-6 py-4">
                        ${m.meeting_number ? `
                        <a href="/zoom-session?meeting_number=${m.meeting_number}&passcode=${m.passcode || ''}&role=1&course_id=${m.classroom_id || ''}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs rounded-xl transition-all">
                            Buka
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>` : '<span class="text-slate-300 text-xs">—</span>'}
                    </td>
                </tr>
            `);
        });
    }

    function updateStats(meetings) {
        const now = new Date();
        const thisMonth = meetings.filter(m => {
            const d = new Date(m.created_at);
            return d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear();
        }).length;

        const uniqueClasses = new Set(meetings.map(m => m.classroom_id)).size;
        const lastDate = meetings.length > 0 ? new Date(meetings[0].created_at).toLocaleDateString('id-ID', {day:'2-digit',month:'short'}) : '—';

        $('#totalMeet').text(meetings.length);
        $('#meetThisMonth').text(thisMonth);
        $('#uniqueClass').text(uniqueClasses);
        $('#lastMeet').text(lastDate);
    }

    $('#meetSearch').on('input', function() {
        const q = $(this).val().toLowerCase();
        $('.meeting-row').each(function() {
            $(this).toggle($(this).data('topic').includes(q));
        });
    });
});
</script>
@endsection
