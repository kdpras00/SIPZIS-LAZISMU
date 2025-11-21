@extends('layouts.app')

@section('page-title', 'Manajemen Distribusi Zakat')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold mb-1">Manajemen Distribusi Zakat</h2>
        <p class="text-gray-600">Kelola dan pantau distribusi zakat kepada mustahik</p>
    </div>
    <div>
        <a href="{{ route('distributions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="bi bi-plus-circle mr-2"></i> Tambah Distribusi
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow-sm mb-6 border border-gray-200">
    <div class="p-6">
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 md:col-span-3">
                <input type="text" id="search-input" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kode distribusi, program, nama mustahik..." value="{{ request('search') }}">
            </div>
            <div class="col-span-12 md:col-span-2">
                <select id="category-filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e')] bg-[length:16px] bg-[right_8px_center] bg-no-repeat pr-8">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 md:col-span-2">
                <select id="distribution-type-filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e')] bg-[length:16px] bg-[right_8px_center] bg-no-repeat pr-8">
                    <option value="">Semua Jenis</option>
                    <option value="cash" {{ request('distribution_type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="goods" {{ request('distribution_type') == 'goods' ? 'selected' : '' }}>Barang</option>
                    <option value="voucher" {{ request('distribution_type') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                    <option value="service" {{ request('distribution_type') == 'service' ? 'selected' : '' }}>Layanan</option>
                </select>
            </div>
            <div class="col-span-12 md:col-span-2">
                <input type="text" id="program-filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="Program" value="{{ request('program') }}">
            </div>
            <div class="col-span-12 md:col-span-2">
                <select id="received-status-filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e')] bg-[length:16px] bg-[right_8px_center] bg-no-repeat pr-8">
                    <option value="">Semua Status</option>
                    <option value="received" {{ request('received_status') == 'received' ? 'selected' : '' }}>Sudah Diterima</option>
                    <option value="pending" {{ request('received_status') == 'pending' ? 'selected' : '' }}>Belum Diterima</option>
                </select>
            </div>
            <div class="col-span-12 md:col-span-1">
                <button type="button" id="reset-filters" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white hover:bg-gray-50 transition-colors focus:outline-none focus:ring-1 focus:ring-blue-500 flex items-center justify-center">
                    <i class="bi bi-arrow-clockwise mr-1"></i>
                    Reset
                </button>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-3 mt-3">
            <div class="col-span-12 md:col-span-3">
                <input type="date" id="date-from" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_from') }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <input type="date" id="date-to" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_to') }}">
            </div>
            <div class="col-span-12 md:col-span-6 flex items-center">
                <div id="search-loading" class="hidden flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-600 border-t-transparent"></div>
                    <span class="ml-2 text-sm text-gray-600">Mencari...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border-0">
        <div class="p-6 text-center">
            <i class="bi bi-cash-stack text-4xl text-blue-600 mb-2"></i>
            <h5 class="text-lg font-semibold mb-0" id="total-amount">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h5>
            <small class="text-gray-600">Total Distribusi</small>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-0">
        <div class="p-6 text-center">
            <i class="bi bi-people text-4xl text-green-600 mb-2"></i>
            <h5 class="text-lg font-semibold mb-0" id="total-count">{{ number_format($stats['total_count']) }}</h5>
            <small class="text-gray-600">Total Penerima</small>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-0">
        <div class="p-6 text-center">
            <i class="bi bi-calendar-month text-4xl text-cyan-600 mb-2"></i>
            <h5 class="text-lg font-semibold mb-0" id="thismonth-amount">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</h5>
            <small class="text-gray-600">Bulan Ini</small>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-0">
        <div class="p-6 text-center">
            <i class="bi bi-clock text-4xl text-yellow-600 mb-2"></i>
            <h5 class="text-lg font-semibold mb-0" id="pending-count">{{ $stats['pending_receipt'] }}</h5>
            <small class="text-gray-600">Belum Diterima</small>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-0 col-span-2 md:col-span-1">
        <div class="p-6 text-center">
            <i class="bi bi-wallet2 text-4xl {{ $stats['available_balance'] > 0 ? 'text-green-600' : 'text-red-600' }} mb-2"></i>
            <h5 class="text-lg font-semibold mb-0" id="available-balance">Rp {{ number_format($stats['available_balance'], 0, ',', '.') }}</h5>
            <small class="text-gray-600">Saldo Tersedia</small>
        </div>
    </div>
</div>

<!-- Distributions Table -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 bg-white">
        <h5 class="text-lg font-semibold mb-0">Daftar Distribusi Zakat</h5>
    </div>
    <div class="p-0" id="distributions-table-container">
        @include('distributions.partials.table')
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let searchTimeout;
        let currentPage = 1;

        // Configuration from server
        const config = {
            apiRoute: "{{ route('api.distributions.search', [], false) }}",
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        // Get CSRF token
        const csrfToken = config.csrfToken;

        // Debounced search function
        function performSearch(page = 1) {
            const searchData = {
                search: document.getElementById('search-input').value,
                category: document.getElementById('category-filter').value,
                distribution_type: document.getElementById('distribution-type-filter').value,
                program: document.getElementById('program-filter').value,
                received_status: document.getElementById('received-status-filter').value,
                date_from: document.getElementById('date-from').value,
                date_to: document.getElementById('date-to').value,
                page: page
            };

            // Show loading indicator
            const loadingEl = document.getElementById('search-loading');
            if (loadingEl) {
                loadingEl.classList.remove('hidden');
            }

            // Create query string
            const params = new URLSearchParams(searchData);

            const apiRoute = config.apiRoute;

            fetch(apiRoute + '?' + params.toString(), {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    cache: 'no-store'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Permintaan pencarian gagal (${response.status})`);
                    }
                    return response.json();
                })
                .then(response => {
                    if (response.success) {
                        // Update table
                        updateTable(response.data.distributions, response.data.pagination);
                        // Update statistics
                        updateStatistics(response.data.statistics);
                        // Update current page
                        currentPage = response.data.pagination.current_page;
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    alert('Terjadi kesalahan saat mencari data. Silakan coba lagi.');
                })
                .finally(() => {
                    // Hide loading indicator
                    const loadingEl = document.getElementById('search-loading');
                    if (loadingEl) {
                        loadingEl.classList.add('hidden');
                    }
                });
        }

        // Update table with new data
        function updateTable(distributions, pagination) {
            let tableHtml = '';

            if (distributions.length > 0) {
                tableHtml = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Distribusi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mustahik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

                distributions.forEach(function(distribution) {
                    const distributionDate = new Date(distribution.distribution_date).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    // Distribution type display names
                    const distributionTypes = {
                        'cash': 'Tunai',
                        'goods': 'Barang',
                        'voucher': 'Voucher',
                        'service': 'Layanan'
                    };

                    // Distribution type colors
                    const typeColors = {
                        'cash': 'bg-green-100 text-green-800',
                        'goods': 'bg-cyan-100 text-cyan-800',
                        'voucher': 'bg-yellow-100 text-yellow-800',
                        'service': 'bg-blue-100 text-blue-800'
                    };

                    // Category display names
                    const categoryMap = {
                        'fakir': 'Fakir',
                        'miskin': 'Miskin',
                        'amil': 'Amil',
                        'muallaf': 'Muallaf',
                        'riqab': 'Riqab',
                        'gharim': 'Gharim',
                        'fisabilillah': 'Fi Sabilillah',
                        'ibnu_sabil': 'Ibnu Sabil'
                    };

                    tableHtml += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold">${distribution.distribution_code}</div>
                            ${distribution.location ? '<small class="text-gray-500">' + distribution.location + '</small>' : ''}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold">${distribution.mustahik.name}</div>
                            <small class="text-gray-500">${categoryMap[distribution.mustahik.category] || distribution.mustahik.category}</small>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${distribution.program_name ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">' + distribution.program_name + '</span>' : '<span class="text-gray-500">-</span>'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${typeColors[distribution.distribution_type]}">${distributionTypes[distribution.distribution_type]}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold">Rp ${parseInt(distribution.amount).toLocaleString('id-ID')}</div>
                            ${distribution.goods_description ? '<small class="text-gray-500">' + distribution.goods_description + '</small>' : ''}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${distribution.is_received ? 
                                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sudah Diterima</span>' : 
                                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Diterima</span>'
                            }
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${distributionDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="/distributions/${distribution.id}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" 
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="/distributions/${distribution.id}/receipt" 
                                   class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" 
                                   title="Kwitansi" target="_blank">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>
                                <a href="/distributions/${distribution.id}/edit" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200" 
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                ${!distribution.is_received ? `
                                <button type="button" 
                                        class="inline-flex items-center px-3 py-1.5 border border-yellow-300 shadow-sm text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200" 
                                        title="Tandai Diterima" 
                                        onclick="markAsReceived(${distribution.id}, '${distribution.mustahik.name.replace(/'/g, "\\'")}')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <form action="/distributions/${distribution.id}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus distribusi ini?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" 
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
                });

                tableHtml += `
                        </tbody>
                    </table>
                </div>
            `;

                // Add pagination if needed
                if (pagination.last_page > 1) {
                    tableHtml += `
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-600 text-sm">
                                Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} data
                            </div>
                            <nav>
                                <ul class="flex space-x-2">
                `;

                    if (pagination.current_page > 1) {
                        tableHtml += '<li><a class="px-3 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 pagination-link" href="#" data-page="' + (pagination.current_page - 1) + '">‹</a></li>';
                    }

                    for (let i = 1; i <= pagination.last_page; i++) {
                        const activeClass = pagination.current_page == i ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 hover:bg-gray-50';
                        tableHtml += '<li><a class="px-3 py-2 text-sm border rounded pagination-link ' + activeClass + '" href="#" data-page="' + i + '">' + i + '</a></li>';
                    }

                    if (pagination.current_page < pagination.last_page) {
                        tableHtml += '<li><a class="px-3 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 pagination-link" href="#" data-page="' + (pagination.current_page + 1) + '">›</a></li>';
                    }

                    tableHtml += `
                                </ul>
                            </nav>
                        </div>
                    </div>
                `;
                }
            } else {
                tableHtml = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h5 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data distribusi</h5>
                    <p class="text-gray-600 mb-4">Tidak ada distribusi yang sesuai dengan kriteria pencarian</p>
                    <button type="button" id="clear-search" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Pencarian
                    </button>
                </div>
            `;
            }

            document.getElementById('distributions-table-container').innerHTML = tableHtml;
        }

        // Update statistics
        function updateStatistics(stats) {
            document.getElementById('total-amount').textContent = 'Rp ' + parseInt(stats.total_amount).toLocaleString('id-ID');
            document.getElementById('total-count').textContent = stats.total_count.toLocaleString('id-ID');
            document.getElementById('thismonth-amount').textContent = 'Rp ' + parseInt(stats.this_month).toLocaleString('id-ID');
            document.getElementById('pending-count').textContent = stats.pending_receipt.toLocaleString('id-ID');
            document.getElementById('available-balance').textContent = 'Rp ' + parseInt(stats.available_balance).toLocaleString('id-ID');

            // Update available balance color
            const balanceIcon = document.getElementById('available-balance').previousElementSibling;
            if (stats.available_balance > 0) {
                balanceIcon.className = balanceIcon.className.replace('text-red-600', 'text-green-600');
            } else {
                balanceIcon.className = balanceIcon.className.replace('text-green-600', 'text-red-600');
            }
        }

        // Search input with debouncing (reduced delay for better responsiveness)
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Show loading immediately
            document.getElementById('search-loading').classList.remove('hidden');
            searchTimeout = setTimeout(function() {
                performSearch(1);
            }, 300); // Reduced to 300ms for better responsiveness
        });

        // Filter changes
        document.getElementById('category-filter').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('distribution-type-filter').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('program-filter').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Show loading immediately
            document.getElementById('search-loading').classList.remove('hidden');
            searchTimeout = setTimeout(function() {
                performSearch(1);
            }, 300); // Reduced to 300ms for better responsiveness
        });

        document.getElementById('received-status-filter').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('date-from').addEventListener('change', function() {
            performSearch(1);
        });

        document.getElementById('date-to').addEventListener('change', function() {
            performSearch(1);
        });

        // Reset filters
        document.getElementById('reset-filters').addEventListener('click', function() {
            document.getElementById('search-input').value = '';
            document.getElementById('category-filter').value = '';
            document.getElementById('distribution-type-filter').value = '';
            document.getElementById('program-filter').value = '';
            document.getElementById('received-status-filter').value = '';
            document.getElementById('date-from').value = '';
            document.getElementById('date-to').value = '';
            performSearch(1);
        });

        // Pagination click handler (using event delegation)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('pagination-link')) {
                e.preventDefault();
                const page = e.target.dataset.page;
                performSearch(page);
            }

            // Clear search button (using event delegation)
            if (e.target.id === 'clear-search' || e.target.closest('#clear-search')) {
                document.getElementById('search-input').value = '';
                document.getElementById('category-filter').value = '';
                document.getElementById('distribution-type-filter').value = '';
                document.getElementById('program-filter').value = '';
                document.getElementById('received-status-filter').value = '';
                document.getElementById('date-from').value = '';
                document.getElementById('date-to').value = '';
                performSearch(1);
            }
        });
    });

    // Mark as received function
    function markAsReceived(distributionId, mustahikName) {
        if (confirm(`Tandai distribusi untuk ${mustahikName} sebagai sudah diterima?`)) {
            // Create a form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/distributions/${distributionId}/mark-received`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PATCH">
        `;

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
    