@extends('layouts.app')

@section('page-title', 'Manajemen Pembayaran Zakat')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">
                @if (auth()->user()->role === 'muzakki')
                    Riwayat Pembayaran Zakat
                @else
                    Manajemen Pembayaran Zakat
                @endif
            </h2>
            <p class="text-gray-600">
                @if (auth()->user()->role === 'muzakki')
                    Kelola dan lihat riwayat pembayaran zakat Anda
                @else
                    Kelola data pembayaran zakat
                @endif
            </p>
        </div>
        <!-- <div>
                                                        @if (auth()->user()->role !== 'muzakki')
    <a href="{{ route('payments.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                                                            <i class="bi bi-plus-circle"></i> Tambah Pembayaran
                                                        </a>
@else
    <a href="{{ route('muzakki.payments.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                                                            <i class="bi bi-plus-circle"></i> Bayar Zakat
                                                        </a>
    @endif
                                                    </div> -->
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-5">
            <!-- Search Input -->
            <div class="mb-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5">
                        <i class="bi bi-search text-base text-gray-400"></i>
                    </span>
                    <input type="text" id="search-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-10 pr-4 py-2.5"
                        placeholder="Cari kode bayar, kwitansi, nama..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
                <div class="lg:col-span-1">
                    <label for="zakat-type-filter" class="block text-xs font-medium text-gray-700 mb-1.5">Jenis
                        Zakat</label>
                    <select id="zakat-type-filter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2">
                        <option value="">Semua</option>
                        @foreach ($zakatTypes as $type)
                            <option value="{{ $type->id }}" {{ request('zakat_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-1">
                    <label for="payment-method-filter" class="block text-xs font-medium text-gray-700 mb-1.5">Metode</label>
                    <select id="payment-method-filter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2">
                        <option value="">Semua</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                        </option>
                        <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Cek</option>
                        <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="midtrans" {{ request('payment_method') == 'midtrans' ? 'selected' : '' }}>Midtrans
                        </option>
                    </select>
                </div>
                <div class="lg:col-span-1">
                    <label for="status-filter" class="block text-xs font-medium text-gray-700 mb-1.5">Status</label>
                    <select id="status-filter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Rentang Tanggal</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" id="date-from"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2"
                            value="{{ request('date_from') }}">
                        <input type="date" id="date-to"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2"
                            value="{{ request('date_to') }}">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-200">
                <div id="search-loading" class="hidden">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                        <span>Memproses...</span>
                    </div>
                </div>
                <button type="button" id="reset-filters"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-2 focus:ring-gray-200 rounded-lg px-4 py-2 transition-colors">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Reset</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-credit-card text-5xl text-blue-600 mb-3 block"></i>
                <h4 class="text-2xl font-bold text-gray-900 mb-1" id="total-amount">Rp
                    {{ number_format($stats['total_amount'], 0, ',', '.') }}</h4>
                <small class="text-gray-600">Total Terkumpul</small>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-check-circle text-5xl text-green-600 mb-3 block"></i>
                <h4 class="text-2xl font-bold text-gray-900 mb-1" id="total-count">
                    {{ number_format($stats['total_count']) }}</h4>
                <small class="text-gray-600">Total Pembayaran</small>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-calendar-month text-5xl text-cyan-600 mb-3 block"></i>
                <h4 class="text-2xl font-bold text-gray-900 mb-1" id="thismonth-amount">Rp
                    {{ number_format($stats['this_month'], 0, ',', '.') }}</h4>
                <small class="text-gray-600">Bulan Ini</small>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-clock text-5xl text-yellow-600 mb-3 block"></i>
                <h4 class="text-2xl font-bold text-gray-900 mb-1" id="pending-count">{{ $stats['pending'] }}</h4>
                <small class="text-gray-600">Menunggu</small>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900">Daftar Pembayaran Zakat</h5>
        </div>
        <div class="p-0" id="payments-table-container">
            @include('payments.partials.table')
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
                isNotMuzakki: {!! auth()->user()->role !== 'muzakki' ? 'true' : 'false' !!},
                apiRoute: '{!! route('api.payments.search') !!}',
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            // Get CSRF token
            const csrfToken = config.csrfToken;

            // Debounced search function
            function performSearch(page = 1) {
                const searchData = {
                    search: document.getElementById('search-input').value,
                    zakat_type: document.getElementById('zakat-type-filter').value,
                    payment_method: document.getElementById('payment-method-filter').value,
                    status: document.getElementById('status-filter').value,
                    date_from: document.getElementById('date-from').value,
                    date_to: document.getElementById('date-to').value,
                    page: page
                };

                // Show loading indicator
                document.getElementById('search-loading').classList.remove('hidden');

                // Create query string
                const params = new URLSearchParams(searchData);

                const apiRoute = config.apiRoute;

                fetch(apiRoute + '?' + params.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            // Update table
                            updateTable(response.data.payments, response.data.pagination);
                            // Update statistics
                            updateStatistics(response.data.statistics);
                            // Update current page
                            currentPage = response.data.pagination.current_page;
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    })
                    .finally(() => {
                        // Hide loading indicator
                        document.getElementById('search-loading').classList.add('hidden');
                    });
            }

            // Update table with new data
            function updateTable(payments, pagination) {
                let tableHtml = '';

                if (payments.length > 0) {
                    tableHtml = `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Kode Pembayaran</th>
                                ${config.isNotMuzakki ? '<th scope="col" class="px-6 py-3">Muzakki</th>' : ''}
                                <th scope="col" class="px-6 py-3">Jenis Zakat</th>
                                <th scope="col" class="px-6 py-3">Jumlah Bayar</th>
                                <th scope="col" class="px-6 py-3">Metode</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                    payments.forEach(function(payment) {
                        const paymentDate = new Date(payment.payment_date).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        // Payment method display names
                        const paymentMethods = {
                            'cash': 'Tunai',
                            'transfer': 'Transfer',
                            'check': 'Cek',
                            'online': 'Online',
                            'midtrans': 'Midtrans'
                        };

                        // Status badge classes
                        const statusClasses = {
                            'pending': 'bg-yellow-100 text-yellow-800',
                            'completed': 'bg-green-100 text-green-800',
                            'cancelled': 'bg-red-100 text-red-800'
                        };

                        const statusTexts = {
                            'pending': 'Menunggu',
                            'completed': 'Selesai',
                            'cancelled': 'Dibatalkan'
                        };

                        const isNotMuzakki = config.isNotMuzakki;
                        const muzakkiCell = isNotMuzakki ? `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-semibold text-gray-900">${payment.muzakki.name}</div>
                        ${payment.muzakki.phone ? '<small class="text-gray-500">' + payment.muzakki.phone + '</small>' : ''}
                    </td>
                ` : '';

                        tableHtml += `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">${payment.payment_code}</div>
                            <small class="text-gray-500">${payment.receipt_number}</small>
                        </td>
                        ${muzakkiCell}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">${payment.zakat_type.name}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">Rp ${parseInt(payment.paid_amount).toLocaleString('id-ID')}</div>
                            ${payment.zakat_amount ? '<small class="text-gray-500">Zakat: Rp ' + parseInt(payment.zakat_amount).toLocaleString('id-ID') + '</small>' : ''}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${paymentMethods[payment.payment_method]}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClasses[payment.status]}">
                                ${statusTexts[payment.status]}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">${paymentDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="/payments/${payment.id}" class="text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1.5" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/payments/${payment.id}/receipt" class="text-green-600 hover:text-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-lg p-1.5" title="Kwitansi" target="_blank">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                ${config.isNotMuzakki ? `
                                                                                <a href="/payments/${payment.id}/edit" class="text-purple-600 hover:text-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-lg p-1.5" title="Edit">
                                                                                    <i class="bi bi-pencil"></i>
                                                                                </a>
                                                                                ${payment.status !== 'completed' ? `
                                <form action="/payments/${payment.id}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-lg p-1.5" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                ` : ''}
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
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} data
                            </div>
                            <nav>
                                <ul class="inline-flex items-center -space-x-px">
                `;

                        if (pagination.current_page > 1) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
                                (pagination.current_page - 1) + '">‹</a></li>';
                        }

                        for (let i = 1; i <= pagination.last_page; i++) {
                            const activeClass = pagination.current_page == i ?
                                'bg-blue-50 border-blue-300 text-blue-600 hover:bg-blue-100 hover:text-blue-700' :
                                'bg-white border-gray-300 text-gray-500 hover:bg-gray-100 hover:text-gray-700';
                            tableHtml +=
                                '<li><a href="#" class="pagination-link block px-3 py-2 leading-tight border ' +
                                activeClass + '" data-page="' + i + '">' + i + '</a></li>';
                        }

                        if (pagination.current_page < pagination.last_page) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link block px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
                                (pagination.current_page + 1) + '">›</a></li>';
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
                <div class="text-center py-12 px-6">
                    <i class="bi bi-inbox text-6xl text-gray-400 mb-4 block"></i>
                    <h5 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pembayaran</h5>
                    <p class="text-gray-600 mb-4">Tidak ada pembayaran yang sesuai dengan kriteria pencarian</p>
                    <button type="button" id="clear-search" class="text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-300 focus:ring-4 focus:ring-blue-200 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        <i class="bi bi-arrow-clockwise"></i> Reset Pencarian
                    </button>
                </div>
            `;
                }

                document.getElementById('payments-table-container').innerHTML = tableHtml;
            }

            // Update statistics
            function updateStatistics(stats) {
                document.getElementById('total-amount').textContent = 'Rp ' + parseInt(stats.total_amount)
                    .toLocaleString('id-ID');
                document.getElementById('total-count').textContent = stats.total_count.toLocaleString('id-ID');
                document.getElementById('thismonth-amount').textContent = 'Rp ' + parseInt(stats.this_month)
                    .toLocaleString('id-ID');
                document.getElementById('pending-count').textContent = stats.pending.toLocaleString('id-ID');
            }

            // Search input with debouncing
            document.getElementById('search-input').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch(1);
                }, 500); // 500ms delay
            });

            // Filter changes
            document.getElementById('zakat-type-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('payment-method-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('status-filter').addEventListener('change', function() {
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
                document.getElementById('zakat-type-filter').value = '';
                document.getElementById('payment-method-filter').value = '';
                document.getElementById('status-filter').value = '';
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
                    document.getElementById('zakat-type-filter').value = '';
                    document.getElementById('payment-method-filter').value = '';
                    document.getElementById('status-filter').value = '';
                    document.getElementById('date-from').value = '';
                    document.getElementById('date-to').value = '';
                    performSearch(1);
                }
            });
        });
    </script>
@endpush
