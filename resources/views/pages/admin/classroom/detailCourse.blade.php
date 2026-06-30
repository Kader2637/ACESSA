@extends('layouts.admin.app')

@section('title', 'Audit Materi — Panel Admin')
@section('page_title', 'Audit Materi &amp; Pembelajaran')

@section('style')
<style>
    .tab-btn { position: relative; transition: all 0.2s ease; }
    .tab-btn.active { color: #4f46e5; border-bottom: 2px solid #4f46e5; }
    #pdf-canvas { max-width: 100%; height: auto; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .pdf-nav-btn { width: 40px; height: 40px; background: white; border: 1px solid #e2e8f0; border-radius: 50%; display: flex; items-center: center; justify-content: center; transition: all 0.2s; }
    .pdf-nav-btn:hover { background: #f1f5f9; color: #0f172a; }
    .content-card { background: white; border-radius: 1.5rem; border: 1px solid #e2e8f0; padding: 2rem; }
</style>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Audit: <span id="class-name1" class="text-indigo-650"></span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5 font-sans">Inspeksi bahan ajar dan ketersediaan tugas siswa secara real-time</p>
    </div>
    <a id="back-button" href="#" class="px-4 py-2 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-slate-800 transition-all shadow-sm active:scale-[0.98] shrink-0 flex items-center gap-1.5">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Kelas
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-2xl p-2 flex items-center gap-2 overflow-x-auto mb-6" data-aos="fade-up">
    <button onclick="showContent('materi')" id="materi-tab" class="tab-btn active px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Lihat Materi</button>
    <button onclick="showContent('tugas')" id="tugas-tab" class="tab-btn px-5 py-2.5 font-bold text-xs text-slate-500 hover:text-slate-800 rounded-lg transition-all">Daftar Tugas</button>
</div>

<div id="v-pills-tabContent" class="mb-20" data-aos="fade-up" data-aos-delay="50">
    <div id="materi-content" class="content-pane space-y-6">
        <div id="link" class="hidden rounded-2xl border border-slate-200 bg-white p-4"></div>
        
        <div id="document" class="hidden relative">
            <div class="flex justify-center mb-6">
                <canvas id="pdf-canvas"></canvas>
            </div>
            <div class="flex items-center justify-center gap-4 bg-white border border-slate-200 p-2.5 rounded-full shadow-sm max-w-xs mx-auto">
                <button id="prev" class="pdf-nav-btn"><i data-feather="chevron-left" class="w-4 h-4"></i></button>
                <div class="px-3 py-1.5 font-bold text-slate-800 text-xs">
                    Halaman <span id="page-num" class="text-indigo-650">1</span> / <span id="page-count">0</span>
                </div>
                <button id="next" class="pdf-nav-btn"><i data-feather="chevron-right" class="w-4 h-4"></i></button>
            </div>
        </div>

        <div id="text" class="hidden content-card prose prose-slate max-w-none text-left"></div>
    </div>

    <div id="tugas-content" class="content-pane hidden space-y-6">
        <div class="flex items-center gap-2 px-2">
            <span class="w-2 h-2 rounded-full bg-indigo-650"></span>
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Informasi Tugas Kuliah</h4>
        </div>

        <div id="loading-message" class="py-24 text-center bg-white rounded-2xl border border-slate-200">
            <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-650 rounded-full animate-spin mx-auto mb-3"></div>
            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase animate-pulse">Menghubungkan data tugas...</p>
        </div>

        <div id="tasks-container" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    function showContent(id) {
        $('.content-pane').addClass('hidden');
        $(`#${id}-content`).removeClass('hidden');
        $('.tab-btn').removeClass('active');
        $(`#${id}-tab`).addClass('active');
    }

    $(document).ready(function() {
        const courseId = {{ $id }};
        
        // Fetch Materials
        fetch(`/api/teacher/course/show/${courseId}`)
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    const c = res.data;
                    $('#class-name1').text(c.name);
                    $('#back-button').attr('href', `/admin/classroom/detail/${c.classroom_id}`);

                    if (c.type === 'link' && c.link) {
                        $('#link').removeClass('hidden');
                        const isZoom = c.link.includes('zoom.us') || c.link.includes('zoom.com');
                        if (c.link.includes('youtube.com') || c.link.includes('youtu.be')) {
                            const vidId = new URL(c.link).searchParams.get('v') || c.link.split('/').pop();
                            $('#link').html(`<div class="aspect-video"><iframe class="w-full h-full rounded-2xl" src="https://www.youtube.com/embed/${vidId}" frameborder="0" allowfullscreen></iframe></div>`);
                        } else if (isZoom) {
                            $('#link').html(`
                                <div class="bg-slate-900 text-white p-8 rounded-2xl text-center border border-slate-800 flex flex-col items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-2xl">📹</div>
                                    <h4 class="text-base font-bold uppercase tracking-tight">Sesi Kelas Virtual Zoom</h4>
                                    <p class="text-slate-400 text-xs max-w-sm">Materi ini diselenggarakan via video conference. Silakan klik tombol di bawah untuk bergabung.</p>
                                    <a href="${c.link}" target="_blank" class="mt-2 px-6 py-2.5 bg-indigo-650 hover:bg-indigo-700 rounded-xl text-xs font-bold text-white transition-all">Buka Zoom Meeting</a>
                                </div>
                            `);
                        } else {
                            $('#link').html(`<iframe src="${c.link}" class="w-full h-[600px] rounded-2xl border border-slate-200" frameborder="0"></iframe>`);
                        }
                    } else if (c.type === 'document' && c.document) {
                        $('#document').removeClass('hidden');
                        const pdfUrl = `/storage/${c.document}`;
                        let pdfDoc = null, pageNum = 1;
                        
                        pdfjsLib.getDocument(pdfUrl).promise.then(doc => {
                            pdfDoc = doc;
                            $('#page-count').text(doc.numPages);
                            renderPage(pageNum);
                        });

                        function renderPage(num) {
                            pdfDoc.getPage(num).then(page => {
                                const viewport = page.getViewport({ scale: 1.2 });
                                const canvas = document.getElementById('pdf-canvas'), context = canvas.getContext('2d');
                                canvas.height = viewport.height; canvas.width = viewport.width;
                                page.render({ canvasContext: context, viewport: viewport });
                                $('#page-num').text(num);
                            });
                        }

                        $('#prev').click(() => { if (pageNum <= 1) return; pageNum--; renderPage(pageNum); });
                        $('#next').click(() => { if (pageNum >= pdfDoc.numPages) return; pageNum++; renderPage(pageNum); });
                    } else if (c.type === 'text_course') {
                        $('#text').removeClass('hidden').html(`<h2 class="text-xl font-extrabold mb-4 text-slate-900">${c.name}</h2><div class="text-slate-650 leading-relaxed text-sm">${c.text_course}</div>`);
                    }
                }
            });

        // Fetch Tasks
        function fetchTasks() {
            $('#loading-message').removeClass('hidden');
            $('#tasks-container').empty();
            
            $.ajax({
                url: `/api/task/course/${courseId}`,
                method: 'GET',
                success: function(res) {
                    $('#loading-message').addClass('hidden');
                    if (res.data && res.data.length > 0) {
                        res.data.forEach(t => {
                            $('#tasks-container').append(`
                                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col group transition-all text-left border-l-4 border-l-indigo-650">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="bg-indigo-50 border border-indigo-100 text-indigo-650 p-2 rounded-lg"><i data-feather="clipboard" class="w-4 h-4"></i></div>
                                        <div class="text-right">
                                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mb-1">Batas Waktu Pengumpulan</p>
                                            <span class="text-[9px] font-bold text-indigo-650 uppercase bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">${t.deadline_format}</span>
                                        </div>
                                    </div>
                                    <h5 class="text-sm font-extrabold text-slate-900 mb-1.5 leading-snug truncate group-hover:text-indigo-600 transition-colors">${t.name}</h5>
                                    <p class="text-slate-500 text-xs font-semibold leading-relaxed mb-6 h-12 overflow-hidden">${t.description || 'Tidak ada instruksi detail untuk tugas ini.'}</p>
                                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Audit Mode Aktif</span>
                                        <a href="/admin/detailTask/${t.id}" class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center hover:bg-indigo-650 transition-all shadow-sm">
                                            <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                </div>
                            `);
                        });
                        feather.replace();
                    } else {
                        $('#tasks-container').html('<div class="col-span-full py-16 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl font-bold text-xs">Belum ada tugas yang dibuat untuk materi ini.</div>');
                    }
                },
                error: function() {
                    $('#loading-message').addClass('hidden');
                    toastr.error('Gagal mengambil data tugas.');
                }
            });
        }

        fetchTasks();
    });
</script>
@endsection