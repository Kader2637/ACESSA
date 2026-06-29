@extends('layouts.teacher.app')

@section('title', 'Statistik — Teacher Panel')
@section('page_title', 'Statistik Kelas')

@section('content')

<div class="mb-8" data-aos="fade-up">
    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Statistik <span class="text-indigo-600">Kelas</span></h1>
    <p class="text-slate-400 text-sm font-medium mt-1">Ringkasan performa dan aktivitas semua kelas Anda</p>
</div>

{{-- Overview Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8" data-aos="fade-up" data-aos-delay="50">
    @php
    $overviewCards = [
        ['id' => 'statTeacher', 'label' => 'Total Guru', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'from' => 'from-indigo-500', 'to' => 'to-indigo-600', 'bg' => 'bg-indigo-50', 'color' => 'text-indigo-500'],
        ['id' => 'statClass', 'label' => 'Kelas Aktif', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'from' => 'from-cyan-500', 'to' => 'to-cyan-600', 'bg' => 'bg-cyan-50', 'color' => 'text-cyan-500'],
        ['id' => 'statStudent', 'label' => 'Total Siswa', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'bg' => 'bg-emerald-50', 'color' => 'text-emerald-500'],
        ['id' => 'statTask', 'label' => 'Tugas Dibuat', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'from' => 'from-violet-500', 'to' => 'to-violet-600', 'bg' => 'bg-violet-50', 'color' => 'text-violet-500'],
    ];
    @endphp

    @foreach($overviewCards as $card)
    <div class="bg-white border border-slate-100 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-11 h-11 {{ $card['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">{{ $card['label'] }}</p>
            <p class="text-3xl font-extrabold text-slate-900" id="{{ $card['id'] }}">—</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Classroom Performance --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden mb-6" data-aos="fade-up" data-aos-delay="100">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-900 text-sm">Performa per Kelas</h3>
        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Live Data</span>
    </div>

    <div id="classStatsLoading" class="py-16 flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest animate-pulse">Memuat statistik...</p>
    </div>

    <div id="classStatsContainer" class="divide-y divide-slate-50 hidden"></div>
</div>

{{-- Submission Chart (Visual) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="150">
    
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h4 class="font-extrabold text-slate-900 text-sm mb-5">Status Pengumpulan Tugas</h4>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5">
                    <span class="text-emerald-600">Dikumpulkan</span>
                    <span id="pctDone" class="text-slate-400">—%</span>
                </div>
                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div id="barDone" class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-1000" style="width:0%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5">
                    <span class="text-amber-500">Belum Dikumpul</span>
                    <span id="pctPending" class="text-slate-400">—%</span>
                </div>
                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div id="barPending" class="h-full bg-gradient-to-r from-amber-400 to-amber-300 rounded-full transition-all duration-1000" style="width:0%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5">
                    <span class="text-red-500">Terlambat</span>
                    <span id="pctLate" class="text-slate-400">—%</span>
                </div>
                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div id="barLate" class="h-full bg-gradient-to-r from-red-500 to-red-400 rounded-full transition-all duration-1000" style="width:0%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h4 class="font-extrabold text-slate-900 text-sm mb-5">Aktivitas Terkini</h4>
        <div class="space-y-3" id="recentActivity">
            <div class="flex items-center gap-3 animate-pulse">
                <div class="w-8 h-8 bg-slate-100 rounded-xl"></div>
                <div class="flex-1">
                    <div class="h-3 bg-slate-100 rounded-full mb-1.5"></div>
                    <div class="h-2.5 bg-slate-50 rounded-full w-2/3"></div>
                </div>
            </div>
            <div class="flex items-center gap-3 animate-pulse">
                <div class="w-8 h-8 bg-slate-100 rounded-xl"></div>
                <div class="flex-1">
                    <div class="h-3 bg-slate-100 rounded-full mb-1.5"></div>
                    <div class="h-2.5 bg-slate-50 rounded-full w-1/2"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    const authId = '{{ auth()->user()->id }}';

    // Fetch overview counts
    $.ajax({
        url: `/api/count/statistika/${authId}`,
        method: 'GET',
        success: function(res) {
            $('#statTeacher').text(res.countTeacher || 0);
            $('#statClass').text(res.countClassroom || 0);
        }
    });

    // Fetch classrooms & per-class stats
    $.ajax({
        url: `/api/my/classroom/teacher/data/${authId}`,
        method: 'GET',
        success: function(res) {
            const classes = res.data || [];
            $('#statStudent').text(classes.reduce((acc, k) => acc + (k.student_count || 0), 0));
            $('#classStatsLoading').hide();

            if (classes.length === 0) {
                $('#classStatsContainer').removeClass('hidden').html(`
                    <div class="py-12 text-center text-slate-400 text-sm font-medium">Belum ada kelas aktif</div>
                `);
                return;
            }

            $('#classStatsContainer').removeClass('hidden');
            classes.forEach(k => {
                const students = k.student_count || 0;
                const progress = Math.min(100, students * 5);
                $('#classStatsContainer').append(`
                    <div class="px-6 py-5 flex items-center gap-5 hover:bg-slate-50/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center text-white font-extrabold text-sm flex-shrink-0">
                            ${k.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="font-extrabold text-slate-900 text-sm truncate">${k.name}</p>
                                <span class="text-xs font-bold text-slate-400 flex-shrink-0 ml-4">${students} siswa</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-indigo-500 to-cyan-400 rounded-full" style="width: ${progress}%"></div>
                            </div>
                        </div>
                        <a href="/teacher/classroom/course/${k.id}" class="text-xs font-bold text-indigo-500 hover:text-indigo-700 transition-colors flex-shrink-0">
                            Detail →
                        </a>
                    </div>
                `);
            });
        },
        error: function() {
            $('#classStatsLoading').hide();
        }
    });

    // Task stats
    let totalDone = 0, totalPending = 0, totalLate = 0;
    $.when(
        $.ajax({ url: `/api/done/assigment/task/${authId}`, method: 'GET' }),
        $.ajax({ url: `/api/not/assigment/task/${authId}`, method: 'GET' })
    ).done(function(doneRes, pendingRes) {
        totalDone = (doneRes[0].data || []).length;
        totalPending = (pendingRes[0].data || []).length;
        const total = totalDone + totalPending + totalLate;
        $('#statTask').text(total);

        if (total > 0) {
            const pDone = Math.round(totalDone / total * 100);
            const pPending = Math.round(totalPending / total * 100);
            const pLate = 100 - pDone - pPending;
            $('#pctDone').text(`${pDone}%`);
            $('#pctPending').text(`${pPending}%`);
            $('#pctLate').text(`${Math.max(0, pLate)}%`);
            setTimeout(() => {
                $('#barDone').css('width', `${pDone}%`);
                $('#barPending').css('width', `${pPending}%`);
                $('#barLate').css('width', `${Math.max(0, pLate)}%`);
            }, 300);
        }

        // Recent activity
        const recent = [...(doneRes[0].data || []).slice(0, 3)].map(t => ({
            icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            color: 'text-emerald-500', bg: 'bg-emerald-50',
            text: t.title || t.name || 'Tugas',
            sub: 'Tugas dikumpulkan'
        }));

        if (recent.length > 0) {
            $('#recentActivity').empty();
            recent.forEach(a => {
                $('#recentActivity').append(`
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 ${a.bg} rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 ${a.color}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${a.icon}"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 leading-tight">${a.text}</p>
                            <p class="text-xs text-slate-400 font-medium">${a.sub}</p>
                        </div>
                    </div>
                `);
            });
        }
    });
});
</script>
@endsection
