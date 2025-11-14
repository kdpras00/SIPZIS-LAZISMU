@extends('layouts.app')

@section('page-title', 'Profil Muzakki')

@section('content')
    <div class="container-fluid py-4" style="padding-top: 1rem !important;">
        <div class="row justify-content-center">

            <div class="col-12 col-md-10 col-lg-8">
                <!-- Profil Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <!-- Progress Lengkapi Profil -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-muted mb-0">Kelengkapan Profil</h6>
                                <!-- Tombol titik tiga -->
                                <button class="btn custom-btn px-2 py-1 no-border">
                                    <i class="bi bi-three-dots-vertical fs-6"></i>
                                </button>

                            </div>
                            <div class="progress" style="height: 6px;">
                                <?php
                                $width = $profileCompleteness . '%';
                                $progressClass = 'bg-warning';
                                if ($profileCompleteness < 30) {
                                    $progressClass = 'bg-danger';
                                } elseif ($profileCompleteness >= 70) {
                                    $progressClass = 'bg-success';
                                }
                                ?>
                                <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar"
                                    style="width: <?php echo $width; ?>; transition: width 0.6s ease-in-out;"></div>
                            </div>
                            <small class="text-muted">{{ $profileCompleteness }}%</small>
                        </div>


                        <!-- Informasi Akun -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                @if($muzakki->profile_photo)
                                <img src="{{ asset('storage/' . $muzakki->profile_photo) }}" 
                                     alt="Profile Photo" 
                                     class="rounded-circle me-3"
                                     style="width:60px; height:60px; object-fit: cover; border: 2px solid #e9ecef;">
                                @else
                                <div class="rounded-circle bg-light border me-3"
                                    style="width:60px; height:60px; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-person-circle fs-1 text-secondary"></i>
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $muzakki->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $muzakki->email }}</p>
                                    <small class="text-muted">{{ explode('@', $muzakki->email)[0] }}</small>
                                </div>
                            </div>
                            <a href="{{ route('profile.show') }}"
                                class="btn btn-outline-success btn-sm rounded-pill custom-btn">Edit profil</a>

                        </div>

                        <!-- Donasi Info -->
                        <div class="bg-green text-white rounded-4 p-3 mb-3"
                            style="background-color:#28a745 !important; box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3), 0 -4px 8px rgba(40, 167, 69, 0.3);">
                            Kamu telah berdonasi sebanyak <strong>{{ $stats['payment_count'] ?? 0 }}</strong> kali, dengan
                            total donasi sebesar <strong>Rp
                                {{ number_format($stats['total_zakat_paid'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Saya -->
                <div class="card border-0 shadow-sm mb-4 sticky-activity">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Aktivitas Saya</h5>

                        <div class="list-group list-group-flush">
                            <a href="{{ route('muzakki.dashboard.transactions') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <i class="bi bi-receipt-cutoff fs-5 text-purple me-3"></i>
                                <span class="fw-semibold text-dark">Transaksi saya</span>
                            </a>

                            <a href="{{ route('muzakki.dashboard.recurring') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <i class="bi bi-calendar-check fs-5 text-purple me-3"></i>
                                <span class="fw-semibold text-dark">Donasi rutin saya</span>
                            </a>

                            <a href="{{ route('muzakki.dashboard.bank-accounts') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <i class="bi bi-bank fs-5 text-purple me-3"></i>
                                <span class="fw-semibold text-dark">Akun bank</span>
                            </a>

                            <a href="{{ route('muzakki.dashboard.management') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <i class="bi bi-person-gear fs-5 text-purple me-3"></i>
                                <span class="fw-semibold text-dark">Manajemen akun</span>
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Bottom Navigation -->
                <div class="card border-0 shadow-sm mt-4 fixed-bottom-nav" style="max-width: 910px;">
                    <div class="card-body d-flex justify-content-between text-center px-2 px-sm-4">
                        <div class="flex-fill">
                            <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-house fs-5 d-block"></i>
                                <small>Home</small>
                            </a>
                        </div>
                        <div class="flex-fill">
                            <a href="{{ route('muzakki.donation') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-heart fs-5 d-block"></i>
                                <small>Donasi</small>
                            </a>
                        </div>
                        <div class="flex-fill">
                            <a href="{{ route('muzakki.fundraising') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-box-seam fs-5 d-block"></i>
                                <small>Galang Dana</small>
                            </a>
                        </div>
                        <div class="flex-fill">
                            <a href="{{ route('muzakki.amalanku') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-person fs-5 d-block"></i>
                                <small>Amalanku</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-green {
            background-color: #28a745 !important;
        }

        .hover-shadow-sm:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Bottom Navigation tetap di bawah */
        .fixed-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 2rem);
            max-width: 800px;
            z-index: 1030;
            margin: 0 auto;
            border-radius: 0 !important;
            filter: drop-shadow(0 -2px 4px rgba(0, 0, 0, 0.1));
            border-top: 1px solid #e0e0e0;
        }

        body {
            padding-bottom: 80px !important;
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        .container-fluid {
            max-width: 100%;
            margin-top: -20px;
        }

        .list-group-item:hover {
            background-color: #f9f9fc !important;
        }

        .sticky-activity {
            position: sticky;
            top: 90px;
            z-index: 100;
            background: white;
        }

        /* 🔥 Custom Button Tanpa Efek Hover */
        .custom-btn {
            font-weight: 500;
            color: #198754 !important;
            border-color: #198754 !important;
            background-color: transparent !important;
            box-shadow: none !important;
            transition: none !important;
            outline: none !important;
            transform: none !important;
        }

        .custom-btn:hover,
        .custom-btn:focus,
        .custom-btn:active,
        .custom-btn:visited {
            background-color: transparent !important;
            color: #198754 !important;
            border-color: #198754 !important;
            font-weight: 600 !important;
            box-shadow: none !important;
            outline: none !important;
            transform: none !important;
            text-decoration: none !important;
        }

        /* 🧱 Hapus efek "klik menekan" pada device mobile */
        .custom-btn:active {
            position: relative;
            top: 0 !important;
        }

        /* 🧩 Pastikan Bootstrap tidak menimpa efek */
        .btn-outline-success.custom-btn:not(:disabled):not(.disabled):active,
        .btn-outline-success.custom-btn:not(:disabled):not(.disabled).active {
            color: #198754 !important;
            background-color: transparent !important;
            border-color: #198754 !important;
            box-shadow: none !important;
        }

        .no-border {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 4px 6px !important;
        }

        .no-border:hover,
        .no-border:focus,
        .no-border:active {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            outline: none !important;
            color: #198754 !important;
            /* tetap hijau */
        }
    </style>

@endsection
