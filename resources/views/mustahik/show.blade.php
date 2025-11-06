@extends('layouts.app')

@section('page-title', 'Detail Mustahik - ' . $mustahik->name)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold mb-1 text-gray-900">Detail Mustahik</h2>
            <p class="text-gray-500 text-sm">{{ $mustahik->name }}</p>
        </div>
        <div>
            <a href="{{ route('mustahik.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200">
                <i class="bi bi-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-900 mb-0 flex items-center">
                        <i class="bi bi-person-circle mr-2"></i> Informasi Personal
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->name }}</div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">NIK</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->nik ?: '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Jenis Kelamin</label>
                            <div class="font-semibold text-gray-900">
                                @if ($mustahik->gender == 'male')
                                    <i class="bi bi-gender-male text-blue-600 mr-1"></i> Laki-laki
                                @elseif($mustahik->gender == 'female')
                                    <i class="bi bi-gender-female text-pink-600 mr-1"></i> Perempuan
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Tanggal Lahir</label>
                            <div class="font-semibold text-gray-900">
                                @if ($mustahik->date_of_birth)
                                    {{ $mustahik->date_of_birth->format('d M Y') }}
                                    @if ($mustahik->age)
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $mustahik->age }}
                                            tahun</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Nomor Telepon</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->phone ?: '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status Verifikasi</label>
                            <div class="font-semibold">
                                @switch($mustahik->verification_status)
                                    @case('pending')
                                        <span
                                            class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Menunggu
                                            Verifikasi</span>
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
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status Aktif</label>
                            <div class="font-semibold">
                                @if ($mustahik->is_active)
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Tidak
                                        Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-900 mb-0 flex items-center">
                        <i class="bi bi-geo-alt mr-2"></i> Informasi Alamat
                    </h5>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Alamat Lengkap</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->address ?: '-' }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Kota/Kabupaten</label>
                                <div class="font-semibold text-gray-900">{{ $mustahik->city ?: '-' }}</div>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Provinsi</label>
                                <div class="font-semibold text-gray-900">{{ $mustahik->province ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-900 mb-0 flex items-center">
                        <i class="bi bi-tags mr-2"></i> Kategori Mustahik (Asnaf)
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                            <div class="font-semibold">
                                <span
                                    class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ ucfirst(str_replace('_', ' ', $mustahik->category)) }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Jumlah Anggota Keluarga</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->family_members }} orang</div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status Keluarga</label>
                            <div class="font-semibold text-gray-900">
                                @switch($mustahik->family_status)
                                    @case('single')
                                        Lajang
                                    @break

                                    @case('married')
                                        Menikah
                                    @break

                                    @case('divorced')
                                        Cerai
                                    @break

                                    @case('widow/widower')
                                        Janda/Duda
                                    @break

                                    @default
                                        -
                                @endswitch
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-500 mb-1">Deskripsi Kondisi</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->category_description ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Economic Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-900 mb-0 flex items-center">
                        <i class="bi bi-currency-dollar mr-2"></i> Informasi Ekonomi
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Penghasilan Bulanan</label>
                            <div class="font-semibold text-gray-900">Rp
                                {{ number_format($mustahik->monthly_income ?? 0, 0, ',', '.') }}</div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Sumber Penghasilan</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->income_source ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-900 mb-0 flex items-center">
                        <i class="bi bi-shield-check mr-2"></i> Informasi Verifikasi
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Tanggal Verifikasi</label>
                            <div class="font-semibold text-gray-900">
                                @if ($mustahik->verified_at)
                                    {{ $mustahik->verified_at->format('d M Y H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Diverifikasi Oleh</label>
                            <div class="font-semibold text-gray-900">
                                @if ($mustahik->verifiedBy)
                                    {{ $mustahik->verifiedBy->name }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-500 mb-1">Catatan Verifikasi</label>
                            <div class="font-semibold text-gray-900">{{ $mustahik->verification_notes ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-blue-600 text-white rounded-t-lg">
                    <h6 class="text-lg font-semibold mb-0 flex items-center">
                        <i class="bi bi-bar-chart mr-2"></i> Statistik Penerimaan Zakat
                    </h6>
                </div>
                <div class="p-6 text-center">
                    <div class="mb-4">
                        <i class="bi bi-cash-coin text-5xl text-blue-600"></i>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-3xl font-bold text-blue-600 mb-1">{{ $stats['distribution_count'] }}</h3>
                        <p class="text-sm text-gray-500">Total Distribusi</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-3xl font-bold text-green-600 mb-1">Rp
                            {{ number_format($stats['total_received'], 0, ',', '.') }}</h3>
                        <p class="text-sm text-gray-500">Total Zakat Diterima</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Terakhir Menerima</p>
                        <p class="font-semibold text-gray-900">
                            @if ($stats['last_distribution'])
                                {{ $stats['last_distribution']->distribution_date->format('d M Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Distributions Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h6 class="text-lg font-semibold text-gray-900 mb-0 flex items-center">
                        <i class="bi bi-list-check mr-2"></i> Distribusi Terbaru
                    </h6>
                </div>
                <div class="p-6">
                    @if ($recentDistributions->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentDistributions as $distribution)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                    <div>
                                        <div class="font-semibold text-gray-900">Rp
                                            {{ number_format($distribution->amount, 0, ',', '.') }}</div>
                                        <small
                                            class="text-sm text-gray-500">{{ $distribution->distribution_date->format('d M Y') }}</small>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500 mb-1">
                                            {{ $distribution->distributedBy->name ?? 'System' }}</div>
                                        @if ($distribution->is_received)
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Diterima</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Belum
                                                Diterima</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="bi bi-inbox text-4xl text-gray-400 block mb-2"></i>
                            <p class="text-sm text-gray-500">Belum ada distribusi</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h6 class="text-lg font-semibold text-gray-900 mb-0 flex items-center">
                        <i class="bi bi-lightning mr-2"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        <a href="{{ route('mustahik.edit', $mustahik) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="bi bi-pencil mr-2"></i> Edit Data
                        </a>

                        @if ($mustahik->verification_status === 'pending')
                            <button type="button"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                                onclick="showVerifyModal('verified')">
                                <i class="bi bi-check-circle mr-2"></i> Verifikasi
                            </button>
                            <button type="button"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                                onclick="showVerifyModal('rejected')">
                                <i class="bi bi-x-circle mr-2"></i> Tolak
                            </button>
                        @endif

                        <form action="{{ route('mustahik.toggle-status', $mustahik) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 {{ $mustahik->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="bi bi-toggle-{{ $mustahik->is_active ? 'on' : 'off' }} mr-2"></i>
                                {{ $mustahik->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div id="verifyModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <form action="{{ route('mustahik.verify', $mustahik) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" id="verifyStatus">

                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900" id="verifyModalLabel">Verifikasi Mustahik</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            onclick="closeModal()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="p-4 md:p-5">
                        <div class="mb-4">
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                Verifikasi</label>
                            <textarea
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                id="verification_notes" name="notes" rows="3" placeholder="Tambahkan catatan verifikasi..."></textarea>
                        </div>
                    </div>

                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-medium transition-colors duration-200"
                            onclick="closeModal()">Batal</button>
                        <button type="submit"
                            class="ms-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-backdrop" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-40"></div>
@endsection

@push('scripts')
    <script>
        function showVerifyModal(status) {
            const modal = document.getElementById('verifyModal');
            const backdrop = document.getElementById('modal-backdrop');
            const statusInput = document.getElementById('verifyStatus');
            const modalTitle = document.getElementById('verifyModalLabel');

            if (status === 'verified') {
                statusInput.value = 'verified';
                modalTitle.textContent = 'Verifikasi Mustahik';
            } else {
                statusInput.value = 'rejected';
                modalTitle.textContent = 'Tolak Mustahik';
            }

            modal.classList.remove('hidden');
            backdrop.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('verifyModal');
            const backdrop = document.getElementById('modal-backdrop');
            modal.classList.add('hidden');
            backdrop.classList.add('hidden');
        }

        // Close modal when clicking backdrop
        document.getElementById('modal-backdrop').addEventListener('click', closeModal);
    </script>
@endpush
