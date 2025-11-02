<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="icon" type="image/png" href="{{ asset('img/lazismu-icon.ico') }}">
    <title>{{ isset($title) ? $title . ' - ' : '' }}SIPZIS</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



    @stack('styles')

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        a,
        button,
        input,
        select,
        textarea {
            transition: all 0.3s ease;
        }

        /* Body styling */
        body {
            font-family: 'Figtree', sans-serif;
            overflow-x: hidden;
            /* Mencegah horizontal scroll */
        }

        /* Container styling untuk memastikan layout tidak melebihi viewport */
        .container-fluid {
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        /* Main content styling */
        main {
            width: 100%;
            max-width: 100%;
            transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: margin;
        }

        /* Untuk muzakki layout */
        .muzakki-layout {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* Responsive adjustments for main content */
        @media (max-width: 767.98px) {
            .p-4 {
                padding: 1rem !important;
            }

            /* Don't hide the aside on mobile, let the sidebar handle visibility */
            aside.col-md-3,
            aside.col-lg-2 {
                position: static;
                display: block !important;
            }

            main.col-md-9,
            main.col-lg-10 {
                width: 100%;
                max-width: 100%;
                margin-left: 0 !important;
                transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                will-change: margin;
            }
        }

        /* Desktop sidebar collapsed behavior */
        @media (min-width: 768px) {
            main.sidebar-collapsed {
                margin-left: 0 !important;
                transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                will-change: margin;
            }

            /* Ensure smooth transition for main content */
            main.col-md-9,
            main.col-lg-10 {
                transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                will-change: margin;
            }

            aside.sidebar-collapsed {
                width: 0 !important;
                overflow: hidden !important;
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                will-change: width;
            }

            main.sidebar-collapsed {
                margin-left: 0 !important;
                width: 100% !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                will-change: margin, width;
            }
        }

        /* Mobile overlay behavior */
        #sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Ensure sidebar overlay doesn't block navbar */
        #sidebar-overlay {
            z-index: 1040 !important;
        }

        /* Ensure navbar is always above sidebar overlay */
        .navbar {
            z-index: 1051 !important;
        }

        /* Ensure no element blocks navbar clicks */
        main {
            position: relative;
            z-index: 1;
        }

        /* Make sure container-fluid doesn't block */
        .navbar .container-fluid {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            @auth
                @if (auth()->user()->role !== 'muzakki')
                    <aside class="col-md-3 col-lg-2 p-0">
                        @include('components.sidebar', [
                            'user' => auth()->user(),
                            'currentRoute' => request()->route()->getName() ?? '',
                        ])
                    </aside>
                    <main class="col-md-9 col-lg-10 p-0">
                        @include('components.navbar')
                        <div class="p-4">
                            @include('components.alerts')
                            @yield('content')
                        </div>
                    </main>
                @else
                    <main class="col-12 p-0 muzakki-layout">
                        <div class="p-4">
                            @include('components.alerts')
                            @yield('content')
                        </div>
                    </main>
                @endif
            @else
                <main class="col-12 p-0">
                    @include('components.navbar')
                    <div class="p-4">
                        @include('components.alerts')
                        @yield('content')
                    </div>
                </main>
            @endauth
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @stack('scripts')

    <script>
        // Wait for Bootstrap to be fully loaded
        (function() {
            function initializeBootstrap() {
                // Check if bootstrap is available
                if (typeof bootstrap === 'undefined') {
                    console.warn('Bootstrap is not loaded yet, retrying...');
                    setTimeout(initializeBootstrap, 100);
                    return;
                }

                // Initialize all Bootstrap dropdowns when DOM is ready
                document.addEventListener('DOMContentLoaded', function() {
                    // Wait a bit longer to ensure everything is loaded
                    setTimeout(function() {
                        // Initialize all Bootstrap dropdowns manually
                        const dropdownElementList = document.querySelectorAll(
                            '[data-bs-toggle="dropdown"]');
                        dropdownElementList.forEach(dropdownToggleEl => {
                            try {
                                // Destroy existing dropdown instance if any
                                const existingDropdown = bootstrap.Dropdown.getInstance(
                                    dropdownToggleEl);
                                if (existingDropdown) {
                                    existingDropdown.dispose();
                                }

                                // Create new dropdown instance
                                const newDropdown = new bootstrap.Dropdown(dropdownToggleEl, {
                                    boundary: 'viewport',
                                    popperConfig: {
                                        modifiers: [{
                                            name: 'preventOverflow',
                                            options: {
                                                boundary: 'viewport'
                                            }
                                        }]
                                    }
                                });

                                console.log('Dropdown initialized:', dropdownToggleEl);
                            } catch (e) {
                                console.error('Error initializing dropdown:', e,
                                    dropdownToggleEl);
                            }
                        });
                    }, 300);

                    // Auto-hide alerts after 5 seconds
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        setTimeout(() => {
                            try {
                                const bsAlert = new bootstrap.Alert(alert);
                                bsAlert.close();
                            } catch (e) {
                                console.error('Error closing alert:', e);
                            }
                        }, 5000);
                    });

                    // Add loading state to forms
                    const forms = document.querySelectorAll('form');
                    forms.forEach(form => {
                        form.addEventListener('submit', function() {
                            const submitBtn = this.querySelector('button[type="submit"]');
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML =
                                    '<i class="fas fa-spinner fa-spin"></i> Loading...';
                            }
                        });
                    });

                    // Ensure navbar dropdowns are clickable (fix z-index issues)
                    const navbar = document.querySelector('.navbar');
                    if (navbar) {
                        navbar.style.zIndex = '1051';
                    }

                    // Fix dropdown menu positioning
                    const dropdownMenus = document.querySelectorAll('.navbar .dropdown-menu');
                    dropdownMenus.forEach(menu => {
                        menu.style.zIndex = '1053';
                    });
                });
            }

            // Start initialization
            initializeBootstrap();
        })();

        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            // Cek jika axios sudah ada sebelum menggunakannya
            if (typeof axios !== 'undefined') {
                window.axios = axios;
                window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
