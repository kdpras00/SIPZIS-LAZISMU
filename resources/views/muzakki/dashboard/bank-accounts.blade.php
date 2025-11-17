@extends('layouts.app')

@section('page-title', 'Akun Bank Saya - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="text-gray-700 mr-3 hover:text-gray-900">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h5 class="text-xl font-semibold text-gray-900 mb-0">Akun bank</h5>
        </div>
        <button class="px-4 py-2 bg-green-600 text-white text-sm rounded-full hover:bg-green-700 transition-colors font-medium" data-bs-toggle="modal" data-bs-target="#addBankAccountModal">
            <i class="bi bi-plus-circle mr-1"></i>Tambah
        </button>
    </div>

    @if($bankAccounts->isEmpty())
        <div class="bg-white rounded-xl shadow-md mb-6">
            <div class="p-12 text-center">
                <i class="bi bi-bank text-6xl text-gray-400 mb-4 block"></i>
                <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum ada akun bank</h4>
                <p class="text-gray-600 mb-6">Simpan informasi rekening bank Anda untuk memudahkan pembayaran zakat.</p>
                <button class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium" data-bs-toggle="modal" data-bs-target="#addBankAccountModal">
                    <i class="bi bi-plus-circle mr-2"></i>Tambah Akun Bank
                </button>
            </div>
        </div>
    @else
        <div class="space-y-4 mb-6">
            @foreach($bankAccounts as $account)
                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h6 class="text-lg font-semibold text-gray-900 mb-0">{{ $account->bank_name }}</h6>
                            @if($account->is_primary)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Rekening Utama</span>
                            @endif
                        </div>
                        <p class="text-gray-700 font-mono text-sm mb-1">{{ $account->account_number }}</p>
                        <p class="text-gray-500 text-sm mb-0">{{ $account->account_holder }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(!$account->is_primary)
                            <form action="{{ route('dashboard.bank-accounts.set-primary', $account) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    Jadikan Utama
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('dashboard.bank-accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Hapus akun bank ini?')">
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

<!-- Add Bank Account Modal -->
<div class="modal fade" id="addBankAccountModal" tabindex="-1" aria-labelledby="addBankAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0">
            <div class="modal-header bg-gradient-to-r from-cyan-50 to-cyan-100 border-0 rounded-t-2xl px-6 py-5">
                <h5 class="modal-title font-semibold text-gray-900" id="addBankAccountModalLabel">Tambah Akun Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('dashboard.bank-accounts.store') }}">
                @csrf
                <div class="modal-body px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                        <input type="text" name="bank_name" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" placeholder="Contoh: BSI" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                        <input type="text" name="account_number" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" placeholder="Masukkan nomor rekening" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama</label>
                        <input type="text" name="account_holder" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" placeholder="Nama pemilik rekening" required>
                    </div>
                    <p class="text-xs text-gray-500">Informasi rekening disimpan aman dan hanya digunakan untuk mempermudah pembayaran zakat Anda.</p>
                </div>
                <div class="modal-footer border-0 px-6 py-4">
                    <button type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        padding-bottom: 80px !important;
    }
</style>

@endsection
