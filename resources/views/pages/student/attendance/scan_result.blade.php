<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Absensi - ACESSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at 50% 50%, #f8fafc 0%, #e2e8f0 100%);
        }
        .success-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .error-bg {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .warning-bg {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl border border-slate-200/60 p-8 rounded-[2.5rem] shadow-2xl text-center relative overflow-hidden transition-all hover:scale-[1.01]">
        
        {{-- Success/Error Icon Banner --}}
        <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center mb-6 shadow-lg text-white 
            @if($status === 'success') success-bg @elseif($status === 'warning') warning-bg @else error-bg @endif">
            @if($status === 'success')
                <svg class="w-12 h-12 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            @elseif($status === 'warning')
                <svg class="w-12 h-12 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            @else
                <svg class="w-12 h-12 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            @endif
        </div>

        {{-- Status Title --}}
        <h2 class="text-2xl font-black text-slate-900 leading-tight mb-2">
            @if($status === 'success') Absensi Berhasil @elseif($status === 'warning') Sudah Absen @else Absensi Gagal @endif
        </h2>
        
        {{-- Message --}}
        <p class="text-sm font-semibold text-slate-500 leading-relaxed mb-6">{{ $message }}</p>

        @if($classroom)
            {{-- Class Card Detail --}}
            <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 text-left mb-8">
                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none block mb-1">Informasi Kelas</span>
                <h3 class="text-sm font-extrabold text-slate-800 line-clamp-1">{{ $classroom->name }}</h3>
                <p class="text-[9px] font-bold text-indigo-600 uppercase mt-0.5 tracking-wider">{{ $classroom->codeClass }} &bull; Dosen: {{ $classroom->user->name ?? 'Instruktur' }}</p>
                
                @if(isset($session))
                    <div class="mt-3 pt-3 border-t border-slate-200/50 flex justify-between items-center text-[10px] font-bold text-slate-400">
                        <span>SESI: {{ $session->title }}</span>
                        <span class="font-mono text-slate-700 bg-slate-200 px-2 py-0.5 rounded-md">{{ $session->code }}</span>
                    </div>
                @endif
            </div>
        @endif

        {{-- Action Button --}}
        <a href="/student/dashboard" class="inline-flex w-full py-3 bg-slate-950 hover:bg-slate-900 text-white font-bold text-xs rounded-xl items-center justify-center gap-1.5 transition-all shadow-md">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
