@extends('layouts.student.app')

@section('page_title', 'Kartu Hasil Studi (KHS)')

@section('style')
<style>
    .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.5); }
    .table-container { background: white; border-radius: 2.5rem; border: 1px solid #f1f5f9; overflow: hidden; }
    .custom-table th { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; padding: 1.5rem 1rem; border: none; background: #f8fafc; }
    .custom-table td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: #1e293b; font-size: 13px; }
    .custom-select { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.25rem; padding: 0.85rem 1.25rem; font-weight: 700; transition: all 0.2s; color: #0f172a; outline: none; }
    .custom-select:focus { border-color: #4f46e5; background: white; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
</style>
@endsection

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm" data-aos="fade-down">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Kartu Hasil <span class="text-indigo-600">Studi (KHS)</span></h2>
        <p class="text-slate-500 font-medium mt-1 uppercase text-[10px] tracking-widest text-indigo-500">Hasil Evaluasi Akademik Mahasiswa</p>
    </div>
    
    <!-- Semester Filter -->
    <div class="flex items-center gap-3">
        <label class="text-[10px] font-black uppercase tracking-wider text-slate-400">Pilih Semester:</label>
        <select id="semester-selector" class="custom-select min-w-[240px] shadow-sm">
            <option value="">Memuat semester...</option>
        </select>
    </div>
</div>

<!-- Academic Stats Panel -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- IPS Card -->
    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-600/20" data-aos="zoom-in" data-aos-delay="100">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Indeks Prestasi Semester (IPS)</p>
            <h3 class="text-5xl font-black mt-4 tracking-tight" id="ips-val">0.00</h3>
            <p class="text-xs font-semibold mt-3 text-indigo-100 opacity-80" id="ips-desc">Pilih semester untuk kalkulasi</p>
        </div>
    </div>

    <!-- Total SKS Card -->
    <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 relative overflow-hidden shadow-sm" data-aos="zoom-in" data-aos-delay="200">
        <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full blur-2xl -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Total SKS Diambil</p>
            <h3 class="text-5xl font-black mt-4 tracking-tight text-slate-900" id="sks-total">0</h3>
            <p class="text-xs font-semibold mt-3 text-slate-500">Bobot total kredit akademik</p>
        </div>
    </div>

    <!-- SKS Lulus Card -->
    <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 relative overflow-hidden shadow-sm" data-aos="zoom-in" data-aos-delay="300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full blur-2xl -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">SKS Dinyatakan Lulus</p>
            <h3 class="text-5xl font-black mt-4 tracking-tight text-emerald-600" id="sks-lulus">0</h3>
            <p class="text-xs font-semibold mt-3 text-slate-500" id="sks-lulus-desc">Nilai mata kuliah >= C</p>
        </div>
    </div>
</div>

<!-- KHS Table -->
<div class="table-container shadow-xl shadow-slate-200/50 mb-12" data-aos="fade-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left custom-table">
            <thead>
                <tr>
                    <th class="text-center w-16">No</th>
                    <th class="w-28">Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>Dosen Pengampu</th>
                    <th class="text-center w-20">SKS</th>
                    <th class="text-center w-24">Nilai Angka</th>
                    <th class="text-center w-24">Nilai Huruf</th>
                    <th class="text-center w-20">Bobot</th>
                    <th class="text-center w-32">Status</th>
                </tr>
            </thead>
            <tbody id="khs-data-list">
                <tr>
                    <td colspan="9" class="text-center py-20 text-slate-400 font-medium">
                        <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                        Memuat data KHS...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Load Semesters
        fetch('/api/semesters')
            .then(r => r.json())
            .then(res => {
                if (res.success && res.data.length > 0) {
                    const selector = $('#semester-selector');
                    selector.empty();
                    
                    let activeId = null;
                    res.data.forEach(sem => {
                        selector.append(`<option value="${sem.id}" ${sem.is_active ? 'selected' : ''}>${sem.name}</option>`);
                        if (sem.is_active) activeId = sem.id;
                    });
                    
                    if (activeId) {
                        fetchKHS(activeId);
                    } else if (res.data[0]) {
                        fetchKHS(res.data[0].id);
                    }
                } else {
                    $('#semester-selector').html('<option value="">Semester tidak tersedia</option>');
                    $('#khs-data-list').html('<tr><td colspan="9" class="text-center py-12 text-slate-400">Belum ada data semester tersedia.</td></tr>');
                }
            });

        // Event listener for semester change
        $('#semester-selector').on('change', function() {
            const semId = $(this).val();
            if (semId) {
                fetchKHS(semId);
            }
        });

        // Function to fetch KHS data
        function fetchKHS(semesterId) {
            $('#khs-data-list').html(`
                <tr>
                    <td colspan="9" class="text-center py-20 text-slate-400 font-medium">
                        <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                        Mengambil data KHS...
                    </td>
                </tr>
            `);

            fetch(`/api/student/khs?semester_id=${semesterId}`)
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        const data = res.data;
                        
                        // Update stats
                        $('#ips-val').text(data.ips);
                        $('#sks-total').text(data.total_sks);
                        $('#sks-lulus').text(data.total_sks_lulus);
                        
                        // Update descriptions based on IPS
                        const ipsFloat = parseFloat(data.ips);
                        let ipsDesc = 'Perlu ditingkatkan lagi';
                        if (ipsFloat >= 3.5) ipsDesc = 'Sangat Memuaskan! Pertahankan!';
                        else if (ipsFloat >= 3.0) ipsDesc = 'Memuaskan! Tingkatkan sedikit lagi!';
                        else if (ipsFloat >= 2.0) ipsDesc = 'Cukup. Belajar lebih giat lagi!';
                        $('#ips-desc').text(ipsDesc);
                        
                        // Populate list
                        const list = $('#khs-data-list');
                        list.empty();
                        
                        if (data.khs_items.length > 0) {
                            data.khs_items.forEach((item, index) => {
                                let statusClass = 'bg-slate-100 text-slate-600';
                                if (item.status === 'Lulus') statusClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                                else if (item.status === 'Tidak Lulus') statusClass = 'bg-rose-50 text-rose-600 border border-rose-100';
                                
                                list.append(`
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="text-center text-slate-400 font-bold">${index + 1}</td>
                                        <td class="font-black text-indigo-600">${item.code}</td>
                                        <td class="font-extrabold text-slate-900">${item.name}</td>
                                        <td class="text-slate-500 font-medium">${item.lecturer}</td>
                                        <td class="text-center text-slate-900 font-black">${item.sks}</td>
                                        <td class="text-center font-bold text-slate-700">${item.nilai_angka}</td>
                                        <td class="text-center"><span class="px-3.5 py-1.5 bg-slate-100 text-slate-800 rounded-full font-black text-xs">${item.nilai_huruf}</span></td>
                                        <td class="text-center font-black text-slate-900">${item.bobot.toFixed(1)}</td>
                                        <td class="text-center">
                                            <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider ${statusClass}">
                                                ${item.status}
                                            </span>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            list.html('<tr><td colspan="9" class="text-center py-24 text-slate-400 font-medium"><img src="/no-data.png" class="w-24 mx-auto opacity-30 mb-4" onerror="this.src=\'https://cdn-icons-png.flaticon.com/512/7486/7486754.png\'"><h4 class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Anda belum bergabung ke kelas mana pun di semester ini</h4></td></tr>');
                        }
                    } else {
                        toastr.error('Gagal mengambil data KHS.');
                    }
                })
                .catch(err => {
                    toastr.error('Terjadi kesalahan koneksi server.');
                });
        }
    });
</script>
@endsection
