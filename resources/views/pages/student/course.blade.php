@extends('layouts.student.app')

@section('title', 'Detail Kelas — Portal Mahasiswa')
@section('page_title', 'Detail Kelas')

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
    <div class="relative h-[240px] rounded-3xl overflow-hidden shadow-sm border border-slate-200" data-aos="fade-down">
        <img id="class-thumbnail" class="w-full h-full object-cover" src="" onerror="this.onerror=null; this.src='/classaccesa.png';">
        <div class="absolute inset-0 banner-overlay"></div>
        <div class="absolute bottom-6 left-6 right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="px-2.5 py-1 bg-white border border-slate-200 text-indigo-650 font-bold text-[9px] uppercase tracking-wider rounded-lg mb-2 inline-block shadow-sm">Portal Kelas</span>
                <h2 id="title" class="text-2xl md:text-3xl font-extrabold text-white tracking-tight leading-tight truncate"></h2>
                <div class="flex items-center gap-2.5 mt-2">
                    <p class="text-slate-300 font-semibold text-xs truncate">Dosen Pengampu: <span id="nameTeacher-top" class="text-white font-extrabold"></span></p>
                </div>
            </div>
            <div class="shrink-0">
                <a href="/student/dashboard" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-850 font-bold text-xs rounded-xl shadow-sm transition-all active:scale-[0.98]">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- Tabs Menu --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-2 flex items-center gap-2 overflow-x-auto" data-aos="fade-up">
        <button onclick="switchTab('detail')" id="tab-detail" class="tab-btn active px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Ringkasan</button>
        <button onclick="switchTab('materi')" id="tab-materi" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Materi Kurikulum</button>
        <button onclick="switchTab('siswa')" id="tab-siswa" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Teman Sekelas</button>
        <button onclick="switchTab('forum')" id="tab-forum" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Forum Diskusi</button>
        <button onclick="switchTab('absensi')" id="tab-absensi" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Absensi Saya</button>
    </div>

    {{-- Tab Contents --}}
    <div id="main-content" data-aos="fade-up" data-aos-delay="50">
        
        {{-- Ringkasan --}}
        <div id="content-detail" class="tab-content block space-y-6">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-left">
                <h3 class="text-xs font-black uppercase text-indigo-650 tracking-wider mb-4">Informasi Ruang Kelas</h3>
                <p id="description_classroom" class="text-sm text-slate-650 font-medium leading-relaxed italic"></p>
                
                <div class="mt-8 flex items-center gap-3 p-4 bg-slate-50 border border-slate-200/50 rounded-2xl max-w-sm">
                    <img id="profile" class="w-10 h-10 rounded-lg object-cover border border-slate-200" src="/user.png" onerror="this.onerror=null; this.src='/user.png';">
                    <div>
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Dosen Pengajar</p>
                        <p id="nameTeacher" class="text-slate-900 font-extrabold text-xs"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kurikulum --}}
        <div id="content-materi" class="tab-content hidden">
            <div class="space-y-4" id="curriculum-list"></div>
        </div>

        {{-- Teman Sekelas --}}
        <div id="content-siswa" class="tab-content hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="student-list"></div>
        </div>

        {{-- Forum Diskusi --}}
        <div id="content-forum" class="tab-content hidden">
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden flex flex-col shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold uppercase text-slate-600 tracking-wider">Aktivitas Forum Diskusi</span>
                    </div>
                </div>
                <div id="kotak-pesan" class="chat-container overflow-y-auto p-6 space-y-4 bg-slate-50/30"></div>
                <form id="form-pesan" class="p-4 bg-white border-t border-slate-200 flex items-center gap-3">
                    <input type="text" name="message" id="input-pesan" class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-indigo-600 transition-all" placeholder="Tulis pesan diskusi di sini...">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="classroom_id" value="{{ $id }}">
                    <button type="submit" class="w-10 h-10 bg-indigo-650 text-white rounded-xl flex items-center justify-center hover:bg-indigo-700 transition-all active:scale-95 shrink-0">
                        <i data-feather="send" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Absensi Saya --}}
        <div id="content-absensi" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-left animate-fade-in">
                
                {{-- Form Input Kode --}}
                <div class="lg:col-span-1 bg-white border border-slate-200 p-6 rounded-3xl h-fit shadow-sm">
                    <h3 class="font-extrabold text-slate-900 text-sm mb-2">Input Kode Absensi</h3>
                    <p class="text-[10px] text-slate-400 font-bold mb-4 uppercase tracking-wider">Masukkan kode 6 digit dari Dosen</p>
                    <form id="form-submit-attendance-code" class="space-y-4">
                        <div>
                            <input type="text" id="attendance-code-input" class="w-full px-4 py-3 text-lg font-mono font-black text-center tracking-widest bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:border-indigo-600 outline-none uppercase" maxlength="6" placeholder="------" required>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all active:scale-[0.98]">
                            ⚡ Kirim Kehadiran
                        </button>
                    </form>
                </div>

                {{-- Riwayat Kehadiran --}}
                <div class="lg:col-span-2 bg-white border border-slate-200 overflow-hidden rounded-3xl shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-extrabold text-slate-900 text-sm">Kehadiran Saya</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 w-16 text-center">No</th>
                                    <th class="px-6 py-3.5">Nama Sesi / Pertemuan</th>
                                    <th class="px-6 py-3.5 text-center">Kode</th>
                                    <th class="px-6 py-3.5">Waktu Scan</th>
                                    <th class="px-6 py-3.5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="student-attendance-table" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
                        </table>
                    </div>
                    <div id="student-attendance-empty" class="py-12 text-center text-slate-400 text-xs font-semibold">
                        Belum ada riwayat absensi untuk kelas ini.
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    const classId = '{{ $id }}';
    const userId = {{ auth()->user()->id }};

    function switchTab(tab) {
        $('.tab-content').addClass('hidden');
        $(`#content-${tab}`).removeClass('hidden');
        $('.tab-btn').removeClass('active');
        $(`#tab-${tab}`).addClass('active');
        if(tab === 'forum') scrollChatBottom();
        if(tab === 'absensi') ambilDataAbsensi();
    }

    const ambilDataKelas = () => {
        $.ajax({
            url: `/api/student/classroom/show/${classId}`,
            method: 'GET',
            success: function(res) {
                if (res.status === "success") {
                    const data = res.data;
                    $('#title').text(data.name);
                    $('#description_classroom').text(data.description);
                    $('#class-thumbnail').attr('src', (data.thumbnail && data.thumbnail !== 'default.png') ? `{{ asset('storage') }}/${data.thumbnail}` : '/classaccesa.png');
                    $('#nameTeacher, #nameTeacher-top').text(data.user.name);
                    $('#profile').attr('src', data.user.profile ? `/storage/${data.user.profile}` : '/user.png');
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
                const list = $('#curriculum-list');
                list.empty();
                if (res.status && res.data.length > 0) {
                    res.data.forEach((item, index) => {
                        list.append(`
                            <a href="/student/course/detail/${item.id}" class="curriculum-item group block bg-white border border-slate-200 p-4 rounded-xl transition-all shadow-sm flex items-center justify-between text-left hover:border-indigo-600">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-9 h-9 bg-indigo-50 border border-indigo-100 text-indigo-650 rounded-lg flex items-center justify-center shrink-0 font-extrabold text-xs">${index + 1}</div>
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-extrabold text-slate-900 truncate leading-snug group-hover:text-indigo-650 transition-colors">${item.name}</h5>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Materi Kuliah</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-indigo-650 group-hover:border-indigo-650 group-hover:text-white transition-all shrink-0">
                                    <i data-feather="chevron-right" class="w-4 h-4"></i>
                                </div>
                            </a>`);
                    });
                    feather.replace();
                } else {
                    list.html(`
                        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Belum ada kurikulum materi yang diunggah.</p>
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
                const list = $('#student-list');
                list.empty();
                if (res.status && res.data.length > 0) {
                    res.data.forEach(s => {
                        const img = s.profile && s.profile !== 'user.png' && s.profile !== 'default.png' ? `/storage/${s.profile}` : '/user.png';
                        list.append(`
                            <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center gap-3 shadow-sm min-w-0">
                                <img src="${img}" class="w-10 h-10 rounded-lg object-cover border border-slate-100 shrink-0" onerror="this.onerror=null; this.src='/user.png';">
                                <div class="overflow-hidden text-left">
                                    <p class="text-xs font-extrabold text-slate-800 truncate leading-none mb-1">${s.name}</p>
                                    <p class="text-[9px] font-bold text-slate-450 truncate">${s.email}</p>
                                </div>
                            </div>`);
                    });
                } else {
                    list.html(`
                        <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white rounded-2xl border border-dashed border-slate-200 w-full">
                            <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Belum ada teman sekelas terdaftar.</p>
                        </div>
                    `);
                }
            }
        });
    };

    // Chat Logic
    const urlAmbilPesan = `/api/forum/discussion/${classId}`;
    const urlKirimPesan = `/api/forum/discussion`;
    let lastMessageId = 0;

    const scrollChatBottom = () => {
        const box = $('#kotak-pesan');
        box.scrollTop(box.prop('scrollHeight'));
    };

    function ambilPesan() {
        const box = $('#kotak-pesan');
        $.ajax({
            url: `${urlAmbilPesan}?last_message_id=${lastMessageId}`,
            type: 'GET',
            success: function(res) {
                if (res.status === "success" && res.data.length > 0) {
                    res.data.forEach(msg => {
                        if ($(`[data-message-id="${msg.id}"]`).length) return;
                        const isMe = msg.user_id == userId;
                        const align = isMe ? 'flex-row-reverse' : 'flex-row';
                        const bg = isMe ? 'bg-indigo-650 text-white rounded-br-none' : 'bg-slate-100 text-slate-900 rounded-bl-none';
                        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const img = msg.user_image && msg.user_image !== 'user.png' && msg.user_image !== 'default.png' ? `/storage/${msg.user_image}` : '/user.png';

                        box.append(`
                            <div class="flex ${align} items-end gap-3 text-left" data-message-id="${msg.id}">
                                <img src="${img}" class="w-7 h-7 rounded-full object-cover mb-1 border border-slate-100 shadow-sm shrink-0" onerror="this.onerror=null; this.src='/user.png';">
                                <div class="msg-bubble flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                                    <div class="px-4 py-2.5 rounded-xl ${bg} shadow-sm">
                                        <p class="text-[8px] font-black opacity-60 uppercase tracking-widest mb-0.5">${isMe ? 'Anda' : msg.user_name}</p>
                                        <p class="text-xs font-semibold leading-relaxed">${msg.message}</p>
                                    </div>
                                    <span class="text-[8px] font-bold text-slate-400 mt-0.5 mx-2 uppercase">${time}</span>
                                </div>
                            </div>`);
                        lastMessageId = msg.id;
                    });
                    scrollChatBottom();
                }
            }
        });
    }

    $('#form-pesan').submit(function(e) {
        e.preventDefault();
        const msg = $('#input-pesan').val();
        if(!msg) return;
        $.ajax({
            url: urlKirimPesan,
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#input-pesan').val('');
                ambilPesan();
            }
        });
    });

    const ambilDataAbsensi = () => {
        const table = $('#student-attendance-table');
        table.empty();
        $('#student-attendance-empty').addClass('hidden');

        $.ajax({
            url: `/api/classroom/${classId}/attendance/my`,
            method: 'GET',
            success: function(res) {
                const data = res.data || [];
                if (data.length === 0) {
                    $('#student-attendance-empty').removeClass('hidden');
                    return;
                }

                data.forEach((s, index) => {
                    let statusBadge = '';
                    if (s.status === 'Hadir') {
                        statusBadge = `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold">Hadir</span>`;
                    } else if (s.status === 'Tidak Hadir') {
                        statusBadge = `<span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold">Tidak Hadir</span>`;
                    } else {
                        statusBadge = `<span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-bold animate-pulse">Belum Absen</span>`;
                    }

                    const timeStr = s.scanned_at ? s.scanned_at : '—';

                    table.append(`
                        <tr>
                            <td class="px-6 py-4 text-center text-slate-400">${index + 1}</td>
                            <td class="px-6 py-4 text-slate-900">${s.title}</td>
                            <td class="px-6 py-4 text-center font-mono font-bold text-slate-700 bg-slate-50/50 rounded-lg">${s.code}</td>
                            <td class="px-6 py-4 text-slate-500">${timeStr}</td>
                            <td class="px-6 py-4 text-center">${statusBadge}</td>
                        </tr>
                    `);
                });
            },
            error: () => $('#student-attendance-empty').removeClass('hidden')
        });
    };

    // Manual input code submit
    $('#form-submit-attendance-code').submit(function(e) {
        e.preventDefault();
        const code = $('#attendance-code-input').val();
        if (!code || code.length !== 6) {
            toastr.error('Masukkan 6 digit kode absensi dengan benar.');
            return;
        }

        $.ajax({
            url: '/api/attendance/code',
            method: 'POST',
            data: {
                code: code,
                classroom_id: classId
            },
            success: function(res) {
                toastr.success(res.message);
                $('#attendance-code-input').val('');
                ambilDataAbsensi();
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal mengirim absensi.';
                toastr.error(msg);
            }
        });
    });

    $(document).ready(function() {
        ambilDataKelas();
        setInterval(ambilPesan, 3000);
    });
</script>
@endsection