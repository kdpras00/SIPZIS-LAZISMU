@extends('layouts.app')

@section('page-title', 'Edit Muzakki - ' . $muzakki->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('muzakki.index') }}"
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 mb-4 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Muzakki
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Edit Muzakki</h2>
            <p class="text-gray-600">Edit data muzakki: {{ $muzakki->name }}</p>
        </div>
    </div>

    <form action="{{ route('muzakki.update', $muzakki) }}" method="POST" id="muzakkiEditForm"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar Card -->
        <div class="mb-6 p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Informasi Dasar</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nama -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3 @error('name') border-red-500 @enderror"
                        value="{{ old('name', $muzakki->name) }}" required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3 @error('email') border-red-500 @enderror"
                        value="{{ old('email', $muzakki->email) }}" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3 @error('phone') border-red-500 @enderror"
                        value="{{ old('phone', $muzakki->phone) }}" required>
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIK -->
                <div>
                    <label for="nik" class="block mb-2 text-sm font-medium text-gray-900">
                        NIK
                    </label>
                    <input type="text" id="nik" name="nik"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3 @error('nik') border-red-500 @enderror"
                        value="{{ old('nik', $muzakki->nik) }}" maxlength="20">
                    @error('nik')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select id="gender" name="gender"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3 @error('gender') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('gender', $muzakki->gender) == 'male' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="female" {{ old('gender', $muzakki->gender) == 'female' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profesi -->
                <div>
                    <label for="occupation" class="block mb-2 text-sm font-medium text-gray-900">
                        Profesi
                    </label>
                    <select id="occupation" name="occupation"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3">
                        <option value="">Pilih Profesi</option>
                        @php
                            $occupations = [
                                'employee' => 'Karyawan',
                                'entrepreneur' => 'Wiraswasta',
                                'civil_servant' => 'PNS',
                                'teacher' => 'Guru',
                                'doctor' => 'Dokter',
                                'nurse' => 'Perawat',
                                'soldier' => 'Tentara',
                                'police' => 'Polisi',
                                'farmer' => 'Petani',
                                'fisherman' => 'Nelayan',
                                'trader' => 'Pedagang',
                                'driver' => 'Sopir',
                                'online_driver' => 'Ojek Online',
                                'programmer' => 'Programmer',
                                'designer' => 'Desainer',
                                'accountant' => 'Akuntan',
                                'student' => 'Mahasiswa',
                                'pupil' => 'Pelajar',
                                'housewife' => 'Ibu Rumah Tangga',
                                'retired' => 'Pensiunan',
                                'artist' => 'Seniman',
                                'musician' => 'Musisi',
                                'athlete' => 'Atlet',
                                'lawyer' => 'Pengacara',
                                'architect' => 'Arsitek',
                                'other' => 'Lainnya',
                            ];
                        @endphp
                        @foreach ($occupations as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('occupation', $muzakki->occupation) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="date_of_birth" class="block mb-2 text-sm font-medium text-gray-900">
                        Tanggal Lahir
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3"
                        value="{{ old('date_of_birth', $muzakki->date_of_birth ? $muzakki->date_of_birth->format('Y-m-d') : '') }}">
                </div>

                <!-- Status Akun -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Status Akun
                    </label>
                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                            {{ old('is_active', $muzakki->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-900">Aktif</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alamat Card -->
        <div class="mb-6 p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Alamat</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Negara -->
                <div>
                    <label for="country" class="block mb-2 text-sm font-medium text-gray-900">
                        Negara
                    </label>
                    <input type="text" id="country" name="country"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3"
                        value="{{ old('country', $muzakki->country ?? 'Indonesia') }}">
                </div>

                <!-- Provinsi -->
                <div>
                    <label for="province" class="block mb-2 text-sm font-medium text-gray-900">
                        Provinsi
                    </label>
                    <input type="text" id="province" name="province"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3"
                        value="{{ old('province', $muzakki->province) }}">
                </div>

                <!-- Kota/Kabupaten -->
                <div>
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-900">
                        Kota/Kabupaten
                    </label>
                    <input type="text" id="city" name="city"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3"
                        value="{{ old('city', $muzakki->city) }}">
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="district" class="block mb-2 text-sm font-medium text-gray-900">
                        Kecamatan
                    </label>
                    <input type="text" id="district" name="district"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3"
                        value="{{ old('district', $muzakki->district) }}">
                </div>

                <!-- Kelurahan -->
                <div>
                    <label for="village" class="block mb-2 text-sm font-medium text-gray-900">
                        Kelurahan
                    </label>
                    <input type="text" id="village" name="village"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3"
                        value="{{ old('village', $muzakki->village) }}">
                </div>

                <!-- Kode Pos -->
                <div>
                    <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">
                        Kode Pos
                    </label>
                    <input type="text" id="postal_code" name="postal_code"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3"
                        value="{{ old('postal_code', $muzakki->postal_code) }}" maxlength="10">
                </div>

                <!-- Alamat Lengkap -->
                <div class="md:col-span-2">
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900">
                        Alamat Lengkap
                    </label>
                    <textarea id="address" name="address" rows="3"
                        class="block h-12 px-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $muzakki->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan Card -->
        <div class="mb-6 p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Informasi Tambahan</h3>
            <div class="space-y-6">
                <!-- Biodata -->
                <div>
                    <label for="bio" class="block mb-2 text-sm font-medium text-gray-900">
                        Biodata
                    </label>
                    <textarea id="bio" name="bio" rows="5"
                        class="block h-12 px-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $muzakki->bio) }}</textarea>
                </div>

                <!-- URL Campaign -->
                <div>
                    <label for="campaign_url" class="block mb-2 text-sm font-medium text-gray-900">
                        URL Campaign
                    </label>
                    <input type="url" id="campaign_url" name="campaign_url"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-12 px-3 cursor-not-allowed"
                        value="{{ old('campaign_url', $muzakki->campaign_url) }}" readonly>
                    <p class="mt-2 text-sm text-gray-500">URL ini dibuat otomatis oleh sistem</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center justify-end gap-4">
            <a href="{{ route('muzakki.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Postal code formatting
            const postalInput = document.getElementById('postal_code');
            if (postalInput) {
                postalInput.addEventListener('input', function() {
                    // Remove any non-digit characters
                    this.value = this.value.replace(/\D/g, '');
                });
            }
        });
    </script>
@endpush
