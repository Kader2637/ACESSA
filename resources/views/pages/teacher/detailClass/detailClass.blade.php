@extends('layouts.teacher.app')

@section('title', 'Detail Kelas — Teacher Panel')
@section('page_title', 'Detail Kelas')

@section('style')
<style>
    .glass-tab-btn {
        transition: all 0.3s ease;
    }
    .glass-tab-btn.active {
        background: #4f46e5;
        color: white;
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.3);
    }
</style>
@endsection

@section('content')

{{-- Class Selector Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Detail <span class="text-indigo-600">Kelas &amp; Siswa</span></h2>
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mt-0.5">Pilih kelas untuk mengelola materi &amp; siswa</p>
    </div>
    <div>
        <select id="classroom-selector" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none cursor-pointer focus:ring-2 focus:ring-indigo-300">
            <option value="">Pilih Ruang Kelas</option>
        </select>
    </div>
</div>

{{-- Loader --}}
<div id="detail-loader" class="py-20 flex flex-col items-center justify-center text-center bg-white border border-slate-200/60 rounded-3xl shadow-sm hidden">
    <div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] animate-pulse">Memuat Detail Kelas...</p>
</div>

{{-- No Class Selected Placeholder --}}
<div id="no-class-selected" class="py-20 flex flex-col items-center justify-center text-center bg-white border border-dashed border-slate-200 rounded-3xl">
    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 text-slate-300">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
    </div>
    <h3 class="font-extrabold text-slate-900 text-base">Silakan Pilih Kelas</h3>
    <p class="text-slate-400 text-xs font-medium mt-1 max-w-xs mx-auto">Pilih salah satu kelas di dropdown kanan atas untuk melihat materi, siswa, dan permohonan masuk.</p>
</div>

{{-- Class Detail Panel (Hidden by default until a class is selected) --}}
<div id="class-detail-panel" class="hidden flex flex-col gap-6">

    {{-- Class Banner Card --}}
    <div class="bg-slate-900 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl" data-aos="fade-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-500/20 to-cyan-500/20 blur-[80px] rounded-full -mr-16 -mt-16"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl overflow-hidden border border-white/10 flex-shrink-0 bg-slate-800">
                    <img id="class-thumbnail" class="w-full h-full object-cover" src="/classaccesa.png" onerror="this.onerror=null; this.src='/classaccesa.png'">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span id="class-status-badge" class="px-2 py-0.5 bg-indigo-600 text-indigo-200 text-[9px] font-bold uppercase rounded-md tracking-wider">Public</span>
                        <span class="text-slate-400 text-xs font-semibold" id="class-semester">Semester</span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-extrabold mt-1 text-white" id="class-title">Kelas Name</h2>
                    <p class="text-slate-400 text-xs mt-1 font-medium max-w-xl" id="class-desc">Class description goes here...</p>
                </div>
            </div>
            
            <div class="flex flex-col items-end gap-2 text-right">
                <div class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-xl">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">Kode Kelas</p>
                    <p class="text-sm font-mono font-extrabold text-indigo-300 mt-1" id="class-code">CODE</p>
                </div>
                <span class="text-[10px] text-slate-500 font-bold" id="class-sks">3 SKS</span>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex gap-2.5 border-b border-slate-200 pb-px">
        <button onclick="switchTab('materi')" id="tab-btn-materi" class="glass-tab-btn active px-5 py-2.5 rounded-xl font-bold text-xs">
            📚 Materi Pembelajaran
        </button>
        <button onclick="switchTab('siswa')" id="tab-btn-siswa" class="glass-tab-btn px-5 py-2.5 rounded-xl font-bold text-xs text-slate-500 hover:text-slate-900">
            👥 Siswa Terdaftar (<span id="count-enrolled">0</span>)
        </button>
        <button onclick="switchTab('pending')" id="tab-btn-pending" class="glass-tab-btn px-5 py-2.5 rounded-xl font-bold text-xs text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            ⏳ Permintaan Masuk
            <span id="badge-pending-count" class="hidden px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-black rounded-full leading-none">0</span>
        </button>
        <button onclick="switchTab('absensi')" id="tab-btn-absensi" class="glass-tab-btn px-5 py-2.5 rounded-xl font-bold text-xs text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            📊 Absensi QR
        </button>
    </div>

    {{-- Tab Contents --}}
    <div>
        {{-- Tab 1: Materi --}}
        <div id="tab-content-materi" class="tab-content-pane flex flex-col gap-4">
            <div class="bg-white border border-slate-200/60 rounded-2xl p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="font-extrabold text-slate-900 text-sm">Daftar Materi Kuliah</h3>
                    <a id="lnk-add-material" href="#" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 transition-colors">
                        + Tambah Materi
                    </a>
                </div>
                <div id="materials-list" class="divide-y divide-slate-100"></div>
                <div id="materials-empty" class="py-8 text-center text-slate-400 text-xs font-semibold hidden">
                    Belum ada materi pembelajaran di kelas ini.
                </div>
            </div>
        </div>

        {{-- Tab 2: Siswa Terdaftar --}}
        <div id="tab-content-siswa" class="tab-content-pane hidden flex flex-col gap-4">
            <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-extrabold text-slate-900 text-sm">Daftar Siswa Aktif</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 w-16 text-center">No</th>
                                <th class="px-6 py-3.5">Nama Lengkap</th>
                                <th class="px-6 py-3.5">Email</th>
                                <th class="px-6 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody id="siswa-list-table" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
                    </table>
                </div>
                <div id="siswa-empty" class="py-12 text-center text-slate-400 text-xs font-semibold hidden">
                    Belum ada siswa yang terdaftar di kelas ini.
                </div>
            </div>
        </div>

        {{-- Tab 3: Permintaan Masuk --}}
        <div id="tab-content-pending" class="tab-content-pane hidden flex flex-col gap-4">
            <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-extrabold text-slate-900 text-sm">Persetujuan Siswa Bergabung</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 w-16 text-center">No</th>
                                <th class="px-6 py-3.5">Nama Lengkap</th>
                                <th class="px-6 py-3.5">Email</th>
                                <th class="px-6 py-3.5 text-right px-8">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="pending-list-table" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
                    </table>
                </div>
                <div id="pending-empty" class="py-12 text-center text-slate-400 text-xs font-semibold hidden">
                    Tidak ada permintaan bergabung yang tertunda.
                </div>
            </div>
        </div>

        {{-- Tab 4: Absensi QR --}}
        <div id="tab-content-absensi" class="tab-content-pane hidden flex flex-col gap-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-left">
                
                {{-- Form Buat Absensi --}}
                <div class="lg:col-span-1 bg-white border border-slate-200/60 rounded-2xl p-6 h-fit shadow-sm">
                    <h3 class="font-extrabold text-slate-900 text-sm mb-4">Buat Sesi Absensi Baru</h3>
                    <form id="form-create-attendance" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Judul Sesi / Pertemuan</label>
                            <input type="text" id="attendance-title" class="w-full px-4 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:border-indigo-500 outline-none" placeholder="Contoh: Pertemuan 1 - Pengenalan" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Durasi Aktif (Menit)</label>
                            <select id="attendance-duration" class="w-full px-4 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:border-indigo-500 outline-none" required>
                                <option value="5">5 Menit (Sangat Cepat)</option>
                                <option value="10" selected>10 Menit (Direkomendasikan)</option>
                                <option value="15">15 Menit</option>
                                <option value="30">30 Menit</option>
                                <option value="60">60 Menit</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all shadow-sm">
                            ⚡ Buat Sesi & Tampilkan QR
                        </button>
                    </form>
                </div>

                {{-- Riwayat Absensi --}}
                <div class="lg:col-span-2 bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-extrabold text-slate-900 text-sm">Riwayat Sesi Absensi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 w-16 text-center">No</th>
                                    <th class="px-6 py-3.5">Nama Sesi</th>
                                    <th class="px-6 py-3.5 text-center">Kode</th>
                                    <th class="px-6 py-3.5">Batas Waktu</th>
                                    <th class="px-6 py-3.5 text-right px-8">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-list-table" class="divide-y divide-slate-50 text-slate-700 font-semibold"></tbody>
                        </table>
                    </div>
                    <div id="attendance-empty" class="py-12 text-center text-slate-400 text-xs font-semibold">
                        Belum ada sesi absensi yang dibuat untuk kelas ini.
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- MODAL QR ABSENSI --}}
    <div id="modal-qr-attendance" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-md overflow-y-auto p-4 md:p-6">
        <div class="bg-white rounded-3xl w-full max-w-4xl shadow-2xl overflow-hidden border border-slate-100 flex flex-col md:flex-row h-full max-h-[85vh] animate-scale-in text-left">
            
            {{-- Left Side: QR Code, Short Code, Countdown Timer --}}
            <div class="flex-1 p-6 md:p-8 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 bg-slate-50/50">
                <span class="px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-wider rounded-lg mb-2">SCAN BARCODE UNTUK ABSEN</span>
                <h4 id="qr-session-title" class="text-lg font-black text-slate-900 mb-6 text-center">Judul Sesi Absensi</h4>
                
                {{-- QR Frame --}}
                <div class="w-56 h-56 md:w-64 md:h-64 bg-white rounded-3xl p-4 shadow-lg border border-slate-200/60 flex items-center justify-center relative mb-4">
                    <img id="attendance-qr-img" src="" class="w-full h-full object-contain" alt="QR Code Absensi">
                    <div id="qr-expired-overlay" class="absolute inset-0 bg-white/95 flex flex-col items-center justify-center text-center p-4 rounded-3xl hidden">
                        <svg class="w-12 h-12 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-extrabold text-slate-800">Sesi Kedaluwarsa</span>
                        <span class="text-[10px] text-slate-400 font-bold mt-1">Siswa tidak bisa lagi melakukan absen</span>
                    </div>
                </div>

                {{-- Short Code --}}
                <div class="text-center mb-6">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">KODE ABSENSI MANUAL</p>
                    <span id="qr-session-code" class="text-2xl font-mono font-black text-slate-950 tracking-widest bg-slate-200 px-4 py-1.5 rounded-xl border">ABCDEF</span>
                </div>

                {{-- Countdown Timer --}}
                <div class="w-full max-w-xs bg-slate-900 rounded-2xl p-4 text-white text-center shadow-md">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1 leading-none">Sisa Waktu Validitas</p>
                    <div id="qr-countdown" class="text-2xl font-mono font-black text-yellow-400 leading-none">00:00</div>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                        <div id="qr-progress-bar" class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Attendance Live List --}}
            <div class="w-full md:w-[380px] p-6 flex flex-col h-full bg-white">
                <div class="flex justify-between items-center mb-4 flex-shrink-0">
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm">Siswa Hadir</h4>
                        <p class="text-[10px] text-slate-400 font-bold mt-0.5">Live update otomatis</p>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-extrabold text-xs rounded-lg" id="qr-attendee-count">0/0</span>
                </div>
                
                {{-- Scanned Student List --}}
                <div class="flex-grow overflow-y-auto min-h-[200px]" id="qr-student-list-container">
                    <div class="divide-y divide-slate-100" id="qr-student-list">
                        {{-- Polled attendees go here --}}
                    </div>
                    <div id="qr-student-empty" class="h-full flex flex-col items-center justify-center text-center p-8">
                        <div class="w-12 h-12 bg-slate-50 border rounded-2xl flex items-center justify-center animate-pulse mb-3">
                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800">Menunggu Kehadiran</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1 leading-relaxed">Minta siswa untuk memindai kode QR atau memasukkan kode manual.</p>
                    </div>
                </div>

                {{-- Close Button --}}
                <div class="pt-4 border-t border-slate-100 flex-shrink-0 mt-4">
                    <button onclick="closeQRModal()" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all">
                        Tutup Layar Absensi
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('script')
<script>
    const authId = '{{ auth()->user()->id }}';
    let managedClasses = [];

    // Load classrooms list
    $(document).ready(function() {
        $.ajax({
            url: `/api/my/classroom/teacher/data/${authId}`,
            method: 'GET',
            success: function(res) {
                managedClasses = res.data || [];
                const selector = $('#classroom-selector');
                
                managedClasses.forEach(kelas => {
                    selector.append(`<option value="${kelas.id}">${kelas.name}</option>`);
                });
            }
        });
    });

    // Handle Selector Change
    $('#classroom-selector').on('change', function() {
        const classId = $(this).val();
        if (!classId) {
            $('#no-class-selected').show();
            $('#class-detail-panel').addClass('hidden');
            return;
        }

        $('#no-class-selected').hide();
        $('#class-detail-panel').addClass('hidden');
        $('#detail-loader').removeClass('hidden');

        // Fetch detailed classroom data
        $.ajax({
            url: `/api/teacher/classroom/show/${classId}`,
            method: 'GET',
            success: function(res) {
                $('#detail-loader').addClass('hidden');
                $('#class-detail-panel').removeClass('hidden');

                const classroom = res.data || res || {};
                
                // Set Banner info
                $('#class-title').text(classroom.name || 'Kelas');
                $('#class-desc').text(classroom.description || 'Tidak ada deskripsi');
                $('#class-code').text(classroom.codeClass || '—');
                $('#class-semester').text('Semester ' + (classroom.semester_id || ''));
                $('#class-status-badge').text(classroom.statusClass || 'public');
                $('#class-sks').text((classroom.sks || 3) + ' SKS');
                
                const thumb = (classroom.thumbnail && classroom.thumbnail !== 'default.png') ? `/storage/${classroom.thumbnail}` : '/classaccesa.png';
                $('#class-thumbnail').attr('src', thumb);

                // Set course details link
                $('#lnk-add-material').attr('href', `/teacher/classroom/course/${classId}`);

                // Load materials & students & pending
                loadMaterials(classId);
                loadStudents(classId);
                loadPending(classId);
                loadAttendanceSessions(classId);
            },
            error: () => {
                $('#detail-loader').addClass('hidden');
                $('#no-class-selected').show();
            }
        });
    });

    function loadMaterials(classId) {
        const list = $('#materials-list');
        list.empty();
        $('#materials-empty').addClass('hidden');

        $.ajax({
            url: `/api/teacher/course/data/${classId}`,
            method: 'GET',
            success: function(res) {
                const courses = res.data || res || [];
                if (courses.length === 0) {
                    $('#materials-empty').removeClass('hidden');
                    return;
                }

                courses.forEach((c, index) => {
                    list.append(`
                        <div class="py-4 flex justify-between items-start">
                            <div>
                                <h4 class="font-extrabold text-slate-800 text-sm">${c.name}</h4>
                                <p class="text-slate-400 text-xs mt-1">${c.description || 'Tidak ada deskripsi'}</p>
                            </div>
                            <a href="/teacher/course/detail/${c.id}" class="px-3 py-1.5 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-[10px] font-bold transition-colors">
                                Kelola Konten
                            </a>
                        </div>
                    `);
                });
            },
            error: () => $('#materials-empty').removeClass('hidden')
        });
    }

    function loadStudents(classId) {
        const table = $('#siswa-list-table');
        table.empty();
        $('#siswa-empty').addClass('hidden');

        $.ajax({
            url: `/api/teacher/data/classroom/${classId}`,
            method: 'GET',
            success: function(res) {
                const students = res.data || res || [];
                $('#count-enrolled').text(students.length);

                if (students.length === 0) {
                    $('#siswa-empty').removeClass('hidden');
                    return;
                }

                students.forEach((s, index) => {
                    table.append(`
                        <tr>
                            <td class="px-6 py-3 text-center text-slate-400">${index + 1}</td>
                            <td class="px-6 py-3 text-slate-900">${s.student_name || s.name}</td>
                            <td class="px-6 py-3 text-slate-400 font-mono">${s.student_email || s.email}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 text-[10px] font-bold">Aktif</span>
                            </td>
                        </tr>
                    `);
                });
            },
            error: () => $('#siswa-empty').removeClass('hidden')
        });
    }

    function loadPending(classId) {
        const table = $('#pending-list-table');
        table.empty();
        $('#pending-empty').addClass('hidden');
        $('#badge-pending-count').addClass('hidden');

        $.ajax({
            url: `/api/pending/teacher/${classId}`,
            method: 'GET',
            success: function(res) {
                const students = res.data || res || [];
                const count = students.length;

                if (count > 0) {
                    $('#badge-pending-count').removeClass('hidden').text(count);
                }

                if (count === 0) {
                    $('#pending-empty').removeClass('hidden');
                    return;
                }

                students.forEach((s, index) => {
                    table.append(`
                        <tr id="pending-row-${s.id}">
                            <td class="px-6 py-3 text-center text-slate-400">${index + 1}</td>
                            <td class="px-6 py-3 text-slate-900">${s.name || s.student_name}</td>
                            <td class="px-6 py-3 text-slate-400 font-mono">${s.email || s.student_email}</td>
                            <td class="px-6 py-3 text-right px-8 flex justify-end gap-2">
                                <button onclick="actionPending(${s.id}, 'accept')" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-bold">Setujui</button>
                                <button onclick="actionPending(${s.id}, 'reject')" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-[10px] font-bold">Tolak</button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: () => $('#pending-empty').removeClass('hidden')
        });
    }

    function actionPending(pendingId, action) {
        const url = action === 'accept' ? `/api/accept/teacher/${pendingId}` : `/api/reject/teacher/${pendingId}`;
        
        $.ajax({
            url: url,
            method: 'POST',
            success: function(res) {
                toastr.success(action === 'accept' ? 'Berhasil menyetujui siswa!' : 'Berhasil menolak siswa.');
                const classId = $('#classroom-selector').val();
                loadStudents(classId);
                loadPending(classId);
            },
            error: () => toastr.error('Gagal memproses permintaan.')
        });
    }

    let pollInterval = null;
    let countdownInterval = null;

    function loadAttendanceSessions(classId) {
        const table = $('#attendance-list-table');
        table.empty();
        $('#attendance-empty').addClass('hidden');

        $.ajax({
            url: `/api/classroom/${classId}/attendance/sessions`,
            method: 'GET',
            success: function(res) {
                const sessions = res.data || [];
                if (sessions.length === 0) {
                    $('#attendance-empty').removeClass('hidden');
                    return;
                }

                sessions.forEach((s, index) => {
                    const validUntil = new Date(s.valid_until);
                    const now = new Date();
                    const isExpired = validUntil < now;
                    
                    const actionBtn = isExpired 
                        ? `<button onclick="openQRModal(${s.id})" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold">Lihat Kehadiran</button>`
                        : `<button onclick="openQRModal(${s.id})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[10px] font-bold flex items-center gap-1">⚡ Tampilkan QR</button>`;

                    const statusBadge = isExpired
                        ? `<span class="text-red-500 font-bold">Kedaluwarsa</span>`
                        : `<span class="text-emerald-500 font-bold animate-pulse">Aktif</span>`;

                    const dateString = validUntil.toLocaleString('id-ID', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: 'short' });

                    table.append(`
                        <tr>
                            <td class="px-6 py-4 text-center text-slate-400">${index + 1}</td>
                            <td class="px-6 py-4 text-slate-900">${s.title}</td>
                            <td class="px-6 py-4 text-center font-mono font-bold text-slate-700 bg-slate-50 rounded-lg">${s.code}</td>
                            <td class="px-6 py-4 text-slate-500">${dateString} (${statusBadge})</td>
                            <td class="px-6 py-4 text-right px-8">${actionBtn}</td>
                        </tr>
                    `);
                });
            },
            error: () => $('#attendance-empty').removeClass('hidden')
        });
    }

    // Submit handler for creating new attendance session
    $('#form-create-attendance').submit(function(e) {
        e.preventDefault();
        const classId = $('#classroom-selector').val();
        if (!classId) return;

        const title = $('#attendance-title').val();
        const duration = $('#attendance-duration').val();

        $.ajax({
            url: '/api/attendance/create',
            method: 'POST',
            data: {
                classroom_id: classId,
                title: title,
                duration: duration
            },
            success: function(res) {
                toastr.success(res.message);
                $('#attendance-title').val('');
                loadAttendanceSessions(classId);
                openQRModal(res.data.id);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal membuat sesi absensi.';
                toastr.error(msg);
            }
        });
    });

    function openQRModal(sessionId) {
        stopAttendancePoll();
        $('#modal-qr-attendance').removeClass('hidden').addClass('flex');
        fetchQRModalDetails(sessionId);
        pollAttendees(sessionId);
        pollInterval = setInterval(() => pollAttendees(sessionId), 3000);
    }

    function fetchQRModalDetails(sessionId) {
        $.ajax({
            url: `/api/attendance/session/${sessionId}/records`,
            method: 'GET',
            success: function(res) {
                const s = res.session;
                $('#qr-session-title').text(s.title);
                $('#qr-session-code').text(s.code);
                
                const scanUrl = window.location.origin + `/attendance/scan/${s.token}`;
                const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(scanUrl)}`;
                $('#attendance-qr-img').attr('src', qrApiUrl);

                if (s.is_expired) {
                    $('#qr-expired-overlay').removeClass('hidden');
                    $('#qr-countdown').text('Selesai').removeClass('text-yellow-400').addClass('text-red-500');
                    $('#qr-progress-bar').css('width', '0%');
                } else {
                    $('#qr-expired-overlay').addClass('hidden');
                    $('#qr-countdown').removeClass('text-red-500').addClass('text-yellow-400');
                    startCountdown(s.valid_until);
                }
            }
        });
    }

    function startCountdown(validUntilStr) {
        if (countdownInterval) clearInterval(countdownInterval);

        const validUntil = new Date(validUntilStr).getTime();
        const serverTimeOffset = 0; // assuming client/server are relatively in sync

        const updateClock = () => {
            const now = new Date().getTime();
            const distance = validUntil - now;

            if (distance <= 0) {
                clearInterval(countdownInterval);
                $('#qr-countdown').text('Waktu Habis').removeClass('text-yellow-400').addClass('text-red-500');
                $('#qr-expired-overlay').removeClass('hidden');
                $('#qr-progress-bar').css('width', '0%');
                
                const classId = $('#classroom-selector').val();
                loadAttendanceSessions(classId);
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const minStr = String(minutes).padStart(2, '0');
            const secStr = String(seconds).padStart(2, '0');
            $('#qr-countdown').text(`${minStr}:${secStr}`);

            // Estimate a total duration of the session in ms (approx using select minutes, default total is difference from start)
            // For simple visualization, we can calculate from validUntil - now relative to 10 minutes max if we don't store startTime.
            // Let's use distance percent assuming max 1 hour duration or just linear time countdown.
            $('#qr-progress-bar').css('width', `100%`);
        };

        updateClock();
        countdownInterval = setInterval(updateClock, 1000);
    }

    function pollAttendees(sessionId) {
        $.ajax({
            url: `/api/attendance/session/${sessionId}/records`,
            method: 'GET',
            success: function(res) {
                const data = res.data || [];
                const attended = data.filter(st => st.status === 'Hadir');
                
                $('#qr-attendee-count').text(`${attended.length} / ${data.length}`);

                const list = $('#qr-student-list');
                list.empty();

                if (attended.length === 0) {
                    $('#qr-student-empty').removeClass('hidden');
                    return;
                }

                $('#qr-student-empty').addClass('hidden');
                attended.forEach(st => {
                    const avatar = st.profile ? `/storage/${st.profile}` : '/user.png';
                    list.append(`
                        <div class="py-3 flex items-center gap-3">
                            <img src="${avatar}" class="w-8 h-8 rounded-lg object-cover border border-slate-100" onerror="this.onerror=null; this.src='/user.png';">
                            <div class="flex-grow">
                                <h5 class="text-xs font-extrabold text-slate-800">${st.name}</h5>
                                <p class="text-[9px] text-emerald-600 font-bold">Hadir pukul ${st.scanned_at}</p>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                    `);
                });
            }
        });
    }

    function closeQRModal() {
        $('#modal-qr-attendance').removeClass('flex').addClass('hidden');
        stopAttendancePoll();
        
        const classId = $('#classroom-selector').val();
        if (classId) loadAttendanceSessions(classId);
    }

    function stopAttendancePoll() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }

    // Tabs switching
    function switchTab(tabName) {
        $('.glass-tab-btn').removeClass('active text-slate-900').addClass('text-slate-500');
        $(`#tab-btn-${tabName}`).addClass('active text-slate-900').removeClass('text-slate-500');
        $('.tab-content-pane').addClass('hidden');
        $(`#tab-content-${tabName}`).removeClass('hidden');
    }
</script>
@endsection
