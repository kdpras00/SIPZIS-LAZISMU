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
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
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
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $muzakki->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $muzakki->email }}</p>
                                    <small class="text-muted">{{ explode('@', $muzakki->email)[0] }}</small>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}"
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
                            <a href="{{ route('dashboard.transactions') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <svg class="w-5 h-5 text-green-600 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="fw-semibold text-dark">Transaksi saya</span>
                            </a>

                            <a href="{{ route('dashboard.recurring') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <svg class="w-5 h-5 text-green-600 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="fw-semibold text-dark">Donasi rutin saya</span>
                            </a>

                            <a href="{{ route('dashboard.bank-accounts') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <svg class="w-5 h-5 text-green-600 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span class="fw-semibold text-dark">Akun bank</span>
                            </a>

                            <a href="{{ route('dashboard.management') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 px-2 border-0">
                                <svg class="w-5 h-5 text-green-600 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
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
                                <svg class="w-5 h-5 mx-auto mb-1 block text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <small>Home</small>
                            </a>
                        </div>
                        <div class="flex-fill">
                            <a href="{{ route('donation') }}" class="text-decoration-none text-dark">
                                <svg class="w-5 h-5 mx-auto mb-1 block text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                <small>Donasi</small>
                            </a>
                        </div>
                        <div class="flex-fill">
                            <a href="{{ route('fundraising') }}" class="text-decoration-none text-dark">
                                <svg class="w-5 h-5 mx-auto mb-1 block text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <small>Galang Dana</small>
                            </a>
                        </div>
                        <div class="flex-fill">
                            <a href="{{ route('amalanku') }}" class="text-decoration-none text-dark">
                                <svg class="w-5 h-5 mx-auto mb-1 block text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
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
