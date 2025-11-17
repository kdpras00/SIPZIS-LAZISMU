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
            <!-- Two Factor Authentication -->
            <a href="{{ route('dashboard.two-factor.setup') }}" class="flex items-center gap-4 p-5 hover:bg-blue-50 transition-all duration-300 cursor-pointer group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 8V12M12 16H12.01" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 12L11 14L15 10" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <div class="font-medium text-gray-900 mb-1">Autentikasi Dua Faktor (2FA)</div>
                    <p class="text-sm text-gray-600 m-0">
                        @if(auth()->user()->two_factor_enabled ?? false)
                            <span class="text-green-600 font-semibold">✓ Aktif</span> - Tambahkan lapisan keamanan ekstra
                        @else
                            Aktifkan autentikasi dua faktor untuk keamanan akun
                        @endif
                    </p>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-all duration-300 group-hover:translate-x-1 group-hover:text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Change Password -->
            <a href="#" class="flex items-center gap-4 p-5 hover:bg-purple-50 transition-all duration-300 cursor-pointer group"
                data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 14.5V16.5M7 10.0288C7.47142 10 8.05259 10 8.8 10H15.2C15.9474 10 16.5286 10 17 10.0288M7 10.0288C6.41168 10.0647 5.99429 10.1455 5.63803 10.327C5.07354 10.6146 4.6146 11.0735 4.32698 11.638C4 12.2798 4 13.1198 4 14.8V16.2C4 17.8802 4 18.7202 4.32698 19.362C4.6146 19.9265 5.07354 20.3854 5.63803 20.673C6.27976 21 7.11984 21 8.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V14.8C20 13.1198 20 12.2798 19.673 11.638C19.3854 11.0735 18.9265 10.6146 18.362 10.327C18.0057 10.1455 17.5883 10.0647 17 10.0288M7 10.0288V8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8V10.0288" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <div class="font-medium text-gray-900 mb-1">Ganti Kata Sandi</div>
                    <p class="text-sm text-gray-600 m-0">Perbarui kata sandi untuk keamanan akun</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-all duration-300 group-hover:translate-x-1 group-hover:text-purple-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Transfer Campaign Ownership -->
            <a href="#" class="flex items-center gap-4 p-5 hover:bg-purple-50 transition-all duration-300 cursor-pointer group"
                data-bs-toggle="modal" data-bs-target="#transferOwnershipModal">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 9L21 3M21 3H15M21 3L13 11M10 5H7.8C6.11984 5 5.27976 5 4.63803 5.32698C4.07354 5.6146 3.6146 6.07354 3.32698 6.63803C3 7.27976 3 8.11984 3 9.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21H14.2C15.8802 21 16.7202 21 17.362 20.673C17.9265 20.3854 18.3854 19.9265 18.673 19.362C19 18.7202 19 17.8802 19 16.2V14" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <div class="font-medium text-gray-900 mb-1">Transfer Campaign Ownership</div>
                    <p class="text-sm text-gray-600 m-0">Transfer kepemilikan campaign ke pengguna lain</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-all duration-300 group-hover:translate-x-1 group-hover:text-purple-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Delete Account -->
            <a href="#" class="flex items-center gap-4 p-5 hover:bg-red-50 transition-all duration-300 cursor-pointer group"
                data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
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

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0">
            <div class="modal-header bg-gradient-to-r from-purple-50 to-purple-100 border-0 rounded-t-2xl px-6 py-5">
                <h5 class="modal-title font-semibold text-gray-900" id="changePasswordModalLabel">Ganti Kata Sandi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" id="changePasswordForm">
                @csrf
                @method('PUT')
                <div class="modal-body px-6 py-5">
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                        <div class="relative">
                            <input type="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" id="current_password" name="current_password" placeholder="Masukkan kata sandi saat ini" required>
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700" type="button" id="toggleCurrentPassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" id="new_password" name="new_password" placeholder="Masukkan kata sandi baru" required>
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700" type="button" id="toggleNewPassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <!-- Password Requirements -->
                        <div class="mt-3">
                            <small class="text-gray-600 block mb-2">Kata sandi harus mengandung:</small>
                            <ul class="list-none space-y-1 text-sm">
                                <li class="flex items-center">
                                    <i class="bi bi-x-circle-fill text-gray-400 mr-2" id="length-icon"></i>
                                    <span id="length-text">Minimal 8 karakter</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="bi bi-x-circle-fill text-gray-400 mr-2" id="uppercase-icon"></i>
                                    <span id="uppercase-text">Minimal 1 huruf kapital</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="bi bi-x-circle-fill text-gray-400 mr-2" id="number-icon"></i>
                                    <span id="number-text">Minimal 1 angka</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ulangi kata sandi baru" required>
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700" type="button" id="toggleConfirmPassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="password-match-feedback" class="mt-2 text-sm hidden"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-6 py-4">
                    <button type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" id="changePasswordBtn" disabled>
                        <i class="bi bi-check-circle mr-1"></i> Ganti Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transfer Ownership Modal -->
