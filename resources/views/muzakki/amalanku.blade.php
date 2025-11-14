@extends('layouts.app')

@section('page-title', 'Amalanku - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('muzakki.dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h5 class="text-xl font-semibold text-gray-900 mb-0">Amalanku</h5>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <h6 class="font-semibold text-gray-900 mb-4">Ringkasan Amal</h6>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <h4 class="font-bold text-green-600 mb-1 text-2xl">{{ $stats['total_count'] }}</h4>
                    <small class="text-gray-600">Total Donasi</small>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <h4 class="font-bold text-green-600 mb-1 text-lg">Rp {{ number_format($stats['total_donated'], 0, ',', '.') }}</h4>
                    <small class="text-gray-600">Total Nominal</small>
                </div>
            </div>
            <div class="text-center p-4 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl">
                <h5 class="font-bold mb-1 text-xl">Rp {{ number_format($stats['this_year'], 0, ',', '.') }}</h5>
                <small>Tahun Ini</small>
            </div>
        </div>
    </div>

    <!-- Recent Donations -->
    @if($payments->count() > 0)
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <h6 class="font-semibold text-gray-900 mb-4">Donasi Terakhir</h6>
            @foreach($payments as $payment)
            <div class="p-4 mb-3 rounded-xl border border-gray-100 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 last:mb-0">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="font-semibold text-gray-900 mb-1">
                            {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}
                        </h6>
                        <small class="text-gray-500">
                            {{ $payment->payment_date->translatedFormat('d F Y') }}
                        </small>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600 mb-1">
                            Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                        </p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="text-center mt-4">
                <a href="{{ route('muzakki.dashboard.transactions') }}" class="inline-flex items-center px-5 py-2 border-2 border-green-600 text-green-600 rounded-full hover:bg-green-50 transition-colors text-sm font-medium no-underline">
                    Lihat Semua Transaksi
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-12 text-center">
            <i class="bi bi-heart text-6xl text-gray-400 mb-4 block"></i>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Donasi</h4>
            <p class="text-gray-600 mb-6">Mulai berdonasi untuk melihat ringkasan amal Anda.</p>
            <a href="{{ route('muzakki.donation') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium no-underline">
                <i class="bi bi-plus-circle mr-2"></i>Mulai Donasi
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
            <a href="{{ route('muzakki.amalanku') }}" class="text-green-600 hover:text-green-700 no-underline">
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

