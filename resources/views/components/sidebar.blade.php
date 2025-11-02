@php
$user = auth()->user();
$currentRoute = Route::currentRouteName();
@endphp

<div id="sidebar" class="sidebar d-flex flex-column p-3 h-100">
    {{-- SIPZIS Logo --}}
    <div class="d-flex justify-content-center align-items-center mb-3">
        <a href="{{ $user->role === 'muzakki' ? route('muzakki.dashboard') : route('dashboard') }}"
            class="navbar-brand text-decoration-none d-flex align-items-center">
            <i class="fas fa-mosque me-2 text-white fs-4"></i>
            <span class="fw-bold fs-5 text-white" style="font-family: 'Poppins', sans-serif;">SIPZIS</span>
        </a>
    </div>

    <hr class="text-white opacity-25">

    <ul class="nav nav-pills flex-column mb-auto">
        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ $user->role === 'muzakki' ? route('muzakki.dashboard') : route('dashboard') }}"
                class="nav-link {{ $currentRoute === 'dashboard' || $currentRoute === 'muzakki.dashboard' ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if($user->role !== 'muzakki')
        {{-- Admin Menu --}}
        <li class="nav-item">
            <a href="{{ route('muzakki.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'muzakki.') && !str_contains($currentRoute, 'dashboard') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>
                <span>Muzakki</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('mustahik.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'mustahik.') ? 'active' : '' }}">
                <i class="bi bi-person-hearts me-2"></i>
                <span>Mustahik</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('payments.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'payments.') ? 'active' : '' }}">
                <i class="bi bi-credit-card me-2"></i>
                <span>Pembayaran Zakat</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('distributions.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'distributions.') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i>
                <span>Distribusi Zakat</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.news.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'admin.news.') ? 'active' : '' }}">
                <i class="bi bi-newspaper me-2"></i>
                <span>Kelola Berita</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.artikel.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'admin.artikel.') ? 'active' : '' }}">
                <i class="bi bi-file-text me-2"></i>
                <span>Kelola Artikel</span>
            </a>
        </li>

        {{-- Reports Dropdown --}}
        <li class="nav-item">
            <a href="javascript:void(0)"
                class="nav-link d-flex align-items-center {{ str_starts_with($currentRoute, 'reports.') ? 'active' : '' }}"
                aria-expanded="true"
                aria-controls="reportsSubmenu"
                id="reportsDropdown">
                <i class="bi bi-file-earmark-text me-2"></i>
                <span>Laporan</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul class="collapse show" id="reportsSubmenu">
                <li class="nav-item">
                    <a href="{{ route('reports.incoming') }}"
                        class="nav-link {{ $currentRoute === 'reports.incoming' ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-circle me-2"></i>
                        <span>Laporan Masuk</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.outgoing') }}"
                        class="nav-link {{ $currentRoute === 'reports.outgoing' ? 'active' : '' }}">
                        <i class="bi bi-arrow-up-circle me-2"></i>
                        <span>Laporan Keluar</span>
                    </a>
                </li>
            </ul>
        </li>


        <li class="nav-item">
            <a href="{{ route('admin.campaigns.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'admin.campaigns.') ? 'active' : '' }}">
                <i class="bi bi-bullseye me-2"></i>
                <span>Kelola Campaign</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.programs.index') }}"
                class="nav-link {{ str_starts_with($currentRoute, 'admin.programs.') ? 'active' : '' }}">
                <i class="bi bi-grid me-2"></i>
                <span>Kelola Program</span>
            </a>
        </li>
        @endif

        <hr class="text-white opacity-25 mt-2">
    </ul>

    {{-- User Info --}}
    {{-- <div class="dropdown border-top pt-3 mt-auto">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2 fs-4"></i>
            <div class="overflow-hidden flex-grow-1">
                <strong class="d-block text-truncate">{{ $user->name }}</strong>
    <small class="d-block text-white-50">{{ ucfirst($user->role) }}</small>
</div>
</a>
<ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="dropdownUser">
    <li><a class="dropdown-item" href="{{ route('profile.show') }}">
            <i class="bi bi-person me-2"></i>Profile
        </a></li>
    <li>
        <hr class="dropdown-divider">
    </li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">
                <i class="bi bi-box-arrow-right me-2"></i>Sign out
            </button>
        </form>
    </li>
</ul>
</div> --}}
</div>

<!-- Overlay for mobile sidebar -->
<div id="sidebar-overlay"></div>

