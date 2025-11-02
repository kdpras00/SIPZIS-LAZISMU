@extends('layouts.app')

@section('page-title', 'Detail Muzakki - ' . $muzakki->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Detail Muzakki</h2>
        <p class="text-muted">Informasi lengkap dan riwayat pembayaran zakat</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('muzakki.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('muzakki.edit', $muzakki) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('payments.create', ['muzakki_id' => $muzakki->id]) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pembayaran
        </a>
    </div>
</div>

<div class="row">
    <!-- Muzakki Profile Information -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Informasi Pribadi
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill fs-1 text-primary"></i>
                    </div>
                    <h4 class="mt-3 mb-1">{{ $muzakki->name }}</h4>
                    <span class="badge bg-{{ $muzakki->is_active ? 'success' : 'danger' }}">
                        {{ $muzakki->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </div>

                <table class="table table-borderless small">
                    <tr>
                        <td class="text-muted" width="100">NIK</td>
                        <td>{{ $muzakki->nik ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $muzakki->email ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Telepon</td>
                        <td>{{ $muzakki->phone ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Gender</td>
                        <td>
                            <span class="badge bg-{{ $muzakki->gender === 'male' ? 'info' : 'pink' }}-subtle text-{{ $muzakki->gender === 'male' ? 'info' : 'pink' }}-emphasis">
                                {{ $muzakki->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                    </tr>
                    @if($muzakki->date_of_birth)
                    <tr>
                        <td class="text-muted">Tanggal Lahir</td>
                        <td>{{ $muzakki->date_of_birth->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Usia</td>
                        <td>{{ $muzakki->age ?? '-' }} tahun</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Pekerjaan</td>
                        <td>{{ $muzakki->occupation ? ucwords(str_replace('_', ' ', $muzakki->occupation)) : '-' }}</td>
                    </tr>
                    @if($muzakki->monthly_income)
                    <tr>
                        <td class="text-muted">Pendapatan</td>
                        <td>Rp {{ number_format($muzakki->monthly_income, 0, ',', '.') }}/bulan</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-geo-alt me-2"></i>
                    Alamat
                </h6>
            </div>
            <div class="card-body">
                @if($muzakki->address || $muzakki->city || $muzakki->province)
                <address class="mb-0">
                    @if($muzakki->address)
                    {{ $muzakki->address }}<br>
                    @endif
                    @if($muzakki->city || $muzakki->province)
                    {{ $muzakki->city }}{{ $muzakki->city && $muzakki->province ? ', ' : '' }}{{ $muzakki->province }}<br>
                    @endif
                    @if($muzakki->postal_code)
                    {{ $muzakki->postal_code }}
                    @endif
                </address>
                @else
                <p class="text-muted mb-0">Alamat belum diisi</p>
                @endif
            </div>
        </div>

        <!-- Account Information -->
        @if($muzakki->user)
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Akun Pengguna
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless small">
                    <tr>
                        <td class="text-muted" width="100">Status</td>
                        <td>
                            <span class="badge bg-{{ $muzakki->user->is_active ? 'success' : 'danger' }}">
                                {{ $muzakki->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Role</td>
                        <td>
                            <span class="badge bg-primary">{{ ucfirst($muzakki->user->role) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Terdaftar</td>
                        <td>{{ $muzakki->user->created_at->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Zakat Statistics and Payments -->
    <div class="col-lg-8">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Zakat</h6>
                                <h4 class="mb-0">Rp {{ number_format($stats['total_zakat'], 0, ',', '.') }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-currency-dollar fs-2"></i>
                            </div>
                        </div>
                        <small class="opacity-75">Sepanjang masa</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Transaksi</h6>
                                <h4 class="mb-0">{{ number_format($stats['payment_count']) }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-receipt fs-2"></i>
                            </div>
                        </div>
                        <small class="opacity-75">Pembayaran selesai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Terakhir Bayar</h6>
                                <h4 class="mb-0">
                                    @if($stats['last_payment'])
                                    {{ $stats['last_payment']->payment_date->diffForHumans() }}
                                    @else
                                    -
                                    @endif
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock-history fs-2"></i>
                            </div>
                        </div>
                        @if($stats['last_payment'])
                        <small class="opacity-75">{{ $stats['last_payment']->payment_date->format('d M Y') }}</small>
                        @else
                        <small class="opacity-75">Belum ada pembayaran</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2 text-primary"></i>
                        Riwayat Pembayaran Zakat
                    </h5>
                    <a href="{{ route('payments.index', ['search' => $muzakki->name]) }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentPayments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Kode Pembayaran</th>
                                <th>Jenis Zakat</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="bi bi-receipt text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $payment->payment_code }}</div>
                                            @if($payment->receipt_number)
                                            <small class="text-muted">No: {{ $payment->receipt_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                                    @if($payment->zakat_amount != $payment->paid_amount)
                                    <small class="text-muted">Zakat: Rp {{ number_format($payment->zakat_amount, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($payment->payment_method)
                                    @case('cash')
                                    <span class="badge bg-success-subtle text-success-emphasis">Tunai</span>
                                    @break
                                    @case('transfer')
                                    <span class="badge bg-primary-subtle text-primary-emphasis">Transfer</span>
                                    @break
                                    @case('online')
                                    <span class="badge bg-warning-subtle text-warning-emphasis">Online</span>
                                    @break
                                    @default
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ ucfirst($payment->payment_method) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div>{{ $payment->payment_date->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $payment->payment_date->format('H:i') }}</small>
                                </td>
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
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-info btn-sm" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($payment->status === 'completed')
                                        <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-outline-success btn-sm" title="Kwitansi" target="_blank">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                        @endif
                                        @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">Belum Ada Pembayaran</h5>
                    <p class="text-muted">Muzakki ini belum memiliki riwayat pembayaran zakat</p>
                    <a href="{{ route('payments.create', ['muzakki_id' => $muzakki->id]) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Pembayaran Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Action Modal for Status Toggle -->
@if(in_array(auth()->user()->role, ['admin', 'staff']))
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Perubahan Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengubah status muzakki ini menjadi {{ $muzakki->is_active ? 'non-aktif' : 'aktif' }}?</p>
                @if($muzakki->user)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Status akun pengguna juga akan ikut berubah.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('muzakki.toggle-status', $muzakki) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $muzakki->is_active ? 'warning' : 'success' }}">
                        {{ $muzakki->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush