<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel — ACESSA')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --sidebar-w: 256px;
            --header-h: 70px;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f3f9;
            color: #0f172a;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Toastr custom styling */
        .toast {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 600 !important;
            border-radius: 14px !important;
        }
    </style>
    @yield('style')
</head>

<body class="antialiased h-full overflow-hidden">

    @include('layouts.admin.loader')

    {{-- Mobile Sidebar Overlay --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"
        class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[950] hidden transition-opacity duration-300">
    </div>

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('layouts.admin.sidebar')

        {{-- Main Area --}}
        <div class="flex-grow flex flex-col min-w-0 h-full lg:pl-64 transition-all duration-300">
            
            {{-- Header --}}
            @include('layouts.admin.header')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                <div class="w-full">
                    @yield('content')
                </div>
            </main>
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
            
            if (sidebar.hasClass('-translate-x-full')) {
                sidebar.removeClass('-translate-x-full').addClass('translate-x-0');
                overlay.removeClass('hidden').addClass('block');
            } else {
                sidebar.removeClass('translate-x-0').addClass('-translate-x-full');
                overlay.addClass('hidden').removeClass('block');
            }
        }
    </script>
    @yield('script')
</body>

</html>
