@extends('layouts.student.app')

@section('title', 'Forum Diskusi Global — Portal Mahasiswa')
@section('page_title', 'Forum Diskusi')

@section('style')
<style>
    .chat-card { transition: border-color 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; }
    .chat-card:hover { border-color: #f59e0b; box-shadow: 0 10px 15px -10px rgba(245, 158, 11, 0.05); }
    .feed-container { height: 600px; scrollbar-width: thin; }
</style>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" data-aos="fade-up">
    
    {{-- Post Discussion Form --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm h-fit">
        <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider mb-4">Bagikan Diskusi Baru</h3>
        
        <form id="frm-post-discussion" class="flex flex-col gap-4 text-left">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            
            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Pilih Ruang Kelas</label>
                <select name="classroom_id" id="sel-classrooms" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-yellow-500 focus:bg-white transition-all" required>
                    <option value="" disabled selected>— Pilih Kelas Pengiriman —</option>
                </select>
            </div>

            <div>
                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Pesan / Diskusi</label>
                <textarea name="message" rows="4" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 outline-none focus:border-yellow-500 focus:bg-white transition-all resize-none" placeholder="Tulis apa yang ingin Anda diskusikan..." required></textarea>
            </div>

            <button type="submit" class="w-full py-3 bg-yellow-500 hover:bg-yellow-600 text-slate-950 font-bold text-xs rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5 active:scale-[0.98]">
                <i data-feather="send" class="w-4 h-4"></i> Bagikan Pesan
            </button>
        </form>
    </div>

    {{-- Discussions Central Feed --}}
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider">Feed Diskusi Terbaru</h3>
                <span class="px-2 py-0.5 bg-yellow-50 border border-yellow-100 text-yellow-650 text-[9px] font-bold uppercase rounded-md tracking-wider">Live Update</span>
            </div>

            <div id="feed-loading" class="py-24 flex flex-col items-center justify-center">
                <div class="w-8 h-8 border-3 border-slate-100 border-t-yellow-500 rounded-full animate-spin mb-3"></div>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest animate-pulse">Memuat feed diskusi...</p>
            </div>

            <div id="feed-empty" class="hidden py-24 text-center text-slate-400 font-bold uppercase text-[10px] tracking-widest border border-dashed border-slate-200 rounded-2xl">
                Belum ada kiriman diskusi saat ini.
            </div>

            <div id="feed-list" class="feed-container overflow-y-auto pr-2 space-y-4 hidden"></div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        const authId = '{{ auth()->user()->id }}';
        let classroomsLoaded = false;

        // Fetch classrooms list for Select input
        $.ajax({
            url: `/api/student/classroom/data/${authId}`,
            method: 'GET',
            success: function(res) {
                const relations = res.StudentClassroomRelations || [];
                const classes = relations.map(r => r.course);

                if (classes.length > 0) {
                    classroomsLoaded = true;
                    classes.forEach(c => {
                        if (c && c.id && c.name) {
                            $('#sel-classrooms').append(`<option value="${c.id}">${c.name}</option>`);
                        }
                    });
                }
            }
        });

        // Fetch All Discussions Feed
        function loadDiscussionsFeed() {
            $.ajax({
                url: '/api/forum/discussions',
                method: 'GET',
                success: function(res) {
                    $('#feed-loading').hide();
                    const feed = $('#feed-list');
                    feed.empty();

                    const list = res.data || [];
                    if (list.length === 0) {
                        $('#feed-empty').removeClass('hidden');
                        feed.addClass('hidden');
                        return;
                    }

                    $('#feed-empty').addClass('hidden');
                    feed.removeClass('hidden');

                    list.forEach(d => {
                        const dateStr = new Date(d.created_at).toLocaleString('id-ID', {
                            day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
                        }) + ' WIB';

                        const initial = d.user_name ? d.user_name.charAt(0).toUpperCase() : 'A';

                        feed.append(`
                            <div class="chat-card p-4 border border-slate-200 bg-slate-50/20 text-left flex items-start gap-3">
                                <div class="w-9 h-9 bg-slate-800 text-white rounded-lg flex items-center justify-center font-extrabold text-sm shrink-0">
                                    ${initial}
                                </div>
                                <div class="overflow-hidden flex-grow">
                                    <div class="flex items-center justify-between gap-4 flex-wrap">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-xs font-extrabold text-slate-800">${d.user_name}</span>
                                            <span class="px-1.5 py-0.5 bg-yellow-50 text-yellow-650 text-[8px] font-black uppercase rounded-md tracking-wider border border-yellow-100">
                                                ${d.classroom_name}
                                            </span>
                                        </div>
                                        <span class="text-[9px] font-bold text-slate-400">${dateStr}</span>
                                    </div>
                                    <p class="mt-2 text-xs font-semibold text-slate-650 leading-relaxed">${d.message}</p>
                                </div>
                            </div>
                        `);
                    });
                    feather.replace();
                },
                error: () => {
                    $('#feed-loading').hide();
                    $('#feed-empty').removeClass('hidden');
                }
            });
        }

        // Post Message
        $('#frm-post-discussion').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<div class="w-4 h-4 border-2 border-slate-900 border-t-transparent rounded-full animate-spin"></div>');

            $.ajax({
                url: '/api/forum/discussion',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success('Diskusi berhasil dibagikan!');
                    $('#frm-post-discussion')[0].reset();
                    loadDiscussionsFeed();
                },
                error: () => {
                    toastr.error('Gagal membagikan diskusi.');
                },
                complete: () => {
                    btn.prop('disabled', false).html('<i data-feather="send" class="w-4 h-4"></i> Bagikan Pesan');
                    feather.replace();
                }
            });
        });

        // Initial load
        loadDiscussionsFeed();
        // Poll discussions every 5 seconds
        setInterval(loadDiscussionsFeed, 5000);
    });
</script>
@endsection