<div class="modal fade" id="transferOwnershipModal" tabindex="-1" aria-labelledby="transferOwnershipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0">
            <div class="modal-header bg-gradient-to-r from-purple-50 to-purple-100 border-0 rounded-t-2xl px-6 py-5">
                <h5 class="modal-title font-semibold text-gray-900" id="transferOwnershipModalLabel">Transfer Campaign Ownership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-6 py-5">
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-4 rounded-lg flex items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <div class="text-sm text-amber-800">
                        <strong>Perhatian!</strong> Setelah transfer, Anda tidak akan lagi memiliki akses penuh terhadap campaign tersebut.
                    </div>
                </div>
                <form id="transferOwnershipForm">
                    <div class="mb-4">
                        <label for="campaign_select" class="block text-sm font-medium text-gray-700 mb-2">Pilih Campaign</label>
                        <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" id="campaign_select" name="campaign_select" required>
                            <option value="">Pilih campaign...</option>
                            <option value="1">Campaign Pendidikan Anak Yatim</option>
                            <option value="2">Program Bantuan Pangan</option>
                            <option value="3">Renovasi Masjid</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="new_owner_email" class="block text-sm font-medium text-gray-700 mb-2">Email Pemilik Baru</label>
                        <input type="email" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" id="new_owner_email" name="new_owner_email" placeholder="contoh@email.com" required>
                        <div class="text-xs text-gray-500 mt-1">Pemilik baru akan menerima notifikasi melalui email</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-6 py-4">
                <button type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg" id="confirmTransferButton">
                    <i class="bi bi-arrow-right-circle mr-1"></i> Transfer Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0">
            <div class="modal-header bg-gradient-to-r from-red-50 to-red-100 border-0 rounded-t-2xl px-6 py-5">
                <h5 class="modal-title font-semibold text-gray-900" id="deleteAccountModalLabel">Hapus Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-6 py-5">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-lg flex items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 flex-shrink-0 mt-0.5"></i>
                    <div class="text-sm text-red-800">
                        <strong>Peringatan!</strong> Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
                    </div>
                </div>
                <p class="mb-3 text-gray-700">Dengan menghapus akun, semua data berikut akan dihapus secara permanen:</p>
                <ul class="mb-4 space-y-1 text-gray-700">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Informasi profil dan data pribadi</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Riwayat transaksi dan donasi</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Campaign yang Anda buat</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Semua preferensi dan pengaturan</span>
                    </li>
                </ul>
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <p class="text-sm font-semibold mb-2 text-gray-900">Sebelum melanjutkan, pastikan Anda telah:</p>
                    <ul class="text-sm space-y-1 text-gray-700">
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Mengunduh atau mencatat semua informasi penting</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Menyelesaikan semua transaksi yang tertunda</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Mentransfer kepemilikan campaign jika diperlukan</span>
                        </li>
                    </ul>
                </div>
                <div class="flex items-center">
                    <input class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" type="checkbox" id="confirmDelete" required>
                    <label class="ml-2 text-sm text-gray-700" for="confirmDelete">
                        Saya memahami konsekuensinya dan ingin menghapus akun saya secara permanen
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 px-6 py-4">
                <button type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" id="confirmDeleteButton" disabled>
                    <i class="bi bi-trash mr-1"></i> Hapus Akun Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable delete button when checkbox is checked
        const confirmDeleteCheckbox = document.getElementById('confirmDelete');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');

        confirmDeleteCheckbox?.addEventListener('change', function() {
            confirmDeleteButton.disabled = !this.checked;
        });

        // Handle delete account confirmation
        confirmDeleteButton?.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus akun Anda secara permanen? Tindakan ini tidak dapat dibatalkan.')) {
                // Show loading state
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...';
                this.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    alert('Fitur penghapusan akun akan segera tersedia. Silakan hubungi administrator untuk bantuan.');
                    bootstrap.Modal.getInstance(document.getElementById('deleteAccountModal')).hide();
                    // Reset button
                    this.innerHTML = '<i class="bi bi-trash me-1"></i> Hapus Akun Sekarang';
                    this.disabled = false;
                    confirmDeleteCheckbox.checked = false;
                }, 1500);
            }
        });

        // Handle transfer ownership confirmation
        const confirmTransferButton = document.getElementById('confirmTransferButton');
        confirmTransferButton?.addEventListener('click', function() {
            const campaignSelect = document.getElementById('campaign_select');
            const email = document.getElementById('new_owner_email').value;
            const campaignName = campaignSelect.options[campaignSelect.selectedIndex].text;

            if (!campaignSelect.value || !email) {
                alert('Mohon lengkapi semua field yang diperlukan.');
                return;
            }

            if (confirm(`Apakah Anda yakin ingin mentransfer "${campaignName}" ke ${email}?`)) {
                // Show loading state
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                this.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    alert('Permintaan transfer campaign telah dikirim. Penerima akan mendapatkan email konfirmasi.');
                    bootstrap.Modal.getInstance(document.getElementById('transferOwnershipModal')).hide();
                    // Reset form and button
                    document.getElementById('transferOwnershipForm').reset();
                    this.innerHTML = '<i class="bi bi-arrow-right-circle me-1"></i> Transfer Sekarang';
                    this.disabled = false;
                }, 1500);
            }
        });

        // Password visibility toggle function
        function togglePasswordVisibility(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Password strength validation
        function validatePassword(password) {
            return {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                number: /\d/.test(password)
            };
        }

        // Update password requirement indicators
        function updatePasswordIndicators(password) {
            const requirements = validatePassword(password);

            // Update each requirement indicator
            updateRequirementIndicator('length', requirements.length);
            updateRequirementIndicator('uppercase', requirements.uppercase);
            updateRequirementIndicator('number', requirements.number);

            // Check password match
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            const passwordsMatch = password === confirmPassword && confirmPassword !== '';
            const allMet = requirements.length && requirements.uppercase && requirements.number;

            // Update password match feedback
            const matchFeedback = document.getElementById('password-match-feedback');
            if (confirmPassword) {
                matchFeedback.classList.remove('hidden');
                if (passwordsMatch) {
                    matchFeedback.className = 'mt-2 text-sm text-green-600';
                    matchFeedback.innerHTML = '<i class="bi bi-check-circle-fill mr-1"></i>Kata sandi cocok';
                } else {
                    matchFeedback.className = 'mt-2 text-sm text-red-600';
                    matchFeedback.innerHTML = '<i class="bi bi-x-circle-fill mr-1"></i>Kata sandi tidak cocok';
                }
            } else {
                matchFeedback.classList.add('hidden');
            }

            // Enable/disable submit button
            const submitBtn = document.getElementById('changePasswordBtn');
            const currentPassword = document.getElementById('current_password').value;
            submitBtn.disabled = !(allMet && passwordsMatch && currentPassword);
        }

        // Update single requirement indicator
        function updateRequirementIndicator(requirementName, isMet) {
            const icon = document.getElementById(`${requirementName}-icon`);
            const text = document.getElementById(`${requirementName}-text`);

            if (isMet) {
                icon.classList.remove('bi-x-circle-fill', 'text-gray-400');
                icon.classList.add('bi-check-circle-fill', 'text-green-600');
                text.classList.add('text-green-600');
            } else {
                icon.classList.remove('bi-check-circle-fill', 'text-green-600');
                icon.classList.add('bi-x-circle-fill', 'text-gray-400');
                text.classList.remove('text-green-600');
            }
        }

        // Password toggle event listeners
        document.getElementById('toggleCurrentPassword')?.addEventListener('click', function() {
            togglePasswordVisibility('current_password', 'toggleCurrentPassword');
        });

        document.getElementById('toggleNewPassword')?.addEventListener('click', function() {
            togglePasswordVisibility('new_password', 'toggleNewPassword');
        });

        document.getElementById('toggleConfirmPassword')?.addEventListener('click', function() {
            togglePasswordVisibility('new_password_confirmation', 'toggleConfirmPassword');
        });

        // Password validation event listeners
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('new_password_confirmation');
        const currentPasswordInput = document.getElementById('current_password');

        newPasswordInput?.addEventListener('input', function() {
            updatePasswordIndicators(this.value);
        });

        confirmPasswordInput?.addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            updatePasswordIndicators(newPassword);
        });

        currentPasswordInput?.addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            updatePasswordIndicators(newPassword);
        });

        // Reset modal forms when closed
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                const forms = this.querySelectorAll('form');
                forms.forEach(form => form.reset());

                // Reset password indicators
                if (this.id === 'changePasswordModal') {
                    updateRequirementIndicator('length', false);
                    updateRequirementIndicator('uppercase', false);
                    updateRequirementIndicator('number', false);
                    document.getElementById('password-match-feedback').classList.add('hidden');
                    document.getElementById('changePasswordBtn').disabled = true;
                }

                // Reset delete checkbox
                if (this.id === 'deleteAccountModal') {
                    document.getElementById('confirmDeleteButton').disabled = true;
                }
            });
        });

        // Form submission handler for change password
        const changePasswordForm = document.getElementById('changePasswordForm');
        changePasswordForm?.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('changePasswordBtn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
            submitBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                // In production, this would be an actual form submission
                // For now, we'll just show a success message
                alert('Kata sandi berhasil diubah!');
                bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    });
</script>
@endpush

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
