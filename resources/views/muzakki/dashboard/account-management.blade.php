@extends('layouts.app')

@section('page-title', 'Manajemen Akun - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h5 class="text-xl font-semibold text-gray-900 mb-0">Manajemen akun</h5>
    </div>

    <!-- Account Settings Menu -->
    <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden">
        <div class="divide-y divide-gray-100">
            <!-- Transfer Campaign Ownership -->
            <a href="{{ route('dashboard.management.transfer-account') }}" class="flex items-center gap-4 p-5 hover:bg-green-50 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 9L21 3M21 3H15M21 3L13 11M10 5H7.8C6.11984 5 5.27976 5 4.63803 5.32698C4.07354 5.6146 3.6146 6.07354 3.32698 6.63803C3 7.27976 3 8.11984 3 9.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21H14.2C15.8802 21 16.7202 21 17.362 20.673C17.9265 20.3854 18.3854 19.9265 18.673 19.362C19 18.7202 19 17.8802 19 16.2V14" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <div class="font-medium text-gray-900 mb-1">Transfer Campaign Ownership</div>
                    <p class="text-sm text-gray-600 m-0">Transfer kepemilikan campaign ke pengguna lain</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-all duration-300 group-hover:translate-x-1 group-hover:text-green-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Delete Account -->
            <a href="{{ route('dashboard.management.delete-account') }}" class="flex items-center gap-4 p-5 hover:bg-red-50 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <div class="font-medium text-gray-900 mb-1">Hapus Akun</div>
                    <p class="text-sm text-gray-600 m-0">Hapus akun secara permanen dari sistem</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-all duration-300 group-hover:translate-x-1 group-hover:text-red-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
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

<style>
    body {
        padding-bottom: 80px !important;
    }
</style>
@endsection
