@extends('layouts.app')

@section('page-title', 'Tambah Campaign')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Tambah Campaign Baru</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-control-label">Judul Campaign</label>
                                    <input class="form-control @error('title') is-invalid @enderror"
                                        type="text"
                                        id="title"
                                        name="title"
                                        value="{{ old('title') }}"
                                        required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-control-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description"
                                        name="description"
                                        rows="5"
                                        required>{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="program_category" class="form-control-label">Kategori Program</label>
                                            <select class="form-control @error('program_category') is-invalid @enderror"
                                                id="program_category"
                                                name="program_category"
                                                required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="pendidikan" {{ old('program_category') == 'pendidikan' ? 'selected' : '' }}>
                                                    Pendidikan
                                                </option>
                                                <option value="kesehatan" {{ old('program_category') == 'kesehatan' ? 'selected' : '' }}>
                                                    Kesehatan
                                                </option>
                                                <option value="ekonomi" {{ old('program_category') == 'ekonomi' ? 'selected' : '' }}>
                                                    Ekonomi
                                                </option>
                                                <option value="sosial-dakwah" {{ old('program_category') == 'sosial-dakwah' ? 'selected' : '' }}>
                                                    Sosial & Dakwah
                                                </option>
                                                <option value="kemanusiaan" {{ old('program_category') == 'kemanusiaan' ? 'selected' : '' }}>
                                                    Kemanusiaan
                                                </option>
                                                <option value="lingkungan" {{ old('program_category') == 'lingkungan' ? 'selected' : '' }}>
                                                    Lingkungan
                                                </option>
                                            </select>
                                            @error('program_category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-control-label">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status"
                                                name="status"
                                                required>
                                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                                    Draft
                                                </option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                                    Published
                                                </option>
                                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                                    Cancelled
                                                </option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="target_amount" class="form-control-label">Target Dana (Rp)</label>
                                            <input class="form-control @error('target_amount') is-invalid @enderror"
                                                type="text"
                                                id="target_amount"
                                                name="target_amount_display"
                                                value="{{ old('target_amount') ? number_format(old('target_amount'), 0, ',', '.') : '' }}"
                                                placeholder="0"
                                                data-amount-input
                                                required>
                                            <input type="hidden" id="target_amount_raw" name="target_amount" value="{{ old('target_amount') }}">
                                            @error('target_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="end_date" class="form-control-label">Tanggal Berakhir</label>
                                            <input class="form-control @error('end_date') is-invalid @enderror"
                                                type="date"
                                                id="end_date"
                                                name="end_date"
                                                value="{{ old('end_date') }}">
                                            <small class="form-text text-muted">Kosongkan jika tidak ada batas waktu.</small>
                                            @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="photo" class="form-control-label">Foto Campaign</label>
                                    <div class="card bg-gradient-dark mb-3">
                                        <div class="card-body text-center p-3">
                                            <img id="preview" src="{{ asset('img/masjid.webp') }}"
                                                class="img-fluid rounded mb-3"
                                                alt="Preview Foto"
                                                style="height: 250px; object-fit: cover; width: 100%;">
                                            <div>
                                                <input type="file"
                                                    class="form-control @error('photo') is-invalid @enderror"
                                                    id="photo"
                                                    name="photo"
                                                    accept="image/*">
                                                <small class="text-white">Format: JPG, PNG, GIF (Max: 2MB)</small>
                                                @error('photo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Progress Preview Card -->
                                    <!-- <div class="card bg-gradient-success mb-3">
                                        <div class="card-body text-center p-3">
                                            <h6 class="text-white">Preview Progress</h6>
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="text-white text-sm font-weight-bold mr-2" id="progressPercentage">
                                                    0%
                                                </span>
                                                <div style="min-width: 100px;">
                                                    <div class="progress">
                                                        <div class="progress-bar bg-white"
                                                            role="progressbar"
                                                            id="progressBar"
                                                            aria-valuenow="0"
                                                            aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            style="width: 0%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="text-white">
                                                Terkumpul: <span id="collectedPreview">Rp 0</span> dari
                                                <span id="targetPreview">Rp 0</span>
                                            </small>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Campaign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Format angka dengan koma untuk input amount
    function formatNumberWithCommas(input) {
        // Hapus semua karakter selain angka
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format dengan titik sebagai pemisah ribuan (format Indonesia)
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        
        input.value = value;
        
        // Update hidden input dengan nilai tanpa format
        const hiddenInput = document.getElementById('target_amount_raw');
        if (hiddenInput) {
            hiddenInput.value = input.value.replace(/[^\d]/g, '');
        }
        
        // Trigger progress calculation
        calculateProgress();
    }

    // Progress calculation functionality
    function calculateProgress() {
        // Get raw values (remove formatting)
        const targetAmountInput = document.getElementById('target_amount');
        const collectedAmountInput = document.getElementById('collected_amount');
        
        const targetAmount = targetAmountInput ? parseFloat(targetAmountInput.value.replace(/[^\d]/g, '')) || 0 : 0;
        const collectedAmount = collectedAmountInput ? parseFloat(collectedAmountInput.value.replace(/[^\d]/g, '')) || 0 : 0;

        // Update previews
        if (document.getElementById('targetPreview')) {
            document.getElementById('targetPreview').textContent = 'Rp ' + targetAmount.toLocaleString('id-ID');
        }
        if (document.getElementById('collectedPreview')) {
            document.getElementById('collectedPreview').textContent = 'Rp ' + collectedAmount.toLocaleString('id-ID');
        }

        // Calculate progress percentage
        let progressPercentage = 0;
        if (targetAmount > 0) {
            progressPercentage = Math.min(100, (collectedAmount / targetAmount) * 100);
        }

        // Update progress display
        if (document.getElementById('progressPercentage')) {
            document.getElementById('progressPercentage').textContent = progressPercentage.toFixed(1) + '%';
        }
        if (document.getElementById('progressBar')) {
            document.getElementById('progressBar').style.width = progressPercentage + '%';
            document.getElementById('progressBar').setAttribute('aria-valuenow', progressPercentage);
        }
    }

    // Initialize format untuk input amount
    document.addEventListener('DOMContentLoaded', function() {
        const targetAmountInput = document.getElementById('target_amount');
        if (targetAmountInput) {
            // Format saat load jika ada value
            if (targetAmountInput.value) {
                formatNumberWithCommas(targetAmountInput);
            }
            
            // Format saat user mengetik
            targetAmountInput.addEventListener('input', function() {
                formatNumberWithCommas(this);
            });
            
            // Format saat blur (ketika user selesai mengetik)
            targetAmountInput.addEventListener('blur', function() {
                formatNumberWithCommas(this);
            });
        }

        // Format collected_amount saat load
        const collectedAmountInput = document.getElementById('collected_amount');
        if (collectedAmountInput && collectedAmountInput.value) {
            const value = collectedAmountInput.value.replace(/[^\d]/g, '');
            if (value) {
                collectedAmountInput.value = parseInt(value).toLocaleString('id-ID');
            }
        }

        // Initial calculation
        calculateProgress();

        // Update hidden input sebelum submit form
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const amountInput = document.getElementById('target_amount');
                const hiddenInput = document.getElementById('target_amount_raw');
                if (amountInput && hiddenInput) {
                    const rawValue = amountInput.value.replace(/[^\d]/g, '');
                    hiddenInput.value = rawValue || '0';
                }
            });
        }
    });
</script>
@endpush