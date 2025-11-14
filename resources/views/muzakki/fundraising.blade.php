@extends('layouts.app')

@section('page-title', 'Galang Dana - Dashboard Muzakki')

@section('content')
<div class="container-fluid py-4" style="padding-top: 1rem !important;">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('muzakki.dashboard') }}" class="text-dark me-3">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </a>
                    <h5 class="fw-semibold mb-0">Galang Dana</h5>
                </div>
                <a href="#" class="btn btn-success btn-sm rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i>Buat Campaign
                </a>
            </div>

            <!-- Campaigns List -->
            @if($campaigns->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Campaign Saya</h6>
                    @foreach($campaigns as $campaign)
                    <div class="campaign-item p-3 mb-3 rounded-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold text-dark mb-1">{{ $campaign->title }}</h6>
                                <p class="text-muted small mb-2">{{ Str::limit($campaign->description, 100) }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <small class="text-success fw-semibold">
                                        Terkumpul: Rp {{ number_format($campaign->total_collected ?? 0, 0, ',', '.') }}
                                    </small>
                                    <small class="text-muted">
                                        Target: Rp {{ number_format($campaign->target_amount ?? 0, 0, ',', '.') }}
                                    </small>
                                </div>
                                <div class="mt-2">
                                    @if($campaign->status === 'published')
                                    <span class="badge bg-success">Aktif</span>
                                    @elseif($campaign->status === 'draft')
                                    <span class="badge bg-warning">Draft</span>
                                    @else
                                    <span class="badge bg-secondary">{{ ucfirst($campaign->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted ms-2"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-box-seam display-4 text-muted mb-3"></i>
                    <h4>Belum Ada Campaign</h4>
                    <p class="text-muted">Anda belum membuat campaign galang dana.</p>
                    <a href="#" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Buat Campaign Pertama
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
                        <a href="{{ route('muzakki.fundraising') }}" class="text-decoration-none text-success">
                            <i class="bi bi-box-seam fs-5 d-block"></i>
                            <small>Galang Dana</small>
                        </a>
                    </div>
                    <div>
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
    body {
        padding-bottom: 80px !important;
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .container-fluid {
        max-width: 100%;
        margin-top: -20px;
    }

    .campaign-item {
        transition: all 0.2s ease;
        border: 1px solid #f1f1f1;
    }

    .campaign-item:hover {
        background-color: #f0f9ff !important;
        border-color: #bae6fd;
        transform: translateX(4px);
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

