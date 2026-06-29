<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ACESSA</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        #premium-loader {
            transition: opacity 0.5s ease;
        }

        .loaded #premium-loader {
            opacity: 0;
            pointer-events: none;
        }
    </style>
    @yield('style')
</head>

<body class="overflow-x-hidden antialiased">

    @include('layouts.admin.loader')

    <div id="sidebar-overlay" onclick="toggleSidebar()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[950] hidden opacity-0 transition-opacity duration-300 lg:hidden">
    </div>

    <div class="flex min-h-screen">
        @include('layouts.admin.sidebar')

        <div class="flex-1 flex flex-col lg:ml-[280px] min-h-screen w-full transition-all duration-300">
            @include('layouts.admin.header')

            <main class="p-4 md:p-10 flex-1 w-full">
                @yield('content')
            </main>

            <footer class="p-8 text-center bg-white border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Copyright 2026 © AETHER CODE
                </p>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        feather.replace();

        function toggleSidebar() {
            const sidebar = $('#admin-sidebar');
            const overlay = $('#sidebar-overlay');
            const body = $('body');

            if (sidebar.hasClass('-translate-x-full')) {
                // OPEN SIDEBAR
                sidebar.removeClass('-translate-x-full').addClass('translate-x-0');
                overlay.removeClass('hidden').addClass('block');
                setTimeout(() => overlay.addClass('opacity-100'), 10);
                body.addClass('sidebar-open');
            } else {
                // CLOSE SIDEBAR
                sidebar.removeClass('translate-x-0').addClass('-translate-x-full');
                overlay.removeClass('opacity-100');
                body.removeClass('sidebar-open');
                setTimeout(() => overlay.removeClass('block').addClass('hidden'), 300);
            }
        }
    </script>
    @yield('script')
</body>

</html>
