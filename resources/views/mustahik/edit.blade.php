@extends('layouts.app')

@section('page-title', 'Edit Mustahik - ' . $mustahik->name)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold mb-1 text-gray-900">Edit Mustahik</h2>
            <p class="text-gray-500 text-sm">Mengubah data mustahik (penerima zakat) dalam sistem</p>
        </div>
        <div>
            <a href="{{ route('mustahik.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200">
                <i class="bi bi-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-900 mb-0">
                        <i class="bi bi-pencil-square mr-2"></i> Form Edit Mustahik
                    </h5>
                </div>
                <div class="p-6">
                    <form action="{{ route('mustahik.update', $mustahik) }}" method="POST" id="mustahikForm">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information Section -->
                        <div class="mb-6">
                            <h6 class="text-blue-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-person-circle mr-2"></i> Informasi Personal
                            </h6>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                        id="name" name="name" value="{{ old('name', $mustahik->name) }}" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik') border-red-500 @enderror"
                                        id="nik" name="nik" value="{{ old('nik', $mustahik->nik) }}"
                                        maxlength="16" placeholder="1234567890123456">
                                    @error('nik')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('gender') border-red-500 @enderror"
                                        id="gender" name="gender" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male"
                                            {{ old('gender', $mustahik->gender) == 'male' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="female"
                                            {{ old('gender', $mustahik->gender) == 'female' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                        Lahir</label>
                                    <input type="date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_of_birth') border-red-500 @enderror"
                                        id="date_of_birth" name="date_of_birth"
                                        value="{{ old('date_of_birth', $mustahik->date_of_birth ? $mustahik->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="md:w-1/2">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                    Telepon</label>
                                <input type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $mustahik->phone) }}"
                                    placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="mb-6">
                            <h6 class="text-blue-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-geo-alt mr-2"></i> Informasi Alamat
                            </h6>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat
                                    Lengkap</label>
                                <textarea
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                    id="address" name="address" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan">{{ old('address', $mustahik->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="city"
                                        class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-500 @enderror"
                                        id="city" name="city" value="{{ old('city', $mustahik->city) }}"
                                        placeholder="Jakarta">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="province"
                                        class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('province') border-red-500 @enderror"
                                        id="province" name="province" value="{{ old('province', $mustahik->province) }}"
                                        placeholder="DKI Jakarta">
                                    @error('province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Category Information Section -->
                        <div class="mb-6">
                            <h6 class="text-blue-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-tags mr-2"></i> Kategori Mustahik (Asnaf)
                            </h6>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('category') border-red-500 @enderror"
                                        id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('category', $mustahik->category) == $key ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $key)) }} -
                                                {{ explode(' - ', $label)[1] ?? $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="family_members" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Anggota Keluarga <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('family_members') border-red-500 @enderror"
                                        id="family_members" name="family_members"
                                        value="{{ old('family_members', $mustahik->family_members) }}" min="1"
                                        required>
                                    @error('family_members')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="category_description"
                                    class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kondisi</label>
                                <textarea
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_description') border-red-500 @enderror"
                                    id="category_description" name="category_description" rows="3"
                                    placeholder="Jelaskan kondisi yang dialami...">{{ old('category_description', $mustahik->category_description) }}</textarea>
                                @error('category_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Family & Economic Information Section -->
                        <div class="mb-6">
                            <h6 class="text-blue-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-house-heart mr-2"></i> Informasi Keluarga & Ekonomi
                            </h6>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="family_status" class="block text-sm font-medium text-gray-700 mb-2">Status
                                        Keluarga</label>
                                    <select
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('family_status') border-red-500 @enderror"
                                        id="family_status" name="family_status">
                                        <option value="">Pilih Status Keluarga</option>
                                        <option value="single"
                                            {{ old('family_status', $mustahik->family_status) == 'single' ? 'selected' : '' }}>
                                            Lajang</option>
                                        <option value="married"
                                            {{ old('family_status', $mustahik->family_status) == 'married' ? 'selected' : '' }}>
                                            Menikah</option>
                                        <option value="divorced"
                                            {{ old('family_status', $mustahik->family_status) == 'divorced' ? 'selected' : '' }}>
                                            Cerai</option>
                                        <option value="widow/widower"
                                            {{ old('family_status', $mustahik->family_status) == 'widow/widower' ? 'selected' : '' }}>
                                            Janda/Duda</option>
                                    </select>
                                    @error('family_status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="monthly_income"
                                        class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Bulanan</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('monthly_income') border-red-500 @enderror"
                                        id="monthly_income" name="monthly_income"
                                        value="{{ old('monthly_income', $mustahik->monthly_income) }}" min="0"
                                        placeholder="0">
                                    @error('monthly_income')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="income_source" class="block text-sm font-medium text-gray-700 mb-2">Sumber
                                    Penghasilan</label>
                                <input type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('income_source') border-red-500 @enderror"
                                    id="income_source" name="income_source"
                                    value="{{ old('income_source', $mustahik->income_source) }}"
                                    placeholder="Buruh harian, pedagang kecil, tidak bekerja, dll">
                                @error('income_source')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Verification Section -->
                        <div class="mb-6">
                            <h6 class="text-blue-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-shield-check mr-2"></i> Status Verifikasi
                            </h6>

                            <div class="md:w-1/2 mb-4">
                                <label for="verification_status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status Verifikasi <span class="text-red-500">*</span>
                                </label>
                                <select
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('verification_status') border-red-500 @enderror"
                                    id="verification_status" name="verification_status" required>
                                    <option value="pending"
                                        {{ old('verification_status', $mustahik->verification_status) == 'pending' ? 'selected' : '' }}>
                                        Menunggu Verifikasi</option>
                                    <option value="verified"
                                        {{ old('verification_status', $mustahik->verification_status) == 'verified' ? 'selected' : '' }}>
                                        Terverifikasi</option>
                                    <option value="rejected"
                                        {{ old('verification_status', $mustahik->verification_status) == 'rejected' ? 'selected' : '' }}>
                                        Ditolak</option>
                                </select>
                                @error('verification_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="verification_notes"
                                    class="block text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi</label>
                                <textarea
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('verification_notes') border-red-500 @enderror"
                                    id="verification_notes" name="verification_notes" rows="3"
                                    placeholder="Catatan atau alasan terkait status verifikasi...">{{ old('verification_notes', $mustahik->verification_notes) }}</textarea>
                                @error('verification_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="mb-6">
                            <h6 class="text-blue-600 font-semibold mb-4 flex items-center">
                                <i class="bi bi-toggle-on mr-2"></i> Status Akun
                            </h6>

                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $mustahik->is_active) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Status Aktif</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <a href="{{ route('mustahik.show', $mustahik) }}"
                                class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 bg-white hover:bg-blue-50 font-medium rounded-lg transition-colors duration-200">
                                <i class="bi bi-eye mr-2"></i> Lihat Detail
                            </a>
                            <div class="flex gap-2">
                                <a href="{{ route('mustahik.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200">
                                    <i class="bi bi-x-circle mr-2"></i> Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="bi bi-check-circle mr-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Current Data Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-blue-500 text-white rounded-t-lg">
                    <h6 class="text-lg font-semibold mb-0 flex items-center">
                        <i class="bi bi-info-circle mr-2"></i> Informasi Saat Ini
                    </h6>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span
                                class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $mustahik->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $mustahik->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Verifikasi</span>
                            @switch($mustahik->verification_status)
                                @case('pending')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                @break

                                @case('verified')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Terverifikasi</span>
                                @break

                                @case('rejected')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">Ditolak</span>
                                @break

                                @default
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $mustahik->verification_status }}</span>
                            @endswitch
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Terdaftar</span>
                            <span class="text-sm text-gray-900">{{ $mustahik->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Terakhir Diupdate</span>
                            <span class="text-sm text-gray-900">{{ $mustahik->updated_at->format('d F Y') }}</span>
                        </div>
                        @if ($mustahik->verified_at)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Diverifikasi</span>
                                <span class="text-sm text-gray-900">{{ $mustahik->verified_at->format('d F Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Statistics (if has distributions) -->
            @if ($mustahik->zakatDistributions()->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 bg-green-600 text-white rounded-t-lg">
                        <h6 class="text-lg font-semibold mb-0 flex items-center">
                            <i class="bi bi-wallet2 mr-2"></i> Statistik Penerimaan Zakat
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <h4 class="text-2xl font-bold text-green-600 mb-0">Rp
                                {{ number_format($mustahik->zakatDistributions()->sum('amount'), 0, ',', '.') }}</h4>
                            <p class="text-sm text-gray-500 mt-1">Total Diterima</p>
                        </div>
                        <hr class="my-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Distribusi</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $mustahik->zakatDistributions()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Distribusi Terakhir</span>
                                <span class="text-sm font-medium text-gray-900">
                                    @php
                                        $lastDistribution = $mustahik
                                            ->zakatDistributions()
                                            ->latest('distribution_date')
                                            ->first();
                                    @endphp
                                    {{ $lastDistribution ? $lastDistribution->distribution_date->format('d M Y') : '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                            <div class="flex items-start">
                                <i class="bi bi-exclamation-triangle mr-2 mt-0.5"></i>
                                <span>Berhati-hati saat mengubah data mustahik yang sudah memiliki riwayat
                                    distribusi.</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 8 Asnaf Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-blue-600 text-white rounded-t-lg">
                    <h6 class="text-lg font-semibold mb-0">
                        <i class="bi bi-info-circle mr-2"></i> 8 Asnaf (Kategori Mustahik)
                    </h6>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-600 space-y-2">
                        <ul class="list-none space-y-2">
                            <li><strong class="text-gray-900">Fakir:</strong> Yang tidak memiliki harta/pekerjaan</li>
                            <li><strong class="text-gray-900">Miskin:</strong> Yang memiliki harta/pekerjaan tapi tidak
                                mencukupi</li>
                            <li><strong class="text-gray-900">Amil:</strong> Petugas pengumpul dan pembagi zakat</li>
                            <li><strong class="text-gray-900">Muallaf:</strong> Yang baru masuk Islam</li>
                            <li><strong class="text-gray-900">Riqab:</strong> Memerdekakan budak/tawanan</li>
                            <li><strong class="text-gray-900">Gharim:</strong> Yang berutang untuk kebaikan</li>
                            <li><strong class="text-gray-900">Fi Sabilillah:</strong> Untuk kepentingan umum di jalan Allah
                            </li>
                            <li><strong class="text-gray-900">Ibnu Sabil:</strong> Musafir yang kehabisan bekal</li>
                        </ul>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                        <div class="flex items-start">
                            <i class="bi bi-exclamation-triangle mr-2 mt-0.5"></i>
                            <span>Pastikan kategori sesuai dengan kondisi mustahik yang sebenarnya.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // NIK validation (numeric only, max 16 digits)
            document.getElementById('nik').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 16);
            });

            // Phone validation (numeric only)
            document.getElementById('phone').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Format monthly income
            document.getElementById('monthly_income').addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                this.value = value;
            });

            // Category change handler - show description
            document.getElementById('category').addEventListener('change', function() {
                const descriptions = {
                    'fakir': 'Orang yang tidak memiliki harta dan pekerjaan untuk mencukupi kebutuhan dasar',
                    'miskin': 'Orang yang memiliki harta atau pekerjaan tetapi tidak mencukupi kebutuhan dasar',
                    'amil': 'Petugas yang bertugas mengumpulkan dan membagikan zakat',
                    'muallaf': 'Orang yang baru masuk Islam atau yang hatinya perlu diperkuat imannya',
                    'riqab': 'Untuk memerdekakan budak atau membebaskan muslim dari tawanan',
                    'gharim': 'Orang yang berutang untuk kepentingan yang tidak maksiat dan tidak mampu membayar',
                    'fisabilillah': 'Untuk kepentingan umum di jalan Allah seperti pendidikan, dakwah, dll',
                    'ibnu_sabil': 'Musafir yang kehabisan bekal dalam perjalanan yang halal'
                };

                const descField = document.getElementById('category_description');
                if (this.value && descriptions[this.value]) {
                    descField.placeholder = descriptions[this.value];
                }
            });
        });
    </script>
@endpush
