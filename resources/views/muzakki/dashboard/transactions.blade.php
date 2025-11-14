@extends('layouts.app')

@section('page-title', 'Transaksi Saya - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('muzakki.dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h5 class="text-xl font-semibold text-gray-900 mb-0">Transaksi saya</h5>
    </div>

    @if($payments->count() > 0)
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <!-- Bulan -->
            <h6 class="font-semibold text-purple-600 mb-4">
                {{ now()->translatedFormat('F Y') }}
            </h6>

            <!-- Daftar Transaksi -->
            @foreach($payments as $payment)
            <div class="p-4 mb-3 rounded-xl border border-gray-100 hover:bg-purple-50 hover:border-purple-200 transition-all duration-200 {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <small class="text-gray-500 block">
                            Donasi • {{ $payment->payment_date->translatedFormat('d F Y') }}
                        </small>
                        <p class="font-semibold text-gray-900 mb-1 mt-1 text-base">
                            {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($payment->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                        @elseif($payment->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Menunggu Pembayaran</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">{{ ucfirst($payment->status) }}</span>
                        @endif
                        <p class="font-semibold mt-2 mb-0 text-base text-gray-900">
                            Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="flex justify-center mt-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-12 text-center">
            <i class="bi bi-credit-card text-6xl text-gray-400 mb-4 block"></i>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Transaksi</h4>
            <p class="text-gray-600 mb-6">Anda belum melakukan pembayaran zakat.</p>
            <a href="{{ route('program') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium">
                <i class="bi bi-plus-circle mr-2"></i> Bayar Zakat Sekarang
            </a>
        </div>
    </div>
    @endif

    <!-- Bottom Navigation -->
    <div class="bg-white rounded-t-xl shadow-lg fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-4xl z-50 border-t border-gray-200">
        <div class="flex justify-around items-center text-center py-4">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 no-underline">
                <i class="bi bi-house text-xl block mb-1"></i>
                <small class="text-xs">Home</small>
            </a>
            <a href="{{ route('muzakki.donation') }}" class="text-gray-700 hover:text-gray-900 no-underline">
                <i class="bi bi-heart text-xl block mb-1"></i>
                <small class="text-xs">Donasi</small>
            </a>
            <a href="{{ route('muzakki.fundraising') }}" class="text-gray-700 hover:text-gray-900 no-underline">
                <i class="bi bi-box-seam text-xl block mb-1"></i>
                <small class="text-xs">Galang Dana</small>
            </a>
            <a href="{{ route('muzakki.amalanku') }}" class="text-gray-700 hover:text-gray-900 no-underline">
                <i class="bi bi-person text-xl block mb-1"></i>
                <small class="text-xs">Amalanku</small>
            </a>
        </div>
    </div>
</div>

<style>
    body {
        padding-bottom: 80px !important;
    }
</style>
@endsection
