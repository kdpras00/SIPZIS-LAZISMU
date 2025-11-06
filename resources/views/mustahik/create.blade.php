@extends('layouts.app')

@section('page-title', 'Tambah Mustahik')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold mb-1 text-gray-900">Tambah Mustahik Baru</h2>
            <p class="text-gray-500 text-sm">Menambahkan data mustahik (penerima zakat) baru ke dalam sistem</p>
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
                        <i class="bi bi-person-heart mr-2"></i> Form Data Mustahik
                    </h5>
                </div>
                <div class="p-6">
                    <form action="{{ route('mustahik.store') }}" method="POST" id="mustahikForm">
                        @csrf

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
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik') border-red-500 @enderror"
                                        id="nik" name="nik" value="{{ old('nik') }}" maxlength="16"
                                        placeholder="1234567890123456">
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
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan
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
                                        id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
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
                                    id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
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
                                    id="address" name="address" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan">{{ old('address') }}</textarea>
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
                                        id="city" name="city" value="{{ old('city') }}" placeholder="Jakarta">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="province"
                                        class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('province') border-red-500 @enderror"
                                        id="province" name="province" value="{{ old('province') }}"
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
                                                {{ old('category') == $key ? 'selected' : '' }}>
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
                                        id="family_members" name="family_members" value="{{ old('family_members', 1) }}"
                                        min="1" required>
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
                                    placeholder="Jelaskan kondisi yang dialami...">{{ old('category_description') }}</textarea>
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
                                        <option value="single" {{ old('family_status') == 'single' ? 'selected' : '' }}>
                                            Lajang</option>
                                        <option value="married" {{ old('family_status') == 'married' ? 'selected' : '' }}>
                                            Menikah</option>
                                        <option value="divorced"
                                            {{ old('family_status') == 'divorced' ? 'selected' : '' }}>Cerai</option>
                                        <option value="widow/widower"
                                            {{ old('family_status') == 'widow/widower' ? 'selected' : '' }}>Janda/Duda
                                        </option>
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
                                        id="monthly_income" name="monthly_income" value="{{ old('monthly_income') }}"
                                        min="0" placeholder="0">
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
                                    id="income_source" name="income_source" value="{{ old('income_source') }}"
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
                                        {{ old('verification_status', 'pending') == 'pending' ? 'selected' : '' }}>Menunggu
                                        Verifikasi</option>
                                    <option value="verified"
                                        {{ old('verification_status') == 'verified' ? 'selected' : '' }}>Terverifikasi
                                    </option>
                                    <option value="rejected"
                                        {{ old('verification_status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
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
                                    placeholder="Catatan atau alasan terkait status verifikasi...">{{ old('verification_notes') }}</textarea>
                                @error('verification_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <a href="{{ route('mustahik.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200">
                                <i class="bi bi-arrow-left mr-2"></i> Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="bi bi-check-circle mr-2"></i> Simpan Mustahik
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
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
