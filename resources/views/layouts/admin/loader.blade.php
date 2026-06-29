<div id="premium-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white transition-opacity duration-500">
    <div class="w-12 h-12 border-[3px] border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
    <p class="mt-4 text-[9px] font-black uppercase text-slate-400 tracking-[0.4em] animate-pulse">ACESSA</p>
</div>

<script>
    window.addEventListener('load', () => {
        const loader = document.getElementById('premium-loader');
        loader.classList.add('opacity-0');
        setTimeout(() => loader.style.display = 'none', 500);
    });
</script>