<style>
    /* ===== SIDEBAR BASE ===== */
    #sidebar {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
        min-height: 100vh;
        width: 100%;
        max-width: 280px;
        overflow-y: auto;
        overflow-x: hidden;
        position: sticky;
        top: 0;
        transition: transform 0.3s ease-in-out;
        will-change: transform;
    }

    /* ===== DESKTOP: Toggle functionality ===== */
    @media (min-width: 768px) {

        /* Fixed positioning for collapsed sidebar to prevent layout shifts */
        #sidebar.collapsed {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
        }

        /* When sidebar is collapsed on desktop, adjust main content */
        aside.col-md-3.sidebar-collapsed,
        aside.col-lg-2.sidebar-collapsed {
            position: relative;
            width: 0;
            overflow: hidden;
            padding: 0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: width;
        }

        /* Main content expands when sidebar is collapsed */
        main.sidebar-collapsed {
            margin-left: 0 !important;
            transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: margin;
        }
    }

    /* ===== MOBILE: Hide Sidebar by default, show on toggle ===== */
    @media (max-width: 767.98px) {

        /* Don't hide the aside, but position it properly */
        aside.col-md-3,
        aside.col-lg-2 {
            position: static;
            display: block !important;
        }

        /* Hide sidebar by default on mobile */
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            will-change: transform;
        }

        /* Show sidebar when it has 'show' class */
        #sidebar.show {
            transform: translateX(0);
        }

        /* Overlay */
        #sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: opacity, visibility;
        }

        #sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Full width main content on mobile */
        main.col-md-9,
        main.col-lg-10 {
            width: 100% !important;
            max-width: 100% !important;
            margin-left: 0 !important;
            transition: margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: margin;
        }
    }

    /* ===== NAV LINKS ===== */
    #sidebar .nav-link {
        color: rgba(255, 255, 255, 0.85);
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        white-space: nowrap;
        width: 100%;
    }

    #sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    #sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        font-weight: 500;
    }

    #sidebar .nav-link i {
        font-size: 1.1rem;
        width: 24px;
        min-width: 24px;
        text-align: center;
        flex-shrink: 0;
    }

    #sidebar .nav-link span {
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ===== SUBMENU / COLLAPSE ===== */
    #sidebar .collapse {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 0.5rem;
        margin: 0.25rem 0;
        padding: 0.5rem 0;
    }

    #sidebar .collapse .nav-link {
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
    }

    #sidebar .collapse .nav-link:hover {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    #sidebar .collapse .nav-link.active {
        color: #ffffff;
        font-weight: bold;
        background-color: transparent;
    }

    /* Chevron rotation */
    #sidebar .nav-link[data-bs-toggle="collapse"] .bi-chevron-down {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.8rem;
        margin-left: auto;
    }

    #sidebar .nav-link[data-bs-toggle="collapse"][aria-expanded="true"] .bi-chevron-down {
        transform: rotate(180deg);
    }

    /* Hide scrollbar */
    #sidebar::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }

    #sidebar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* ===== UTILITAS LAINNYA ===== */
    #sidebar ul {
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
    }

    #sidebar hr {
        margin: 1rem 0;
        opacity: 0.25;
        border-color: white;
    }

    aside.col-md-3,
    aside.col-lg-2 {
        padding: 0;
        max-width: 280px;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: width;
    }

    .sidebar {
        overflow-x: hidden;
    }

    /* Desktop collapse */
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

    /* Mobile overlay behavior */
    #sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* Align bullet/indicator styling with default profiles */
    #sidebar .nav-link.active::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #ffffff;
    }

    #sidebar .nav-link {
        position: relative;
        padding-left: 1.5rem;
    }

    #sidebar .nav-link.active {
        position: relative;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.getElementById("sidebarToggle");
        const overlay = document.getElementById("sidebar-overlay");
        const main = document.querySelector("main");
        const aside = sidebar ? sidebar.closest("aside") : null;

        // Only initialize if sidebar and toggle button exist
        if (!sidebar || !toggleBtn) {
            return;
        }

        // Use requestAnimationFrame for smoother animations
        function smoothToggle() {
            const isMobile = window.innerWidth < 768;

            if (isMobile) {
                // === Mobile Mode ===
                sidebar.classList.toggle("show");
                if (overlay) {
                    overlay.classList.toggle("show");
                }
            } else {
                // === Desktop Mode ===
                if (aside) {
                    aside.classList.toggle("sidebar-collapsed");
                }
                if (main) {
                    main.classList.toggle("sidebar-collapsed");
                }
            }
        }

        // Debounce function to limit how often resize events fire
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        toggleBtn.addEventListener("click", function(e) {
            e.preventDefault();
            requestAnimationFrame(smoothToggle);
        });

        // Tutup sidebar kalau klik overlay (mobile)
        if (overlay) {
            overlay.addEventListener("click", function() {
                requestAnimationFrame(() => {
                    sidebar.classList.remove("show");
                    if (overlay) {
                        overlay.classList.remove("show");
                    }
                });
            });
        }

        // Reset ke mode normal saat resize dengan debounce
        const handleResize = debounce(function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove("show");
                if (overlay) {
                    overlay.classList.remove("show");
                }
            } else {
                if (aside) {
                    aside.classList.remove("sidebar-collapsed");
                }
                if (main) {
                    main.classList.remove("sidebar-collapsed");
                }
            }
        }, 150);

        window.addEventListener("resize", handleResize);
    });
</script>