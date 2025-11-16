@extends('layouts.app')

@section('page-title', 'Tambah Distribusi Zakat')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="mb-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Distribusi Zakat</h1>
            <p class="text-gray-600 text-sm">Catat distribusi zakat kepada mustahik yang berhak menerima</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-700 border-b border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Form Distribusi Zakat</h2>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <form action="{{ route('distributions.store') }}" method="POST" id="distributionForm">
                    @csrf

                    <!-- Mustahik Selection Section -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Mustahik</h3>
                        </div>

                        <div class="md:col-span-4 mb-4">
                            <label for="category_filter" class="block mb-2 text-sm font-medium text-gray-900">
                                Filter Kategori
                            </label>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                id="category_filter">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category }}">
                                    {{ ucfirst(str_replace('_', ' ', $category)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                            <div class="md:col-span-8">
                                <label for="mustahik_id" class="block mb-2 text-sm font-medium text-gray-900">
                                    Pilih Mustahik <span class="text-red-500">*</span>
                                </label>
                                <select
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('mustahik_id') border-red-500 bg-red-50 @enderror"
                                    id="mustahik_id" name="mustahik_id" required>
                                    <option value="">Pilih Mustahik</option>
                                    @foreach ($allMustahik as $m)
                                    <option value="{{ $m->id }}" data-category="{{ $m->category }}"
                                        data-address="{{ $m->address }}" data-phone="{{ $m->phone }}"
                                        {{ old('mustahik_id', $mustahik?->id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }} - {{ ucfirst(str_replace('_', ' ', $m->category)) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('mustahik_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                        </div>

                        <!-- Mustahik Details Display -->
                        <div id="mustahik-details" class="hidden transition-all duration-300">
                            <div
                                class="bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-200 rounded-lg p-5 shadow-sm">
                                <div class="flex items-center mb-4">
                                    <svg class="w-5 h-5 text-cyan-600 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="font-semibold text-cyan-900">Detail Mustahik</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white/60 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">Kategori</p>
                                        <p id="mustahik-category" class="font-semibold text-gray-900"></p>
                                    </div>
                                    <div class="bg-white/60 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">Telepon</p>
                                        <p id="mustahik-phone" class="font-semibold text-gray-900"></p>
                                    </div>
                                    <div class="md:col-span-2 bg-white/60 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">Alamat</p>
                                        <p id="mustahik-address" class="font-semibold text-gray-900"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribution Details Section -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Detail Distribusi</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="distribution_type" class="block mb-2 text-sm font-medium text-gray-900">
                                    Jenis Distribusi <span class="text-red-500">*</span>
                                </label>
                                <select
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('distribution_type') border-red-500 bg-red-50 @enderror"
                                    id="distribution_type" name="distribution_type" required>
                                    <option value="">Pilih Jenis Distribusi</option>
                                    <option value="cash" {{ old('distribution_type') == 'cash' ? 'selected' : '' }}>
                                        Tunai</option>
                                    <option value="goods"
                                        {{ old('distribution_type') == 'goods' ? 'selected' : '' }}>Barang</option>
                                    <option value="voucher"
                                        {{ old('distribution_type') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                                    <option value="service"
                                        {{ old('distribution_type') == 'service' ? 'selected' : '' }}>Layanan</option>
                                </select>
                                @error('distribution_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">
                                    Jumlah <span class="text-red-500">*</span>
                                </label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-4 text-sm text-gray-900 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">Rp</span>
                                    <input type="text"
                                        class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm p-2.5 @error('amount') border-red-500 bg-red-50 @enderror"
                                        id="amount" name="amount_display"
                                        value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                                        placeholder="0" data-amount-input required>
                                    <input type="hidden" id="amount_raw" name="amount"
                                        value="{{ old('amount') }}">
                                </div>
                                @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="amount-warning"
                                    class="hidden mt-2 p-3 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Jumlah melebihi saldo tersedia!
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Goods Description (conditional) -->
                        <div class="mb-4 hidden transition-all duration-300" id="goods-description-field">
                            <label for="goods_description" class="block mb-2 text-sm font-medium text-gray-900">
                                Deskripsi Barang/Layanan
                            </label>
                            <textarea
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('goods_description') border-red-500 bg-red-50 @enderror"
                                id="goods_description" name="goods_description" rows="3"
                                placeholder="Contoh: Beras 10kg, Minyak goreng 2L, dll.">{{ old('goods_description') }}</textarea>
                            @error('goods_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="distribution_date" class="block mb-2 text-sm font-medium text-gray-900">
                                    Tanggal Distribusi <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('distribution_date') border-red-500 bg-red-50 @enderror"
                                    id="distribution_date" name="distribution_date"
                                    value="{{ old('distribution_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                    required>
                                @error('distribution_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block mb-2 text-sm font-medium text-gray-900">
                                    Lokasi Distribusi
                                </label>
                                <input type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('location') border-red-500 bg-red-50 @enderror"
                                    id="location" name="location" value="{{ old('location') }}"
                                    placeholder="Contoh: Masjid Al-Ikhlas, Kantor Amil, dll.">
                                @error('location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Program Information Section -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Program & Catatan</h3>
                        </div>

                        <div class="mb-4">
                            <label for="program_name" class="block mb-2 text-sm font-medium text-gray-900">
                                Nama Program
                            </label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('program_name') border-red-500 bg-red-50 @enderror"
                                id="program_name" name="program_name" value="{{ old('program_name') }}"
                                placeholder="Contoh: Bantuan Ramadan, Program Pendidikan, dll.">
                            @error('program_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">
                                Catatan
                            </label>
                            <textarea
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 bg-red-50 @enderror"
                                id="notes" name="notes" rows="3" placeholder="Catatan tambahan mengenai distribusi ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('distributions.index') }}"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-all duration-200 shadow-sm hover:shadow">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Distribusi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Section -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Available Balance Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div
                class="px-6 py-4 {{ $availableBalance > 0 ? 'bg-gradient-to-r from-green-600 to-green-700' : 'bg-gradient-to-r from-red-600 to-red-700' }} text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold">Saldo Tersedia</h3>
                </div>
            </div>
            <div class="p-6 text-center">
                <h2 class="text-3xl font-bold {{ $availableBalance > 0 ? 'text-green-600' : 'text-red-600' }} mb-2"
                    id="available-balance">
                    Rp {{ number_format($availableBalance, 0, ',', '.') }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ $availableBalance > 0 ? 'Dapat didistribusikan' : 'Saldo tidak mencukupi' }}
                </p>
            </div>
        </div>

        <!-- Guidelines Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold">Panduan Distribusi</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Jenis Distribusi:</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-2 mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                    </path>
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span><strong class="text-gray-900">Tunai:</strong> <span class="text-gray-600">Bantuan
                                    dalam bentuk uang</span></span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-cyan-100 rounded-full flex items-center justify-center mr-2 mt-0.5">
                                <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
                                    </path>
                                </svg>
                            </span>
                            <span><strong class="text-gray-900">Barang:</strong> <span class="text-gray-600">Sembako,
                                    pakaian, dll.</span></span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-2 mt-0.5">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                    <path fill-rule="evenodd"
                                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span><strong class="text-gray-900">Voucher:</strong> <span class="text-gray-600">Kupon
                                    belanja/layanan</span></span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2 mt-0.5">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span><strong class="text-gray-900">Layanan:</strong> <span
                                    class="text-gray-600">Beasiswa, pengobatan, dll.</span></span>
                        </li>
                    </ul>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Kategori Mustahik (8 Asnaf):</h4>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Fakir:</strong> Tidak memiliki harta dan
                                pekerjaan</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Miskin:</strong> Memiliki harta/pekerjaan tapi tidak
                                mencukupi</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Amil:</strong> Petugas pengumpul zakat</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Muallaf:</strong> Mualaf atau yang hatinya perlu
                                diperkuat</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Riqab:</strong> Memerdekakan budak/tawanan</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Gharim:</strong> Orang berutang untuk kepentingan
                                baik</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Fi Sabilillah:</strong> Untuk kepentingan umum</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span><strong class="text-gray-900">Ibnu Sabil:</strong> Musafir kehabisan bekal</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-yellow-800">
                            <strong class="font-semibold">Penting:</strong> Pastikan mustahik sudah terverifikasi
                            sebelum distribusi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
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
        // Simpan original options dengan semua data attributes
        const originalOptions = [];
        Array.from(mustahikSelect.options).slice(1).forEach(option => {
            originalOptions.push({
                value: option.value,
                text: option.text,
                category: option.dataset.category,
                address: option.dataset.address,
                phone: option.dataset.phone,
                selected: option.selected
            });
        });

        // Flag untuk mencegah infinite loop saat update filter dari mustahik selection
        let isUpdatingFromMustahikSelection = false;

        // ===== FUNCTION TO FILTER MUSTAHIK =====
        function filterMustahikList(selectedCategory, preserveSelectedId = null) {
            mustahikSelect.innerHTML = '<option value="">Pilih Mustahik</option>';

            originalOptions.forEach(optionData => {
                if (!selectedCategory || optionData.category === selectedCategory) {
                    const option = document.createElement('option');
                    option.value = optionData.value;
                    option.textContent = optionData.text;
                    option.dataset.category = optionData.category || '';
                    option.dataset.address = optionData.address || '';
                    option.dataset.phone = optionData.phone || '';
                    
                    if (preserveSelectedId && optionData.value === preserveSelectedId) {
                        option.selected = true;
                    }
                    
                    mustahikSelect.appendChild(option);
                }
            });
        }

        // ===== MUSTAHIK SELECTION =====
        function showMustahikDetails() {
            const selectedOption = mustahikSelect.options[mustahikSelect.selectedIndex];
            if (mustahikSelect.value && selectedOption) {
                const category = selectedOption.dataset.category || '-';
                const address = selectedOption.dataset.address || '-';
                const phone = selectedOption.dataset.phone || '-';

                document.getElementById('mustahik-category').textContent = category.replace('_', ' ').replace(
                    /\b\w/g, l => l.toUpperCase());
                document.getElementById('mustahik-address').textContent = address;
                document.getElementById('mustahik-phone').textContent = phone;
                mustahikDetails.classList.remove('hidden');

                // Update filter kategori sesuai dengan mustahik yang dipilih
                if (category && categoryFilter.value !== category && !isUpdatingFromMustahikSelection) {
                    isUpdatingFromMustahikSelection = true;
                    const currentMustahikId = mustahikSelect.value;
                    categoryFilter.value = category;
                    // Filter ulang list mustahik dengan kategori baru
                    filterMustahikList(category, currentMustahikId);
                    // Pastikan mustahik yang dipilih masih terpilih
                    if (mustahikSelect.value !== currentMustahikId) {
                        mustahikSelect.value = currentMustahikId;
                    }
                    isUpdatingFromMustahikSelection = false;
                }
            } else {
                mustahikDetails.classList.add('hidden');
            }
        }

        mustahikSelect.addEventListener('change', showMustahikDetails);

        // ===== CATEGORY FILTER =====
        categoryFilter.addEventListener('change', function() {
            if (isUpdatingFromMustahikSelection) {
                return;
            }

            const selectedCategory = this.value;
            const previouslySelectedMustahikId = mustahikSelect.value;

            // Filter list mustahik
            filterMustahikList(selectedCategory, previouslySelectedMustahikId);

            // Jika ada mustahik yang sudah dipilih sebelumnya, cek apakah masih ada di list baru
            if (previouslySelectedMustahikId) {
                const stillExists = Array.from(mustahikSelect.options).some(
                    opt => opt.value === previouslySelectedMustahikId
                );

                if (stillExists) {
                    // Jika mustahik masih ada di list baru, pertahankan pilihan
                    mustahikSelect.value = previouslySelectedMustahikId;
                    // Update detail display
                    const selectedOption = mustahikSelect.options[mustahikSelect.selectedIndex];
                    if (selectedOption) {
                        const category = selectedOption.dataset.category || '-';
                        const address = selectedOption.dataset.address || '-';
                        const phone = selectedOption.dataset.phone || '-';

                        document.getElementById('mustahik-category').textContent = category.replace('_', ' ').replace(
                            /\b\w/g, l => l.toUpperCase());
                        document.getElementById('mustahik-address').textContent = address;
                        document.getElementById('mustahik-phone').textContent = phone;
                        mustahikDetails.classList.remove('hidden');
                    }
                } else {
                    // Jika tidak ada atau tidak sesuai filter, reset
                    mustahikSelect.value = '';
                    mustahikDetails.classList.add('hidden');
                }
            } else {
            mustahikSelect.value = '';
            mustahikDetails.classList.add('hidden');
            }
        });

        // ===== INITIALIZE FILTER ON PAGE LOAD =====
        // Terapkan filter jika ada nilai di filter kategori saat halaman pertama kali dimuat
        if (categoryFilter.value) {
            filterMustahikList(categoryFilter.value);
            // Jika ada mustahik yang sudah dipilih (dari old input), cek apakah masih sesuai dengan filter
            if (mustahikSelect.value) {
                const selectedOption = Array.from(mustahikSelect.options).find(
                    opt => opt.value === mustahikSelect.value
                );
                if (!selectedOption) {
                    // Jika mustahik yang dipilih tidak sesuai dengan filter, reset
                    mustahikSelect.value = '';
                    mustahikDetails.classList.add('hidden');
                } else {
                    // Jika sesuai, tampilkan detail
                    showMustahikDetails();
                }
            }
        }

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