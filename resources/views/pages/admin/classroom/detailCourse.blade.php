@extends('layouts.admin.app')

@section('page_title', 'Material Audit')

@section('style')
<style>
    .tab-btn { position: relative; transition: all 0.3s ease; }
    .tab-btn.active { color: #4f46e5; }
    .tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: #4f46e5; }
    
    /* PDF Viewer Style */
    #pdf-canvas { max-width: 100%; height: auto; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1); border: 1px solid #f1f5f9; }
    .pdf-nav-btn { width: 55px; height: 55px; background: white; border: 1px solid #e2e8f0; border-radius: 50%; display: flex; items-center: center; justify-content: center; transition: all 0.2s; shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .pdf-nav-btn:hover { background: #4f46e5; color: white; border-color: #4f46e5; transform: scale(1.1); }
    
    .content-card { background: white; border-radius: 3rem; border: 1px solid #f1f5f9; padding: 2.5rem; }
</style>
@endsection

@section('content')
<div class="mb-10 px-2 flex flex-col md:flex-row md:items-end justify-between gap-6 animate-fade-in">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
            <h4 class="text-xl font-black text-slate-900 tracking-tight uppercase italic">Audit: <span id="class-name1" class="text-indigo-600"></span></h4>
        </div>
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-none">Inspeksi materi pembelajaran dan ketersediaan tugas</p>
    </div>
    <a id="back-button" href="#" class="px-8 py-3 bg-slate-900 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-600 transition-all shadow-lg active:scale-95 flex items-center gap-2">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Kelas
    </a>
</div>

<div class="flex border-b border-slate-200 mb-10 overflow-x-auto no-scrollbar">
    <button onclick="showContent('materi')" id="materi-tab" class="tab-btn active px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 whitespace-nowrap">View Material</button>
    <button onclick="showContent('tugas')" id="tugas-tab" class="tab-btn px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 whitespace-nowrap">Task Ledger</button>
</div>

<div id="v-pills-tabContent" class="mb-32">
    <div id="materi-content" class="content-pane space-y-8 animate-fade-in">
        <div id="link" class="hidden rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100 bg-white p-4"></div>
        
        <div id="document" class="hidden relative">
            <div class="flex justify-center mb-10">
                <canvas id="pdf-canvas"></canvas>
            </div>
            <div class="fixed bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-4 z-[50] bg-white/80 backdrop-blur-xl p-3 rounded-full shadow-2xl border border-slate-100">
                <button id="prev" class="pdf-nav-btn"><i data-feather="chevron-left"></i></button>
                <div class="px-6 py-2 font-black text-slate-900 text-xs">
                    PAGE <span id="page-num" class="text-indigo-600">1</span> / <span id="page-count">0</span>
                </div>
                <button id="next" class="pdf-nav-btn"><i data-feather="chevron-right"></i></button>
            </div>
        </div>

        <div id="text" class="hidden content-card shadow-sm prose prose-indigo max-w-none"></div>
    </div>

    <div id="tugas-content" class="content-pane hidden space-y-8 animate-fade-in">
        <div class="flex items-center gap-3 px-4">
            <h4 class="text-sm font-black text-slate-900 uppercase tracking-[0.3em]">Assignment Overview</h4>
        </div>

        <div id="loading-message" class="py-24 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">Fetching Task Information...</p>
        </div>

        <div id="tasks-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    // Tab Switching Logic
    function showContent(id) {
        $('.content-pane').addClass('hidden');
        $(`#${id}-content`).removeClass('hidden');
        $('.tab-btn').removeClass('active');
        $(`#${id}-tab`).addClass('active');
        
        // Hide PDF controls if not in material tab
        if(id !== 'materi') {
            $('.fixed.bottom-10').fadeOut();
        } else if($('#document').is(':visible')) {
            $('.fixed.bottom-10').fadeIn();
        }
    }

    $(document).ready(function() {
        const courseId = {{ $id }};
        
        // 1. Fetch Materials
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
                            $('#link').html(`<div class="aspect-video"><iframe class="w-full h-full rounded-[2.5rem]" src="https://www.youtube.com/embed/${vidId}" frameborder="0" allowfullscreen></iframe></div>`);
                        } else if (isZoom) {
                            $('#link').html(`
                                <div class="bg-slate-900 text-white p-10 rounded-[3rem] text-center border border-slate-800 shadow-xl flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-3xl">📹</div>
                                    <h4 class="text-xl font-bold uppercase tracking-tight">Materi Rapat Zoom</h4>
                                    <p class="text-slate-400 text-sm max-w-md">Mata kuliah ini menggunakan Zoom Meeting. Klik tombol di bawah untuk membukanya secara langsung.</p>
                                    <a href="${c.link}" target="_blank" class="mt-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-black uppercase tracking-wider text-white transition-all shadow-lg">Buka Zoom Meeting</a>
                                </div>
                            `);
                        } else {
                            $('#link').html(`<iframe src="${c.link}" class="w-full h-[700px] rounded-[2.5rem]" frameborder="0"></iframe>`);
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
                                const viewport = page.getViewport({ scale: 1.5 });
                                const canvas = document.getElementById('pdf-canvas'), context = canvas.getContext('2d');
                                canvas.height = viewport.height; canvas.width = viewport.width;
                                page.render({ canvasContext: context, viewport: viewport });
                                $('#page-num').text(num);
                            });
                        }

                        $('#prev').click(() => { if (pageNum <= 1) return; pageNum--; renderPage(pageNum); });
                        $('#next').click(() => { if (pageNum >= pdfDoc.numPages) return; pageNum++; renderPage(pageNum); });
                    } else if (c.type === 'text_course') {
                        $('#text').removeClass('hidden').html(`<h2 class="text-3xl font-black mb-6 text-slate-900 leading-tight">${c.name}</h2><div class="text-slate-600 leading-relaxed text-lg">${c.text_course}</div>`);
                    }
                }
            });

        // 2. Fetch Tasks (READ ONLY)
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
                                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col group transition-all hover:border-indigo-600">
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="bg-indigo-50 text-indigo-600 p-3 rounded-2xl shadow-inner"><i data-feather="clipboard" class="w-5 h-5"></i></div>
                                        <div class="text-right">
                                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">Deadline Horizon</p>
                                            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-tighter bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100">${t.deadline_format}</span>
                                        </div>
                                    </div>
                                    <h5 class="text-sm font-black text-slate-900 uppercase tracking-tight mb-2 group-hover:text-indigo-600 transition-colors">${t.name}</h5>
                                    <p class="text-slate-500 text-xs font-medium leading-relaxed mb-8 h-12 overflow-hidden">${t.description || 'No instruction briefing provided for this task.'}</p>
                                    <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Monitoring Mode Active</span>
                                        <a href="/admin/detailTask/${t.id}" class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-indigo-600 transition-all shadow-lg shadow-slate-200">
                                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            `);
                        });
                        feather.replace();
                    } else {
                        $('#tasks-container').html('<div class="col-span-full py-20 text-center text-slate-300 font-bold uppercase text-[10px] tracking-[0.4em]">Zero task entries detected in system</div>');
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