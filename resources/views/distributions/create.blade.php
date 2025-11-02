@extends('layouts.app')

@section('page-title', 'Tambah Distribusi Zakat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Tambah Distribusi Zakat</h2>
        <p class="text-muted">Catat distribusi zakat kepada mustahik yang berhak</p>
    </div>
    <div>
        <a href="{{ route('distributions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-hand-thumbs-up"></i> Form Distribusi Zakat</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('distributions.store') }}" method="POST" id="distributionForm">
                    @csrf

                    <!-- Mustahik Selection Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-person-heart"></i> Informasi Mustahik
                        </h6>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="mustahik_id" class="form-label">Pilih Mustahik <span class="text-danger">*</span></label>
                                <select class="form-select @error('mustahik_id') is-invalid @enderror"
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
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="category_filter" class="form-label">Filter Kategori</label>
                                <select class="form-select" id="category_filter">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ ucfirst(str_replace('_', ' ', $category)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Mustahik Details Display -->
                        <div id="mustahik-details" class="d-none">
                            <div class="alert alert-info">
                                <h6 class="mb-2"><i class="bi bi-info-circle"></i> Detail Mustahik</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Kategori:</small>
                                        <div id="mustahik-category" class="fw-semibold"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Telepon:</small>
                                        <div id="mustahik-phone" class="fw-semibold"></div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small class="text-muted">Alamat:</small>
                                        <div id="mustahik-address" class="fw-semibold"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribution Details Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-gift"></i> Detail Distribusi
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="distribution_type" class="form-label">Jenis Distribusi <span class="text-danger">*</span></label>
                                <select class="form-select @error('distribution_type') is-invalid @enderror"
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
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        id="amount"
                                        name="amount"
                                        value="{{ old('amount') }}"
                                        min="0"
                                        step="1000"
                                        required>
                                </div>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="amount-warning" class="d-none">
                                    <small class="text-danger">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Jumlah melebihi saldo tersedia!
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Goods Description (conditional) -->
                        <div class="mb-3 d-none" id="goods-description-field">
                            <label for="goods_description" class="form-label">Deskripsi Barang/Layanan</label>
                            <textarea class="form-control @error('goods_description') is-invalid @enderror"
                                id="goods_description"
                                name="goods_description"
                                rows="3"
                                placeholder="Contoh: Beras 10kg, Minyak goreng 2L, dll.">{{ old('goods_description') }}</textarea>
                            @error('goods_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="distribution_date" class="form-label">Tanggal Distribusi <span class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('distribution_date') is-invalid @enderror"
                                    id="distribution_date"
                                    name="distribution_date"
                                    value="{{ old('distribution_date', date('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}"
                                    required>
                                @error('distribution_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Lokasi Distribusi</label>
                                <input type="text"
                                    class="form-control @error('location') is-invalid @enderror"
                                    id="location"
                                    name="location"
                                    value="{{ old('location') }}"
                                    placeholder="Contoh: Masjid Al-Ikhlas, Kantor Amil, dll.">
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Program Information Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-bookmark"></i> Program & Catatan
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="program_name" class="form-label">Nama Program</label>
                                <input type="text"
                                    class="form-control @error('program_name') is-invalid @enderror"
                                    id="program_name"
                                    name="program_name"
                                    value="{{ old('program_name') }}"
                                    placeholder="Contoh: Bantuan Ramadan, Program Pendidikan, dll.">
                                @error('program_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                id="notes"
                                name="notes"
                                rows="3"
                                placeholder="Catatan tambahan mengenai distribusi ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('distributions.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan Distribusi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Available Balance Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-{{ $availableBalance > 0 ? 'success' : 'danger' }} text-white">
                <h6 class="mb-0"><i class="bi bi-wallet2"></i> Saldo Tersedia</h6>
            </div>
            <div class="card-body text-center">
                <h3 class="text-{{ $availableBalance > 0 ? 'success' : 'danger' }}" id="available-balance">
                    Rp {{ number_format($availableBalance, 0, ',', '.') }}
                </h3>
                <small class="text-muted">
                    {{ $availableBalance > 0 ? 'Dapat didistribusikan' : 'Saldo tidak mencukupi' }}
                </small>
            </div>
        </div>

        <!-- Guidelines Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Panduan Distribusi</h6>
            </div>
            <div class="card-body">
                <h6>Jenis Distribusi:</h6>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-cash text-success"></i> <strong>Tunai:</strong> Bantuan dalam bentuk uang</li>
                    <li><i class="bi bi-box text-info"></i> <strong>Barang:</strong> Sembako, pakaian, dll.</li>
                    <li><i class="bi bi-card-text text-warning"></i> <strong>Voucher:</strong> Kupon belanja/layanan</li>
                    <li><i class="bi bi-gear text-primary"></i> <strong>Layanan:</strong> Beasiswa, pengobatan, dll.</li>
                </ul>

                <h6 class="mt-3">Kategori Mustahik (8 Asnaf):</h6>
                <ul class="small text-muted">
                    <li><strong>Fakir:</strong> Tidak memiliki harta dan pekerjaan</li>
                    <li><strong>Miskin:</strong> Memiliki harta/pekerjaan tapi tidak mencukupi</li>
                    <li><strong>Amil:</strong> Petugas pengumpul zakat</li>
                    <li><strong>Muallaf:</strong> Mualaf atau yang hatinya perlu diperkuat</li>
                    <li><strong>Riqab:</strong> Memerdekakan budak/tawanan</li>
                    <li><strong>Gharim:</strong> Orang berutang untuk kepentingan baik</li>
                    <li><strong>Fi Sabilillah:</strong> Untuk kepentingan umum</li>
                    <li><strong>Ibnu Sabil:</strong> Musafir kehabisan bekal</li>
                </ul>

                <div class="alert alert-warning small mt-3">
                    <i class="bi bi-exclamation-triangle"></i>
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
            mustahikDetails.classList.remove('d-none');
        } else {
            mustahikDetails.classList.add('d-none');
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
        mustahikDetails.classList.add('d-none');
    });

    // ===== DISTRIBUTION TYPE TOGGLE GOODS FIELD =====
    function toggleGoodsField() {
        const typeValue = distributionType.value;
        
        if (typeValue === 'goods' || typeValue === 'service') {
            goodsDescriptionField.classList.remove('d-none');
            goodsDescField.setAttribute('required', 'required');
        } else {
            goodsDescriptionField.classList.add('d-none');
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

    // ===== AMOUNT VALIDATION =====
    function validateAmount() {
        const amount = parseFloat(amountInput.value) || 0;
        if (distributionType.value === 'cash' && amount > availableBalance) {
            amountWarning.classList.remove('d-none');
            amountInput.classList.add('is-invalid');
        } else {
            amountWarning.classList.add('d-none');
            amountInput.classList.remove('is-invalid');
        }
    }

    amountInput.addEventListener('input', validateAmount);
    distributionType.addEventListener('change', validateAmount);

    // Format amount input
    amountInput.addEventListener('blur', function() {
        const value = parseInt(this.value.replace(/[^0-9]/g, ''));
        if (!isNaN(value)) this.value = value;
    });

    // ===== FORM SUBMIT VALIDATION =====
    document.getElementById('distributionForm').addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput.value) || 0;

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