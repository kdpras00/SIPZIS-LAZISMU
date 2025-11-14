@php
    // Gunakan parameter user jika ada, atau ambil dari auth
    $user = $user ?? auth()->user();
    $currentRoute = $currentRoute ?? Route::currentRouteName();

    // Safety check: jika user null, return early untuk mencegah error
    if (!$user) {
        return;
    }
@endphp

<div id="sidebar" class="sidebar flex flex-col h-screen" style="padding: 1rem 0.5rem 1rem 0.75rem;">
    {{-- SIPZIS Logo --}}
    <div class="flex justify-center items-center mb-4 px-2">
        <a href="{{ route('dashboard') }}"
            class="flex items-center text-white no-underline">
            <i class="fas fa-mosque mr-2 text-white text-2xl"></i>
            <span class="font-bold text-xl text-white" style="font-family: 'Poppins', sans-serif;">SIPZIS</span>
        </a>
    </div>

    <hr class="border-white opacity-25 my-0 mx-2">

    <ul class="flex flex-col mb-auto list-none p-0" style="padding-left: 0.5rem; padding-right: 0.5rem;">
        {{-- Dashboard --}}
        <li class="nav-item mb-1">
            <a href="{{ route('dashboard') }}"
                class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ $currentRoute === 'dashboard' ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                style="padding-left: 0.75rem; padding-right: 0.75rem;">
                <i class="bi bi-speedometer2 mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Dashboard</span>
            </a>
        </li>

        @if ($user->role !== 'muzakki')
            {{-- Admin Menu --}}
            <li class="nav-item mb-1">
                <a href="{{ route('muzakki.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'muzakki.') && !str_contains($currentRoute, 'dashboard') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-people mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Muzakki</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('mustahik.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'mustahik.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-person-hearts mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Mustahik</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('payments.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'payments.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-credit-card mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Pembayaran Zakat</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('distributions.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'distributions.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-box-seam mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Distribusi Zakat</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.news.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'admin.news.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-newspaper mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Kelola Berita</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.artikel.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'admin.artikel.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-file-text mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Kelola Artikel</span>
                </a>
            </li>

            {{-- Reports Dropdown --}}
            <li class="nav-item mb-1">
                <a href="javascript:void(0)"
                    class="reports-dropdown-toggle nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'reports.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;"
                    aria-expanded="{{ str_starts_with($currentRoute, 'reports.') ? 'true' : 'false' }}"
                    aria-controls="reportsSubmenu" id="reportsDropdown">
                    <i class="bi bi-file-earmark-text mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Laporan</span>
                    <i class="bi bi-chevron-down ml-auto mr-2 text-sm chevron-icon"
                        style="transition: transform 0.3s ease; transform-origin: center; display: inline-block;"></i>
                </a>

                <ul class="bg-opacity-20 rounded-lg my-1 transition-all duration-300 overflow-hidden list-none p-0 {{ str_starts_with($currentRoute, 'reports.') ? '' : 'hidden' }}"
                    id="reportsSubmenu"
                    style="background: linear-gradient(135deg, rgba(6, 78, 59, 0.3) 0%, rgba(6, 95, 70, 0.3) 50%, rgba(4, 120, 87, 0.3) 100%); max-height: {{ str_starts_with($currentRoute, 'reports.') ? '500px' : '0' }}; opacity: {{ str_starts_with($currentRoute, 'reports.') ? '1' : '0' }}; padding-top: {{ str_starts_with($currentRoute, 'reports.') ? '0.75rem' : '0' }}; padding-bottom: {{ str_starts_with($currentRoute, 'reports.') ? '0.75rem' : '0' }}; padding-left: 0.5rem; padding-right: 0.5rem;">
                    <li class="nav-item mb-3">
                        <a href="{{ route('reports.incoming') }}"
                            class="nav-link flex items-center py-2.5 rounded-lg transition-all duration-200 text-sm {{ $currentRoute === 'reports.incoming' ? 'text-white font-bold bg-white bg-opacity-20' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                            style="padding-left: 1.25rem; padding-right: 0.75rem;">
                            <i
                                class="bi bi-arrow-down-circle mr-3 text-base min-w-[20px] text-center flex-shrink-0"></i>
                            <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Laporan Masuk</span>
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a href="{{ route('reports.outgoing') }}"
                            class="nav-link flex items-center py-2.5 rounded-lg transition-all duration-200 text-sm {{ $currentRoute === 'reports.outgoing' ? 'text-white font-bold bg-white bg-opacity-20' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                            style="padding-left: 1.25rem; padding-right: 0.75rem;">
                            <i class="bi bi-arrow-up-circle mr-3 text-base min-w-[20px] text-center flex-shrink-0"></i>
                            <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Laporan
                                Keluar</span>
                        </a>
                    </li>
                </ul>
            </li>


            <li class="nav-item mb-1">
                <a href="{{ route('admin.campaigns.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'admin.campaigns.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-bullseye mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Kelola Campaign</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.programs.index') }}"
                    class="nav-link flex items-center py-3 rounded-lg transition-all duration-200 whitespace-nowrap w-full {{ str_starts_with($currentRoute, 'admin.programs.') ? 'bg-white bg-opacity-20 text-white font-medium' : 'text-white text-opacity-85 hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                    style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <i class="bi bi-grid mr-3 text-lg min-w-[20px] text-center flex-shrink-0"></i>
                    <span class="flex-grow whitespace-nowrap overflow-hidden text-ellipsis">Kelola Program</span>
                </a>
            </li>
        @endif

        <hr class="border-white opacity-25 my-4 mx-2">
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
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-left: 0 !important;
        padding-right: 0.5rem !important;
    }

    #sidebar::-webkit-scrollbar {
        width: 0px;
        background: transparent;
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
        position: relative;
    }

    /* Prevent white background flash on click */
    #sidebar .nav-link {
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    /* Active state - maintain bg-white bg-opacity-20 (light green) */
    #sidebar .nav-link.bg-white {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }

    /* Hover for active items - keep same background */
    #sidebar .nav-link.bg-white:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }

    /* Hover for non-active items */
    #sidebar .nav-link:not(.bg-white):hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Focus/active states for non-active items only */
    #sidebar .nav-link:not(.bg-white):active,
    #sidebar .nav-link:not(.bg-white):focus,
    #sidebar .nav-link:not(.bg-white):focus-visible {
        background-color: rgba(255, 255, 255, 0.1) !important;
        outline: none !important;
        box-shadow: none !important;
    }

    /* Reports dropdown submenu animation */
    #sidebar #reportsSubmenu {
        max-height: 0;
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out, padding 0.3s ease-out;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
        display: none;
        visibility: hidden;
    }

    #sidebar #reportsSubmenu:not(.hidden) {
        max-height: 500px;
        opacity: 1;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        display: block !important;
        visibility: visible !important;
        transition: max-height 0.3s ease-in, opacity 0.3s ease-in, padding 0.3s ease-in, visibility 0.3s ease-in;
    }

    /* Submenu items styling */
    #sidebar #reportsSubmenu .nav-link {
        margin-bottom: 0;
    }

    /* Active state for submenu items */
    #sidebar #reportsSubmenu .nav-link.bg-white {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }

    /* Hover for active submenu items - keep same background */
    #sidebar #reportsSubmenu .nav-link.bg-white:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }

    /* Hover for non-active submenu items */
    #sidebar #reportsSubmenu .nav-link:not(.bg-white):hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Chevron rotation for reports dropdown */
    #sidebar .chevron-icon {
        transition: transform 0.3s ease;
        display: inline-block;
        transform-origin: center;
    }

    #sidebar #reportsDropdown[aria-expanded="true"] .chevron-icon {
        transform: rotate(180deg) !important;
    }

    #sidebar #reportsDropdown[aria-expanded="false"] .chevron-icon {
        transform: rotate(0deg) !important;
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

    /* List styling */
    #sidebar ul {
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
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

        // Handle Reports dropdown toggle (for Tailwind, since we're not using Bootstrap collapse)
        function initReportsDropdown() {
            const reportsDropdown = document.querySelector(".reports-dropdown-toggle");
            const reportsSubmenu = document.getElementById("reportsSubmenu");

            if (!reportsDropdown || !reportsSubmenu) {
                console.warn("Reports dropdown elements not found");
                return;
            }

            const chevronIcon = reportsDropdown.querySelector(".chevron-icon");

            // Function to update chevron rotation
            function updateChevron(isExpanded) {
                if (chevronIcon) {
                    // Force immediate update
                    chevronIcon.style.transition = "transform 0.3s ease";
                    chevronIcon.style.transformOrigin = "center";
                    chevronIcon.style.display = "inline-block";
                    chevronIcon.style.transform = isExpanded ? "rotate(180deg)" : "rotate(0deg)";
                }
            }

            // Function to open dropdown
            function openDropdown() {
                reportsDropdown.setAttribute("aria-expanded", "true");
                updateChevron(true);

                reportsSubmenu.classList.remove("hidden");
                reportsSubmenu.style.display = "block";
                reportsSubmenu.style.visibility = "visible";
                // Force reflow
                void reportsSubmenu.offsetHeight;
                const height = reportsSubmenu.scrollHeight;
                reportsSubmenu.style.maxHeight = height + "px";
                reportsSubmenu.style.opacity = "1";
                reportsSubmenu.style.paddingTop = "0.75rem";
                reportsSubmenu.style.paddingBottom = "0.75rem";
            }

            // Function to close dropdown
            function closeDropdown() {
                reportsDropdown.setAttribute("aria-expanded", "false");
                updateChevron(false);

                reportsSubmenu.style.maxHeight = "0";
                reportsSubmenu.style.opacity = "0";
                reportsSubmenu.style.paddingTop = "0";
                reportsSubmenu.style.paddingBottom = "0";
                setTimeout(() => {
                    reportsSubmenu.classList.add("hidden");
                    reportsSubmenu.style.display = "none";
                    reportsSubmenu.style.visibility = "hidden";
                }, 300);
            }

            // Click handler
            reportsDropdown.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                const isExpanded = this.getAttribute("aria-expanded") === "true";

                if (isExpanded) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });

            // Set initial state based on current route
            const isReportsActive = reportsDropdown.getAttribute("aria-expanded") === "true";
            if (isReportsActive) {
                openDropdown();
            } else {
                closeDropdown();
            }
        }

        // Initialize dropdown
        initReportsDropdown();
    });
</script>
