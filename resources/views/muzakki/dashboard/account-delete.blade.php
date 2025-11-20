@extends('layouts.app')

@section('page-title', 'Hapus Akun - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.management') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h5 class="text-xl font-semibold text-gray-900 mb-1">Hapus Akun</h5>
            <p class="text-sm text-gray-600 mb-0">Tindakan permanen untuk menghapus seluruh data akun Anda</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-5">
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill text-red-600 flex-shrink-0 mt-0.5"></i>
            <div class="text-sm text-red-800">
                <strong>Peringatan!</strong> Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
            </div>
        </div>
        <p class="text-gray-700">Dengan menghapus akun, semua data berikut akan dihapus secara permanen:</p>
        <ul class="space-y-1 text-gray-700">
            <li class="flex items-start gap-2">
                <span>•</span>
                <span>Informasi profil dan data pribadi</span>
            </li>
            <li class="flex items-start gap-2">
                <span>•</span>
                <span>Riwayat transaksi dan donasi</span>
            </li>
            <li class="flex items-start gap-2">
                <span>•</span>
                <span>Campaign yang Anda buat</span>
            </li>
            <li class="flex items-start gap-2">
                <span>•</span>
                <span>Semua preferensi dan pengaturan</span>
            </li>
        </ul>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm font-semibold mb-2 text-gray-900">Sebelum melanjutkan, pastikan Anda telah:</p>
            <ul class="text-sm space-y-1 text-gray-700">
                <li class="flex items-start gap-2">
                    <span>•</span>
                    <span>Mengunduh atau mencatat semua informasi penting</span>
                </li>
                <li class="flex items-start gap-2">
                    <span>•</span>
                    <span>Menyelesaikan semua transaksi yang tertunda</span>
                </li>
                <li class="flex items-start gap-2">
                    <span>•</span>
                    <span>Mentransfer kepemilikan campaign jika diperlukan</span>
                </li>
            </ul>
        </div>

        <form id="deleteAccountForm" class="space-y-4">
            <div class="flex items-center">
                <input class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" type="checkbox" id="confirmDelete" required>
                <label class="ml-2 text-sm text-gray-700" for="confirmDelete">
                    Saya memahami konsekuensinya dan ingin menghapus akun saya secara permanen
                </label>
            </div>
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('dashboard.management') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="button" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" id="confirmDeleteButton" disabled>
                    <i class="bi bi-trash mr-1"></i> Hapus Akun Sekarang
                </button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmDeleteCheckbox = document.getElementById('confirmDelete');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');

        confirmDeleteCheckbox?.addEventListener('change', function() {
            confirmDeleteButton.disabled = !this.checked;
        });

        confirmDeleteButton?.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus akun Anda secara permanen? Tindakan ini tidak dapat dibatalkan.')) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...';
                this.disabled = true;

                setTimeout(() => {
                    alert('Fitur penghapusan akun akan segera tersedia. Silakan hubungi administrator untuk bantuan.');
                    document.getElementById('deleteAccountForm').reset();
                    this.innerHTML = '<i class="bi bi-trash mr-1"></i> Hapus Akun Sekarang';
                    this.disabled = true;
                }, 1500);
            }
        });
    });
</script>
@endpush

<style>
    body {
        padding-bottom: 80px !important;
    }
</style>
@endsection

