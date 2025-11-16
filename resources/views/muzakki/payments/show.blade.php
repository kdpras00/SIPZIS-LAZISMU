@extends('layouts.app')

@section('page-title', 'Detail Transaksi - ' . $payment->payment_code)

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 mb-3">
            <i class="bi bi-arrow-left text-xl"></i>
            <span class="ml-2">Kembali</span>
        </a>
        <div>
            <h5 class="text-xl font-semibold text-gray-900 mb-0">Detail Transaksi</h5>
            <p class="text-sm text-gray-500 mt-1">{{ $payment->payment_code }}</p>
        </div>
    </div>

    <!-- Payment Status Card -->
    <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h6 class="text-white font-semibold mb-1">Status Pembayaran</h6>
                    <div class="mt-2">
                        @switch($payment->status)
                        @case('completed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                            <i class="bi bi-check-circle mr-2"></i>Selesai
                        </span>
                        @break
                        @case('pending')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-700">
                            <i class="bi bi-clock mr-2"></i>Menunggu Pembayaran
                        </span>
                        @break
                        @case('cancelled')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                            <i class="bi bi-x-circle mr-2"></i>Dibatalkan
                        </span>
                        @break
                        @default
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                            {{ ucfirst($payment->status) }}
                        </span>
                        @endswitch
                    </div>
                </div>
                @if($payment->status === 'completed')
                <a href="{{ route('payments.receipt', $payment) }}" target="_blank" 
                    class="inline-flex items-center px-4 py-2 bg-white text-green-600 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    <i class="bi bi-receipt mr-2"></i>Kwitansi
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Details Card -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <h6 class="font-semibold text-gray-900 mb-4 flex items-center">
                <i class="bi bi-info-circle text-green-600 mr-2"></i>
                Informasi Pembayaran
            </h6>
            <div class="space-y-4">
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <span class="text-gray-600 text-sm">Tanggal Pembayaran</span>
                    <span class="text-gray-900 font-medium text-right">{{ $payment->payment_date->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <span class="text-gray-600 text-sm">Jenis Zakat</span>
                    <span class="text-gray-900 font-medium text-right">{{ $payment->zakatType->name ?? 'Donasi Umum' }}</span>
                </div>
                @php
                    $campaign = $payment->campaign();
                @endphp
                @if($campaign || $payment->program || $payment->programType)
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <span class="text-gray-600 text-sm">Program/Campaign</span>
                    <span class="text-gray-900 font-medium text-right">
                        @if($campaign)
                            {{ $campaign->title }}
                        @elseif($payment->program)
                            {{ $payment->program->name }}
                        @elseif($payment->programType)
                            {{ $payment->programType->name }}
                        @endif
                    </span>
                </div>
                @endif
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <span class="text-gray-600 text-sm">Metode Pembayaran</span>
                    <span class="text-gray-900 font-medium text-right">
                        @switch($payment->payment_method)
                        @case('cash')
                        <span class="inline-flex items-center">
                            <i class="bi bi-cash mr-1"></i>Tunai
                        </span>
                        @break
                        @case('transfer')
                        <span class="inline-flex items-center">
                            <i class="bi bi-bank mr-1"></i>Transfer Bank
                        </span>
                        @break
                        @case('online')
                        <span class="inline-flex items-center">
                            <i class="bi bi-globe mr-1"></i>Online
                        </span>
                        @break
                        @default
                        {{ ucfirst($payment->payment_method) }}
                        @endswitch
                    </span>
                </div>
                @if($payment->payment_reference)
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <span class="text-gray-600 text-sm">Referensi</span>
                    <span class="text-gray-900 font-mono text-sm text-right">{{ $payment->payment_reference }}</span>
                </div>
                @endif
                @if($payment->receipt_number)
                <div class="flex justify-between items-start py-3">
                    <span class="text-gray-600 text-sm">No. Kwitansi</span>
                    <span class="text-gray-900 font-mono text-sm text-right">{{ $payment->receipt_number }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Amount Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md mb-6 overflow-hidden">
        <div class="p-6 text-white">
            <h6 class="text-green-100 text-sm font-medium mb-2">Jumlah Pembayaran</h6>
            <h3 class="text-3xl font-bold mb-4">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</h3>
            @if($payment->zakat_amount && $payment->zakat_amount > 0)
            <div class="bg-white bg-opacity-20 rounded-lg p-3 mt-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-green-100">Zakat Wajib</span>
                    <span class="font-semibold">Rp {{ number_format($payment->zakat_amount, 0, ',', '.') }}</span>
                </div>
                @if($payment->paid_amount > $payment->zakat_amount)
                <div class="flex justify-between items-center text-sm mt-2 pt-2 border-t border-white border-opacity-30">
                    <span class="text-green-100">Kelebihan (Infaq/Shadaqah)</span>
                    <span class="font-semibold">Rp {{ number_format($payment->paid_amount - $payment->zakat_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Timeline Card -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <h6 class="font-semibold text-gray-900 mb-4 flex items-center">
                <i class="bi bi-clock-history text-green-600 mr-2"></i>
                Timeline
            </h6>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-yellow-500 mt-1.5"></div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900">Pembayaran Dibuat</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
                @if($payment->status === 'completed')
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-green-500 mt-1.5"></div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900">Pembayaran Selesai</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $payment->payment_date->translatedFormat('d F Y, H:i') }} WIB</p>
                        @if($payment->receivedBy)
                        <p class="text-xs text-gray-500 mt-1">Diterima oleh: {{ $payment->receivedBy->name }}</p>
                        @endif
                    </div>
                </div>
                @elseif($payment->status === 'pending')
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-amber-500 mt-1.5"></div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900">Menunggu Konfirmasi</p>
                        <p class="text-xs text-gray-500 mt-1">Pembayaran sedang diproses</p>
                    </div>
                </div>
                @elseif($payment->status === 'cancelled')
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-red-500 mt-1.5"></div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900">Pembayaran Dibatalkan</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $payment->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notes Card -->
    @if($payment->notes)
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <h6 class="font-semibold text-gray-900 mb-3 flex items-center">
                <i class="bi bi-sticky text-yellow-600 mr-2"></i>
                Catatan
            </h6>
            <p class="text-gray-700 text-sm">{{ $payment->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="flex justify-center mt-6">
        <a href="{{ route('dashboard') }}" 
            class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

<style>
    body {
        padding-bottom: 20px !important;
    }
</style>
@endsection

