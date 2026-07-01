@extends('layouts.student.app')

@section('page_title', 'Materi Modul')

@section('style')
<style>
    .nav-tab-active { border-bottom: 3px solid #4f46e5; color: #4f46e5; font-weight: 800; }
    .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 2.5rem; background: #000; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); }
    .video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
    #pdf-canvas { max-width: 100%; height: auto; border-radius: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .glass-control { background: rgba(15, 23, 42, 0.8); backdrop-blur: 16px; border: 1px solid rgba(255, 255, 255, 0.1); }
</style>
@endsection

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="" id="class-link" class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] leading-none mb-1.5">Learning Unit</p>
            <h4 class="text-2xl font-black text-slate-900 tracking-tight" id="class-name1">Memuat Materi...</h4>
        </div>
    </div>
</div>

<div class="mb-10 flex border-b border-slate-200 gap-10">
    <button onclick="switchTab('materi')" id="btn-materi" class="pb-4 text-sm font-bold text-slate-400 nav-tab-active transition-all">Isi Materi</button>
    <button onclick="switchTab('zoom')" id="btn-zoom" class="pb-4 text-sm font-bold text-slate-400 transition-all">Pertemuan Zoom</button>
    <button onclick="switchTab('tugas')" id="btn-tugas" class="pb-4 text-sm font-bold text-slate-400 transition-all">Penugasan</button>
</div>

<div id="content-materi" class="tab-content block animate-fade-in">
    <div id="link" class="hidden mb-12"></div>
    
    <div id="document" class="hidden relative w-full mb-12 group min-h-[600px]">
        <div class="flex justify-center bg-slate-100 p-6 md:p-12 rounded-[3rem] border border-slate-200 overflow-x-auto shadow-inner">
            <canvas id="pdf-canvas"></canvas>
        </div>
        
        <div id="pdf-controls" class="absolute right-8 top-1/2 -translate-y-1/2 flex flex-col items-center gap-4 z-50 opacity-0 group-hover:opacity-100 transition-all duration-500 translate-x-4 group-hover:translate-x-0">
            <button id="prev" class="w-14 h-14 glass-control text-white rounded-2xl flex items-center justify-center shadow-2xl hover:bg-indigo-600 transition-all active:scale-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <div class="py-5 px-3 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-100 flex flex-col items-center gap-1 min-w-[60px]">
                <span id="current_page" class="text-sm font-black text-indigo-600">1</span>
                <div class="w-4 h-[1px] bg-slate-200"></div>
                <span id="total_pages" class="text-[10px] font-bold text-slate-400">0</span>
            </div>

            <button id="next" class="w-14 h-14 glass-control text-white rounded-2xl flex items-center justify-center shadow-2xl hover:bg-indigo-600 transition-all active:scale-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    <div id="text" class="bg-white p-10 md:p-16 rounded-[3rem] border border-slate-100 shadow-sm prose prose-indigo max-w-none prose-img:rounded-3xl prose-headings:font-black"></div>
</div>

<div id="content-zoom" class="tab-content hidden animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Pertemuan Kelas Zoom</h3>
            <p class="text-slate-400 text-xs font-medium">Bergabung ke kelas video konferensi langsung yang dijadwalkan oleh pengajar Anda.</p>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex items-center gap-2.5 mb-8">
        <button onclick="setZoomFilter('all')" id="filter-zoom-all" class="px-5 py-2.5 bg-indigo-650 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
            Semua
        </button>
        <button onclick="setZoomFilter('active')" id="filter-zoom-active" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-650 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all">
            Aktif / Terjadwal
        </button>
        <button onclick="setZoomFilter('ended')" id="filter-zoom-ended" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-650 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all">
            Selesai
        </button>
    </div>

    <div id="zoom-loading-message" class="py-20 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest animate-pulse">Memuat jadwal pertemuan...</div>
    <div id="zoom-meetings-container" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
</div>

