@extends('layouts.app')

@section('page-title', 'Detail Pembayaran - ' . $payment->payment_code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Detail Pembayaran Zakat</h2>
        <p class="text-muted">{{ $payment->payment_code }}</p>
    </div>
    <div class="btn-group">
        <!-- <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a> -->
        @if($payment->status === 'completed')
        <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-outline-success" target="_blank">
            <i class="bi bi-receipt"></i> Kwitansi
        </a>
        @endif
        @if(auth()->user()->role === 'admin' && $payment->status !== 'completed')
        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        @endif
    </div>
</div>

<div class="row">
    <!-- Payment Details -->
    <div class="col-lg-8">
        <!-- Main Payment Information -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-credit-card me-2 text-primary"></i>
                    Informasi Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="140">Kode Pembayaran</td>
                                <td class="fw-semibold">{{ $payment->payment_code }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. Kwitansi</td>
                                <td>{{ $payment->receipt_number ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Zakat</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        {{ $payment->zakatType->name ?? 'Donasi Umum' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Pembayaran</td>
                                <td>{{ $payment->payment_date->format('d F Y, H:i') }} WIB</td>
                            </tr>
                            @if($payment->hijri_year)
                            <tr>
                                <td class="text-muted">Tahun Hijriyah</td>
                                <td>{{ $payment->hijri_year }} H</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="140">Status</td>
                                <td>
                                    @switch($payment->status)
                                    @case('completed')
                                    <span class="badge bg-success">Selesai</span>
                                    @break
                                    @case('pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @break
                                    @case('cancelled')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                    @break
                                    @default
                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Metode Pembayaran</td>
                                <td>
                                    @switch($payment->payment_method)
                                    @case('cash')
                                    <span class="badge bg-success-subtle text-success-emphasis">
                                        <i class="bi bi-cash me-1"></i>Tunai
                                    </span>
                                    @break
                                    @case('transfer')
                                    <span class="badge bg-primary-subtle text-primary-emphasis">
                                        <i class="bi bi-bank me-1"></i>Transfer Bank
                                    </span>
                                    @break
                                    @case('online')
                                    <span class="badge bg-warning-subtle text-warning-emphasis">
                                        <i class="bi bi-globe me-1"></i>Online
                                    </span>
                                    @break
                                    @case('midtrans')
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        <i class="bi bi-credit-card me-1"></i>Midtrans
                                    </span>
                                    @break
                                    @case('check')
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                        <i class="bi bi-file-text me-1"></i>Cek
                                    </span>
                                    @break
                                    @default
                                    <span class="badge bg-light text-dark">{{ ucfirst($payment->payment_method) }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            @if($payment->payment_reference)
                            <tr>
                                <td class="text-muted">Referensi</td>
                                <td class="font-monospace">{{ $payment->payment_reference }}</td>
                            </tr>
                            @endif

                            <!-- Add Midtrans specific fields -->
                            @if($payment->midtrans_order_id)
                            <tr>
                                <td class="text-muted">Midtrans Order ID</td>
                                <td class="font-monospace">{{ $payment->midtrans_order_id }}</td>
                            </tr>
                            @endif

                            @if($payment->midtrans_payment_method)
                            <tr>
                                <td class="text-muted">Metode Pembayaran Midtrans</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        {{ $payment->midtrans_payment_method }}
                                    </span>
                                </td>
                            </tr>
                            @endif

                            @if($payment->program_category)
                            <tr>
                                <td class="text-muted">Kategori Program</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary-emphasis">
                                        {{ ucfirst(str_replace('-', ' ', $payment->program_category)) }}
                                    </span>
                                </td>
                            </tr>
                            @endif

                            @if($payment->programType)
                            <tr>
                                <td class="text-muted">Jenis Program</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}
                                    </span>
                                </td>
                            </tr>
                            @endif

                            @if($payment->receivedBy)
                            <tr>
                                <td class="text-muted">Diterima oleh</td>
                                <td>{{ $payment->receivedBy->name }}</td>
                            </tr>
                            @endif
                            @if($payment->is_guest_payment)
                            <tr>
                                <td class="text-muted">Tipe Pembayaran</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        <i class="bi bi-person-badge me-1"></i>Guest Payment
                                    </span>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amount Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-calculator me-2 text-success"></i>
                    Rincian Jumlah
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($payment->wealth_amount)
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted mb-2">Jumlah Harta</h6>
                            <h4 class="text-primary mb-0">Rp {{ number_format($payment->wealth_amount, 0, ',', '.') }}</h4>
                            <small class="text-muted">Total kekayaan yang dizakatkan</small>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <h6 class="text-muted mb-2">Zakat Wajib</h6>
                            <h4 class="text-warning mb-0">Rp {{ number_format($payment->zakat_amount, 0, ',', '.') }}</h4>
                            <small class="text-muted">Jumlah zakat yang wajib</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h6 class="text-muted mb-2">Jumlah Dibayar</h6>
                            <h4 class="text-success mb-0">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</h4>
                            <small class="text-muted">Total yang dibayarkan</small>
                        </div>
                    </div>
                </div>

                @if($payment->paid_amount > $payment->zakat_amount)
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Kelebihan Pembayaran:</strong>
                    Rp {{ number_format($payment->paid_amount - $payment->zakat_amount, 0, ',', '.') }}
                    <br>
                    <small>Kelebihan ini dapat dianggap sebagai infaq atau shodaqoh.</small>
                </div>
                @elseif($payment->paid_amount < $payment->zakat_amount)
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Kekurangan Pembayaran:</strong>
                        Rp {{ number_format($payment->zakat_amount - $payment->paid_amount, 0, ',', '.') }}
                    </div>
                    @else
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Pembayaran Pas:</strong> Jumlah yang dibayar sesuai dengan kewajiban zakat.
                    </div>
                    @endif
            </div>
        </div>

        <!-- Notes Section -->
        @if($payment->notes)
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-sticky me-2 text-warning"></i>
                    Catatan
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $payment->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Muzakki Information -->
    <div class="col-lg-4">
        <!-- Muzakki Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Informasi Muzakki
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-fill fs-4 text-primary"></i>
                    </div>
                    <h5 class="mt-2 mb-1">{{ $payment->muzakki->name }}</h5>
                    @if(!$payment->is_guest_payment)
                    <span class="badge bg-success">Terdaftar</span>
                    @else
                    <span class="badge bg-info">Guest</span>
                    @endif
                </div>

                <table class="table table-borderless small">
                    @if($payment->muzakki->email)
                    <tr>
                        <td class="text-muted" width="80">Email</td>
                        <td>{{ $payment->muzakki->email }}</td>
                    </tr>
                    @endif
                    @if($payment->muzakki->phone)
                    <tr>
                        <td class="text-muted">Telepon</td>
                        <td>{{ $payment->muzakki->phone }}</td>
                    </tr>
                    @endif
                    @if($payment->muzakki->address)
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>{{ Str::limit($payment->muzakki->address, 50) }}</td>
                    </tr>
                    @endif
                    @if($payment->muzakki->city)
                    <tr>
                        <td class="text-muted">Kota</td>
                        <td>{{ $payment->muzakki->city }}</td>
                    </tr>
                    @endif
                </table>

                @if(!$payment->is_guest_payment && $payment->muzakki->user)
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Akun Pengguna:</small>
                        <span class="badge bg-{{ $payment->muzakki->user->is_active ? 'success' : 'secondary' }}">
                            {{ $payment->muzakki->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
                @endif

                @if(auth()->user()->role === 'admin')
                <div class="border-top pt-3 mt-3">
                    <a href="{{ route('muzakki.show', $payment->muzakki) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-eye me-1"></i> Lihat Detail Muzakki
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Timeline -->
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Timeline Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Pembayaran Dibuat</h6>
                            <p class="timeline-text small text-muted mb-0">
                                {{ $payment->created_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    @if($payment->status === 'completed')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Pembayaran Selesai</h6>
                            <p class="timeline-text small text-muted mb-0">
                                {{ $payment->payment_date->format('d F Y, H:i') }} WIB
                            </p>
                            @if($payment->receivedBy)
                            <p class="timeline-text small text-muted mb-0">
                                Diterima oleh: {{ $payment->receivedBy->name }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @elseif($payment->status === 'pending')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Menunggu Konfirmasi</h6>
                            <p class="timeline-text small text-muted mb-0">
                                Pembayaran sedang diproses
                            </p>
                        </div>
                    </div>
                    @elseif($payment->status === 'cancelled')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Pembayaran Dibatalkan</h6>
                            <p class="timeline-text small text-muted mb-0">
                                {{ $payment->updated_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Actions (if admin) -->
@if(auth()->user()->role === 'admin' && $payment->status !== 'completed')
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-tools me-2"></i>
                    Aksi Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    @if($payment->status === 'pending')
                    <form action="{{ route('payments.update', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi pembayaran ini sebagai selesai?')">
                            <i class="bi bi-check-circle me-1"></i>
                            Konfirmasi Pembayaran
                        </button>
                    </form>

                    <form action="{{ route('payments.update', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan pembayaran ini?')">
                            <i class="bi bi-x-circle me-1"></i>
                            Batalkan Pembayaran
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Custom Timeline Styles -->
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -1.4375rem;
        top: 1.5rem;
        width: 2px;
        height: calc(100% + 0.5rem);
        background-color: #dee2e6;
    }

    .timeline-marker {
        position: absolute;
        left: -1.875rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px #dee2e6;
    }

    .timeline-content {
        margin-left: 0.5rem;
    }

    .timeline-title {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .timeline-text {
        line-height: 1.4;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Copy payment code functionality
        const paymentCode = '{{ $payment->payment_code }}';

        // Add click-to-copy functionality if needed
        document.querySelectorAll('.font-monospace').forEach(element => {
            element.style.cursor = 'pointer';
            element.title = 'Klik untuk menyalin';

            element.addEventListener('click', function() {
                navigator.clipboard.writeText(this.textContent).then(function() {
                    // Show toast or notification
                });
            });
        });
    });
</script>
@endpush