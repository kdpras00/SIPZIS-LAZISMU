@extends('layouts.app')

@section('page-title', 'Detail Pembayaran - ' . $payment->payment_code)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pembayaran Zakat</h1>
            <p class="text-gray-600 mt-1">{{ $payment->payment_code }}</p>
        </div>
        <div class="flex gap-2">
            @if($payment->status === 'completed')
            <a href="{{ route('payments.receipt', $payment) }}" target="_blank" 
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="bi bi-receipt mr-2"></i>
                Kwitansi
            </a>
            @endif
            @if(auth()->user()->role === 'admin' && $payment->status !== 'completed')
            <a href="{{ route('payments.edit', $payment) }}" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="bi bi-pencil mr-2"></i>
                Edit
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-credit-card mr-2"></i>
                        Informasi Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Kode Pembayaran</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $payment->payment_code }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">No. Kwitansi</label>
                                <p class="text-gray-900 mt-1">{{ $payment->receipt_number ?: '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Jenis Zakat</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $payment->zakatType->name ?? 'Donasi Umum' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Pembayaran</label>
                                <p class="text-gray-900 mt-1">{{ $payment->payment_date->format('d F Y, H:i') }} WIB</p>
                            </div>
                            @if($payment->hijri_year)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tahun Hijriyah</label>
                                <p class="text-gray-900 mt-1">{{ $payment->hijri_year }} H</p>
                            </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <div class="mt-1">
                                    @switch($payment->status)
                                    @case('completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                    @break
                                    @case('pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                    @break
                                    @case('cancelled')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Dibatalkan
                                    </span>
                                    @break
                                    @default
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                    @endswitch
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Metode Pembayaran</label>
                                <div class="mt-1">
                                    @switch($payment->payment_method)
                                    @case('cash')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="bi bi-cash mr-1"></i>Tunai
                                    </span>
                                    @break
                                    @case('transfer')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="bi bi-bank mr-1"></i>Transfer Bank
                                    </span>
                                    @break
                                    @case('online')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="bi bi-globe mr-1"></i>Online
                                    </span>
                                    @break
                                    @default
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>
                                    @endswitch
                                </div>
                            </div>
                            @if($payment->payment_reference)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Referensi</label>
                                <p class="text-gray-900 font-mono text-sm mt-1">{{ $payment->payment_reference }}</p>
                            </div>
                            @endif
                            @if($payment->midtrans_order_id)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Midtrans Order ID</label>
                                <p class="text-gray-900 font-mono text-sm mt-1">{{ $payment->midtrans_order_id }}</p>
                            </div>
                            @endif
                            @if($payment->program_category)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Kategori Program</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst(str_replace('-', ' ', $payment->program_category)) }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            @php
                                $campaign = $payment->campaign;
                            @endphp
                            @if($campaign || $payment->program || $payment->programType)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Program/Campaign</label>
                                <div class="mt-1">
                                    @if($campaign)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $campaign->title }}
                                    </span>
                                    @elseif($payment->program)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $payment->program->name }}
                                    </span>
                                    @elseif($payment->programType)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $payment->programType->name }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($payment->receivedBy)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Diterima oleh</label>
                                <p class="text-gray-900 mt-1">{{ $payment->receivedBy->name }}</p>
                            </div>
                            @endif
                            @if($payment->is_guest_payment)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tipe Pembayaran</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="bi bi-person-badge mr-1"></i>Guest Payment
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amount Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-calculator mr-2"></i>
                        Rincian Jumlah
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($payment->wealth_amount)
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <h6 class="text-sm font-medium text-gray-600 mb-2">Jumlah Harta</h6>
                            <h4 class="text-2xl font-bold text-blue-600 mb-1">Rp {{ number_format($payment->wealth_amount, 0, ',', '.') }}</h4>
                            <small class="text-gray-500">Total kekayaan yang dizakatkan</small>
                        </div>
                        @endif
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <h6 class="text-sm font-medium text-gray-600 mb-2">Zakat Wajib</h6>
                            <h4 class="text-2xl font-bold text-yellow-600 mb-1">Rp {{ number_format($payment->zakat_amount ?? 0, 0, ',', '.') }}</h4>
                            <small class="text-gray-500">Jumlah zakat yang wajib</small>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <h6 class="text-sm font-medium text-gray-600 mb-2">Jumlah Dibayar</h6>
                            <h4 class="text-2xl font-bold text-green-600 mb-1">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</h4>
                            <small class="text-gray-500">Total yang dibayarkan</small>
                        </div>
                    </div>

                    @if($payment->zakat_amount && $payment->paid_amount > $payment->zakat_amount)
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bi bi-info-circle text-blue-600 mr-2 mt-0.5"></i>
                            <div>
                                <strong class="text-blue-900">Kelebihan Pembayaran:</strong>
                                <p class="text-blue-800 mt-1">Rp {{ number_format($payment->paid_amount - $payment->zakat_amount, 0, ',', '.') }}</p>
                                <small class="text-blue-700">Kelebihan ini dapat dianggap sebagai infaq atau shodaqoh.</small>
                            </div>
                        </div>
                    </div>
                    @elseif($payment->zakat_amount && $payment->paid_amount < $payment->zakat_amount)
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bi bi-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
                            <div>
                                <strong class="text-yellow-900">Kekurangan Pembayaran:</strong>
                                <p class="text-yellow-800 mt-1">Rp {{ number_format($payment->zakat_amount - $payment->paid_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @elseif($payment->zakat_amount)
                    <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bi bi-check-circle text-green-600 mr-2 mt-0.5"></i>
                            <div>
                                <strong class="text-green-900">Pembayaran Pas:</strong>
                                <p class="text-green-800 mt-1">Jumlah yang dibayar sesuai dengan kewajiban zakat.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes Section -->
            @if($payment->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-sticky mr-2"></i>
                        Catatan
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $payment->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Muzakki Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-person-circle mr-2"></i>
                        Informasi Muzakki
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="bg-purple-100 rounded-full p-4 inline-flex items-center justify-center mb-3">
                            <i class="bi bi-person-fill text-3xl text-purple-600"></i>
                        </div>
                        <h5 class="font-semibold text-gray-900 mb-2">{{ $payment->muzakki->name }}</h5>
                        @if(!$payment->is_guest_payment)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Terdaftar
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Guest
                        </span>
                        @endif
                    </div>

                    <div class="space-y-3 border-t pt-4">
                        @if($payment->muzakki->email)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Email</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $payment->muzakki->email }}</p>
                        </div>
                        @endif
                        @if($payment->muzakki->phone)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Telepon</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $payment->muzakki->phone }}</p>
                        </div>
                        @endif
                        @if($payment->muzakki->address)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Alamat</label>
                            <p class="text-sm text-gray-900 mt-1">{{ Str::limit($payment->muzakki->address, 50) }}</p>
                        </div>
                        @endif
                        @if($payment->muzakki->city)
                        <div>
                            <label class="text-xs font-medium text-gray-500">Kota</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $payment->muzakki->city }}</p>
                        </div>
                        @endif
                    </div>

                    @if(!$payment->is_guest_payment && $payment->muzakki->user)
                    <div class="border-t pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Akun Pengguna:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $payment->muzakki->user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $payment->muzakki->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->role === 'admin')
                    <div class="border-t pt-4 mt-4">
                        <a href="{{ route('muzakki.show', $payment->muzakki) }}" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                            <i class="bi bi-eye mr-2"></i> Lihat Detail Muzakki
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="bi bi-clock-history mr-2"></i>
                        Timeline Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <!-- Created -->
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-blue-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Pembayaran Dibuat</h6>
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        @if($payment->status === 'completed')
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-green-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Pembayaran Selesai</h6>
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->payment_date->format('d F Y, H:i') }} WIB</p>
                                @if($payment->receivedBy)
                                <p class="text-xs text-gray-500 mt-1">Diterima oleh: {{ $payment->receivedBy->name }}</p>
                                @endif
                            </div>
                        </div>
                        @elseif($payment->status === 'pending')
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-yellow-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Menunggu Konfirmasi</h6>
                                <p class="text-xs text-gray-500 mt-1">Pembayaran sedang diproses</p>
                            </div>
                        </div>
                        @elseif($payment->status === 'cancelled')
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full bg-red-600 border-2 border-white shadow"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h6 class="text-sm font-semibold text-gray-900">Pembayaran Dibatalkan</h6>
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->updated_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Actions (Admin Only) -->
    @if(auth()->user()->role === 'admin' && $payment->status !== 'completed')
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-yellow-900 mb-4 flex items-center">
            <i class="bi bi-tools mr-2"></i>
            Aksi Pembayaran
        </h3>
        <div class="flex gap-3">
            @if($payment->status === 'pending')
            <form action="{{ route('payments.update', $payment) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" 
                    onclick="return confirm('Konfirmasi pembayaran ini sebagai selesai?')"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bi bi-check-circle mr-2"></i>
                    Konfirmasi Pembayaran
                </button>
            </form>

            <form action="{{ route('payments.update', $payment) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" 
                    onclick="return confirm('Batalkan pembayaran ini?')"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-x-circle mr-2"></i>
                    Batalkan Pembayaran
                </button>
            </form>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Copy payment code functionality
        document.querySelectorAll('.font-mono').forEach(element => {
            element.style.cursor = 'pointer';
            element.title = 'Klik untuk menyalin';
            element.addEventListener('click', function() {
                navigator.clipboard.writeText(this.textContent).then(function() {
                    // Show notification if needed
                });
            });
        });
    });
</script>
@endpush
