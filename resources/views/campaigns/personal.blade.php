@extends('layouts.app')

@section('page-title', 'Campaign - ' . $muzakki->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <!-- Profile Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ $muzakki->profile_photo ? asset('storage/' . $muzakki->profile_photo) : asset('images/profile-default.jpg') }}"
                                alt="{{ $muzakki->name }}"
                                class="rounded-circle"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col">
                            <h4 class="mb-1 fw-bold">{{ $muzakki->name }}</h4>
                            <p class="text-muted mb-2">
                                <i class="bi bi-envelope me-1"></i>{{ $muzakki->email }}
                            </p>
                            @if($muzakki->bio)
                            <p class="mb-0">{{ Str::limit(strip_tags($muzakki->bio), 150) }}</p>
                            @endif
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <h3 class="mb-0 text-primary">{{ $campaigns->total() }}</h3>
                                <small class="text-muted">Campaign Aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($campaignFeatureAvailable) && !$campaignFeatureAvailable)
            <div class="alert alert-warning border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill fs-4 me-3 text-warning"></i>
                    <div>
                        <h6 class="mb-1 fw-semibold text-warning">Fitur kampanye pribadi belum tersedia</h6>
                        <p class="mb-0 text-muted">Kami belum bisa menampilkan campaign berdasarkan akun ini karena data pembuat campaign belum ditautkan. Silakan hubungi admin jika ingin mengaktifkan fitur ini.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Campaign Stats -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-heart-fill text-danger fs-1 mb-2"></i>
                            <h5 class="mb-0">{{ number_format($campaigns->sum(function($c) { return $c->donations_count ?? 0; })) }}</h5>
                            <small class="text-muted">Total Donatur</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-cash-stack text-success fs-1 mb-2"></i>
                            <h5 class="mb-0">Rp {{ number_format($campaigns->sum('collected_amount'), 0, ',', '.') }}</h5>
                            <small class="text-muted">Dana Terkumpul</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-trophy-fill text-warning fs-1 mb-2"></i>
                            <h5 class="mb-0">{{ $campaigns->where('status', 'completed')->count() }}</h5>
                            <small class="text-muted">Campaign Selesai</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share Button -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">Bagikan halaman campaign saya</h6>
                            <small class="text-muted">{{ url()->current() }}</small>
                        </div>
                        <button class="btn btn-primary" onclick="copyToClipboard('{{ url()->current() }}')">
                            <i class="bi bi-share me-2"></i>Bagikan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Campaign List -->
            <div class="mb-3">
                <h5 class="fw-bold">Campaign Aktif</h5>
            </div>

            @if($campaigns->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada campaign aktif</h5>
                    <p class="text-muted">Campaign akan muncul di sini setelah dibuat</p>
                </div>
            </div>
            @else
            <div class="row">
                @foreach($campaigns as $campaign)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        @if($campaign->image)
                        <img src="{{ asset('storage/' . $campaign->image) }}"
                            class="card-img-top"
                            alt="{{ $campaign->title }}"
                            style="height: 200px; object-fit: cover;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                        @endif

                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ ucfirst($campaign->category) }}</span>
                            <h6 class="card-title fw-bold">{{ Str::limit($campaign->title, 50) }}</h6>
                            <p class="card-text text-muted small">
                                {{ Str::limit(strip_tags($campaign->description), 80) }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width: {{ $campaign->progress_percentage }}%">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Terkumpul</small>
                                <small class="fw-bold text-success">
                                    {{ $campaign->formatted_collected_amount }}
                                </small>
                            </div>

                            @if($campaign->target_amount > 0)
                            <div class="d-flex justify-content-between mb-3">
                                <small class="text-muted">Target</small>
                                <small class="text-muted">
                                    Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                                </small>
                            </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $campaign->donations_count ?? 0 }} donatur
                                </small>
                                <a href="{{ route('campaigns.show', ['category' => $campaign->category, 'campaign' => $campaign->slug]) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Donasi
                                </a>
                            </div>
                        </div>

                        @if($campaign->end_date)
                        <div class="card-footer bg-transparent border-top">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                @if(\Carbon\Carbon::parse($campaign->end_date)->isFuture())
                                {{ \Carbon\Carbon::parse($campaign->end_date)->diffForHumans() }}
                                @else
                                Campaign selesai
                                @endif
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $campaigns->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
    }
</style>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const btn = event.target.closest('button');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Tersalin!';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');

            setTimeout(function() {
                btn.innerHTML = originalContent;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
            }, 2000);
        }).catch(function(err) {
            alert('Gagal menyalin link: ' + err);
        });
    }
</script>
@endsection