@extends('layouts.app')

@section('page-title', 'Tambah Program')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Tambah Program Baru</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                {{-- NAMA PROGRAM --}}
                                <div class="form-group mb-3">
                                    <label for="name" class="form-control-label">Nama Program</label>
                                    <input class="form-control @error('name') is-invalid @enderror"
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- DESKRIPSI --}}
                                <div class="form-group mb-3">
                                    <label for="description" class="form-control-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description"
                                        name="description"
                                        rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KATEGORI UTAMA --}}
                                <div class="form-group mb-3">
                                    <label for="category" class="form-control-label">Kategori</label>
                                    <select class="form-control @error('category') is-invalid @enderror"
                                        id="category"
                                        name="category"
                                        required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="zakat" {{ old('category') == 'zakat' ? 'selected' : '' }}>Zakat</option>
                                        <option value="infaq" {{ old('category') == 'infaq' ? 'selected' : '' }}>Infaq</option>
                                        <option value="shadaqah" {{ old('category') == 'shadaqah' ? 'selected' : '' }}>Shadaqah</option>
                                        <option value="pendidikan" {{ old('category') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                        <option value="kesehatan" {{ old('category') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                        <option value="ekonomi" {{ old('category') == 'ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                                        <option value="sosial-dakwah" {{ old('category') == 'sosial-dakwah' ? 'selected' : '' }}>Sosial & Dakwah</option>
                                        <option value="kemanusiaan" {{ old('category') == 'kemanusiaan' ? 'selected' : '' }}>Kemanusiaan</option>
                                        <option value="lingkungan" {{ old('category') == 'lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                                    </select>
                                    @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TARGET DAN STATUS --}}
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
                                                data-amount-input>
                                            <input type="hidden" id="target_amount_raw" name="target_amount" value="{{ old('target_amount') }}">
                                            @error('target_amount')
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
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- FOTO PROGRAM --}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="photo" class="form-control-label">Foto Program</label>
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
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Program
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
    document.addEventListener('DOMContentLoaded', function() {
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
            
            // Format dengan titik sebagai pemisah ribuan
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            
            input.value = value;
            
            // Update hidden input dengan nilai tanpa format
            const hiddenInput = document.getElementById('target_amount_raw');
            if (hiddenInput) {
                hiddenInput.value = input.value.replace(/[^\d]/g, '');
            }
        }

        // Initialize format untuk input amount
        const amountInput = document.getElementById('target_amount');
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