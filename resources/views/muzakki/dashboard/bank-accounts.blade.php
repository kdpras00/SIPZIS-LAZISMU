@extends('layouts.app')

@section('page-title', 'Akun Bank Saya - Dashboard Muzakki')

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
                    <h5 class="fw-semibold mb-0">Akun bank</h5>
                </div>
                <button class="btn btn-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addBankAccountModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah
                </button>
            </div>

            <!-- Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Fitur manajemen akun bank akan segera tersedia. Anda dapat menyimpan informasi rekening bank untuk memudahkan pembayaran.
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-bank display-4 text-muted mb-3"></i>
                    <h4>Akun Bank</h4>
                    <p class="text-muted">Simpan informasi rekening bank Anda untuk memudahkan pembayaran zakat.</p>
                    <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addBankAccountModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Akun Bank
                    </button>
                </div>
            </div>

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

<!-- Add Bank Account Modal -->
<div class="modal fade" id="addBankAccountModal" tabindex="-1" aria-labelledby="addBankAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankAccountModalLabel">Tambah Akun Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning border-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Fitur ini sedang dalam pengembangan.
                </div>
                <p>Dengan menyimpan akun bank, Anda dapat:</p>
                <ul>
                    <li>Menggunakan rekening yang sama untuk pembayaran berikutnya</li>
                    <li>Melihat riwayat transaksi berdasarkan rekening</li>
                    <li>Mengatur rekening utama untuk pembayaran otomatis</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
