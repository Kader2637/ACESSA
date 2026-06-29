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
            background: #f8fafc;
        }
        #app-wrapper {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100%;
        }

        /* ── LEFT: Zoom panel ── */
        #zoom-section {
            flex: 1 1 0%;
            position: relative;
            background: #0f172a;
            min-width: 0;
            /* NO overflow:hidden here — Zoom's dropdown/popup menus must be able to appear above the toolbar */
        }
        #zoom-lobby {
            position: absolute; inset: 0; z-index: 40;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 2rem; text-align: center;
            background: rgba(241,245,249,0.92);
            backdrop-filter: blur(8px);
        }
        #zoom-lobby.hidden { display: none !important; }
        #zoom-container-wrapper {
            position: absolute; inset: 0; z-index: 10;
            width: 100%; height: 100%;
            background: #000;
        }
        #zoom-container-wrapper.hidden { display: none !important; }
        #meetingSDKElement {
            position: absolute !important;
            top: 0 !important; left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: #000;
        }
        /* Only stretch width on Zoom internal wrappers — let Zoom own the height internally */
        #meetingSDKElement .meeting-app,
        #meetingSDKElement .meeting-client {
            width: 100% !important;
            max-width: none !important;
        }

        /* ── RIGHT: Settings panel ── */
        #settings-panel {
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            height: 100%;
            background: #fff;
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            z-index: 20;
            box-shadow: -4px 0 16px rgba(0,0,0,0.04);
            transition: transform 0.3s ease;
        }
        #settings-panel .panel-inner {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            flex: 1;
        }
        /* Mobile: panel slides in from right as drawer */
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
                box-shadow: -8px 0 32px rgba(0,0,0,0.15);
                z-index: 9999;
            }
            #settings-panel.open { transform: translateX(0) !important; }
            #mobile-panel-toggle {
                display: flex !important;
            }
        }
        #mobile-panel-toggle {
            display: none;
            position: fixed;
            bottom: 80px; right: 12px;
            z-index: 99999;
            width: 48px; height: 48px;
            background: #4f46e5;
            border-radius: 50%;
            align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(79,70,229,0.4);
            cursor: pointer;
            border: none;
            color: #fff;
            font-size: 20px;
        }

        /* ── SUBTITLE overlay ── */
        #zoom-subtitle-overlay {
            position: fixed;
            bottom: 90px;
            left: 39%;
            transform: translateX(-50%);
            z-index: 2147483647;
            background: rgba(0,0,0,0.82);
            padding: 14px 32px;
            border-radius: 999px;
            max-width: 68vw;
            text-align: center;
            pointer-events: none;
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
        }
        #zoom-subtitle-overlay.hidden { display: none !important; }
        #zoom-subtitle-text {
            color: #fbbf24;
            font-weight: 800;
            font-size: 1.35rem;
            line-height: 1.5;
            letter-spacing: 0.01em;
            text-shadow: 0 2px 8px rgba(0,0,0,0.9);
        }

        /* ── Lobby card ── */
        .light-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 2rem;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
            max-width: 420px; width: 100%;
            display: flex; flex-direction: column; align-items: center;
        }

        /* ── Protected inputs (immune to Zoom global CSS) ── */
        .dashboard-select {
            display: block !important;
            width: 100% !important;
            height: 42px !important;
            padding: 8px 14px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #334155 !important;
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            cursor: pointer !important;
            appearance: auto !important;
            -webkit-appearance: auto !important;
            line-height: normal !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .dashboard-label {
            display: block !important;
            font-size: 9px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            color: #94a3b8 !important;
            letter-spacing: 0.08em !important;
            margin-bottom: 6px !important;
        }
        .panel-heading {
            font-size: 9px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.12em !important;
            color: #94a3b8 !important;
            margin: 0 !important;
        }
        .panel-brand {
            font-size: 13px !important;
            font-weight: 900 !important;
            color: #1e293b !important;
        }
        .panel-brand-sub {
            font-size: 8px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.15em !important;
            color: #4f46e5 !important;
        }
        .toggle-card {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 14px 16px !important;
            border-radius: 16px !important;
            border: 1px solid !important;
        }
        .toggle-card-gray { background: #f8fafc !important; border-color: #e2e8f0 !important; }
        .toggle-card-green { background: #f0fdf4 !important; border-color: #bbf7d0 !important; }
        .toggle-label-title {
            font-size: 11px !important;
            font-weight: 700 !important;
            color: #334155 !important;
        }
        .toggle-label-sub {
            font-size: 8px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            color: #94a3b8 !important;
            margin-top: 2px !important;
        }
        .toggle-label-title-green { color: #166534 !important; }
        .toggle-label-sub-green { color: #4ade80 !important; }

        /* ── Toggle switch (protected from Zoom CSS) ── */
        .acessa-toggle-wrap {
            display: inline-flex !important;
            align-items: center !important;
            cursor: pointer !important;
            flex-shrink: 0 !important;
            user-select: none !important;
        }
        .acessa-toggle-wrap input {
            position: absolute !important;
            opacity: 0 !important; width: 0 !important; height: 0 !important;
            pointer-events: none !important;
        }
        .acessa-toggle-track {
            display: inline-block !important;
            position: relative !important;
            width: 44px !important; height: 24px !important;
            background-color: #cbd5e1 !important;
            border-radius: 9999px !important;
            transition: background-color 0.2s ease !important;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.15) !important;
            flex-shrink: 0 !important;
        }
        .acessa-toggle-track::after {
            content: '' !important;
            position: absolute !important;
            top: 3px !important; left: 3px !important;
            width: 18px !important; height: 18px !important;
            background: #fff !important;
            border-radius: 9999px !important;
            box-shadow: 0 1px 4px rgba(0,0,0,0.25) !important;
            transition: transform 0.2s ease !important;
        }
        .acessa-toggle-track.is-on { background-color: #4f46e5 !important; }
        .acessa-toggle-track.is-on-green { background-color: #10b981 !important; }
        .acessa-toggle-track.is-on::after,
        .acessa-toggle-track.is-on-green::after { transform: translateX(20px) !important; }

        /* ── Exit button ── */
        .btn-exit {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            width: 100% !important;
            padding: 14px !important;
            background: #e11d48 !important;
            color: #fff !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.15em !important;
            border-radius: 16px !important;
            text-decoration: none !important;
            transition: background 0.2s !important;
        }
        .btn-exit:hover { background: #be123c !important; }

        /* ── Lobby join button ── */
        .btn-join {
            width: 100%;
            padding: 16px;
            background: #4f46e5;
            color: #fff;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            border-radius: 16px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(79,70,229,0.25);
            transition: all 0.2s;
        }
        .btn-join:hover { background: #4338ca; }
        .btn-join:active { transform: scale(0.97); }
    </style>
</head>
<body>

    <!-- Mobile panel toggle button (hidden on desktop) -->
    <button id="mobile-panel-toggle" onclick="document.getElementById('settings-panel').classList.toggle('open')">⚙️</button>

    <div id="app-wrapper">

        <!-- ══ LEFT: Zoom Area ══ -->
        <section id="zoom-section">

            <!-- Lobby -->
            <div id="zoom-lobby">
                <div class="light-card">
                    <div style="width:72px;height:72px;background:#eef2ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;margin-bottom:1.5rem;animation:pulse 2s infinite;">📹</div>
                    <h2 id="lobby-heading" style="font-size:1.4rem;font-weight:900;color:#1e293b;margin-bottom:.5rem;">Mempersiapkan Kelas...</h2>
                    <p style="color:#64748b;font-size:.85rem;margin-bottom:2rem;line-height:1.6;">Pastikan kamera dan mikrofon Anda aktif sebelum bergabung.</p>
                    <button id="btn-join-meeting" class="btn-join">Masuk Kelas Virtual</button>
                </div>
            </div>

            <!-- Zoom SDK container (hidden until joined) -->
            <div id="zoom-container-wrapper" class="hidden">
                <div id="meetingSDKElement"></div>

                <!-- Subtitle overlay -->
                <div id="zoom-subtitle-overlay" class="hidden">
                    <p id="zoom-subtitle-text"></p>
                </div>
            </div>

        </section>

        <!-- ══ RIGHT: Settings Panel ══ -->
        <aside id="settings-panel">
            <div class="panel-inner">

                <!-- Brand -->
                <div style="padding-bottom:1rem;border-bottom:1px solid #f1f5f9;">
                    <p class="panel-brand">🌐 ACESSA</p>
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
                        <option value="ar-SA">العربية</option>
                        <option value="zh-CN">中文</option>
                    </select>
                </div>

                <!-- Terjemah ke -->
                <div>
                    <label class="dashboard-label">Terjemah Bahasa Ke:</label>
                    <select id="subtitle-lang" class="dashboard-select">
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en" selected>English</option>
                        <option value="ja">日本語</option>
                        <option value="ar">العربية</option>
                        <option value="zh">中文</option>
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

                <!-- Toggle: Tampilkan Subtitel -->
                <div class="toggle-card toggle-card-gray">
                    <div>
                        <p class="toggle-label-title">Tampilkan Subtitel</p>
                        <p class="toggle-label-sub">Teks melayang di layar</p>
                    </div>
                    <label class="acessa-toggle-wrap" id="label-subtitles">
                        <input type="checkbox" id="toggle-subtitles">
                        <span class="acessa-toggle-track is-on" id="track-subtitles"></span>
                    </label>
                </div>

                <!-- Toggle: Auto Translate -->
                <div class="toggle-card toggle-card-green">
                    <div>
                        <p class="toggle-label-title toggle-label-title-green">🌐 Auto Translate</p>
                        <p class="toggle-label-sub toggle-label-sub-green">Terjemah ke bahasa pilihan</p>
                    </div>
                    <label class="acessa-toggle-wrap" id="label-translate">
                        <input type="checkbox" id="toggle-translate">
                        <span class="acessa-toggle-track" id="track-translate"></span>
                    </label>
                </div>

            </div>

            <!-- Exit button -->
            <div style="padding:1.25rem;">
                <a href="#" id="btn-back-to-course" class="btn-exit">🚪 Keluar Kelas</a>
            </div>
        </aside>

    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Zoom SDK -->
    <script src="https://source.zoom.us/3.8.5/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.8.5/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-embedded-3.8.5.min.js"></script>

    <script>
        // ── Params ──
        const urlParams  = new URLSearchParams(window.location.search);
        const meetingNumber = urlParams.get('meeting_number');
        const passcode      = urlParams.get('passcode') || '';
        const role          = parseInt(urlParams.get('role') || '0');
        const courseId      = urlParams.get('course_id');
        const userName  = "{{ auth()->user()->name }}";
        const userEmail = "{{ auth()->user()->email }}";

        // ── Routing ──
        if (role === 1) {
            $('#btn-back-to-course').attr('href', `/teacher/course/detail/${courseId}`);
            $('#lobby-heading').text('Mulai Rapat (Dosen / Host)');
        } else {
            $('#btn-back-to-course').attr('href', `/student/course/detail/${courseId}`);
            $('#lobby-heading').text('Gabung Rapat (Mahasiswa)');
        }

        // ── Toastr config ──
        toastr.options = { positionClass: 'toast-top-right', timeOut: 3000 };

        // ── State ──
        let isSubtitlesEnabled = true;
        let isTranslateEnabled = false;
        let isRecognizing      = false;
        let recognition        = null;
        let captionInterval    = null;
        let lastCaptionId      = 0;
        let overlayTimeout     = null;
        let interimDebounce    = null; // debounce timer for interim translation

        // ── Toggle: Subtitel ──
        $('#label-subtitles').on('click', function (e) {
            e.preventDefault();
            isSubtitlesEnabled = !isSubtitlesEnabled;
            if (isSubtitlesEnabled) {
                $('#track-subtitles').addClass('is-on');
            } else {
                $('#track-subtitles').removeClass('is-on');
                $('#zoom-subtitle-overlay').addClass('hidden');
            }
        });

        // ── Toggle: Auto Translate ──
        $('#label-translate').on('click', function (e) {
            e.preventDefault();
            isTranslateEnabled = !isTranslateEnabled;
            if (isTranslateEnabled) {
                $('#track-translate').addClass('is-on-green');
                toastr.success('Auto Translate aktif.');
            } else {
                $('#track-translate').removeClass('is-on-green');
                toastr.info('Auto Translate nonaktif.');
            }
        });

        // ── Font size ──
        $('#font-size').on('change', function () {
            document.getElementById('zoom-subtitle-text').style.fontSize = this.value;
        });

        // ── Zoom SDK ──
        const client = ZoomMtgEmbedded.createClient();

        $('#btn-join-meeting').on('click', function () {
            const btn = $(this);
            btn.prop('disabled', true).text('Menghubungkan...');

            $.ajax({
                url: '/api/zoom/signature',
                method: 'POST',
                data: { meeting_number: meetingNumber, role: role },
                success: function (res) {
                    // Show container so dimensions are real
                    $('#zoom-lobby').addClass('hidden');
                    $('#zoom-container-wrapper').removeClass('hidden');

                    // Measure the exact pixel size of #zoom-section AFTER display
                    const section = document.getElementById('zoom-section');
                    const w = section.clientWidth;
                    const h = section.clientHeight;
                    // Reserve 70px for Zoom's own toolbar at the bottom
                    const sdkH = Math.max(h - 70, 400);

                    client.init({
                        zoomAppRoot: document.getElementById('meetingSDKElement'),
                        language: 'en-US',
                        patchJsMedia: true,
                        customize: {
                            video: {
                                isResizable: false,
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

        // Debounced resize handler
        let resizeTimer = null;
        window.addEventListener('resize', function () {
            if (!document.getElementById('zoom-container-wrapper').classList.contains('hidden')) {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    const section = document.getElementById('zoom-section');
                    const w = section.clientWidth;
                    const h = section.clientHeight;
                    const sdkH = Math.max(h - 70, 400);
                    client.updateVideoOptions({
                        viewSizes: {
                            default: { width: w, height: sdkH },
                            ribbon:  { width: 320, height: sdkH }
                        }
                    });
                }, 200);
            }
        });

        // ── Accessibility ──
        function initAccessibility() {
            if (role === 1) startMic();
            else startPolling();
        }

        // ── Teacher: Speech Recognition ──
        function startMic() {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SR) { toastr.error('Browser tidak support Speech Recognition.'); return; }

            recognition = new SR();
            recognition.continuous     = true;
            recognition.interimResults = true;
            recognition.lang = $('#spoken-lang').val();

            recognition.onstart = function () {
                isRecognizing = true;
                toastr.success('Mic Transkripsi Aktif.');
            };

            recognition.onresult = function (event) {
                let interim = '';
                let final   = '';

                for (let i = event.resultIndex; i < event.results.length; i++) {
                    if (event.results[i].isFinal) {
                        final += event.results[i][0].transcript;
                    } else {
                        interim += event.results[i][0].transcript;
                    }
                }

                const spokenLang = $('#spoken-lang').val().split('-')[0];
                const targetLang = $('#subtitle-lang').val();

                // ── Interim ──
                if (interim.trim()) {
                    if (isTranslateEnabled && spokenLang !== targetLang) {
                        // Debounce: translate interim every 800ms of continuous speech
                        // This prevents flooding the API while giving real-time feel
                        clearTimeout(interimDebounce);
                        interimDebounce = setTimeout(function () {
                            const snapshot = interim.trim(); // capture current interim
                            translateText(snapshot, spokenLang, targetLang)
                                .then(translated => showSubtitle(translated, false, false))
                                .catch(() => {}); // silent fail, final will catch it
                        }, 800);
                    } else {
                        // Translate OFF: show immediately
                        showSubtitle(interim.trim(), false, false);
                    }
                }

                // ── Final ──
                if (final.trim()) {
                    clearTimeout(interimDebounce); // cancel pending interim translate
                    const text = final.trim();

                    if (isTranslateEnabled && spokenLang !== targetLang) {
                        translateText(text, spokenLang, targetLang)
                            .then(translated => showSubtitle(translated, true, false))
                            .catch(() => showSubtitle(text, true, false));
                    } else {
                        showSubtitle(text, true, false);
                    }

                    // Broadcast to students
                    $.ajax({
                        url: '/api/live-captions',
                        method: 'POST',
                        data: { course_id: courseId, text: text, language: $('#spoken-lang').val() }
                    });
                }
            };

            recognition.onerror = function (e) { console.error('SR Error', e); };
            recognition.onend   = function ()  { if (isRecognizing) recognition.start(); };
            recognition.start();
        }

        // ── Student: Polling ──
        function startPolling() {
            toastr.info('Subtitle aksesibilitas aktif.');

            captionInterval = setInterval(function () {
                $.ajax({
                    url: `/api/live-captions?course_id=${courseId}&last_id=${lastCaptionId}`,
                    method: 'GET',
                    success: function (res) {
                        if (res.success && res.data.length > 0) {
                            res.data.forEach(function (caption) {
                                lastCaptionId = Math.max(lastCaptionId, caption.id);

                                const spokenLang = $('#spoken-lang').val().split('-')[0];
                                const targetLang = $('#subtitle-lang').val();

                                if (isTranslateEnabled && spokenLang !== targetLang) {
                                    translateText(caption.text, spokenLang, targetLang)
                                        .then(translated => showSubtitle(translated, true))
                                        .catch(() => showSubtitle(caption.text, true));
                                } else {
                                    showSubtitle(caption.text, true);
                                }
                            });
                        }
                    }
                });
            }, 3000);
        }

        // ── Translation helper — uses Google Translate unofficial endpoint (fast, ~100-300ms) ──
        function translateText(text, from, to) {
            const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${from}&tl=${to}&dt=t&q=${encodeURIComponent(text)}`;
            return fetch(url)
                .then(r => r.json())
                .then(data => {
                    // Response: [[['translated', 'original', ...], ...], ...]
                    return data[0].map(seg => seg[0]).join('');
                });
        }

        // ── Subtitle renderer ──
        // isFinal: true = solid text, false = interim placeholder
        // isTranslating: true = show as dimmed (waiting for translation)
        function showSubtitle(text, isFinal, isTranslating) {
            if (!isSubtitlesEnabled) return;

            const textEl  = document.getElementById('zoom-subtitle-text');
            const overlay = document.getElementById('zoom-subtitle-overlay');

            textEl.style.fontSize = document.getElementById('font-size').value;

            if (isFinal) {
                // Bold, fully opaque — final confirmed text
                textEl.style.opacity = '1';
                textEl.style.fontStyle = 'normal';
                textEl.innerHTML = text;
            } else if (isTranslating) {
                // Dimmed italic — original shown while translation is in-flight
                textEl.style.opacity = '0.5';
                textEl.style.fontStyle = 'italic';
                textEl.innerHTML = text;
            } else {
                // Interim non-translate mode — semi-dim italic
                textEl.style.opacity = '0.75';
                textEl.style.fontStyle = 'italic';
                textEl.innerHTML = text;
            }

            overlay.classList.remove('hidden');

            clearTimeout(overlayTimeout);
            overlayTimeout = setTimeout(function () {
                overlay.classList.add('hidden');
                textEl.innerHTML = '';
            }, 6000);
        }
    </script>
</body>
</html>
