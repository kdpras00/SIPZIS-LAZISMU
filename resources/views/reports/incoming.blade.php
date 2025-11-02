@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Masuk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Masuk</li>
    </ol>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Data
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.incoming') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="date_from" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_to" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Cek</option>
                            <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Cari (Kode Pembayaran, Nama Muzakki)</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('reports.incoming') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-sync me-1"></i> Reset
                            </a>
                            <!-- Export Buttons -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item" onclick="exportReport('pdf')">
                                            PDF
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item" onclick="exportReport('excel')">
                                            Excel (CSV)
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Pembayaran</div>
                            <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['total_count'], 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Nominal</div>
                            <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['pending'], 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Pembayaran Zakat
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pembayaran</th>
                            <th>Nama Muzakki</th>
                            <th>Jenis Zakat</th>
                            <th>Metode Pembayaran</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
                            <td>{{ $payment->payment_code }}</td>
                            <td>{{ $payment->muzakki?->name ?? '-' }}</td>
                            <td>{{ $payment->zakatType?->name ?? '-' }}</td>
                            <td>
                                @switch($payment->payment_method)
                                @case('cash')
                                Tunai
                                @break
                                @case('transfer')
                                Transfer
                                @break
                                @case('check')
                                Cek
                                @break
                                @case('online')
                                Online
                                @break
                                @endswitch
                            </td>
                            <td>Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td>
                                @if($payment->status == 'completed')
                                <span class="badge bg-success">Selesai</span>
                                @elseif($payment->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                                @else
                                <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data pembayaran zakat</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Menampilkan {{ $payments->count() }} dari {{ $payments->total() }} data
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportReport(format) {
        // Get current form data
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);

        // Build query string
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }

        // Add export parameter
        params.append('export', format);

        // Redirect to export URL
        window.location.href = "{{ route('reports.incoming') }}?" + params.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Ensure dropdown functionality works
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>
@endpush
@endsection