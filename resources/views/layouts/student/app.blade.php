<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Mahasiswa - ACESSA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        indigo: {
                            600: '#d97706', /* map indigo-600 to amber-600 */
                            650: '#b45309',
                            700: '#b45309',
                            50: '#fef3c7',
                            100: '#fde68a',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background-color: #fcfbfa; color: #0f172a; overflow: hidden; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #f59e0b; border-radius: 10px; }
        .toast-warning { background-color: #f59e0b !important; }
        .toast-success { background-color: #d97706 !important; }
        .toast-error { background-color: #ef4444 !important; }
    </style>
    @yield('style')
</head>
<body class="antialiased h-full">
    <div class="flex h-screen overflow-hidden">
        
        @if (!request()->is('student/materi/detail'))
            @include('layouts.student.sidebar')
        @endif

        <div class="flex-grow flex flex-col min-w-0 h-full lg:pl-64 transition-all duration-300">
            
            @include('layouts.student.header')

            <main class="flex-1 overflow-y-auto scroll-smooth">
                <div class="p-6 md:p-8 max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            AOS.init({ once: true, duration: 800 });
            feather.replace();
        });
    </script>
    @yield('script')
</body>
</html>