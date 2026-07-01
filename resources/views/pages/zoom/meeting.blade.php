<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ACESSA - Virtual Classroom</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/zoom-meeting-sdk-3.8.5.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0; padding: 0;
            width: 100%; height: 100%;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f3f9;
            color: #0f172a;
        }
        #app-wrapper {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        /* ── LEFT: Zoom panel ── */
        #zoom-section {
            flex: 1 1 0%;
            position: relative;
            background: #e2e8f0;
            min-width: 0;
            height: 100%;
            overflow: hidden;
        }
        #zoom-lobby {
            position: absolute; inset: 0; z-index: 40;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 2rem;
            background: #f1f3f9;
            overflow-y: auto;
        }
        #zoom-lobby.hidden { display: none !important; }
        #zoom-container-wrapper {
            position: absolute; inset: 0; z-index: 10;
            width: 100%; height: 100%;
            background: #000;
            overflow: hidden;
        }
        #zoom-container-wrapper.hidden { display: none !important; }
        #meetingSDKElement {
            position: absolute !important;
            top: 0 !important; left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: #000;
            overflow: hidden;
        }
        #meetingSDKElement .meeting-app,
        #meetingSDKElement .meeting-client {
            width: 100% !important;
            max-width: none !important;
            height: 100% !important;
        }

        /* ── RIGHT: Settings panel ── */
        #settings-panel {
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            height: 100%;
            background: #ffffff;
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            z-index: 20;
            box-shadow: -4px 0 16px rgba(0,0,0,0.02);
            transition: transform 0.3s ease;
        }
        #settings-panel .panel-inner {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            flex: 1;
        }
        @media (max-width: 768px) {
            #app-wrapper { flex-direction: column; }
            #zoom-section { flex: 1 1 auto; height: 100%; }
            #settings-panel {
                position: fixed;
                top: 0; right: 0; bottom: 0;
                width: 85vw !important;
                max-width: 320px;
                min-width: unset;
                transform: translateX(100%);
                box-shadow: -8px 0 32px rgba(0,0,0,0.1);
                z-index: 9999;
            }
            #settings-panel.open { transform: translateX(0) !important; }
            #mobile-panel-toggle { display: flex !important; }
        }
        #mobile-panel-toggle {
            display: none;
            position: fixed;
            bottom: 80px; right: 12px;
            z-index: 99999;
            width: 48px; height: 48px;
            background: #d97706;
            border-radius: 50%;
            align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(217,119,6,0.3);
            cursor: pointer;
            border: none;
            color: #fff;
            font-size: 20px;
        }

        /* ── SUBTITLE overlay ── */
        #zoom-subtitle-overlay {
            position: fixed;
            bottom: 90px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2147483647;
            background: rgba(255,255,255,0.95);
            padding: 12px 28px;
            border-radius: 999px;
            max-width: 80vw;
            text-align: center;
            pointer-events: none;
            border: 1px solid #e2e8f0;
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        #zoom-subtitle-overlay.hidden { display: none !important; }
        #zoom-subtitle-text {
            color: #1e293b;
            font-weight: 800;
            font-size: 1.35rem;
            line-height: 1.5;
            letter-spacing: 0.01em;
            text-shadow: none;
        }

        /* Lobby layouts */
        .glass-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
        }
        
        .meeting-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .meeting-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: 0 8px 16px -8px rgba(0,0,0,0.05);
        }
        .meeting-card.active-hour {
            background: #fffbeb;
            border: 1px solid #fde68a;
            box-shadow: 0 0 20px rgba(245,158,11,0.05);
        }

        .dashboard-select {
            display: block !important;
            width: 100% !important;
            height: 40px !important;
            padding: 8px 12px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #334155 !important;
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            cursor: pointer !important;
            outline: none !important;
        }
        .dashboard-select:focus {
            border-color: #d97706 !important;
            background-color: #fff !important;
        }
        .dashboard-label {
            display: block !important;
            font-size: 9px !important;
            font-weight: 850 !important;
            text-transform: uppercase !important;
            color: #64748b !important;
            letter-spacing: 0.08em !important;
            margin-bottom: 6px !important;
        }
        .panel-heading {
            font-size: 10px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            color: #d97706 !important;
            letter-spacing: 0.1em !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding-bottom: 8px !important;
        }
        .panel-brand {
            font-weight: 900 !important;
            color: #0f172a !important;
            font-size: 15px !important;
            letter-spacing: -0.02em !important;
        }
        .panel-brand-sub {
            font-size: 8px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            color: #d97706 !important;
            letter-spacing: 0.15em !important;
            margin-top: 2px !important;
        }
        .btn-exit {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            width: 100% !important;
            padding: 12px !important;
            background: #475569 !important;
            color: #fff !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.12em !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            transition: background 0.15s ease !important;
        }
        .btn-exit:hover { background: #334155 !important; }

        .btn-danger {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            width: 100% !important;
            padding: 12px !important;
            background: #dc2626 !important;
            color: #fff !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.12em !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            transition: background 0.15s ease !important;
            cursor: pointer;
            border: none;
        }
        .btn-danger:hover { background: #b91c1c !important; }
    </style>
</head>
<body>

    <button id="mobile-panel-toggle" onclick="document.getElementById('settings-panel').classList.toggle('open')">⚙️</button>

    <div id="app-wrapper">

        <!-- ══ LEFT: Zoom Area ══ -->
        <section id="zoom-section">

            <!-- Lobby / Schedule Selector -->
            <div id="zoom-lobby">
                
                {{-- If meeting number specified, show direct join card --}}
                <div id="join-specific-meeting-container" class="w-full max-w-md glass-container p-8 hidden flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-amber-50 border border-amber-200 text-amber-600 rounded-3xl flex items-center justify-center text-3xl mb-6">📹</div>
                    <h2 id="lobby-heading" class="text-xl font-black text-slate-900 mb-2">Mempersiapkan Rapat</h2>
                    <p class="text-slate-500 text-xs leading-relaxed mb-6 font-semibold">Pastikan izin kamera dan mikrofon Anda sudah disetujui di peramban.</p>
                    
                    <button id="btn-join-meeting" class="w-full py-3.5 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-white font-extrabold text-xs uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-[0.98]">
                        Masuk Kelas Virtual
                    </button>
                    
                    <button id="btn-show-schedules" class="mt-4 text-xs font-bold text-amber-600 hover:text-amber-800 transition-colors">
                        ← Lihat Jadwal Sesi Lainnya
                    </button>
                </div>

                {{-- If no meeting or want to switch, show Schedule Dashboard --}}
                <div id="schedule-dashboard" class="w-full max-w-4xl glass-container p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-slate-200/60 pb-6">
                        <div>
                            <span class="text-xs text-amber-600 font-bold uppercase tracking-widest">Sesi Kelas Virtual</span>
                            <h2 class="text-2xl font-black text-slate-900 mt-1">Jadwal Zoom Meeting</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <select id="schedule-class-filter" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none cursor-pointer">
                                <option value="all">Semua Kelas</option>
                            </select>
                        </div>
                    </div>

                    <div id="schedules-loading" class="py-12 flex flex-col items-center justify-center gap-3">
                        <div class="w-8 h-8 border-3 border-white/10 border-t-indigo-500 rounded-full animate-spin"></div>
                        <p class="text-slate-400 text-[10px] uppercase font-bold tracking-widest animate-pulse">Menghubungkan Jadwal Sesi...</p>
                    </div>

                    <div id="schedules-empty" class="hidden py-16 text-center">
                        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="font-bold text-white text-sm">Tidak Ada Sesi Zoom Terjadwal</h4>
                        <p class="text-slate-400 text-xs mt-1">Belum ada kelas virtual yang aktif saat ini.</p>
                    </div>

                    <div id="schedules-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[450px] overflow-y-auto pr-1"></div>
                </div>

            </div>

            <!-- Zoom SDK container -->
            <div id="zoom-container-wrapper" class="hidden">
                <div id="meetingSDKElement"></div>
                <div id="zoom-subtitle-overlay" class="hidden">
                    <p id="zoom-subtitle-text"></p>
                </div>
            </div>

        </section>

        <!-- ══ RIGHT: Settings Panel ══ -->
        <aside id="settings-panel">
            <div class="panel-inner">

                <!-- Brand -->
                <div style="padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.06);">
                    <p class="panel-brand text-white">🌐 ACESSA</p>
                    <p class="panel-brand-sub">Virtual Class Dashboard</p>
                </div>

                <p class="panel-heading">Aksesibilitas &amp; Pengaturan</p>

                <!-- Bahasa Dosen -->
                <div>
                    <label class="dashboard-label">Bahasa Bicara Dosen:</label>
                    <select id="spoken-lang" class="dashboard-select">
                        <option value="id-ID">Bahasa Indonesia</option>
                        <option value="en-US">English</option>
                        <option value="ja-JP">日本語</option>
                    </select>
                </div>

                <!-- Terjemah ke -->
                <div>
                    <label class="dashboard-label">Terjemah Bahasa Ke:</label>
                    <select id="subtitle-lang" class="dashboard-select">
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en" selected>English</option>
                        <option value="ja">日本語</option>
                    </select>
                </div>

                <!-- Ukuran font -->
                <div>
                    <label class="dashboard-label">Ukuran Font Subtitel:</label>
                    <select id="font-size" class="dashboard-select">
                        <option value="1rem">Kecil</option>
                        <option value="1.35rem" selected>Normal</option>
                        <option value="1.75rem">Besar</option>
                        <option value="2.25rem">Maksimal (Disabilitas)</option>
                    </select>
                </div>

                <!-- Subtitle toggle switch -->
                <div class="flex items-center justify-between mt-2">
                    <label class="dashboard-label" style="margin-bottom: 0;">Tampilkan Subtitel:</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="toggle-subtitles" class="sr-only peer" checked>
                        <div class="w-9 h-5 bg-slate-800 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-650 border border-slate-700"></div>
                    </label>
                </div>

                <!-- Translation toggle switch -->
                <div class="flex items-center justify-between mt-2">
                    <label class="dashboard-label" style="margin-bottom: 0;">Terjemahan Otomatis:</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="toggle-translation" class="sr-only peer" checked>
                        <div class="w-9 h-5 bg-slate-800 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-650 border border-slate-700"></div>
                    </label>
                </div>

            </div>

            <!-- Exit button -->
            <div style="padding:1.25rem;" class="flex flex-col gap-2">
                @if(request()->query('role') == '1')
                    <button id="btn-end-meeting" class="btn-danger">🛑 Akhiri Sesi Kelas</button>
                @endif
                <a href="#" id="btn-back-to-course" class="btn-exit">🚪 Keluar Kelas</a>
            </div>
        </aside>

    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Zoom SDK Dependencies -->
    <script src="https://source.zoom.us/3.8.5/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-embedded-3.8.5.min.js"></script>

    <script>
        // ── Params ──
        const urlParams  = new URLSearchParams(window.location.search);
        let meetingNumber = urlParams.get('meeting_number');
        let passcode      = urlParams.get('passcode') || '';
        const role          = parseInt(urlParams.get('role') || '0');
        const courseId      = urlParams.get('course_id');
        const userName  = "{{ auth()->user()->name }}";
        const userEmail = "{{ auth()->user()->email }}";

        // ── Routing ──
        if (role === 1) {
            $('#btn-back-to-course').attr('href', '/teacher');
            $('#lobby-heading').text('Mulai Rapat (Dosen / Host)');
        } else {
            $('#btn-back-to-course').attr('href', '/classroom');
            $('#lobby-heading').text('Gabung Rapat (Mahasiswa)');
        }

        // Show schedules vs join container based on presence of parameter
        if (meetingNumber) {
            $('#join-specific-meeting-container').removeClass('hidden');
            $('#schedule-dashboard').addClass('hidden');
        } else {
            $('#join-specific-meeting-container').addClass('hidden');
            $('#schedule-dashboard').removeClass('hidden');
        }

        $('#btn-show-schedules').on('click', function() {
            $('#join-specific-meeting-container').addClass('hidden');
            $('#schedule-dashboard').removeClass('hidden');
        });

        // ── Fetch Schedules & Highlight Current Hour ──
        const authId = '{{ auth()->user()->id }}';
        let allMeetings = [];
        let meetingDbId = urlParams.get('meeting_db_id') || '{{ $meetingDbId ?? "" }}';

        const classesUrl = (role === 1) 
            ? `/api/my/classroom/teacher/data/${authId}`
            : `/api/student/classroom/data/${authId}`;

        $.ajax({
            url: classesUrl,
            method: 'GET',
            success: function(res) {
                let classes = [];
                if (role === 1) {
                    classes = res.data || [];
                } else {
                    const relations = res.StudentClassroomRelations || [];
                    classes = relations.map(r => r.course);
                }
                
                // Add classrooms to filter select
                classes.forEach(c => {
                    if (c && c.id && c.name) {
                        $('#schedule-class-filter').append(`<option value="${c.id}">${c.name}</option>`);
                    }
                });

                if (classes.length === 0) {
                    $('#schedules-loading').hide();
                    $('#schedules-empty').removeClass('hidden');
                    return;
                }

                let fetched = 0;
                classes.forEach(kelas => {
                    if (!kelas || !kelas.id) return;
                    $.ajax({
                        url: `/api/zoom-meetings/${kelas.id}`,
                        method: 'GET',
                        success: function(mRes) {
                            const list = (mRes.data || mRes || []).map(m => ({
                                ...m, 
                                classroom_name: kelas.name, 
                                classroom_id: kelas.id
                            }));
                            allMeetings = [...allMeetings, ...list];
                        },
                        complete: function() {
                            fetched++;
                            if (fetched === classes.length) {
                                renderLobbySchedules(allMeetings);
                            }
                        }
                    });
                });
            },
            error: () => {
                $('#schedules-loading').hide();
                $('#schedules-empty').removeClass('hidden');
            }
        });

        function renderLobbySchedules(meetings) {
            $('#schedules-loading').hide();
            const grid = $('#schedules-grid');
            grid.empty();

            if (meetings.length === 0) {
                $('#schedules-empty').removeClass('hidden');
                return;
            }

            $('#schedules-empty').addClass('hidden');

            const now = new Date();

            meetings.forEach(m => {
                const mTime = new Date(m.meeting_time);
                
                // Check if current hour matches meeting hour (within same hour / +-45 minutes margin)
                const diffMs = Math.abs(now - mTime);
                const isActiveHour = diffMs < (45 * 60 * 1000); // 45 minutes margin
                const isEnded = m.status === 'ended';

                let activeTag = '';
                let buttonHtml = '';
                let cardClass = 'meeting-card';

                if (isEnded) {
                    cardClass = 'meeting-card opacity-50';
                    activeTag = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-500/10 text-red-450 text-[10px] font-bold rounded-lg border border-red-500/20">Selesai / Berakhir</span>`;
                    buttonHtml = `<button class="px-4 py-2 bg-slate-800 text-slate-500 font-bold text-xs rounded-xl cursor-not-allowed" disabled>Selesai</button>`;
                } else if (isActiveHour) {
                    cardClass = 'meeting-card active-hour';
                    activeTag = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold rounded-lg border border-emerald-500/20 animate-pulse">🔴 Jam Sesi Aktif</span>`;
                    buttonHtml = `
                        <button onclick="selectMeeting('${m.meeting_number}', '${m.passcode}', '${m.classroom_id}', '${m.id}')" 
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 shadow-md shadow-emerald-600/20 text-white font-bold text-xs rounded-xl transition-all">
                            Mulai Sesi
                        </button>
                    `;
                } else {
                    activeTag = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-500/10 text-slate-400 text-[10px] font-bold rounded-lg border border-white/5">Terjadwal</span>`;
                    buttonHtml = `
                        <button onclick="selectMeeting('${m.meeting_number}', '${m.passcode}', '${m.classroom_id}', '${m.id}')" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl transition-all">
                            Mulai Sesi
                        </button>
                    `;
                }

                const timeString = mTime.toLocaleDateString('id-ID', {day: '2-digit', month: 'short'}) + ', ' + mTime.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}) + ' WIB';

                grid.append(`
                    <div class="${cardClass} p-5 rounded-2xl flex flex-col justify-between" data-class="${m.classroom_id}">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-3">
                                ${activeTag}
                                <span class="text-[10px] font-bold text-slate-400 truncate max-w-[150px]">${m.classroom_name}</span>
                            </div>
                            <h4 class="font-extrabold text-white text-sm leading-snug">${m.topic || m.title || 'Sesi Kelas Virtual'}</h4>
                            <p class="text-slate-400 text-xs mt-1.5 font-medium">${timeString}</p>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-3">
                            <span class="text-[10px] font-mono text-slate-500">ID: ${m.meeting_number || '—'}</span>
                            ${buttonHtml}
                        </div>
                    </div>
                `);
            });
        }

        function selectMeeting(mNum, pass, cId, dbId) {
            const nextUrl = `${window.location.pathname}?meeting_number=${mNum}&passcode=${pass}&role=${role}&course_id=${cId}&meeting_db_id=${dbId}`;
            window.history.pushState({}, '', nextUrl);
            meetingNumber = mNum;
            passcode = pass;
            meetingDbId = dbId;
            $('#join-specific-meeting-container').removeClass('hidden');
            $('#schedule-dashboard').addClass('hidden');
        }

        $('#schedule-class-filter').on('change', function() {
            const val = $(this).val();
            if (val === 'all') {
                $('.meeting-card').show();
            } else {
                $('.meeting-card').each(function() {
                    $(this).toggle($(this).data('class').toString() === val);
                });
            }
        });

        // ── Toastr config ──
        toastr.options = { positionClass: 'toast-top-right', timeOut: 3000 };

        // ── End Meeting Handler ──
        $('#btn-end-meeting').on('click', function(e) {
            e.preventDefault();
            if (!meetingDbId) {
                toastr.error('ID Meeting database tidak ditemukan. Tidak dapat mengakhiri sesi.');
                return;
            }
            if (confirm('Apakah Anda yakin ingin mengakhiri sesi kelas virtual ini untuk semua peserta?')) {
                $.ajax({
                    url: `/api/zoom-meetings/end/${meetingDbId}`,
                    method: 'POST',
                    headers: {
                        'X-CSR-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.success) {
                            toastr.success('Sesi kelas virtual telah berhasil diakhiri.');
                            setTimeout(() => {
                                window.location.href = '/teacher';
                            }, 1500);
                        } else {
                            toastr.error(res.message || 'Gagal mengakhiri sesi.');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error saat menghubungi server.');
                    }
                });
            }
        });

        // ── Speech Transcriptions & Custom Subtitles ──
        let isSubtitlesEnabled = true;
        let isRecognizing      = false;
        let recognition        = null;
        let captionInterval    = null;
        let lastCaptionId      = 0;
        let overlayTimeout     = null;
        let translationAbortController = null;
        let translationTimeout = null;

        const client = ZoomMtgEmbedded.createClient();

        $('#btn-join-meeting').on('click', function () {
            const btn = $(this);
            btn.prop('disabled', true).text('Menghubungkan...');

            $.ajax({
                url: '/api/zoom/signature',
                method: 'POST',
                data: { meeting_number: meetingNumber, role: role },
                success: function (res) {
                    $('#zoom-lobby').addClass('hidden');
                    $('#zoom-container-wrapper').removeClass('hidden');

                    const section = document.getElementById('zoom-section');
                    const w = section.clientWidth;
                    const h = section.clientHeight;
                    const sdkH = Math.max(h - 70, 400);

                    client.init({
                        zoomAppRoot: document.getElementById('meetingSDKElement'),
                        language: 'en-US',
                        patchJsMedia: true,
                        customize: {
                            video: {
                                isResizable: false,
                                defaultViewType: 'gallery',
                                viewSizes: {
                                    default: { width: w, height: sdkH },
                                    ribbon:  { width: 320, height: sdkH }
                                }
                            }
                        }
                    }).then(() => {
                        client.join({
                            sdkKey: res.sdkKey,
                            signature: res.signature,
                            meetingNumber: meetingNumber,
                            password: passcode,
                            userName: userName,
                            userEmail: userEmail
                        }).then(() => {
                            toastr.success('Berhasil bergabung ke kelas!');
                            initAccessibility();
                        }).catch(err => {
                            showLobby(btn);
                            toastr.error('Gagal join: ' + JSON.stringify(err));
                        });
                    }).catch(err => {
                        showLobby(btn);
                        toastr.error('Gagal init SDK: ' + JSON.stringify(err));
                    });
                },
                error: function () {
                    btn.prop('disabled', false).text('Masuk Kelas Virtual');
                    toastr.error('Gagal ambil signature dari server.');
                }
            });
        });

        function showLobby(btn) {
            $('#zoom-lobby').removeClass('hidden');
            $('#zoom-container-wrapper').addClass('hidden');
            if (btn) btn.prop('disabled', false).text('Masuk Kelas Virtual');
        }

        // Accessibility Speech
        function initAccessibility() {
            if (role === 1) startMic();
            startCaptionPolling();
        }

        function startCaptionPolling() {
            if (captionInterval) clearInterval(captionInterval);

            captionInterval = setInterval(() => {
                $.ajax({
                    url: `/api/live-captions?course_id=${courseId}&last_id=${lastCaptionId}`,
                    method: 'GET',
                    success: function(res) {
                        if (res.success && res.data.length > 0) {
                            res.data.forEach(caption => {
                                lastCaptionId = Math.max(lastCaptionId, caption.id);
                                
                                // Do not display own broadcasted caption again for host
                                if (role === 1 && caption.user_id == authId) {
                                    return;
                                }

                                processCaption(caption.text, caption.language, caption.user_name || 'Pembicara');
                            });
                        }
                    }
                });
            }, 2500);
        }

        function processCaption(text, srcLang, speakerName) {
            const isTranslationEnabled = $('#toggle-translation').is(':checked');
            const targetLang = $('#subtitle-lang').val();
            const srcLangShort = srcLang.split('-')[0];

            if (isTranslationEnabled && srcLangShort !== targetLang) {
                const apiUrl = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${srcLangShort}&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;

                fetch(apiUrl)
                    .then(r => r.json())
                    .then(transRes => {
                        if (transRes && transRes[0]) {
                            const translatedText = transRes[0].map(x => x ? x[0] : '').join('');
                            showSubtitle(`<span class="text-slate-400 font-medium">${speakerName}:</span> ${translatedText} <span class="text-[9px] text-yellow-500/80 font-bold uppercase">(Translated)</span>`, true);
                        } else {
                            showSubtitle(`<span class="text-slate-400 font-medium">${speakerName}:</span> ${text}`, true);
                        }
                    })
                    .catch(() => {
                        showSubtitle(`<span class="text-slate-400 font-medium">${speakerName}:</span> ${text}`, true);
                    });
            } else {
                showSubtitle(`<span class="text-slate-400 font-medium">${speakerName}:</span> ${text}`, true);
            }
        }

        function startMic() {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SR) return;

            recognition = new SR();
            recognition.continuous     = true;
            recognition.interimResults = true;
            recognition.lang = $('#spoken-lang').val();

            recognition.onstart = function () {
                isRecognizing = true;
                toastr.success('Mic Transkripsi Aktif.');
            };

            recognition.onresult = function (event) {
                let interimTranscript = '';
                let finalTranscript = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    const transcriptText = event.results[i][0].transcript;
                    if (event.results[i].isFinal) {
                        finalTranscript += transcriptText;
                    } else {
                        interimTranscript += transcriptText;
                    }
                }
                
                const liveText = (finalTranscript + interimTranscript).trim();
                if (!liveText) return;

                const spokenLang = $('#spoken-lang').val();
                const isTranslationEnabled = $('#toggle-translation').is(':checked');
                const targetLang = $('#subtitle-lang').val();
                const srcLangShort = spokenLang.split('-')[0];

                if (isTranslationEnabled && targetLang && targetLang !== 'none' && srcLangShort !== targetLang) {
                    // Debounce and fetch translation in real-time
                    if (translationTimeout) clearTimeout(translationTimeout);
                    if (translationAbortController) {
                        translationAbortController.abort();
                    }
                    translationTimeout = setTimeout(() => {
                        translationAbortController = new AbortController();
                        const signal = translationAbortController.signal;
                        const apiUrl = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${srcLangShort}&tl=${targetLang}&dt=t&q=${encodeURIComponent(liveText)}`;

                        fetch(apiUrl, { signal })
                            .then(r => r.json())
                            .then(transRes => {
                                if (transRes && transRes[0]) {
                                    const translatedText = transRes[0].map(x => x ? x[0] : '').join('');
                                    showSubtitle(`<span class="text-slate-400 font-medium">Anda:</span> ${translatedText} <span class="text-[9px] text-yellow-500/80 font-bold uppercase">(Translated)</span>`, true);
                                }
                            })
                            .catch(err => {
                                if (err.name !== 'AbortError') {
                                    console.error("Translation error:", err);
                                }
                            });
                    }, 50); // 50ms debounce
                } else {
                    showSubtitle(`<span class="text-slate-400 font-medium">Anda:</span> ${liveText}`, false);
                }

                if (finalTranscript.trim()) {
                    const text = finalTranscript.trim();
                    // Broadcast
                    $.ajax({
                        url: '/api/live-captions',
                        method: 'POST',
                        data: { course_id: courseId, text: text, language: spokenLang }
                    });
                }
            };

            recognition.onend = function ()  { if (isRecognizing) recognition.start(); };
            recognition.start();
        }

        function showSubtitle(text, isFinal) {
            if (!isSubtitlesEnabled) return;
            const textEl  = document.getElementById('zoom-subtitle-text');
            const overlay = document.getElementById('zoom-subtitle-overlay');
            const fontSize = $('#font-size').val() || '1.35rem';
            
            textEl.style.fontSize = fontSize;
            textEl.innerHTML = text;
            overlay.classList.remove('hidden');
            clearTimeout(overlayTimeout);
            overlayTimeout = setTimeout(function () {
                overlay.classList.add('hidden');
            }, 6000);
        }

        // Toggle Subtitle Switch listener
        $('#toggle-subtitles').on('change', function() {
            isSubtitlesEnabled = this.checked;
            if (!isSubtitlesEnabled) {
                $('#zoom-subtitle-overlay').addClass('hidden');
            }
            toastr.info(isSubtitlesEnabled ? 'Subtitel Diaktifkan' : 'Subtitel Dinonaktifkan');
        });

        // Toggle Translation Switch listener
        $('#toggle-translation').on('change', function() {
            toastr.info(this.checked ? 'Terjemahan Otomatis Aktif' : 'Terjemahan Otomatis Nonaktif');
        });
    </script>
</body>
</html>
