@extends('layouts.admin.app')

@section('title', 'Detail Kelas — Panel Admin')
@section('page_title', 'Audit Ruang Kelas')

@section('style')
<style>
    .tab-btn { position: relative; transition: all 0.2s ease; }
    .tab-btn.active { color: #4f46e5; border-bottom: 2px solid #4f46e5; }
    .chat-container { height: 500px; scrollbar-width: thin; }
    .msg-bubble { max-width: 85%; }
    .msg-left .bubble-content { background: #f1f5f9; color: #1e293b; border-radius: 1rem 1rem 1rem 0; }
    .msg-right .bubble-content { background: #4f46e5; color: white; border-radius: 1rem 1rem 0 1rem; }
    .banner-overlay { background: rgba(15, 23, 42, 0.7); }
</style>
@endsection

@section('content')
<div class="flex flex-col gap-6">
    
    {{-- Header Banner (Flat Overlay, No Gradients) --}}
    <div class="relative h-[280px] rounded-3xl overflow-hidden shadow-sm border border-slate-200" data-aos="fade-down">
        <img id="class-thumbnail" class="w-full h-full object-cover" src="" onerror="this.onerror=null; this.src='/classaccesa.png';">
        <div class="absolute inset-0 banner-overlay"></div>
        <div class="absolute bottom-6 left-6 right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="px-2.5 py-1 bg-white border border-slate-200 text-indigo-650 font-bold text-[9px] uppercase tracking-wider rounded-lg mb-2 inline-block shadow-sm">Status Audit</span>
                <h2 id="title" class="text-2xl md:text-3xl font-extrabold text-white tracking-tight leading-tight truncate"></h2>
                <div class="flex items-center gap-2.5 mt-2">
                    <img id="profileUser" class="w-6 h-6 rounded-md border border-white/20 object-cover shrink-0" src="" onerror="this.onerror=null; this.src='/user.png';">
                    <p class="text-slate-300 font-semibold text-xs truncate">Dosen Pengampu: <span id="nameTeacher" class="text-white"></span></p>
                </div>
            </div>
            <div class="shrink-0 flex gap-2">
                <a href="/admin/classroom" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-bold text-xs rounded-xl shadow-sm transition-all active:scale-[0.98]"> Kembali </a>
            </div>
        </div>
    </div>

    {{-- Tabs Menu --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-2 flex items-center gap-2 overflow-x-auto" data-aos="fade-up">
        <button onclick="showContent('detail')" id="tab-detail" class="tab-btn active px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Ringkasan</button>
        <button onclick="showContent('materi')" id="tab-materi" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Daftar Materi</button>
        <button onclick="showContent('siswa')" id="tab-siswa" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Siswa Terdaftar</button>
        <button onclick="showContent('discussion')" id="tab-discussion" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Forum Diskusi</button>
    </div>

    {{-- Tab Contents --}}
    <div id="main-content" data-aos="fade-up" data-aos-delay="50">
        
        {{-- Ringkasan --}}
        <div id="detail-content" class="content-pane space-y-6">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black uppercase text-indigo-650 tracking-wider mb-4">Deskripsi Ruang Kelas</h3>
                <p id="description_classroom" class="text-sm text-slate-650 font-medium leading-relaxed italic"></p>
            </div>
        </div>

        {{-- Kurikulum --}}
        <div id="materi-content" class="content-pane hidden">
            <div id="curriculum-list" class="grid grid-cols-1 gap-4"></div>
        </div>

        {{-- Siswa --}}
        <div id="siswa-content" class="content-pane hidden">
            <div id="student-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"></div>
        </div>

        {{-- Diskusi --}}
        <div id="discussion-content" class="content-pane hidden">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold uppercase text-slate-600 tracking-wider">Aktivitas Forum Diskusi</span>
                    </div>
                </div>
                <div id="kotak-pesan" class="chat-container overflow-y-auto p-6 space-y-4 bg-slate-50/30"></div>
                <form id="form-pesan" class="p-4 bg-white border-t border-slate-200 flex items-center gap-3">
                    <input type="text" name="message" id="input-pesan" class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-indigo-600 transition-all" placeholder="Kirim pesan moderasi ke kelas...">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="classroom_id" value="{{ $id }}">
                    <button type="submit" class="w-10 h-10 bg-indigo-650 text-white rounded-xl flex items-center justify-center hover:bg-indigo-700 transition-all active:scale-95 shrink-0">
                        <i data-feather="send" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Kick Student --}}
<div id="modal-kick" class="fixed inset-0 z-[1000] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeKickModal()"></div>
    <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl p-8 text-center border border-slate-200 animate-zoom-in">
        <h3 class="text-lg font-extrabold text-slate-900 mb-1">Keluarkan Siswa?</h3>
        <p class="text-slate-550 font-semibold mb-6 text-xs leading-relaxed">Siswa ini akan segera kehilangan akses ke modul ini.</p>
        <form id="form-kick-student-manual">
            <input type="hidden" id="kickIdStudent" name="student_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeKickModal()" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-650 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">Keluarkan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function showContent(paneId) {
        $('.content-pane').addClass('hidden');
        $('.tab-btn').removeClass('active');
        $(`#${paneId}-content`).removeClass('hidden').addClass('animate-fade-in');
        $(`#tab-${paneId}`).addClass('active');
        if(paneId === 'discussion') scrollChatBottom();
    }

    window.openKickModal = function(studentId) {
        $('#kickIdStudent').val(studentId);
        $(`#modal-kick`).removeClass('hidden').addClass('flex');
    };

    window.closeKickModal = function() {
        $('#modal-kick').removeClass('flex').addClass('hidden');
    };

    $('#form-kick-student-manual').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/kick/student/${$('#kickIdStudent').val()}`,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                toastr.success('Akses siswa telah dicabut');
                closeKickModal();
                ambilDataSiswa();
            }
        });
    });

    const classId = '{{ $id }}';
    const userId = '{{ auth()->user()->id }}';

    const ambilDataKelas = () => {
        $.ajax({
            url: `/api/student/classroom/show/${classId}`,
            method: 'GET',
            success: function(res) {
                if (res.status === "success") {
                    const data = res.data;
                    $('#title').text(data.name);
                    $('#description_classroom').text(data.description);
                    $('#class-thumbnail').attr('src', (data.thumbnail && data.thumbnail !== 'default.png') ? `/storage/${data.thumbnail}` : '/classaccesa.png');
                    $('#profileUser').attr('src', data.user.profile ? `/storage/${data.user.profile}` : '/user.png');
                    $('#nameTeacher').text(data.user.name);
                    ambilDataMateri();
                    ambilDataSiswa();
                }
            }
        });
    };

    const ambilDataMateri = () => {
        $.ajax({
            url: `/api/student/course/data/${classId}`,
            method: 'GET',
            success: function(res) {
                const list = $('#curriculum-list'); list.empty();
                if (res.data.length > 0) {
                    res.data.forEach(m => {
                        list.append(`
                            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-indigo-600 transition-all text-left">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-9 h-9 bg-indigo-50 border border-indigo-100 text-indigo-650 rounded-lg flex items-center justify-center shrink-0">
                                        <i data-feather="book-open" class="w-4.5 h-4.5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-extrabold text-slate-900 truncate">${m.name}</h5>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase truncate">${m.description ? m.description.substring(0, 50) + '...' : 'Tanpa deskripsi.'}</p>
                                    </div>
                                </div>
                                <a href="/admin/classroom/detail/course/${m.id}" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg font-bold text-[10px] uppercase transition-all shadow-sm shrink-0">Inspeksi</a>
                            </div>
                        `);
                    });
                    feather.replace();
                } else {
                    list.html(`
                        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Materi Kurikulum Belum Diunggah</p>
                        </div>
                    `);
                }
            }
        });
    };

    const ambilDataSiswa = () => {
        $.ajax({
            url: `/api/student/data/classroom/${classId}`,
            method: 'GET',
            success: function(res) {
                const list = $('#student-list'); list.empty();
                if (res.data.length > 0) {
                    res.data.forEach(s => {
                        const img = s.profile ? `/storage/${s.profile}` : '/user.png';
                        list.append(`
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center flex flex-col items-center group min-w-0">
                                <img src="${img}" class="w-16 h-16 rounded-xl object-cover mb-3 border border-slate-100 shadow-sm shrink-0" onerror="this.src='/user.png'">
                                <h6 class="text-xs font-extrabold text-slate-900 truncate w-full leading-snug">${s.name}</h6>
                                <p class="text-[9px] text-slate-400 font-semibold mb-4 truncate w-full px-2">${s.email}</p>
                                <button onclick="openKickModal(${s.id_relation})" class="w-full py-2 bg-red-50 hover:bg-red-500 text-red-650 hover:text-white rounded-xl font-bold text-[10px] uppercase transition-all shadow-sm">Keluarkan</button>
                            </div>
                        `);
                    });
                } else {
                    list.html(`
                        <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white rounded-2xl border border-dashed border-slate-200 w-full">
                            <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Belum Ada Siswa Terdaftar Di Kelas Ini</p>
                        </div>
                    `);
                }
            }
        });
    };

    let lastMessageId = 0;
    const scrollChatBottom = () => {
        const box = $('#kotak-pesan');
        box.scrollTop(box.prop('scrollHeight'));
    };

    function fetchMessages() {
        $.ajax({
            url: `/api/forum/discussion/${classId}?last_message_id=${lastMessageId}`,
            method: 'GET',
            success: function(res) {
                if (res.status === "success" && res.data.length > 0) {
                    res.data.forEach(msg => {
                        if ($(`[data-message-id="${msg.id}"]`).length) return;
                        const isMe = msg.user_id == userId;
                        const alignClass = isMe ? 'msg-right flex-row-reverse' : 'msg-left';
                        const img = msg.user_image ? `/storage/${msg.user_image}` : '/user.png';
                        $('#kotak-pesan').append(`
                            <div class="msg flex ${alignClass} items-end gap-3 text-left" data-message-id="${msg.id}">
                                <img src="${img}" class="w-7 h-7 rounded-full object-cover mb-1 border border-slate-100 shadow-sm shrink-0" onerror="this.src='/user.png'">
                                <div class="msg-bubble flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                                    <div class="px-4 py-2.5 bubble-content shadow-sm">
                                        <p class="text-[8px] font-black uppercase tracking-wider mb-0.5 opacity-60">${isMe ? 'Anda' : msg.user_name}</p>
                                        <p class="text-xs font-semibold leading-relaxed">${msg.message}</p>
                                    </div>
                                    <span class="text-[8px] font-bold text-slate-400 mt-0.5 mx-2 uppercase">${new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</span>
                                </div>
                            </div>
                        `);
                        lastMessageId = msg.id;
                    });
                    scrollChatBottom();
                }
            }
        });
    }

    $('#form-pesan').submit(function(e) {
        e.preventDefault();
        if(!$('#input-pesan').val()) return;
        $.ajax({
            url: '/api/forum/discussion',
            method: 'POST',
            data: $(this).serialize(),
            success: function() { $('#input-pesan').val(''); fetchMessages(); }
        });
    });

    $(document).ready(function() {
        ambilDataKelas();
        setInterval(fetchMessages, 3000);
    });
</script>
@endsection