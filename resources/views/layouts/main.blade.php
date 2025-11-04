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
    <!-- Fonts - Optimized -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles from Pages -->
    @stack('styles')

    <style>
        body{font-family:'Poppins',sans-serif}
        *{-webkit-tap-highlight-color:transparent}
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
</body>

</html>
