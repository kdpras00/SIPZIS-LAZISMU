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

    <!-- Info Card -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="bi bi-info-circle text-blue-600 mr-2 mt-0.5"></i>
                    <p class="text-sm text-blue-800 m-0">Fitur donasi rutin akan segera tersedia. Anda dapat mengatur donasi otomatis setiap bulan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-12 text-center">
            <i class="bi bi-calendar-check text-6xl text-gray-400 mb-4 block"></i>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Donasi Rutin</h4>
            <p class="text-gray-600 mb-6">Fitur ini memungkinkan Anda untuk membuat donasi otomatis setiap bulan.</p>
            <button class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium" data-bs-toggle="modal" data-bs-target="#createRecurringModal">
                <i class="bi bi-plus-circle mr-2"></i>Buat Donasi Rutin
            </button>
        </div>
    </div>

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

<!-- Create Recurring Donation Modal -->
<div class="modal fade" id="createRecurringModal" tabindex="-1" aria-labelledby="createRecurringModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0">
            <div class="modal-header bg-gradient-to-r from-green-50 to-green-100 border-0 rounded-t-2xl px-6 py-5">
                <h5 class="modal-title font-semibold text-gray-900" id="createRecurringModalLabel">Buat Donasi Rutin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-6 py-5">
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-4 rounded-lg">
                    <div class="flex items-start">
                        <i class="bi bi-exclamation-triangle text-amber-600 mr-2 mt-0.5"></i>
                        <p class="text-sm text-amber-800 m-0">Fitur ini sedang dalam pengembangan.</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-3">Fitur donasi rutin akan memungkinkan Anda untuk:</p>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Mengatur donasi otomatis setiap bulan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Memilih program zakat yang akan didonasikan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Mengatur jumlah donasi tetap atau variabel</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Mengelola jadwal donasi</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-0 px-6 py-4">
                <button type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        padding-bottom: 80px !important;
    }
</style>
@endsection
