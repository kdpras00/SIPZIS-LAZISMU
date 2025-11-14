@extends('layouts.app')

@section('page-title', 'Galang Dana - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('muzakki.dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h5 class="text-xl font-semibold text-gray-900 mb-0">Galang Dana</h5>
        </div>
        <a href="#" class="px-4 py-2 bg-green-600 text-white text-sm rounded-full hover:bg-green-700 transition-colors font-medium no-underline">
            <i class="bi bi-plus-circle mr-1"></i>Buat Campaign
        </a>
    </div>

    <!-- Campaigns List -->
    @if($campaigns->count() > 0)
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <h6 class="font-semibold text-gray-900 mb-4">Campaign Saya</h6>
            @foreach($campaigns as $campaign)
            <div class="p-4 mb-4 rounded-xl border border-gray-100 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 hover:translate-x-1 last:mb-0">
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <h6 class="font-semibold text-gray-900 mb-1">{{ $campaign->title }}</h6>
                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($campaign->description, 100) }}</p>
                        <div class="flex items-center gap-4 mb-2">
                            <small class="text-green-600 font-semibold">
                                Terkumpul: Rp {{ number_format($campaign->total_collected ?? 0, 0, ',', '.') }}
                            </small>
                            <small class="text-gray-500">
                                Target: Rp {{ number_format($campaign->target_amount ?? 0, 0, ',', '.') }}
                            </small>
                        </div>
                        <div class="mt-2">
                            @if($campaign->status === 'published')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                            @elseif($campaign->status === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Draft</span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">{{ ucfirst($campaign->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-gray-400 ml-2 text-xl"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-12 text-center">
            <i class="bi bi-box-seam text-6xl text-gray-400 mb-4 block"></i>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Campaign</h4>
            <p class="text-gray-600 mb-6">Anda belum membuat campaign galang dana.</p>
            <a href="#" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium no-underline">
                <i class="bi bi-plus-circle mr-2"></i>Buat Campaign Pertama
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
            <a href="{{ route('muzakki.fundraising') }}" class="text-green-600 hover:text-green-700 no-underline">
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

