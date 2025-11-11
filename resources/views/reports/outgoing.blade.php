@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-2xl font-bold">Laporan Keluar</h1>
    <ol class="breadcrumb mb-4 flex space-x-2">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
        <li class="breadcrumb-item active text-gray-600">Laporan Keluar</li>
    </ol>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <i class="fas fa-filter me-1 mr-2"></i>
            Filter Data
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.outgoing') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div>
                        <label for="distribution_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Distribusi</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="distribution_type" name="distribution_type">
                            <option value="">Semua Jenis</option>
                            <option value="cash" {{ request('distribution_type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="goods" {{ request('distribution_type') == 'goods' ? 'selected' : '' }}>Barang</option>
                            <option value="voucher" {{ request('distribution_type') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                            <option value="service" {{ request('distribution_type') == 'service' ? 'selected' : '' }}>Layanan</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori Mustahik</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="category" name="category">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ \App\Models\Mustahik::CATEGORIES[$category] ?? $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari (Kode Distribusi, Nama Mustahik)</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="search" name="search" placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-search me-1 mr-2"></i> Filter
                            </button>
                        <a href="{{ route('reports.outgoing') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-sync me-1 mr-2"></i> Reset
                            </a>
                            <!-- Export Buttons -->
                        <div class="relative">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-download me-1 mr-2"></i> Export
                                </button>
                            <ul class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden dropdown-menu">
                                    <li>
                                    <button type="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="exportReport('pdf')">
                                            PDF
                                        </button>
                                    </li>
                                    <li>
                                    <button type="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="exportReport('excel')">
                                            Excel (CSV)
                                        </button>
                                    </li>
                                </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-600 text-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold uppercase mb-1">Total Distribusi</div>
                        <div class="text-2xl font-bold">{{ number_format($stats['total_count'], 0, ',', '.') }}</div>
                        </div>
                    <div class="text-4xl opacity-75">
                        <i class="fas fa-donate"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-green-600 text-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold uppercase mb-1">Total Nominal</div>
                        <div class="text-2xl font-bold">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
                        </div>
                    <div class="text-4xl opacity-75">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-cyan-600 text-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold uppercase mb-1">Bulan Ini</div>
                        <div class="text-2xl font-bold">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</div>
                        </div>
                    <div class="text-4xl opacity-75">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-yellow-600 text-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold uppercase mb-1">Pending Diterima</div>
                        <div class="text-2xl font-bold">{{ number_format($stats['pending_receipt'], 0, ',', '.') }}</div>
                        </div>
                    <div class="text-4xl opacity-75">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Card -->
    <div class="grid grid-cols-1 mb-6">
        <div class="bg-gray-900 text-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold uppercase mb-1">Saldo Tersedia</div>
                        <div class="text-2xl font-bold">Rp {{ number_format($stats['available_balance'], 0, ',', '.') }}</div>
                        </div>
                    <div class="text-4xl opacity-75">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <i class="fas fa-table me-1 mr-2"></i>
            Data Distribusi Zakat
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Kode Distribusi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Nama Mustahik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Jenis Distribusi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($distributions as $distribution)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $loop->iteration + ($distributions->currentPage() - 1) * $distributions->perPage() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $distribution->distribution_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $distribution->mustahik->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ \App\Models\Mustahik::CATEGORIES[$distribution->mustahik->category] ?? $distribution->mustahik->category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">
                                    @switch($distribution->distribution_type)
                                        @case('cash')
                                            Tunai
                                            @break
                                        @case('goods')
                                            Barang
                                            @break
                                        @case('voucher')
                                            Voucher
                                            @break
                                        @case('service')
                                            Layanan
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">{{ $distribution->distribution_date->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-300">
                                    @if($distribution->is_received)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sudah Diterima</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Diterima</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 border border-gray-300">Tidak ada data distribusi zakat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div class="text-gray-600">
                    Menampilkan {{ $distributions->count() }} dari {{ $distributions->total() }} data
                </div>
                <div>
                    {{ $distributions->links() }}
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
    window.location.href = "{{ route('reports.outgoing') }}?" + params.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    // Ensure dropdown functionality works
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
@endpush
@endsection
