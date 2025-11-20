@extends('layouts.app')

@section('page-title', 'Tambah Akun Bank - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.bank-accounts') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h5 class="text-xl font-semibold text-gray-900 mb-1">Tambah Akun Bank</h5>
            <p class="text-sm text-gray-600 mb-0">Simpan informasi rekening bank Anda untuk transaksi zakat</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <form method="POST" action="{{ route('dashboard.bank-accounts.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" placeholder="Contoh: BSI" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                <input type="text" name="account_number" value="{{ old('account_number') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" placeholder="Masukkan nomor rekening" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama</label>
                <input type="text" name="account_holder" value="{{ old('account_holder') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" placeholder="Nama pemilik rekening" required>
            </div>
            <p class="text-xs text-gray-500">Informasi rekening disimpan aman dan hanya digunakan untuk mempermudah pembayaran zakat Anda.</p>
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('dashboard.bank-accounts') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

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

