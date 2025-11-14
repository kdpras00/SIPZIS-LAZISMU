@extends('layouts.app')

@section('page-title', 'Manajemen Akun - Dashboard Muzakki')

@section('content')
<div class="container-fluid py-4" style="padding-top: 1rem !important;">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('muzakki.dashboard') }}" class="text-dark me-3">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <h5 class="fw-semibold mb-0">Manajemen akun</h5>
            </div>

<style>
    .account-menu-item {
        transition: all 0.3s ease;
        border: none;
        padding: 1rem 1.25rem;
        cursor: pointer;
    }

    .account-menu-item:hover {
        background-color: #f8f9fa;
        transform: translateX(4px);
    }

    .account-menu-item:active {
        transform: translateX(2px);
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .account-menu-item:hover .icon-wrapper {
        transform: scale(1.05);
    }

    .bg-purple-gradient {
        background: linear-gradient(135deg, #e9d5ff 0%, #f3e8ff 100%);
    }

    .menu-title {
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .menu-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
    }

    .chevron-icon {
        color: #9ca3af;
        transition: transform 0.3s ease;
    }

    .account-menu-item:hover .chevron-icon {
        transform: translateX(4px);
        color: #9333ea;
    }

    .back-button {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #374151;
    }

    .back-button:hover {
        background-color: #f9fafb;
        border-color: #d1d5db;
        color: #1f2937;
        transform: translateX(-2px);
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .card-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    }

    .modal-title {
        font-weight: 600;
        color: #1a1a1a;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.625rem 0.875rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #9333ea;
        box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
    }

    .btn {
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
    }

    .btn-primary:disabled {
        background: #d1d5db;
        transform: none;
        box-shadow: none;
    }

    .requirement-icon {
        margin-right: 0.5rem;
        transition: all 0.3s ease;
    }

    .alert {
        border-radius: 12px;
        border: none;
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.25rem;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
        }

        .menu-description {
            font-size: 0.8125rem;
        }
    }

    body {
        padding-bottom: 80px !important;
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .container-fluid {
        max-width: 100%;
        margin-top: -20px;
    }

    .fixed-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 2rem);
        max-width: 800px;
        z-index: 1030;
        margin: 0 auto;
        border-radius: 0 !important;
        filter: drop-shadow(0 -2px 4px rgba(0, 0, 0, 0.1));
        border-top: 1px solid #e0e0e0;
    }
</style>

        <!-- Account Settings Menu -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="list-group list-group-flush">
                <!-- Change Password -->
                <a href="#" class="list-group-item account-menu-item d-flex align-items-center gap-3"
                    data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <div class="icon-wrapper bg-purple-gradient">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14.5V16.5M7 10.0288C7.47142 10 8.05259 10 8.8 10H15.2C15.9474 10 16.5286 10 17 10.0288M7 10.0288C6.41168 10.0647 5.99429 10.1455 5.63803 10.327C5.07354 10.6146 4.6146 11.0735 4.32698 11.638C4 12.2798 4 13.1198 4 14.8V16.2C4 17.8802 4 18.7202 4.32698 19.362C4.6146 19.9265 5.07354 20.3854 5.63803 20.673C6.27976 21 7.11984 21 8.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V14.8C20 13.1198 20 12.2798 19.673 11.638C19.3854 11.0735 18.9265 10.6146 18.362 10.327C18.0057 10.1455 17.5883 10.0647 17 10.0288M7 10.0288V8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8V10.0288" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="flex-grow-1">
                        <div class="menu-title">Ganti Kata Sandi</div>
                        <p class="menu-description">Perbarui kata sandi untuk keamanan akun</p>
                    </div>
                    <svg class="chevron-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <!-- Transfer Campaign Ownership -->
                <a href="#" class="list-group-item account-menu-item d-flex align-items-center gap-3"
                    data-bs-toggle="modal" data-bs-target="#transferOwnershipModal">
                    <div class="icon-wrapper bg-purple-gradient">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 9L21 3M21 3H15M21 3L13 11M10 5H7.8C6.11984 5 5.27976 5 4.63803 5.32698C4.07354 5.6146 3.6146 6.07354 3.32698 6.63803C3 7.27976 3 8.11984 3 9.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21H14.2C15.8802 21 16.7202 21 17.362 20.673C17.9265 20.3854 18.3854 19.9265 18.673 19.362C19 18.7202 19 17.8802 19 16.2V14" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="flex-grow-1">
                        <div class="menu-title">Transfer Campaign Ownership</div>
                        <p class="menu-description">Transfer kepemilikan campaign ke pengguna lain</p>
                    </div>
                    <svg class="chevron-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <!-- Delete Account -->
                <a href="#" class="list-group-item account-menu-item d-flex align-items-center gap-3"
                    data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <div class="icon-wrapper bg-purple-gradient">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="flex-grow-1">
                        <div class="menu-title">Hapus Akun</div>
                        <p class="menu-description">Hapus akun secara permanen dari sistem</p>
                    </div>
                    <svg class="chevron-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ganti Kata Sandi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" id="changePasswordForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Masukkan kata sandi saat ini" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukkan kata sandi baru" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <!-- Password Requirements -->
                        <div class="mt-2">
                            <small class="text-muted d-block mb-2">Kata sandi harus mengandung:</small>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-1">
                                    <i class="bi bi-x-circle-fill text-secondary requirement-icon" id="length-icon"></i>
                                    <span id="length-text">Minimal 8 karakter</span>
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-x-circle-fill text-secondary requirement-icon" id="uppercase-icon"></i>
                                    <span id="uppercase-text">Minimal 1 huruf kapital</span>
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-x-circle-fill text-secondary requirement-icon" id="number-icon"></i>
                                    <span id="number-text">Minimal 1 angka</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ulangi kata sandi baru" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="password-match-feedback" class="form-text mt-2" style="display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="changePasswordBtn" disabled>
                        <i class="bi bi-check-circle me-1"></i> Ganti Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transfer Ownership Modal -->
