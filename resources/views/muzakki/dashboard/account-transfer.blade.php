@extends('layouts.app')

@section('page-title', 'Transfer Campaign Ownership - Dashboard Muzakki')

@section('content')
<div class="py-4 px-4 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.management') }}" class="text-gray-700 mr-3 hover:text-gray-900">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h5 class="text-xl font-semibold text-gray-900 mb-1">Transfer Campaign Ownership</h5>
            <p class="text-sm text-gray-600 mb-0">Alihkan kepemilikan campaign ke akun muzakki lain</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-5">
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg flex items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill text-amber-600 flex-shrink-0 mt-0.5"></i>
            <div class="text-sm text-amber-800">
                <strong>Perhatian!</strong> Setelah transfer, Anda tidak akan lagi memiliki akses penuh terhadap campaign tersebut.
            </div>
        </div>

        <form id="transferOwnershipForm" class="space-y-4">
            <div>
                <label for="campaign_select" class="block text-sm font-medium text-gray-700 mb-2">Pilih Campaign</label>
                <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all" id="campaign_select" name="campaign_select" required>
                    <option value="">Pilih campaign...</option>
                    <option value="1">Campaign Pendidikan Anak Yatim</option>
                    <option value="2">Program Bantuan Pangan</option>
                    <option value="3">Renovasi Masjid</option>
                </select>
            </div>
            <div>
                <label for="new_owner_email" class="block text-sm font-medium text-gray-700 mb-2">Email Pemilik Baru</label>
                <input type="email" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all" id="new_owner_email" name="new_owner_email" placeholder="contoh@email.com" required>
                <div class="text-xs text-gray-500 mt-1">Pemilik baru akan menerima notifikasi melalui email</div>
            </div>
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('dashboard.management') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="button" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:from-green-700 hover:to-green-800 transition-all shadow-md hover:shadow-lg" id="confirmTransferButton">
                    <i class="bi bi-arrow-right-circle mr-1"></i> Transfer Sekarang
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
        const confirmTransferButton = document.getElementById('confirmTransferButton');
        confirmTransferButton?.addEventListener('click', function() {
            const campaignSelect = document.getElementById('campaign_select');
            const emailInput = document.getElementById('new_owner_email');
            const campaignName = campaignSelect.options[campaignSelect.selectedIndex]?.text || '';

            if (!campaignSelect.value || !emailInput.value) {
                alert('Mohon lengkapi semua field yang diperlukan.');
                return;
            }

            if (confirm(`Apakah Anda yakin ingin mentransfer "${campaignName}" ke ${emailInput.value}?`)) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                this.disabled = true;

                setTimeout(() => {
                    alert('Permintaan transfer campaign telah dikirim. Penerima akan mendapatkan email konfirmasi.');
                    document.getElementById('transferOwnershipForm').reset();
                    this.innerHTML = '<i class="bi bi-arrow-right-circle mr-1"></i> Transfer Sekarang';
                    this.disabled = false;
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

