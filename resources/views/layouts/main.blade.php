<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title"
        content="{{ isset($title) && $title ? $title . ' - SIPZIS' : 'SIPZIS - Sistem Informasi Pengelolaan Zakat' }}">
    <meta name="application-name" content="SIPZIS">

    <link rel="icon" type="image/x-icon" href="{{ asset('img/lazismu-icon.ico') }}">

    <title>{{ isset($title) && $title ? $title . ' - SIPZIS' : 'SIPZIS' }}</title>
    <!-- Preload critical resources -->
    @if(Route::currentRouteName() === 'home')
    <link rel="preload" href="{{ asset('img/masjid.webp') }}" as="image" fetchpriority="high">
    @endif
    <!-- Fonts - Optimized -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles from Pages -->
    @stack('styles')

    <style>
        body{font-family:'Poppins',sans-serif}
        *{-webkit-tap-highlight-color:transparent}
        /* Prevent FOUC - hide animated elements until page loads */
        body:not(.page-loaded) [class*="animate-fadeIn"] {
            opacity: 0 !important;
            visibility: hidden;
        }
    </style>
</head>

<body class="bg-gray-50" style="overflow-x:hidden">
    {{-- Navbar --}}
    @yield('navbar')

    {{-- Konten Utama --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer hanya untuk halaman tertentu --}}
    @php
        $routeName = Route::currentRouteName();
        $showFooterRoutes = ['home', 'tentang', 'berita'];
        $showFooterPattern = '/^(artikel\.)/';
    @endphp

    @if (in_array($routeName, $showFooterRoutes) || preg_match($showFooterPattern, $routeName))
        @include('partials.footer')
    @endif

    {{-- Script Tambahan --}}
    @yield('scripts')
    
    {{-- Additional Scripts from Pages --}}
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const useSweetAlert = {{ auth()->check() && auth()->user()->role === 'muzakki' ? 'true' : 'false' }};

            if (!useSweetAlert || typeof Swal === 'undefined') {
                return;
            }

            const flash = {
                success: @json(session('success')),
                error: @json(session('error')),
                warning: @json(session('warning')),
                info: @json(session('info'))
            };

            const swalBase = {
                confirmButtonColor: '#047857',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                buttonsStyling: true,
            };

            if (flash.success) {
                Swal.fire({ ...swalBase, icon: 'success', title: 'Berhasil', text: flash.success });
            } else if (flash.error) {
                Swal.fire({ ...swalBase, icon: 'error', title: 'Gagal', text: flash.error });
            } else if (flash.warning) {
                Swal.fire({ ...swalBase, icon: 'warning', title: 'Perhatian', text: flash.warning });
            } else if (flash.info) {
                Swal.fire({ ...swalBase, icon: 'info', title: 'Informasi', text: flash.info });
            }

            const validationErrors = @json($errors->all());
            if (validationErrors.length) {
                const errorList = validationErrors.map(err => `<li class="mb-1">${err}</li>`).join('');
                Swal.fire({
                    ...swalBase,
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: `<ul class="text-left list-disc pl-4 text-sm text-gray-700">${errorList}</ul>`
                });
            }
        });
    </script>

    <script>
        // Add page-loaded class to body after page loads to show animated elements
        (function() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('page-loaded');
                });
            } else {
                // DOMContentLoaded already fired, add class immediately
                document.body.classList.add('page-loaded');
            }
        })();
    </script>
</body>

</html>
