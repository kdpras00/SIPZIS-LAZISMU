<nav class="navbar navbar-expand-lg shadow-sm py-3 mb-3"
    style="background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%); border-bottom: 1px solid rgba(255, 255, 255, 0.1); position: relative; z-index: 1051;">
    <div class="container-fluid">
        <button class="btn btn-outline-light" type="button" id="sidebarToggle" style="position: relative; z-index: 1052;">
            <i class="bi bi-list"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown me-4">
                <button class="btn btn-outline-light position-relative px-3 py-2" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    @php
                        $user = auth()->user();
                        $unreadCount = 0;

                        if ($user->role === 'muzakki' && $user->muzakki) {
                            $unreadCount = $user->muzakki->unread_notifications_count;
                        } else {
                            $unreadCount = $user->unread_notifications_count;
                        }

                        // Also count pending mustahik for admin
                        $pendingMustahik = 0;
                        if ($user->role !== 'muzakki') {
                            $pendingMustahik = \App\Models\Mustahik::pending()->count();
                        }
                    @endphp
                    @if ($unreadCount > 0 || $pendingMustahik > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount + $pendingMustahik }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 300px;">
                    <li class="dropdown-header fw-bold">Notifikasi</li>
                    @php
                        $notifications = collect();
                        if ($user->role === 'muzakki' && $user->muzakki) {
                            $notifications = $user->muzakki->notifications()->latest()->limit(5)->get();
                        } else {
                            $notifications = $user->notifications()->latest()->limit(5)->get();
                            // For admin, also include pending mustahik as a notification
                            if ($pendingMustahik > 0) {
                                $mustahikNotification = new \stdClass();
                                $mustahikNotification->id = 'mustahik_pending';
                                $mustahikNotification->title = 'Mustahik Menunggu Verifikasi';
                                $mustahikNotification->message = $pendingMustahik . ' mustahik menunggu verifikasi';
                                $mustahikNotification->type = 'account';
                                $mustahikNotification->created_at = now();
                                $mustahikNotification->is_read = false;
                                $notifications->prepend($mustahikNotification);
                            }
                        }
                    @endphp

                    @if ($notifications->count() > 0)
                        @foreach ($notifications as $notification)
                            @if (isset($notification->id) && $notification->id === 'mustahik_pending')
                                <li>
                                    <a class="dropdown-item d-flex align-items-start py-2"
                                        href="{{ route('mustahik.index', ['status' => 'pending']) }}">
                                        <div class="me-2 mt-1">
                                            <i class="bi bi-person-exclamation-fill text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ $notification->title }}</strong>
                                            </div>
                                            <small class="text-muted">{{ $notification->message }}</small>
                                            <div class="small text-muted">
                                                {{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item d-flex align-items-start py-2" href="#">
                                        <div class="me-2 mt-1">
                                            @switch($notification->type)
                                                @case('payment')
                                                    <i class="bi bi-credit-card text-success"></i>
                                                @break

                                                @case('distribution')
                                                    <i class="bi bi-box-seam text-primary"></i>
                                                @break

                                                @case('program')
                                                    <i class="bi bi-calendar-event text-purple"></i>
                                                @break

                                                @case('account')
                                                    <i class="bi bi-person-circle text-warning"></i>
                                                @break

                                                @case('reminder')
                                                    <i class="bi bi-alarm text-orange"></i>
                                                @break

                                                @default
                                                    <i class="bi bi-bell text-secondary"></i>
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ $notification->title }}</strong>
                                                @if (!$notification->is_read)
                                                    <span class="badge bg-danger rounded-pill">baru</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $notification->message }}</small>
                                            <div class="small text-muted">
                                                {{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-center"
                                href="{{ $user->role === 'muzakki' ? route('muzakki.notifications.index') : '#' }}">Lihat
                                semua notifikasi</a>
                        </li>
                    @else
                        <li><span class="dropdown-item text-muted text-center">Tidak ada notifikasi</span></li>
                    @endif
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle px-3 py-2" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    {{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                    <li>
                        <h6 class="dropdown-header">{{ ucfirst(auth()->user()->role) }}</h6>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i
                                class="bi bi-person me-2"></i>Profile</a></li>
                    <!-- <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li> -->
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Ensure navbar works properly with new layout */
    .navbar {
        margin-bottom: 0;
        border-radius: 0;
        max-width: 100%;
        overflow: visible !important;
    }

    /* Custom colors for notification icons */
    .text-purple {
        color: #8b5cf6;
    }

    .text-orange {
        color: #f97316;
    }

    /* Notification item hover effect */
    .dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Memastikan container navbar tidak menyebabkan horizontal scroll */
    .container-fluid {
        max-width: 100%;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .navbar .container-fluid {
        overflow: visible !important;
    }


    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .navbar {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
</style>
