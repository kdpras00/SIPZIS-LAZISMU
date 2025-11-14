@extends('layouts.app')

@section('page-title', 'Donasi - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('muzakki.dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h5 class="text-xl font-semibold text-gray-900 mb-0">Donasi</h5>
    </div>

    <!-- Programs List -->
    @if($programs->count() > 0)
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <h6 class="font-semibold text-gray-900 mb-4">Program Donasi</h6>
            @foreach($programs as $program)
            <a href="{{ route('program.show', $program->slug) }}" class="block no-underline mb-4 last:mb-0">
                <div class="p-4 rounded-xl border border-gray-100 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 hover:translate-x-1">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <h6 class="font-semibold text-gray-900 mb-1">{{ $program->name }}</h6>
                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($program->description, 100) }}</p>
                            <div class="flex items-center gap-4">
                                <small class="text-green-600 font-semibold">
                                    Terkumpul: Rp {{ number_format($program->net_total_collected ?? 0, 0, ',', '.') }}
                                </small>
                                <small class="text-gray-500">
                                    Target: Rp {{ number_format($program->total_target ?? 0, 0, ',', '.') }}
                                </small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-gray-400 ml-2 text-xl"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-12 text-center">
            <i class="bi bi-heart text-6xl text-gray-400 mb-4 block"></i>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Program</h4>
            <p class="text-gray-600">Tidak ada program donasi yang tersedia saat ini.</p>
        </div>
    </div>
    @endif

    <!-- Quick Donate Button -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6 text-center">
            <a href="{{ route('program') }}" class="inline-flex items-center px-8 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium">
                <i class="bi bi-plus-circle mr-2"></i>Donasi Sekarang
            </a>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bg-white rounded-t-xl shadow-lg fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-4xl z-50 border-t border-gray-200">
        <div class="flex justify-around items-center text-center py-4">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 no-underline">
                <i class="bi bi-house text-xl block mb-1"></i>
                <small class="text-xs">Home</small>
            </a>
            <a href="{{ route('muzakki.donation') }}" class="text-green-600 hover:text-green-700 no-underline">
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