<div id="content-tugas" class="tab-content hidden animate-fade-in">
    <form id="submit-task-form">
        @csrf
        <input type="hidden" name="task_course_id" id="task_course_id">
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" id="user_id">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight" id="taskTitle"></h3>
                        <div class="px-5 py-2.5 bg-red-50 text-red-600 rounded-2xl flex items-center gap-2.5 border border-red-100">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.1em]" id="deadlineDate"></span>
                        </div>
                    </div>
                    <p class="text-slate-600 font-medium leading-relaxed text-lg" id="taskDescription"></p>
                </div>
                <div id="taskSubmissionContainer"></div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900 p-10 rounded-[3rem] text-white relative overflow-hidden shadow-2xl shadow-slate-200">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/20 blur-[60px]"></div>
                    <div class="relative z-10">
                        <h4 class="text-xl font-black mb-3 uppercase tracking-tighter">Submission</h4>
                        <p class="text-slate-400 text-sm font-medium mb-8 leading-relaxed">Pastikan file atau tautan yang Anda kirimkan sudah diperiksa kembali.</p>
                        <div id="form-action-area"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    const courseId = {{ $id }};
    
    function switchTab(tab) {
        $('.tab-content').addClass('hidden');
        $(`#content-${tab}`).removeClass('hidden');
        $('[id^="btn-"]').removeClass('nav-tab-active text-indigo-600').addClass('text-slate-400');
        $(`#btn-${tab}`).addClass('nav-tab-active text-indigo-600').removeClass('text-slate-400');
        
        if (tab === 'zoom') {
            fetchZoomMeetings();
        }
    }

    function fetchTaskData() {
        $.ajax({
            url: '/api/task/course/' + courseId,
            method: 'GET',
            success: function(res) {
                if (res.status === "success" && res.data.length > 0) {
                    const task = res.data[0];
                    $('#task_course_id').val(task.id);
                    $('#taskTitle').text(task.name);
                    $('#deadlineDate').text(task.deadline_format);
                    $('#taskDescription').text(task.description);

                    $.ajax({
                        url: '/api/Apiassigment/' + task.id,
                        method: 'GET',
                        success: function(assRes) {
                            const container = $('#taskSubmissionContainer');
                            const actionArea = $('#form-action-area');
                            container.empty(); actionArea.empty();

                            if (assRes.status === "success" && assRes.data.length > 0) {
                                const ass = assRes.data[0];
                                const gradeHtml = ass.grade != null ? `
                                    <div class="mt-6 p-6 bg-indigo-50 rounded-[2rem] border border-indigo-100 flex items-center justify-between">
                                        <span class="text-xs font-black text-indigo-700 uppercase tracking-widest">Grade Result</span>
                                        <span class="text-3xl font-black text-indigo-700">${ass.grade}</span>
                                    </div>` : '';

                                container.append(`
                                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                                        <div class="flex items-center gap-6">
                                            <div class="w-16 h-16 bg-slate-50 text-3xl flex items-center justify-center rounded-2xl group-hover:bg-indigo-50 transition-all">${task.type === 'file' ? '📂' : '🔗'}</div>
                                            <div>
                                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Status: Terkirim</p>
                                                <h5 class="text-slate-900 font-extrabold text-lg">Pekerjaan Anda telah diunggah</h5>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            ${ass.grade == null ? `<button type="button" class="delete-assignment w-12 h-12 bg-red-50 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm" data-id="${ass.id}"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>` : ''}
                                            <a href="${task.type === 'file' ? '/storage/'+ass.file : ass.link}" target="_blank" class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                                        </div>
                                    </div>
                                    ${gradeHtml}
                                `);
                            } else {
                                if (task.type === "file") {
                                    actionArea.append(`<div class="mb-6"><label class="block text-[10px] font-black text-slate-500 uppercase mb-3 tracking-widest ml-1">Archive (ZIP/PDF)</label><input type="file" name="file" class="w-full text-xs font-bold text-slate-400 file:mr-4 file:py-3.5 file:px-6 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer transition-all"></div>`);
                                } else {
                                    actionArea.append(`<div class="mb-6"><label class="block text-[10px] font-black text-slate-500 uppercase mb-3 tracking-widest ml-1">URL Submission</label><input type="url" name="link" class="w-full px-6 py-4.5 bg-white/5 border border-white/10 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm font-bold text-white" placeholder="https://github.com/..."></div>`);
                                }
                                actionArea.append(`<button type="button" id="submitTaskButton" class="w-full py-4.5 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-2xl hover:bg-indigo-500 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Submit Assignment</button>`);
                            }
                        }
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        fetchTaskData();

        $(document).on('click', '#submitTaskButton', function() {
            const btn = $(this);
            const formData = new FormData($('#submit-task-form')[0]);
            btn.prop('disabled', true).html('<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mx-auto"></div>');

            $.ajax({
                url: '/api/assigment/post',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    toastr.success('Tugas berhasil dikirim!');
                    fetchTaskData();
                },
                error: () => {
                    toastr.error('Terjadi kesalahan pengiriman.');
                    btn.prop('disabled', false).html('Submit Assignment');
                }
            });
        });

        $(document).on('click', '.delete-assignment', function() {
            if(confirm('Hapus kiriman tugas ini?')) {
                $.ajax({
                    url: '/api/assigment/delete/' + $(this).data('id'),
                    method: 'DELETE',
                    success: function() {
                        toastr.success('Tugas dihapus');
                        fetchTaskData();
                    }
                });
            }
        });

        fetch(`/api/student/course/show/${courseId}`)
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    const course = res.data;
                    $('#class-name1').text(course.name);
                    $('#class-link').attr('href', `/student/classroom/course/${course.classroom_id}`);

                    if (course.type === 'link' && course.link) {
                        const isYoutube = course.link.includes('youtube.com') || course.link.includes('youtu.be');
                        const isZoom = course.link.includes('zoom.us') || course.link.includes('zoom.com');
                        
                        if (isZoom) {
                            try {
                                const urlObj = new URL(course.link);
                                const pathParts = urlObj.pathname.split('/');
                                const jIndex = pathParts.indexOf('j');
                                if (jIndex !== -1 && pathParts[jIndex + 1]) {
                                    meetingNumber = pathParts[jIndex + 1];
                                } else {
                                    meetingNumber = course.link.match(/\/j\/(\d+)/)[1];
                                }
                                passcode = urlObj.searchParams.get('pwd') || '';
                            } catch (e) {
                                console.error('Failed to parse Zoom URL', e);
                            }
                            
                            $('#link').removeClass('hidden').html(`
                                <div id="zoom-meeting-container" class="relative bg-slate-950 rounded-[3rem] p-8 text-white min-h-[450px] flex flex-col items-center justify-center overflow-hidden shadow-2xl border border-slate-900">
                                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(79,70,229,0.1),transparent)] pointer-events-none"></div>
                                    
                                    <div id="zoom-lobby" class="z-10 text-center flex flex-col items-center gap-6 max-w-md">
                                        <div class="w-20 h-20 bg-indigo-500/10 rounded-[2rem] flex items-center justify-center text-4xl shadow-inner animate-pulse">📹</div>
                                        <div>
                                            <h3 class="text-2xl font-black tracking-tight mb-2 uppercase">Kelas Zoom Virtual</h3>
                                            <p class="text-slate-400 text-sm font-medium leading-relaxed">Bergabung ke kelas video konferensi langsung di dalam portal pembelajaran ini.</p>
                                        </div>
                                        <button id="btn-join-zoom" class="px-8 py-4.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase text-[10px] tracking-[0.2em] rounded-2xl shadow-xl shadow-indigo-600/30 transition-all active:scale-95">
                                            Gabung Kelas Sekarang
                                        </button>
                                    </div>
                                    
                                    <div id="meetingSDKElement" class="hidden w-full h-[650px] rounded-[2rem] overflow-hidden bg-slate-900 border border-slate-800 z-10"></div>
                                </div>
                                
                                <!-- Accessibility Toggle -->
                                <div class="mt-6 flex justify-between items-center bg-white border border-slate-200 rounded-[2rem] p-6 shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg">♿</div>
                                        <div>
                                            <h5 class="text-sm font-black text-slate-800">Fitur Aksesibilitas Disabilitas</h5>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">Subtitel & Terjemahan Suara Otomatis</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="toggle-disability" class="sr-only peer">
                                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                                
                                <!-- Disability Panel -->
                                <div id="disability-panel" class="hidden mt-4 bg-slate-900 border border-slate-850 rounded-[2.5rem] p-6 shadow-xl relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl"></div>
                                    <div class="relative z-10">
                                        <div class="flex flex-wrap items-center justify-between gap-4 mb-5 pb-5 border-b border-slate-800/60">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 bg-indigo-500 rounded-full animate-ping" id="caption-status-dot"></span>
                                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-[0.2em]">Penerjemah Subtitle Aktif</span>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-4 text-white">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] font-black text-slate-500 uppercase">Bahasa Sumber:</span>
                                                    <select id="spoken-lang" class="bg-slate-800 border border-slate-700 text-xs rounded-xl px-3 py-1.5 font-bold focus:outline-none cursor-pointer">
                                                        <option value="id-ID">Bahasa Indonesia</option>
                                                        <option value="en-US">English</option>
                                                        <option value="ja-JP">日本語</option>
                                                    </select>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] font-black text-slate-500 uppercase">Bahasa Target:</span>
                                                    <select id="subtitle-lang" class="bg-slate-800 border border-slate-700 text-xs rounded-xl px-3 py-1.5 font-bold focus:outline-none cursor-pointer">
                                                        <option value="id">Bahasa Indonesia</option>
                                                        <option value="en">English</option>
                                                        <option value="ja">日本語</option>
                                                        <option value="ar">العربية</option>
                                                        <option value="zh">中文</option>
                                                    </select>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] font-black text-slate-500 uppercase">Ukuran Teks:</span>
                                                    <select id="font-size" class="bg-slate-800 border border-slate-700 text-xs rounded-xl px-3 py-1.5 font-bold focus:outline-none cursor-pointer">
                                                        <option value="text-lg">Normal</option>
                                                        <option value="text-2xl" selected>Besar</option>
                                                        <option value="text-3xl">Sangat Besar</option>
                                                        <option value="text-4xl">Maksimal (Disabilitas)</option>
                                                    </select>
                                                </div>
                                                <button id="btn-toggle-mic" class="px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 hover:bg-indigo-600/30 font-black text-[9px] uppercase tracking-wider rounded-xl transition-all active:scale-95">
                                                    Aktifkan Mic Saya
                                                </button>
                                            </div>
                                        </div>
                                        <div id="subtitles-display-box" class="min-h-[120px] max-h-[220px] overflow-y-auto bg-black/50 border border-slate-800 rounded-2xl p-5 flex flex-col justify-end text-center">
                                            <p id="active-subtitle-text" class="text-yellow-400 font-extrabold text-2xl tracking-wide leading-relaxed drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">
                                                Menunggu pembicara bersuara...
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        } else if (isYoutube) {
                            const videoId = new URL(course.link).searchParams.get('v') || course.link.split('/').pop();
                            $('#link').removeClass('hidden').html(`<div class="video-container shadow-2xl shadow-indigo-100"><iframe src="https://www.youtube.com/embed/${videoId}" allowfullscreen></iframe></div>`);
                        } else {
                            $('#link').removeClass('hidden').html(`<div class="bg-white p-5 rounded-[3rem] border border-slate-100 shadow-2xl overflow-hidden"><iframe src="${course.link}" class="w-full h-[700px] rounded-[2rem]" frameborder="0"></iframe></div>`);
                        }
                    } else if (course.type === 'document' && course.document) {
                        $('#document').removeClass('hidden');
                        let pdfDoc = null, pageNum = 1;
                        pdfjsLib.getDocument(`/storage/${course.document}`).promise.then(doc => {
                            pdfDoc = doc;
                            $('#total_pages').text(doc.numPages);
                            renderPage(pageNum);
                        });
                        function renderPage(num) {
                            pdfDoc.getPage(num).then(page => {
                                const canvas = document.getElementById('pdf-canvas');
                                const ctx = canvas.getContext('2d');
                                const viewport = page.getViewport({ scale: 1.5 });
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                page.render({ canvasContext: ctx, viewport: viewport });
                                $('#current_page').text(num);
                            });
                        }
                        $('#prev').click(() => { if (pageNum > 1) { pageNum--; renderPage(pageNum); } });
                        $('#next').click(() => { if (pageNum < pdfDoc.numPages) { pageNum++; renderPage(pageNum); } });
                    } else if (course.type === 'text_course') {
                        $('#text').html(`<h1 class="text-4xl font-black text-slate-900 mb-8">${course.name}</h1><div class="text-slate-600 font-medium text-lg">${course.text_course}</div>`);
                    }
                }
            });
    });

    // Zoom & Disability Features Variables
    let meetingNumber = '';
    let passcode = '';
    let zoomSignature = '';
    let zoomSdkKey = '';
    let captionPollInterval = null;
    let lastCaptionId = 0;
    let recognition = null;
    let isRecognizing = false;
    let captionTimeout = null;
    let translationAbortController = null;
    let translationTimeout = null;

    // Join Zoom Meeting SDK
    $(document).on('click', '#btn-join-zoom', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mx-auto"></div>');
        
        $.ajax({
            url: '/api/zoom/signature',
            method: 'POST',
            data: {
                meeting_number: meetingNumber,
                role: 0
            },
            success: function(sigRes) {
                zoomSignature = sigRes.signature;
                zoomSdkKey = sigRes.sdkKey;
                
                $('#zoom-lobby').addClass('hidden');
                $('#meetingSDKElement').removeClass('hidden');
                
                initializeZoomSDK();
            },
            error: function() {
                toastr.error('Gagal mendapatkan signature Zoom. Kredensial tidak valid.');
                btn.prop('disabled', false).html('Gabung Kelas Sekarang');
            }
        });
    });

    function initializeZoomSDK() {
        const meetingSDKElement = document.getElementById('meetingSDKElement');
        
        ZoomMtgEmbedded.createClient().init({
            zoomAppRoot: meetingSDKElement,
            language: 'en-US',
            customize: {
                video: {
                    defaultViewType: 'gallery'
                }
            }
        }).then((client) => {
            client.join({
                sdkKey: zoomSdkKey,
                signature: zoomSignature,
                meetingNumber: meetingNumber,
                passCode: passcode,
                userName: '{{ auth()->user()->name }}',
                userEmail: '{{ auth()->user()->email }}'
            }).then(() => {
                toastr.success('Berhasil terhubung ke Zoom Meeting!');
            }).catch((err) => {
                console.error('Failed to join Zoom meeting', err);
                toastr.error('Gagal bergabung ke Zoom Meeting.');
            });
        }).catch((err) => {
            console.error('Failed to init Zoom Web SDK', err);
            toastr.error('Inisialisasi Zoom Web SDK gagal.');
        });
    }

    // Toggle Disability Features
    $(document).on('change', '#toggle-disability', function() {
        const checked = this.checked;
        const panel = $('#disability-panel');
        
        if (checked) {
            panel.removeClass('hidden').addClass('block');
            startCaptionPolling();
            toastr.success('Fitur disabilitas (subtitle) diaktifkan!');
        } else {
            panel.removeClass('block').addClass('hidden');
            stopCaptionPolling();
            stopMic();
            toastr.info('Fitur disabilitas dinonaktifkan.');
        }
    });

    function startCaptionPolling() {
        if (captionPollInterval) clearInterval(captionPollInterval);
        
        captionPollInterval = setInterval(() => {
            $.ajax({
                url: `/api/live-captions?course_id=${courseId}&last_id=${lastCaptionId}`,
                method: 'GET',
                success: function(capRes) {
                    if (capRes.success && capRes.data.length > 0) {
                        capRes.data.forEach(caption => {
                            lastCaptionId = Math.max(lastCaptionId, caption.id);
                            processCaption(caption.text, caption.language, caption.user.name);
                        });
                    }
                }
            });
        }, 2000);
    }

    function stopCaptionPolling() {
        if (captionPollInterval) {
            clearInterval(captionPollInterval);
            captionPollInterval = null;
        }
    }

    function processCaption(text, srcLang, speakerName) {
        const targetLang = $('#subtitle-lang').val();
        const srcLangShort = srcLang.split('-')[0];
        
        if (srcLangShort !== targetLang && targetLang !== 'none') {
            const apiUrl = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${srcLangShort}&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
            
            fetch(apiUrl)
                .then(r => r.json())
                .then(transRes => {
                    if (transRes && transRes[0]) {
                        const translatedText = transRes[0].map(x => x ? x[0] : '').join('');
                        renderSubtitleText(speakerName, translatedText, true);
                    } else {
                        renderSubtitleText(speakerName, text, false);
                    }
                })
                .catch(() => {
                    renderSubtitleText(speakerName, text, false);
                });
        } else {
            renderSubtitleText(speakerName, text, false);
        }
    }

    function renderSubtitleText(speaker, text, isTranslated) {
        const fontSize = $('#font-size').val();
        const displayBox = $('#subtitles-display-box');
        const textEl = $('#active-subtitle-text');
        
        textEl.removeClass('text-lg text-2xl text-3xl text-4xl').addClass(fontSize);
        
        const flag = isTranslated ? ' <span class="text-xs text-indigo-400 font-bold uppercase">(Translated)</span>' : '';
        textEl.html(`<span class="text-slate-400 font-medium">${speaker}:</span> ${text}${flag}`);
        
        displayBox.scrollTop(displayBox[0].scrollHeight);
        
        if (captionTimeout) clearTimeout(captionTimeout);
        captionTimeout = setTimeout(() => {
            textEl.html('<span class="text-slate-600 font-medium">Menunggu pembicara bersuara...</span>');
        }, 12000);
    }

    // Toggle Mic Captions
    $(document).on('click', '#btn-toggle-mic', function() {
        if (isRecognizing) {
            stopMic();
        } else {
            startMic();
        }
    });

    function startMic() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            toastr.error('Browser Anda tidak mendukung Web Speech API untuk caption.');
            return;
        }
        
        recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = $('#spoken-lang').val();
        
        recognition.onstart = function() {
            isRecognizing = true;
            $('#btn-toggle-mic').removeClass('bg-indigo-600/20 text-indigo-400 border-indigo-500/30')
                .addClass('bg-red-600 text-white border-red-500')
                .text('Nonaktifkan Mic');
            $('#caption-status-dot').removeClass('bg-indigo-500').addClass('bg-red-500');
            toastr.success('Caption Mikrofon Aktif!');
        };
        
        recognition.onresult = function(event) {
            let interimTranscript = '';
            let finalTranscript = '';
            
            for (let i = event.resultIndex; i < event.results.length; ++i) {
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
            const targetLang = $('#subtitle-lang').val();
            const srcLangShort = spokenLang.split('-')[0];

            if (targetLang && targetLang !== 'none' && srcLangShort !== targetLang) {
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
                                renderSubtitleText('{{ auth()->user()->name }} (Saya)', translatedText, true);
                            }
                        })
                        .catch(err => {
                            if (err.name !== 'AbortError') {
                                console.error("Translation error:", err);
                            }
                        });
                }, 50); // 50ms debounce
            } else {
                renderSubtitleText('{{ auth()->user()->name }} (Saya)', liveText, false);
            }
            
            if (finalTranscript.trim()) {
                const text = finalTranscript.trim();
                $.ajax({
                    url: '/api/live-captions',
                    method: 'POST',
                    data: {
                        course_id: courseId,
                        text: text,
                        language: spokenLang
                    }
                });
            }
        };
        
        recognition.onerror = function(event) {
            console.error('Speech Recognition Error', event);
        };
        
        recognition.onend = function() {
            if (isRecognizing) {
                recognition.start();
            }
        };
        
        recognition.start();
    }

    function stopMic() {
        isRecognizing = false;
        if (recognition) {
            recognition.stop();
        }
        $('#btn-toggle-mic').addClass('bg-indigo-600/20 text-indigo-400 border-indigo-500/30')
            .removeClass('bg-red-650 text-white border-red-500')
            .text('Aktifkan Mic Saya');
        $('#caption-status-dot').addClass('bg-indigo-500').removeClass('bg-red-500');
    }

    // ZOOM HISTORY TABS FUNCTIONS FOR STUDENT
    let currentZoomFilter = 'all';

    function setZoomFilter(filter) {
        currentZoomFilter = filter;
        $('[id^="filter-zoom-"]').removeClass('bg-indigo-650 text-white shadow-sm').addClass('bg-white border border-slate-200 text-slate-650 hover:bg-slate-50');
        $(`#filter-zoom-${filter}`).addClass('bg-indigo-650 text-white shadow-sm').removeClass('bg-white border border-slate-200 text-slate-650 hover:bg-slate-50');
        applyZoomFilter();
    }

    function applyZoomFilter() {
        const cards = $('.zoom-meeting-card');
        let visibleCount = 0;
        
        cards.each(function() {
            const cardStatus = $(this).data('status');
            if (currentZoomFilter === 'all' || cardStatus === currentZoomFilter) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        if (visibleCount === 0) {
            if ($('#zoom-empty-filter-message').length === 0) {
                $('#zoom-meetings-container').append(`
                    <div id="zoom-empty-filter-message" class="col-span-full py-16 bg-white border border-dashed border-slate-200 rounded-2xl text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                        Tidak ada pertemuan dengan status ini
                    </div>
                `);
            } else {
                $('#zoom-empty-filter-message').show();
            }
        } else {
            $('#zoom-empty-filter-message').hide();
        }
    }

    function fetchZoomMeetings() {
        $('#zoom-loading-message').show();
        $('#zoom-meetings-container').empty();
        
        $.ajax({
            url: `/api/zoom-meetings/${courseId}`,
            method: 'GET',
            success: function(res) {
                $('#zoom-loading-message').hide();
                if (res.success && res.data.length > 0) {
                    res.data.sort((a, b) => {
                        const aEnded = a.status === 'ended';
                        const bEnded = b.status === 'ended';
                        if (aEnded !== bEnded) {
                            return aEnded ? 1 : -1;
                        }
                        return new Date(a.meeting_time) - new Date(b.meeting_time);
                    });
                    res.data.forEach(meet => {
                        const dateStr = new Date(meet.meeting_time).toLocaleString('id-ID', {
                            dateStyle: 'medium',
                            timeStyle: 'short'
                        });
                        
                        let meetNum = '';
                        let pwd = '';
                        try {
                            const urlObj = new URL(meet.zoom_link);
                            const pathParts = urlObj.pathname.split('/');
                            const jIndex = pathParts.indexOf('j');
                            if (jIndex !== -1 && pathParts[jIndex + 1]) {
                                meetNum = pathParts[jIndex + 1];
                            } else {
                                meetNum = meet.zoom_link.match(/\/j\/(\d+)/)[1];
                            }
                             pwd = meet.passcode || urlObj.searchParams.get('pwd') || '';
                        } catch (e) {}

                        const isEnded = meet.status === 'ended';
                        const statusBadge = isEnded
                            ? `<span class="px-2 py-0.5 bg-red-50 text-red-755 border border-red-100 font-bold text-[8px] uppercase tracking-wider rounded-md">Selesai</span>`
                            : `<span class="px-2 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-650 font-bold text-[8px] uppercase tracking-wider rounded-md">Kelas Virtual</span>`;

                        const btnHtml = isEnded
                            ? `<button class="flex-grow py-2 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl cursor-not-allowed" disabled>Sesi Berakhir</button>`
                            : `<button onclick="startZoomSession('${meet.zoom_link}', '${pwd}', '${meet.id}')" class="flex-grow py-2 bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm">📹 Gabung Rapat</button>`;

                        const descHtml = meet.description ? `<p class="text-slate-550 text-xs font-semibold mb-3 leading-relaxed">${meet.description}</p>` : '';
                        const card = `
                            <div class="zoom-meeting-card bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:border-slate-350 transition-all flex flex-col justify-between ${isEnded ? 'opacity-60' : ''}" data-status="${meet.status || 'active'}">
                                <div class="text-left">
                                    <div class="flex items-center justify-between mb-4">
                                        ${statusBadge}
                                        <span class="text-[10px] font-bold text-slate-400">${dateStr}</span>
                                    </div>
                                    <h4 class="text-sm font-extrabold text-slate-900 mb-1.5 leading-tight">${meet.title}</h4>
                                    ${descHtml}
                                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-5">ID: ${meetNum} &bull; Passcode: ${pwd}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    ${btnHtml}
                                </div>
                            </div>
                        `;
                        $('#zoom-meetings-container').append(card);
                    });
                    applyZoomFilter();
                } else {
                    $('#zoom-meetings-container').html(`
                        <div class="col-span-full py-16 bg-white border border-dashed border-slate-200 rounded-2xl text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                            Belum ada pertemuan Zoom terjadwal dari dosen.
                        </div>
                    `);
                }
            },
            error: function() {
                $('#zoom-loading-message').hide();
                toastr.error('Gagal mengambil data pertemuan Zoom.');
            }
        });
    }

    function startZoomSession(link, rawPasscode, dbId) {
        try {
            const urlObj = new URL(link);
            const pathParts = urlObj.pathname.split('/');
            const jIndex = pathParts.indexOf('j');
            let meetingNumber = '';
            if (jIndex !== -1 && pathParts[jIndex + 1]) {
                meetingNumber = pathParts[jIndex + 1];
            } else {
                meetingNumber = link.match(/\/j\/(\d+)/)[1];
            }
            const passcode = rawPasscode || urlObj.searchParams.get('pwd') || '';
            
            // Redirect to standalone zoom-session view in a new window/tab
            const zoomUrl = `/zoom-session?meeting_number=${meetingNumber}&passcode=${encodeURIComponent(passcode)}&role=0&course_id=${courseId}&meeting_db_id=${dbId}`;
            window.open(zoomUrl, '_blank');
        } catch (e) {
            toastr.error('Format tautan Zoom tidak valid.');
        }
    }
</script>
@endsection