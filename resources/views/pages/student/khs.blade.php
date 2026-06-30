@extends('layouts.student.app')

@section('title', 'KHS Akademik — Portal Mahasiswa')
@section('page_title', 'Kartu Hasil Studi')

@section('style')
<style>
    .stat-box { border-radius: 1.5rem; border: 1px solid #e2e8f0; background: white; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; }
    .table-container { background: white; border-radius: 1.5rem; border: 1px solid #e2e8f0; overflow: hidden; }
    .khs-table th { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; padding: 1rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .khs-table td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: #1e293b; font-size: 12px; }
</style>
@endsection

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm animate-fade-in">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">KHS &amp; <span class="text-indigo-650">Hasil Evaluasi</span></h2>
        <p class="text-slate-500 text-xs font-semibold mt-0.5">Daftar nilai akademik mahasiswa per semester berjalan.</p>
    </div>
    
    {{-- Semester Selector --}}
    <div class="flex items-center gap-2">
        <label class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Pilih Semester:</label>
        <select id="semester-selector" class="px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-yellow-500 focus:bg-white transition-all shadow-sm min-w-[200px]">
            <option value="">Memuat semester...</option>
        </select>
    </div>
</div>

{{-- Stats Panel --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-delay="50">
    <div class="bg-yellow-500 rounded-3xl p-6 text-slate-950 relative overflow-hidden shadow-sm">
        <div>
            <p class="text-[9px] font-black uppercase tracking-wider opacity-70">Indeks Prestasi Semester (IPS)</p>
            <h3 class="text-4xl font-black mt-3 tracking-tight" id="ips-val">0.00</h3>
            <p class="text-[10px] font-bold mt-2 opacity-80" id="ips-desc">Pilih semester untuk kalkulasi</p>
        </div>
    </div>

    <div class="stat-box">
        <div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Total SKS Diambil</p>
            <h3 class="text-3xl font-black mt-3 tracking-tight text-slate-800" id="sks-total">0</h3>
        </div>
        <p class="text-[10px] font-bold mt-2 text-slate-550">Kredit semester berjalan</p>
    </div>

    <div class="stat-box">
        <div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">SKS Dinyatakan Lulus</p>
            <h3 class="text-3xl font-black mt-3 tracking-tight text-emerald-600" id="sks-lulus">0</h3>
        </div>
        <p class="text-[10px] font-bold mt-2 text-slate-550" id="sks-lulus-desc">Nilai mata kuliah &gt;= C</p>
    </div>
</div>

{{-- KHS Table --}}
<div class="table-container shadow-sm mb-12" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-left khs-table">
            <thead>
                <tr>
                    <th class="text-center w-12">No</th>
                    <th class="w-24">Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>Dosen Pengampu</th>
                    <th class="text-center w-16">SKS</th>
                    <th class="text-center w-20">Nilai Angka</th>
                    <th class="text-center w-20">Nilai Huruf</th>
                    <th class="text-center w-16">Bobot</th>
                    <th class="text-center w-24">Status</th>
                </tr>
            </thead>
            <tbody id="khs-data-list">
                <tr>
                    <td colspan="9" class="text-center py-20 text-slate-400 font-medium">
                        <div class="w-6 h-6 border-2 border-slate-100 border-t-yellow-500 rounded-full animate-spin mx-auto mb-3"></div>
                        Memuat data evaluasi akademik...
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
                        <div class="w-6 h-6 border-2 border-slate-100 border-t-yellow-500 rounded-full animate-spin mx-auto mb-3"></div>
                        Mengambil data akademik...
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
                                let statusClass = 'bg-slate-100 text-slate-650';
                                if (item.status === 'Lulus') statusClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                                else if (item.status === 'Tidak Lulus') statusClass = 'bg-rose-50 text-rose-600 border border-rose-100';
                                
                                list.append(`
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="text-center text-slate-400 font-bold">${index + 1}</td>
                                        <td class="font-extrabold text-yellow-650">${item.code}</td>
                                        <td class="font-extrabold text-slate-800">${item.name}</td>
                                        <td class="text-slate-500 font-medium">${item.lecturer}</td>
                                        <td class="text-center text-slate-800 font-black">${item.sks}</td>
                                        <td class="text-center font-bold text-slate-700">${item.nilai_angka}</td>
                                        <td class="text-center"><span class="px-2.5 py-1 bg-slate-100 text-slate-850 rounded-lg font-extrabold text-xs border border-slate-200">${item.nilai_huruf}</span></td>
                                        <td class="text-center font-black text-slate-800">${item.bobot.toFixed(1)}</td>
                                        <td class="text-center">
                                            <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider ${statusClass}">
                                                ${item.status}
                                            </span>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            list.html('<tr><td colspan="9" class="text-center py-24 text-slate-400 font-medium"><i data-feather="slash" class="w-10 h-10 mx-auto opacity-30 mb-3"></i><p class="text-slate-400 font-bold uppercase tracking-widest text-[9px]">Anda belum bergabung ke kelas mana pun di semester ini</p></td></tr>');
                            feather.replace();
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
