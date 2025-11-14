@extends('layouts.app')

@section('page-title', 'Amalanku - Dashboard Muzakki')

@section('content')
<div class="container-fluid py-4" style="padding-top: 1rem !important;">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('muzakki.dashboard') }}" class="text-dark me-3">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <h5 class="fw-semibold mb-0">Amalanku</h5>
            </div>

            <!-- Stats Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Ringkasan Amal</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded-3">
                                <h4 class="fw-bold text-success mb-1">{{ $stats['total_count'] }}</h4>
                                <small class="text-muted">Total Donasi</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded-3">
                                <h4 class="fw-bold text-success mb-1">Rp {{ number_format($stats['total_donated'], 0, ',', '.') }}</h4>
                                <small class="text-muted">Total Nominal</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center p-3 bg-success text-white rounded-3">
                                <h5 class="fw-bold mb-1">Rp {{ number_format($stats['this_year'], 0, ',', '.') }}</h5>
                                <small>Tahun Ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Donations -->
            @if($payments->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Donasi Terakhir</h6>
                    @foreach($payments as $payment)
                    <div class="donation-item p-3 mb-2 rounded-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-semibold text-dark mb-1">
                                    {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}
                                </h6>
                                <small class="text-muted">
                                    {{ $payment->payment_date->translatedFormat('d F Y') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <p class="fw-bold text-success mb-0">
                                    Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                                </p>
                                <span class="badge bg-success">Selesai</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('muzakki.dashboard.transactions') }}" class="btn btn-outline-success btn-sm rounded-pill">
                            Lihat Semua Transaksi
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-heart display-4 text-muted mb-3"></i>
                    <h4>Belum Ada Donasi</h4>
                    <p class="text-muted">Mulai berdonasi untuk melihat ringkasan amal Anda.</p>
                    <a href="{{ route('muzakki.donation') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Mulai Donasi
                    </a>
                </div>
            </div>
            @endif

            <!-- Bottom Navigation -->
            <div class="card border-0 shadow-sm mt-4 fixed-bottom-nav">
                <div class="card-body d-flex justify-content-around text-center">
                    <div>
                        <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-house fs-5 d-block"></i>
                            <small>Home</small>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('muzakki.donation') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-heart fs-5 d-block"></i>
                            <small>Donasi</small>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('muzakki.fundraising') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-box-seam fs-5 d-block"></i>
                            <small>Galang Dana</small>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('muzakki.amalanku') }}" class="text-decoration-none text-success">
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
    body {
        padding-bottom: 80px !important;
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .container-fluid {
        max-width: 100%;
        margin-top: -20px;
    }

    .donation-item {
        transition: all 0.2s ease;
        border: 1px solid #f1f1f1;
    }

    .donation-item:hover {
        background-color: #f0f9ff !important;
        border-color: #bae6fd;
    }

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
</style>
@endsection