<div class="modal fade" id="transferOwnershipModal" tabindex="-1" aria-labelledby="transferOwnershipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferOwnershipModalLabel">Transfer Campaign Ownership</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <div>
                        <strong>Perhatian!</strong> Setelah transfer, Anda tidak akan lagi memiliki akses penuh terhadap campaign tersebut.
                    </div>
                </div>
                <form id="transferOwnershipForm">
                    <div class="mb-3">
                        <label for="campaign_select" class="form-label">Pilih Campaign</label>
                        <select class="form-select" id="campaign_select" name="campaign_select" required>
                            <option value="">Pilih campaign...</option>
                            <option value="1">Campaign Pendidikan Anak Yatim</option>
                            <option value="2">Program Bantuan Pangan</option>
                            <option value="3">Renovasi Masjid</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="new_owner_email" class="form-label">Email Pemilik Baru</label>
                        <input type="email" class="form-control" id="new_owner_email" name="new_owner_email" placeholder="contoh@email.com" required>
                        <div class="form-text">Pemilik baru akan menerima notifikasi melalui email</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmTransferButton">
                    <i class="bi bi-arrow-right-circle me-1"></i> Transfer Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Hapus Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <div>
                        <strong>Peringatan!</strong> Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
                    </div>
                </div>
                <p class="mb-3">Dengan menghapus akun, semua data berikut akan dihapus secara permanen:</p>
                <ul class="mb-3">
                    <li>Informasi profil dan data pribadi</li>
                    <li>Riwayat transaksi dan donasi</li>
                    <li>Campaign yang Anda buat</li>
                    <li>Semua preferensi dan pengaturan</li>
                </ul>
                <div class="bg-light p-3 rounded mb-3">
                    <p class="small mb-2"><strong>Sebelum melanjutkan, pastikan Anda telah:</strong></p>
                    <ul class="small mb-0">
                        <li>Mengunduh atau mencatat semua informasi penting</li>
                        <li>Menyelesaikan semua transaksi yang tertunda</li>
                        <li>Mentransfer kepemilikan campaign jika diperlukan</li>
                    </ul>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                    <label class="form-check-label" for="confirmDelete">
                        Saya memahami konsekuensinya dan ingin menghapus akun saya secara permanen
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton" disabled>
                    <i class="bi bi-trash me-1"></i> Hapus Akun Sekarang
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
                matchFeedback.style.display = 'block';
                if (passwordsMatch) {
                    matchFeedback.className = 'form-text mt-2 text-success';
                    matchFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Kata sandi cocok';
                } else {
                    matchFeedback.className = 'form-text mt-2 text-danger';
                    matchFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>Kata sandi tidak cocok';
                }
            } else {
                matchFeedback.style.display = 'none';
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
                icon.classList.remove('bi-x-circle-fill', 'text-secondary');
                icon.classList.add('bi-check-circle-fill', 'text-success');
                text.classList.add('text-success');
            } else {
                icon.classList.remove('bi-check-circle-fill', 'text-success');
                icon.classList.add('bi-x-circle-fill', 'text-secondary');
                text.classList.remove('text-success');
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
                    document.getElementById('password-match-feedback').style.display = 'none';
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
        <div class="card border-0 shadow-sm mt-4 fixed-bottom-nav">
            <div class="card-body d-flex justify-content-around text-center">
                <div>
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                        <i class="bi bi-house fs-5 d-block"></i>
                        <small>Home</small>
                    </a>
                </div>
                <div>
                    <a href="{{ route('muzakki.donation') }}" class="text-decoration-none text-dark">
                        <i class="bi bi-heart fs-5 d-block"></i>
                        <small>Donasi</small>
                    </a>
                </div>
                <div>
                    <a href="{{ route('muzakki.fundraising') }}" class="text-decoration-none text-dark">
                        <i class="bi bi-box-seam fs-5 d-block"></i>
                        <small>Galang Dana</small>
                    </a>
                </div>
                <div>
                    <a href="{{ route('muzakki.amalanku') }}" class="text-decoration-none text-dark">
                        <i class="bi bi-person fs-5 d-block"></i>
                        <small>Amalanku</small>
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection