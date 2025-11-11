@extends('layouts.app')

@section('page-title', 'Edit Distribusi - ' . $distribution->distribution_code)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold mb-1">Edit Distribusi Zakat</h2>
        <p class="text-gray-600">{{ $distribution->distribution_code }} - {{ $distribution->mustahik->name }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('distributions.show', $distribution) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            <i class="bi bi-arrow-left mr-2"></i> Kembali
        </a>
        <a href="{{ route('distributions.index') }}" class="inline-flex items-center px-4 py-2 border border-cyan-300 rounded-lg text-cyan-700 hover:bg-cyan-50">
            <i class="bi bi-list mr-2"></i> Daftar Distribusi
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-white">
                <div class="flex justify-between items-center">
                    <h5 class="text-lg font-semibold mb-0"><i class="bi bi-pencil mr-2"></i> Edit Form Distribusi</h5>
                    @if($distribution->is_received)
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Sudah Diterima</span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Diterima</span>
                    @endif
                </div>
            </div>
            <div class="p-6">
                @if($distribution->is_received)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <i class="bi bi-exclamation-triangle text-yellow-600 mr-2"></i>
                    <strong>Perhatian:</strong> Distribusi ini sudah ditandai sebagai diterima pada {{ $distribution->received_date?->format('d F Y H:i') }}. 
                    Perubahan data harus dilakukan dengan hati-hati.
                </div>
                @endif
                
                <form action="{{ route('distributions.update', $distribution) }}" method="POST" id="distributionEditForm">
                    @csrf
                    @method('PUT')
                    
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
                                            {{ old('mustahik_id', $distribution->mustahik_id) == $m->id ? 'selected' : '' }}>
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
                                    <option value="cash" {{ old('distribution_type', $distribution->distribution_type) == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="goods" {{ old('distribution_type', $distribution->distribution_type) == 'goods' ? 'selected' : '' }}>Barang</option>
                                    <option value="voucher" {{ old('distribution_type', $distribution->distribution_type) == 'voucher' ? 'selected' : '' }}>Voucher</option>
                                    <option value="service" {{ old('distribution_type', $distribution->distribution_type) == 'service' ? 'selected' : '' }}>Layanan</option>
                                </select>
                                @error('distribution_type')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50 text-gray-500">Rp</span>
                                    <input type="number" 
                                           class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount', $distribution->amount) }}" 
                                           min="0"
                                           step="1000"
                                           required>
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
                                <div id="amount-change-warning" class="hidden mt-1">
                                    <small class="text-cyan-600">
                                        <i class="bi bi-info-circle mr-1"></i>
                                        Jumlah asli: Rp {{ number_format($distribution->amount, 0, ',', '.') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Goods Description (conditional) -->
                        <div class="mb-4 {{ in_array($distribution->distribution_type, ['goods', 'service']) ? '' : 'hidden' }}" id="goods-description-field">
                            <label for="goods_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Barang/Layanan</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('goods_description') border-red-500 @enderror" 
                                      id="goods_description" 
                                      name="goods_description" 
                                      rows="3"
                                      placeholder="Contoh: Beras 10kg, Minyak goreng 2L, dll.">{{ old('goods_description', $distribution->goods_description) }}</textarea>
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
                                       value="{{ old('distribution_date', $distribution->distribution_date->format('Y-m-d')) }}"
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
                                       value="{{ old('location', $distribution->location) }}"
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
                                       value="{{ old('program_name', $distribution->program_name) }}"
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
                                      placeholder="Catatan tambahan mengenai distribusi ini...">{{ old('notes', $distribution->notes) }}</textarea>
                            @error('notes')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Receipt Status Section (if already received) -->
                    @if($distribution->is_received)
                    <div class="mb-6">
                        <h6 class="text-green-600 font-semibold mb-3">
                            <i class="bi bi-check-circle mr-2"></i> Status Penerimaan
                        </h6>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="received_by_name" class="block text-sm font-medium text-gray-700 mb-1">Diterima Oleh</label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('received_by_name') border-red-500 @enderror" 
                                       id="received_by_name" 
                                       name="received_by_name" 
                                       value="{{ old('received_by_name', $distribution->received_by_name) }}"
                                       placeholder="Nama penerima">
                                @error('received_by_name')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="received_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Diterima</label>
                                <input type="datetime-local" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('received_date') border-red-500 @enderror" 
                                       id="received_date" 
                                       name="received_date" 
                                       value="{{ old('received_date', $distribution->received_date?->format('Y-m-d\TH:i')) }}"
                                       max="{{ date('Y-m-d\TH:i') }}">
                                @error('received_date')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="received_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Penerimaan</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('received_notes') border-red-500 @enderror" 
                                      id="received_notes" 
                                      name="received_notes" 
                                      rows="3"
                                      placeholder="Catatan penerimaan distribusi...">{{ old('received_notes', $distribution->received_notes) }}</textarea>
                            @error('received_notes')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="flex items-center">
                            <input class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded" type="checkbox" id="mark_as_not_received" name="mark_as_not_received" value="1">
                            <label class="ml-2 block text-sm text-yellow-600" for="mark_as_not_received">
                                <i class="bi bi-exclamation-triangle mr-1"></i> Batalkan status diterima (tandai sebagai belum diterima)
                            </label>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex justify-between pt-4 border-t">
                        <div>
                            <a href="{{ route('distributions.show', $distribution) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                <i class="bi bi-arrow-left mr-2"></i> Batal
                            </a>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="bi bi-check-circle mr-2"></i> Simpan Perubahan
                            </button>
                            <button type="submit" name="save_and_continue" value="1" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="bi bi-check-circle mr-2"></i> Simpan & Lihat
                            </button>
                        </div>
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
                <hr class="my-4">
                <div class="text-left">
                    <small class="text-gray-600">
                        <strong>Catatan:</strong> Saldo dihitung dari total pembayaran dikurangi distribusi yang sudah ada. 
                        Jumlah distribusi saat ini (Rp {{ number_format($distribution->amount, 0, ',', '.') }}) sudah dikurangi dari perhitungan.
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Original Data Card -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 bg-cyan-600 text-white rounded-t-lg">
                <h6 class="font-semibold mb-0"><i class="bi bi-archive mr-2"></i> Data Asli</h6>
            </div>
            <div class="p-6">
                <table class="w-full text-sm">
                    <tr class="border-b">
                        <td class="py-2 text-gray-600">Kode:</td>
                        <td class="py-2 font-semibold">{{ $distribution->distribution_code }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600">Mustahik:</td>
                        <td class="py-2 font-semibold">{{ $distribution->mustahik->name }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600">Jenis:</td>
                        <td class="py-2">
                            @switch($distribution->distribution_type)
                                @case('cash')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Tunai</span>
                                    @break
                                @case('goods')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">Barang</span>
                                    @break
                                @case('voucher')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Voucher</span>
                                    @break
                                @case('service')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Layanan</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600">Jumlah:</td>
                        <td class="py-2 font-bold">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600">Tanggal:</td>
                        <td class="py-2 font-semibold">{{ $distribution->distribution_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Dicatat:</td>
                        <td class="py-2 font-semibold">{{ $distribution->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Guidelines Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 bg-yellow-500 text-gray-900 rounded-t-lg">
                <h6 class="font-semibold mb-0"><i class="bi bi-exclamation-triangle mr-2"></i> Perhatian</h6>
            </div>
            <div class="p-6">
                <ul class="list-none space-y-2 text-sm">
                    <li><i class="bi bi-check-circle text-green-600 mr-2"></i> Pastikan data yang diubah sudah benar</li>
                    <li><i class="bi bi-check-circle text-green-600 mr-2"></i> Perubahan jumlah akan mempengaruhi saldo</li>
                    <li><i class="bi bi-check-circle text-green-600 mr-2"></i> Jika distribusi sudah diterima, berhati-hatilah mengubah data</li>
                    <li><i class="bi bi-check-circle text-green-600 mr-2"></i> Backup data penting sebelum perubahan besar</li>
                </ul>
                
                @if($distribution->is_received)
                <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-3 mt-4 text-sm">
                    <i class="bi bi-info-circle text-cyan-600 mr-2"></i>
                    Distribusi ini sudah diterima. Pastikan mustahik mengetahui perubahan yang dilakukan.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mustahikSelect = document.getElementById('mustahik_id');
    const categoryFilter = document.getElementById('category_filter');
    const distributionType = document.getElementById('distribution_type');
    const amountInput = document.getElementById('amount');
    const goodsDescriptionField = document.getElementById('goods-description-field');
    const mustahikDetails = document.getElementById('mustahik-details');
    const amountWarning = document.getElementById('amount-warning');
    const amountChangeWarning = document.getElementById('amount-change-warning');
    const originalAmount = {{ $distribution->amount }};
    const availableBalance = {{ $availableBalance }};
    
    // Store original options for filtering
    const originalOptions = Array.from(mustahikSelect.options).slice(1); // Exclude empty option
    
    // Initialize mustahik details on load
    if (mustahikSelect.value) {
        mustahikSelect.dispatchEvent(new Event('change'));
    }
    
    // Mustahik selection handler
    mustahikSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const category = selectedOption.dataset.category;
            const address = selectedOption.dataset.address || '-';
            const phone = selectedOption.dataset.phone || '-';
            
            // Show mustahik details
            document.getElementById('mustahik-category').textContent = category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            document.getElementById('mustahik-address').textContent = address;
            document.getElementById('mustahik-phone').textContent = phone;
            mustahikDetails.classList.remove('hidden');
        } else {
            mustahikDetails.classList.add('hidden');
        }
    });
    
    // Category filter handler
    categoryFilter.addEventListener('change', function() {
        const selectedCategory = this.value;
        const currentValue = mustahikSelect.value;
        
        // Clear current options (except empty option)
        mustahikSelect.innerHTML = '<option value="">Pilih Mustahik</option>';
        
        // Filter and add options
        originalOptions.forEach(option => {
            if (!selectedCategory || option.dataset.category === selectedCategory) {
                mustahikSelect.appendChild(option.cloneNode(true));
            }
        });
        
        // Restore selection if still valid
        if (currentValue) {
            mustahikSelect.value = currentValue;
            if (mustahikSelect.value) {
                mustahikSelect.dispatchEvent(new Event('change'));
            }
        }
    });
    
    // Distribution type handler
    distributionType.addEventListener('change', function() {
        const goodsDescField = document.getElementById('goods_description');
        
        if (this.value === 'goods' || this.value === 'service') {
            goodsDescriptionField.classList.remove('hidden');
            goodsDescField.setAttribute('required', 'required');
        } else {
            goodsDescriptionField.classList.add('hidden');
            goodsDescField.removeAttribute('required');
        }
        
        validateAmount();
    });
    
    // Amount validation
    function validateAmount() {
        const amount = parseFloat(amountInput.value) || 0;
        const isCash = distributionType.value === 'cash';
        const adjustedBalance = availableBalance + originalAmount; // Add back original amount for comparison
        
        // Show amount change warning if different from original
        if (amount !== originalAmount) {
            amountChangeWarning.classList.remove('hidden');
        } else {
            amountChangeWarning.classList.add('hidden');
        }
        
        // Validate cash amount against available balance
        if (isCash && amount > adjustedBalance) {
            amountWarning.classList.remove('hidden');
            amountInput.classList.add('border-red-500');
        } else {
            amountWarning.classList.add('hidden');
            amountInput.classList.remove('border-red-500');
        }
    }
    
    amountInput.addEventListener('input', validateAmount);
    distributionType.addEventListener('change', validateAmount);
    
    // Format amount input
    amountInput.addEventListener('blur', function() {
        if (this.value) {
            const value = parseInt(this.value.replace(/[^0-9]/g, ''));
            if (!isNaN(value)) {
                this.value = value;
            }
        }
    });
    
    // Form submission validation
    document.getElementById('distributionEditForm').addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput.value) || 0;
        const isCash = distributionType.value === 'cash';
        const adjustedBalance = availableBalance + originalAmount;
        
        if (isCash && amount > adjustedBalance) {
            e.preventDefault();
            alert('Jumlah distribusi tunai melebihi saldo tersedia!');
            amountInput.focus();
            return;
        }
        
        if ((distributionType.value === 'goods' || distributionType.value === 'service') && !document.getElementById('goods_description').value) {
            e.preventDefault();
            alert('Deskripsi barang/layanan wajib diisi!');
            document.getElementById('goods_description').focus();
            return;
        }
        
        // Confirm if significant changes are made
        const significantChanges = [];
        if (amount !== originalAmount) {
            significantChanges.push('Jumlah distribusi');
        }
        if (mustahikSelect.value != {{ $distribution->mustahik_id }}) {
            significantChanges.push('Penerima (Mustahik)');
        }
        if (distributionType.value !== '{{ $distribution->distribution_type }}') {
            significantChanges.push('Jenis distribusi');
        }
        
        if (significantChanges.length > 0) {
            const changes = significantChanges.join(', ');
            if (!confirm(`Anda akan mengubah: ${changes}. Lanjutkan?`)) {
                e.preventDefault();
                return;
            }
        }
    });
    
    // Initialize form state
    validateAmount();
});
</script>
@endpush
