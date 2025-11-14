@extends('layouts.app')

@section('page-title', 'Dashboard Muzakki')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold mb-1">Assalamualaikum, {{ $muzakki->name }}!</h2>
            <p class="text-gray-600">Selamat datang di dashboard muzakki Anda</p>
        </div>
        <div class="text-right">
            <small class="text-gray-500">{{ now()->format('d F Y') }}</small>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg text-white p-6">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="text-green-100 text-xs font-semibold uppercase mb-1">Total Zakat</h6>
                <h4 class="text-2xl font-bold mb-0">Rp {{ number_format($stats['total_zakat_paid'], 0, ',', '.') }}</h4>
                <small class="text-green-100">Sepanjang masa</small>
            </div>
            <div class="self-center">
                <i class="bi bi-currency-dollar text-4xl text-green-200"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg text-white p-6">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="text-yellow-100 text-xs font-semibold uppercase mb-1">Zakat {{ date('Y') }}</h6>
                <h4 class="text-2xl font-bold mb-0">Rp {{ number_format($stats['zakat_this_year'], 0, ',', '.') }}</h4>
                <small class="text-yellow-100">Tahun ini</small>
            </div>
            <div class="self-center">
                <i class="bi bi-calendar text-4xl text-yellow-200"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg text-white p-6">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="text-blue-100 text-xs font-semibold uppercase mb-1">Jumlah Pembayaran</h6>
                <h4 class="text-2xl font-bold mb-0">{{ number_format($stats['payment_count']) }}</h4>
                <small class="text-blue-100">Kali pembayaran</small>
            </div>
            <div class="self-center">
                <i class="bi bi-credit-card text-4xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg text-white p-6">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="text-purple-100 text-xs font-semibold uppercase mb-1">Terakhir Bayar</h6>
                @if($stats['last_payment'])
                <h6 class="text-lg font-bold mb-0">{{ $stats['last_payment']->payment_date->format('d M Y') }}</h6>
                <small class="text-purple-100">{{ $stats['last_payment']->programType ? $stats['last_payment']->programType->name : 'Donasi Umum' }}</small>
                @else
                <h6 class="text-lg font-bold mb-0">Belum ada</h6>
                <small class="text-purple-100">Pembayaran</small>
                @endif
            </div>
            <div class="self-center">
                <i class="bi bi-clock text-4xl text-purple-200"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Navigation Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('dashboard.transactions') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center p-6 block no-underline h-full">
        <i class="bi bi-credit-card text-5xl text-blue-500 mb-3 block"></i>
        <h5 class="text-lg font-semibold text-gray-900 mb-2">Transaksi Saya</h5>
        <p class="text-gray-600 text-sm">Lihat riwayat pembayaran zakat Anda</p>
    </a>

    <a href="{{ route('dashboard.recurring') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center p-6 block no-underline h-full">
        <i class="bi bi-arrow-repeat text-5xl text-green-500 mb-3 block"></i>
        <h5 class="text-lg font-semibold text-gray-900 mb-2">Donasi Rutin</h5>
        <p class="text-gray-600 text-sm">Atur donasi otomatis setiap bulan</p>
    </a>

    <a href="{{ route('dashboard.bank-accounts') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center p-6 block no-underline h-full">
        <i class="bi bi-bank text-5xl text-cyan-500 mb-3 block"></i>
        <h5 class="text-lg font-semibold text-gray-900 mb-2">Akun Bank</h5>
        <p class="text-gray-600 text-sm">Kelola rekening bank Anda</p>
    </a>

    <a href="{{ route('dashboard.management') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center p-6 block no-underline h-full">
        <i class="bi bi-person-gear text-5xl text-amber-500 mb-3 block"></i>
        <h5 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Akun</h5>
        <p class="text-gray-600 text-sm">Kelola profil dan pengaturan akun</p>
    </a>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
        <h5 class="text-lg font-semibold text-gray-900 mb-0">
            <i class="bi bi-clock-history mr-2 text-blue-500"></i> Aktivitas Terbaru
        </h5>
    </div>
    <div class="p-0">
        @if($recentPayments->count() > 0)
        @foreach($recentPayments as $payment)
        <div class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
            <div>
                <h6 class="font-semibold text-gray-900 mb-1">{{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}</h6>
                <small class="text-gray-500">{{ $payment->payment_code }}</small>
            </div>
            <div class="text-right">
                <div class="font-bold text-gray-900">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                <small class="text-gray-500">{{ $payment->payment_date->format('d M Y') }}</small>
            </div>
        </div>
        @endforeach
        @else
        <div class="p-6 text-center text-gray-500">
            <i class="bi bi-inbox text-5xl block mb-2"></i>
            Belum ada aktivitas
        </div>
        @endif
    </div>
</div>
@endsection