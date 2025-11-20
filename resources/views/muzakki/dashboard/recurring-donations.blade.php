@extends('layouts.app')

@section('page-title', 'Donasi Rutin Saya - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h5 class="text-xl font-semibold text-gray-900 mb-0">Donasi rutin saya</h5>
    </div>

    @if($recurringDonations->isEmpty())
        <div class="bg-white rounded-xl shadow-md mb-6">
            <div class="p-12 text-center">
                <i class="bi bi-calendar-check text-6xl text-gray-400 mb-4 block"></i>
                <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum ada donasi rutin</h4>
                <p class="text-gray-600 mb-6">Buat donasi otomatis agar ibadah berbagi tetap konsisten.</p>
                <a href="{{ route('dashboard.recurring.create') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium">
                    <i class="bi bi-plus-circle mr-2"></i>Buat Donasi Rutin
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4 mb-6">
            @foreach($recurringDonations as $donation)
                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-gray-500 mb-1">{{ ucfirst($donation->frequency) }}</p>
                        <h6 class="text-lg font-semibold text-gray-900 mb-1">
                            {{ $donation->program?->name ?? 'Program Pilihan' }}
                        </h6>
                        <p class="text-emerald-600 font-semibold mb-1">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-sm mb-0">Mulai {{ optional($donation->start_date)->translatedFormat('d F Y') ?? 'segera' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('dashboard.recurring-donations.toggle', $donation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 text-sm font-medium {{ $donation->is_active ? 'text-yellow-600 border border-yellow-200 hover:bg-yellow-50' : 'text-green-600 border border-green-200 hover:bg-green-50' }} rounded-lg transition-colors">
                                {{ $donation->is_active ? 'Jeda' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('dashboard.recurring-donations.destroy', $donation) }}" method="POST" onsubmit="return confirm('Hapus donasi rutin ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-right mb-6">
            <a href="{{ route('dashboard.recurring.create') }}" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white rounded-full hover:bg-green-700 text-sm font-medium">
                <i class="bi bi-plus-circle mr-2"></i>Tambah lagi
            </a>
        </div>
    @endif

    <!-- Bottom Navigation -->
    <div class="bg-white rounded-t-xl shadow-lg fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-4xl z-50 border-t border-gray-200">
        <div class="flex justify-around items-center text-center py-4">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 no-underline">
                <i class="bi bi-house text-xl block mb-1"></i>
                <small class="text-xs">Home</small>
            </a>
        <a href="{{ route('donation') }}" class="text-gray-700 hover:text-gray-900 no-underline">
        <i class="bi bi-heart text-xl block mb-1"></i>
        <small class="text-xs">Donasi</small>
    </a>
    <a href="{{ route('fundraising') }}" class="text-gray-700 hover:text-gray-900 no-underline">
        <i class="bi bi-box-seam text-xl block mb-1"></i>
        <small class="text-xs">Galang Dana</small>
    </a>
    <a href="{{ route('amalanku') }}" class="text-gray-700 hover:text-gray-900 no-underline">
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
