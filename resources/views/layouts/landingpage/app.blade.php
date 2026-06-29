<!doctype html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'ACESSA')</title>

    {{-- Google Font Premium (Tech Startup Vibe) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Konfigurasi Default Tailwind --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        inter: ['"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- AOS Animation CSS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- jQuery (Wajib untuk fitur AJAX Load Kelas) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Global Style & Sticky Footer Fix --}}
    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fafafa;
            color: #0f172a;
        }

        main {
            flex: 1 0 auto;
            /* Padding top dihapus, karena setiap halaman (Home/About) sudah memiliki padding top Tailwind (pt-32/pt-48) masing-masing */
        }

        footer {
            flex-shrink: 0;
        }

        /* Custom Scrollbar Global yang Elegan */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    @yield('style')
</head>

<body class="antialiased selection:bg-indigo-500 selection:text-white">

    {{-- NAVBAR --}}
    {{-- Pastikan kode Tailwind Navbar yang melayang sebelumnya ditaruh di file header ini --}}
    @include('layouts.landingpage.header')

    {{-- KONTEN UTAMA --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('layouts.landingpage.footer')

    {{-- AOS Animation JS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    {{-- Inisialisasi AOS Global --}}
    <script>
        $(document).ready(function () {
            AOS.init({
                once: true,
                offset: 50,
                duration: 800,
                easing: 'ease-out-cubic',
            });
        });
    </script>

    @yield('script')
</body>

</html>