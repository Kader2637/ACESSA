<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACESSA - Kesalahan Server (500)</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-200/30 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-violet-200/20 rounded-full blur-[120px] translate-x-1/3 translate-y-1/3"></div>

    <div class="relative z-10 max-w-md w-full bg-white border border-slate-100 rounded-[3rem] p-10 md:p-12 shadow-2xl shadow-indigo-100/40 text-center animate-fade-in">
        <div class="mb-8">
            <span class="text-8xl md:text-9xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-br from-indigo-655 to-violet-600">
                500
            </span>
        </div>
        
        <h1 class="text-2xl font-black text-slate-900 mb-3 tracking-tight">
            Kesalahan Server
        </h1>
        
        <p class="text-slate-500 text-sm font-semibold leading-relaxed mb-10">
            Terjadi kesalahan internal pada server kami. Kami sedang berusaha memperbaikinya secepat mungkin.
        </p>
        
        <div class="flex flex-col gap-3">
            <a href="/" class="py-4 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center justify-center gap-2">
                🏠 Kembali ke Beranda
            </a>
            <button onclick="window.history.back()" class="py-4 bg-slate-100 text-slate-650 font-bold uppercase text-[10px] tracking-widest rounded-2xl hover:bg-slate-200 transition-all">
                🔙 Halaman Sebelumnya
            </button>
        </div>
    </div>
</body>
</html>
