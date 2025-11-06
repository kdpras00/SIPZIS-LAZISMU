@extends('layouts.app')

@section('page-title', 'Manajemen Mustahik')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold mb-1 text-gray-900">Manajemen Mustahik</h2>
            <p class="text-gray-500">Kelola data mustahik (penerima zakat) </p>
        </div>
        <div>
            <a href="{{ route('mustahik.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="bi bi-plus-circle mr-2"></i> Tambah Mustahik
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" id="search-input"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Cari nama, NIK, telepon..." value="{{ request('search') }}">
                </div>
                <div class="w-[180px]">
                    <select id="category-filter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                        <option value="">Semua Kategori</option>
                        @foreach (\App\Models\Mustahik::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-[150px]">
                    <input type="text" id="city-filter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Kota" value="{{ request('city') }}">
                </div>
                <div class="w-[180px]">
                    <select id="status-filter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi
                        </option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="reset-filters"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200 text-sm">
                        <i class="bi bi-arrow-clockwise mr-2"></i> Reset
                    </button>
                    <div id="search-loading" class="hidden">
                        <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6"
        style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem;">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-person-hearts text-5xl text-blue-600 mb-3"></i>
                <h4 class="text-2xl font-bold mb-0 text-gray-900" id="total-count">{{ $mustahik->total() }}</h4>
                <small class="text-gray-500 text-sm">Total Mustahik</small>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-check-circle text-5xl text-green-600 mb-3"></i>
                <h4 class="text-2xl font-bold mb-0 text-gray-900" id="verified-count">
                    {{ $mustahik->where('verification_status', 'verified')->count() }}</h4>
                <small class="text-gray-500 text-sm">Terverifikasi</small>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-clock text-5xl text-yellow-600 mb-3"></i>
                <h4 class="text-2xl font-bold mb-0 text-gray-900" id="pending-count">
                    {{ $mustahik->where('verification_status', 'pending')->count() }}</h4>
                <small class="text-gray-500 text-sm">Menunggu Verifikasi</small>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 text-center">
                <i class="bi bi-person-plus text-5xl text-blue-400 mb-3"></i>
                <h4 class="text-2xl font-bold mb-0 text-gray-900" id="thismonth-count">
                    {{ $mustahik->where('created_at', '>=', now()->startOfMonth())->count() }}</h4>
                <small class="text-gray-500 text-sm">Baru Bulan Ini</small>
            </div>
        </div>
    </div>

    <!-- Mustahik Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h5 class="text-lg font-semibold text-gray-900 mb-0">Daftar Mustahik</h5>
        </div>
        <div class="p-0" id="mustahik-table-container">
            @include('mustahik.partials.table')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let searchTimeout;
            let currentPage = 1;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Debounced search function
            function performSearch(page = 1) {
                const searchData = {
                    search: document.getElementById('search-input').value,
                    category: document.getElementById('category-filter').value,
                    city: document.getElementById('city-filter').value,
                    status: document.getElementById('status-filter').value,
                    page: page
                };

                // Show loading indicator
                document.getElementById('search-loading').classList.remove('hidden');

                // Create query string
                const params = new URLSearchParams(searchData);

                fetch('{{ route('api.mustahik.search') }}?' + params.toString(), {
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
                            updateTable(response.data.mustahik, response.data.pagination);
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
            function updateTable(mustahik, pagination) {
                let tableHtml = '';

                if (mustahik.length > 0) {
                    tableHtml = `
                <div>
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3">Telepon</th>
                                <th scope="col" class="px-6 py-3">Kota</th>
                                <th scope="col" class="px-6 py-3">Status Verifikasi</th>
                                <th scope="col" class="px-6 py-3">Terdaftar</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                    mustahik.forEach(function(item) {
                        const createdAt = new Date(item.created_at).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        // Get category display name
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

                        // Status badge classes
                        const statusClasses = {
                            'pending': 'bg-yellow-100 text-yellow-800',
                            'verified': 'bg-green-100 text-green-800',
                            'rejected': 'bg-red-100 text-red-800'
                        };

                        const statusTexts = {
                            'pending': 'Menunggu Verifikasi',
                            'verified': 'Terverifikasi',
                            'rejected': 'Ditolak'
                        };

                        tableHtml += `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full p-2 mr-3">
                                    <i class="bi bi-person-heart text-green-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">${item.name}</div>
                                    ${item.nik ? '<small class="text-gray-500">NIK: ' + item.nik + '</small>' : ''}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${categoryMap[item.category] || item.category}</span>
                        </td>
                        <td class="px-6 py-4">${item.phone || '-'}</td>
                        <td class="px-6 py-4">${item.city || '-'}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full ${statusClasses[item.verification_status]}">
                                ${statusTexts[item.verification_status]}
                            </span>
                        </td>
                        <td class="px-6 py-4">${createdAt}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <a href="/mustahik/${item.id}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/mustahik/${item.id}/edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                ${item.verification_status === 'pending' ? `
                                                                                                            <button type="button" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Verifikasi" onclick="showVerifyModal(${item.id}, '${item.name}')">
                                                                                                                <i class="bi bi-check-circle"></i>
                                                                                                            </button>
                                                                                                            ` : ''}
                                <form action="/mustahik/${item.id}/toggle-status" method="POST" class="inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Toggle Status">
                                        <i class="bi bi-toggle-${item.is_active ? 'on' : 'off'}"></i>
                                    </button>
                                </form>
                                <form action="/mustahik/${item.id}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-gray-500">
                                Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} data
                            </div>
                            <nav>
                                <ul class="inline-flex items-center -space-x-px">
                `;

                        if (pagination.current_page > 1) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
                                (pagination.current_page - 1) + '">‹</a></li>';
                        }

                        for (let i = 1; i <= pagination.last_page; i++) {
                            const isActive = pagination.current_page == i;
                            tableHtml += '<li><a href="#" class="pagination-link px-3 py-2 leading-tight ' + (
                                isActive ?
                                'text-blue-600 bg-blue-50 border border-blue-300 hover:bg-blue-100 hover:text-blue-700' :
                                'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'
                            ) + '" data-page="' + i + '">' + i + '</a></li>';
                        }

                        if (pagination.current_page < pagination.last_page) {
                            tableHtml +=
                                '<li><a href="#" class="pagination-link px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700" data-page="' +
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
                <div class="text-center py-12">
                    <i class="bi bi-inbox text-6xl text-gray-400 mb-4 block"></i>
                    <h5 class="text-lg font-medium text-gray-500 mb-2">Tidak ada data mustahik</h5>
                    <p class="text-sm text-gray-400 mb-4">Tidak ada mustahik yang sesuai dengan kriteria pencarian</p>
                    <button type="button" id="clear-search" class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 bg-white hover:bg-blue-50 font-medium rounded-lg transition-colors">
                        <i class="bi bi-arrow-clockwise mr-2"></i> Reset Pencarian
                    </button>
                </div>
            `;
                }

                document.getElementById('mustahik-table-container').innerHTML = tableHtml;
            }

            // Update statistics
            function updateStatistics(stats) {
                document.getElementById('total-count').textContent = stats.total.toLocaleString();
                document.getElementById('verified-count').textContent = stats.verified.toLocaleString();
                document.getElementById('pending-count').textContent = stats.pending.toLocaleString();
                document.getElementById('thismonth-count').textContent = stats.this_month.toLocaleString();
            }

            // Search input with debouncing
            document.getElementById('search-input').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch(1);
                }, 500); // 500ms delay
            });

            // Filter changes
            document.getElementById('category-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('status-filter').addEventListener('change', function() {
                performSearch(1);
            });

            document.getElementById('city-filter').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch(1);
                }, 500);
            });

            // Reset filters
            document.getElementById('reset-filters').addEventListener('click', function() {
                document.getElementById('search-input').value = '';
                document.getElementById('category-filter').value = '';
                document.getElementById('city-filter').value = '';
                document.getElementById('status-filter').value = '';
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
                    document.getElementById('city-filter').value = '';
                    document.getElementById('status-filter').value = '';
                    performSearch(1);
                }
            });
        });

        // Verify modal function (if needed)
        function showVerifyModal(id, name) {
            if (confirm(`Verifikasi mustahik: ${name}?`)) {
                // Create a form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/mustahik/${id}/verify`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="status" value="verified">
        `;

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
