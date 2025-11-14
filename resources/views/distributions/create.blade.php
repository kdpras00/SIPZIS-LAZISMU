@extends('layouts.app')

@section('page-title', 'Tambah Distribusi Zakat')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('distributions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
    <div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Tambah Distribusi Zakat</h2>
        <p class="text-gray-600">Catat distribusi zakat kepada mustahik yang berhak</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-white">
                <h5 class="text-lg font-semibold mb-0"><i class="bi bi-hand-thumbs-up mr-2"></i> Form Distribusi Zakat</h5>
            </div>
            <div class="p-6">
                <form action="{{ route('distributions.store') }}" method="POST" id="distributionForm">
                    @csrf

                    <!-- Mustahik Selection Section -->
                    <div class="mb-6">
                        <h6 class="text-blue-600 font-semibold mb-3">
                            <i class="bi bi-person-heart mr-2"></i> Informasi Mustahik
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-8 mb-4">
                                <label for="mustahik_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Mustahik <span class="text-red-500">*</span></label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mustahik_id') border-red-500 @enderror"
                                    id="mustahik_id"
                                    name="mustahik_id"
                                    required>
                                    <option value="">Pilih Mustahik</option>
                                    @foreach($allMustahik as $m)
                                    <option value="{{ $m->id }}"
                                        data-category="{{ $m->category }}"
                                        data-address="{{ $m->address }}"
                                        data-phone="{{ $m->phone }}"
                                        {{ old('mustahik_id', $mustahik?->id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }} - {{ ucfirst(str_replace('_', ' ', $m->category)) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('mustahik_id')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="md:col-span-4 mb-4">
                                <label for="category_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Kategori</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="category_filter">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ ucfirst(str_replace('_', ' ', $category)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Mustahik Details Display -->
                        <div id="mustahik-details" class="hidden">
                            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <h6 class="mb-2 font-semibold text-cyan-800"><i class="bi bi-info-circle mr-2"></i> Detail Mustahik</h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <small class="text-gray-600">Kategori:</small>
                                        <div id="mustahik-category" class="font-semibold text-gray-800"></div>
                                    </div>
                                    <div>
                                        <small class="text-gray-600">Telepon:</small>
                                        <div id="mustahik-phone" class="font-semibold text-gray-800"></div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <small class="text-gray-600">Alamat:</small>
                                        <div id="mustahik-address" class="font-semibold text-gray-800"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribution Details Section -->
                    <div class="mb-6">
                        <h6 class="text-blue-600 font-semibold mb-3">
                            <i class="bi bi-gift mr-2"></i> Detail Distribusi
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="distribution_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Distribusi <span class="text-red-500">*</span></label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('distribution_type') border-red-500 @enderror"
                                    id="distribution_type"
                                    name="distribution_type"
                                    required>
                                    <option value="">Pilih Jenis Distribusi</option>
                                    <option value="cash" {{ old('distribution_type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="goods" {{ old('distribution_type') == 'goods' ? 'selected' : '' }}>Barang</option>
                                    <option value="voucher" {{ old('distribution_type') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                                    <option value="service" {{ old('distribution_type') == 'service' ? 'selected' : '' }}>Layanan</option>
                                </select>
                                @error('distribution_type')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50 text-gray-500">Rp</span>
                                    <input type="text"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                                        id="amount"
                                        name="amount_display"
                                        value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                                        placeholder="0"
                                        data-amount-input
                                        required>
                                    <input type="hidden" id="amount_raw" name="amount" value="{{ old('amount') }}">
                                </div>
                                @error('amount')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                                <div id="amount-warning" class="hidden mt-1">
                                    <small class="text-red-600">
                                        <i class="bi bi-exclamation-triangle mr-1"></i>
                                        Jumlah melebihi saldo tersedia!
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Goods Description (conditional) -->
                        <div class="mb-4 hidden" id="goods-description-field">
                            <label for="goods_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Barang/Layanan</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('goods_description') border-red-500 @enderror"
                                id="goods_description"
                                name="goods_description"
                                rows="3"
                                placeholder="Contoh: Beras 10kg, Minyak goreng 2L, dll.">{{ old('goods_description') }}</textarea>
                            @error('goods_description')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="distribution_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Distribusi <span class="text-red-500">*</span></label>
                                <input type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('distribution_date') border-red-500 @enderror"
                                    id="distribution_date"
                                    name="distribution_date"
                                    value="{{ old('distribution_date', date('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}"
                                    required>
                                @error('distribution_date')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Distribusi</label>
                                <input type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                                    id="location"
                                    name="location"
                                    value="{{ old('location') }}"
                                    placeholder="Contoh: Masjid Al-Ikhlas, Kantor Amil, dll.">
                                @error('location')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Program Information Section -->
                    <div class="mb-6">
                        <h6 class="text-blue-600 font-semibold mb-3">
                            <i class="bi bi-bookmark mr-2"></i> Program & Catatan
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="program_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Program</label>
                                <input type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('program_name') border-red-500 @enderror"
                                    id="program_name"
                                    name="program_name"
                                    value="{{ old('program_name') }}"
                                    placeholder="Contoh: Bantuan Ramadan, Program Pendidikan, dll.">
                                @error('program_name')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                id="notes"
                                name="notes"
                                rows="3"
                                placeholder="Catatan tambahan mengenai distribusi ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t">
                        <a href="{{ route('distributions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            <i class="bi bi-arrow-left mr-2"></i> Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="bi bi-check-circle mr-2"></i> Simpan Distribusi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <!-- Available Balance Card -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 {{ $availableBalance > 0 ? 'bg-green-600' : 'bg-red-600' }} text-white rounded-t-lg">
                <h6 class="font-semibold mb-0"><i class="bi bi-wallet2 mr-2"></i> Saldo Tersedia</h6>
            </div>
            <div class="p-6 text-center">
                <h3 class="text-2xl font-bold {{ $availableBalance > 0 ? 'text-green-600' : 'text-red-600' }}" id="available-balance">
                    Rp {{ number_format($availableBalance, 0, ',', '.') }}
                </h3>
                <small class="text-gray-600">
                    {{ $availableBalance > 0 ? 'Dapat didistribusikan' : 'Saldo tidak mencukupi' }}
                </small>
            </div>
        </div>

        <!-- Guidelines Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 bg-blue-600 text-white rounded-t-lg">
                <h6 class="font-semibold mb-0"><i class="bi bi-info-circle mr-2"></i> Panduan Distribusi</h6>
            </div>
            <div class="p-6">
                <h6 class="font-semibold mb-2">Jenis Distribusi:</h6>
                <ul class="list-none space-y-2 text-sm">
                    <li><i class="bi bi-cash text-green-600 mr-2"></i> <strong>Tunai:</strong> Bantuan dalam bentuk uang</li>
                    <li><i class="bi bi-box text-cyan-600 mr-2"></i> <strong>Barang:</strong> Sembako, pakaian, dll.</li>
                    <li><i class="bi bi-card-text text-yellow-600 mr-2"></i> <strong>Voucher:</strong> Kupon belanja/layanan</li>
                    <li><i class="bi bi-gear text-blue-600 mr-2"></i> <strong>Layanan:</strong> Beasiswa, pengobatan, dll.</li>
                </ul>

                <h6 class="font-semibold mt-4 mb-2">Kategori Mustahik (8 Asnaf):</h6>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><strong>Fakir:</strong> Tidak memiliki harta dan pekerjaan</li>
                    <li><strong>Miskin:</strong> Memiliki harta/pekerjaan tapi tidak mencukupi</li>
                    <li><strong>Amil:</strong> Petugas pengumpul zakat</li>
                    <li><strong>Muallaf:</strong> Mualaf atau yang hatinya perlu diperkuat</li>
                    <li><strong>Riqab:</strong> Memerdekakan budak/tawanan</li>
                    <li><strong>Gharim:</strong> Orang berutang untuk kepentingan baik</li>
                    <li><strong>Fi Sabilillah:</strong> Untuk kepentingan umum</li>
                    <li><strong>Ibnu Sabil:</strong> Musafir kehabisan bekal</li>
                </ul>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4 text-sm">
                    <i class="bi bi-exclamation-triangle text-yellow-600 mr-2"></i>
                    Pastikan mustahik sudah terverifikasi sebelum distribusi.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // ===== ELEMENTS =====
    const mustahikSelect = document.getElementById('mustahik_id');
    const categoryFilter = document.getElementById('category_filter');
    const distributionType = document.getElementById('distribution_type');
    const amountInput = document.getElementById('amount');
    const goodsDescriptionField = document.getElementById('goods-description-field');
    const goodsDescField = document.getElementById('goods_description');
    const mustahikDetails = document.getElementById('mustahik-details');
    const amountWarning = document.getElementById('amount-warning');
    const availableBalance = {{ $availableBalance ?? 0 }};

    // ===== STORE ORIGINAL MUSTAHIK OPTIONS =====
    const originalOptions = Array.from(mustahikSelect.options).slice(1);

    // ===== MUSTAHIK SELECTION =====
    function showMustahikDetails() {
        const selectedOption = mustahikSelect.options[mustahikSelect.selectedIndex];
        if (mustahikSelect.value) {
            const category = selectedOption.dataset.category || '-';
            const address = selectedOption.dataset.address || '-';
            const phone = selectedOption.dataset.phone || '-';

            document.getElementById('mustahik-category').textContent = category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            document.getElementById('mustahik-address').textContent = address;
            document.getElementById('mustahik-phone').textContent = phone;
            mustahikDetails.classList.remove('hidden');
        } else {
            mustahikDetails.classList.add('hidden');
        }
    }

    mustahikSelect.addEventListener('change', showMustahikDetails);

    // ===== CATEGORY FILTER =====
    categoryFilter.addEventListener('change', function() {
        const selectedCategory = this.value;

        mustahikSelect.innerHTML = '<option value="">Pilih Mustahik</option>';

        originalOptions.forEach(option => {
            if (!selectedCategory || option.dataset.category === selectedCategory) {
                mustahikSelect.appendChild(option.cloneNode(true));
            }
        });

        mustahikSelect.value = '';
        mustahikDetails.classList.add('hidden');
    });

    // ===== DISTRIBUTION TYPE TOGGLE GOODS FIELD =====
    function toggleGoodsField() {
        const typeValue = distributionType.value;
        
        if (typeValue === 'goods' || typeValue === 'service') {
            goodsDescriptionField.classList.remove('hidden');
            goodsDescField.setAttribute('required', 'required');
        } else {
            goodsDescriptionField.classList.add('hidden');
            goodsDescField.removeAttribute('required');
            goodsDescField.value = '';
        }
    }

    // PENTING: Event listener harus dipasang SEBELUM memanggil fungsi
    distributionType.addEventListener('change', function() {
        toggleGoodsField();
    });

    // Jalankan saat load jika ada old value
    if (distributionType.value) {
        toggleGoodsField();
    }

    // ===== FORMAT ANGKA DENGAN KOMA =====
    function formatNumberWithCommas(input) {
        // Hapus semua karakter selain angka
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format dengan titik sebagai pemisah ribuan (format Indonesia)
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        
        input.value = value;
        
        // Update hidden input dengan nilai tanpa format
        const hiddenInput = document.getElementById('amount_raw');
        if (hiddenInput) {
            hiddenInput.value = input.value.replace(/[^\d]/g, '');
        }
        
        // Trigger validation
        validateAmount();
    }

    // Initialize format untuk input amount
    if (amountInput) {
        // Format saat load jika ada value
        if (amountInput.value) {
            formatNumberWithCommas(amountInput);
        }
        
        // Format saat user mengetik
        amountInput.addEventListener('input', function() {
            formatNumberWithCommas(this);
        });
        
        // Format saat blur (ketika user selesai mengetik)
        amountInput.addEventListener('blur', function() {
            formatNumberWithCommas(this);
        });
    }

    // ===== AMOUNT VALIDATION =====
    function validateAmount() {
        // Get raw value (remove formatting)
        const rawValue = amountInput.value.replace(/[^\d]/g, '');
        const amount = parseFloat(rawValue) || 0;
        
        if (distributionType.value === 'cash' && amount > availableBalance) {
            amountWarning.classList.remove('hidden');
            amountInput.classList.add('border-red-500');
        } else {
            amountWarning.classList.add('hidden');
            amountInput.classList.remove('border-red-500');
        }
    }

    amountInput.addEventListener('input', validateAmount);
    distributionType.addEventListener('change', validateAmount);

    // ===== FORM SUBMIT VALIDATION =====
    document.getElementById('distributionForm').addEventListener('submit', function(e) {
        // Get raw value (remove formatting)
        const rawValue = amountInput.value.replace(/[^\d]/g, '');
        const amount = parseFloat(rawValue) || 0;
        
        // Update hidden input sebelum submit
        const hiddenInput = document.getElementById('amount_raw');
        if (hiddenInput) {
            hiddenInput.value = rawValue || '0';
        }

        if (distributionType.value === 'cash' && amount > availableBalance) {
            e.preventDefault();
            alert('Jumlah distribusi tunai melebihi saldo tersedia!');
            amountInput.focus();
            return;
        }

        if (distributionType.value === 'goods' && !goodsDescField.value.trim()) {
            e.preventDefault();
            alert('Deskripsi barang wajib diisi!');
            goodsDescField.focus();
            return;
        }
    });

    // ===== AUTO-POPULATE MUSTAHIK =====
    @if($mustahik)
    mustahikSelect.value = "{{ $mustahik->id }}";
    mustahikSelect.dispatchEvent(new Event('change'));
    @endif

    // ===== SET DEFAULT DISTRIBUTION DATE =====
    const distributionDate = document.getElementById('distribution_date');
    if (distributionDate && !distributionDate.value) {
        distributionDate.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endpush
