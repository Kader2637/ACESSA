@extends('layouts.admin.app')

@section('title', 'Kelola Kelas — Panel Admin')
@section('page_title', 'Database Kelas Global')

@section('style')
<style>
    .course-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; padding: 1.5rem; display: flex; flex-direction: column; }
    .course-card:hover { transform: translateY(-4px); border-color: #cbd5e1; box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05); }
    .image-container { aspect-ratio: 16/9; overflow: hidden; border-radius: 1rem; position: relative; border: 1px solid #f1f5f9; }
</style>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Database <span class="text-indigo-600">Kelas Global</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Memantau seluruh aktivitas materi dan pendaftaran kelas di platform ACESSA</p>
    </div>
    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Global Audit</span>
    </div>
</div>

<div class="container-fluid">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6" id="classroom-container">
        <div class="col-span-full py-24 text-center" id="loading">
            <div class="w-8 h-8 border-3 border-slate-100 border-t-indigo-650 rounded-full animate-spin mx-auto mb-3"></div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">Sinkronisasi data kelas...</p>
        </div>
    </div>

    <div id="no-data" class="hidden py-24 flex flex-col items-center justify-center bg-white rounded-3xl border border-dashed border-slate-200 animate-fade-in">
        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 mb-4">
            <i data-feather="folder-open" class="w-6 h-6"></i>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Data Belum Tersedia</h4>
        <p class="text-slate-400 text-xs mt-1">Belum ada kelas yang dibuat oleh instruktur di platform.</p>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        const fetchClassrooms = () => {
            $('#loading').removeClass('hidden');
            
            $.ajax({
                url: '/api/classroom/admin',
                type: 'GET',
                success: function(response) {
                    $('#loading').addClass('hidden');
                    const container = $('#classroom-container');
                    container.empty();

                    if (response.data.length === 0) {
                        $('#no-data').removeClass('hidden');
                        feather.replace();
                    } else {
                        response.data.forEach(function(course) {
                            const courseThumbnail = (course.thumbnail && course.thumbnail !== 'default.png') ? `/storage/${course.thumbnail}` : '/classaccesa.png';
                            const authorImage = course.profile ? `/storage/${course.profile}` : '/user.png';
                            const desc = course.description.length > 90 ? course.description.substring(0, 90) + '...' : course.description;

                            const percent = (course.total_user / course.limit) * 100;
                            const barColor = percent > 90 ? 'bg-red-500' : (percent > 60 ? 'bg-amber-500' : 'bg-indigo-600');

                            const statusColor = course.status === 'accept' ? 'bg-emerald-100 text-emerald-700' : (course.status === 'reject' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700');
                            const statusText = course.status === 'accept' ? 'Aktif' : (course.status === 'reject' ? 'Ditolak' : 'Menunggu');

                            container.append(`
                                <div class="course-card group animate-fade-in">
                                    <div class="image-container relative mb-4">
                                        <img src="${courseThumbnail}" class="w-full h-full object-cover" onerror="this.src='/classaccesa.png'">
                                        
                                        <div class="absolute top-3 left-3">
                                            <span class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-indigo-600 font-bold text-[8px] uppercase tracking-wider shadow-sm">
                                                ${course.statusClass || 'Umum'}
                                            </span>
                                        </div>

                                        <div class="absolute bottom-3 right-3">
                                            <span class="px-2.5 py-1 ${statusColor} rounded-lg font-bold text-[8px] uppercase tracking-wider shadow-sm">
                                                ${statusText}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex-grow flex flex-col">
                                        <h3 class="text-sm font-extrabold text-slate-900 mb-1.5 truncate group-hover:text-indigo-600 transition-colors leading-tight">${course.name}</h3>
                                        
                                        <div class="flex items-center gap-2 mb-3">
                                            <img src="${authorImage}" class="w-5 h-5 rounded-md object-cover border border-slate-100" onerror="this.src='/user.png'">
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tight truncate">${course.user_name}</p>
                                        </div>

                                        <p class="text-slate-500 text-xs font-semibold leading-relaxed mb-4 h-12 overflow-hidden">${desc}</p>

                                        <div class="bg-slate-50 p-3.5 border border-slate-200/50 rounded-xl mb-4">
                                            <div class="flex justify-between items-center mb-1.5">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Kapasitas Terisi</span>
                                                <span class="text-[9px] font-extrabold text-slate-800">${course.total_user} / ${course.limit}</span>
                                            </div>
                                            <div class="w-full h-1 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="${barColor} h-full rounded-full transition-all duration-1000" style="width: ${percent}%"></div>
                                            </div>
                                        </div>

                                        <a href="/admin/classroom/detail/${course.id}" class="mt-auto block w-full py-2.5 bg-slate-900 text-white text-center font-bold text-xs rounded-xl hover:bg-indigo-650 transition-all active:scale-[0.98]">
                                            Audit Kelas
                                        </a>
                                    </div>
                                </div>
                            `);
                        });
                        feather.replace();
                    }
                },
                error: function(xhr) {
                    $('#loading').addClass('hidden');
                    toastr.error('System Failure: Gagal menyinkronkan data kelas.');
                }
            });
        };

        fetchClassrooms();
    });
</script>
@endsection