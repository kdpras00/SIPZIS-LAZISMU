@extends('layouts.app')

@section('page-title', 'Edit Muzakki - ' . $muzakki->name)

@section('content')
    <div class="container-fluid px-4" style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ request()->route()->hasParameter('muzakki') ? route('muzakki.index') : route('muzakki.dashboard') }}"
                    class="text-decoration-none text-dark me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="mb-0">{{ request()->route()->hasParameter('muzakki') ? 'Edit Muzakki' : 'Profil' }}</h4>
            </div>
            <button type="submit" form="muzakkiEditForm"
                class="btn btn-link p-0 text-success fw-semibold text-decoration-none" style="font-size: 1rem;">
                Selesai
            </button>

        </div>

        <!-- Profile Completion Progress -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Kelengkapan Profil</span>
                    <span class="text-success fw-bold">
                        <span id="profileCompletion">{{ calculateProfileCompletion($muzakki) }}%</span>
                        <i class="bi bi-info-circle text-muted ms-1" data-bs-toggle="tooltip"
                            title="Lengkapi profil untuk meningkatkan persentase"></i>
                    </span>
                </div>
                <div class="progress" style="height: 8px;" id="profileProgress">
                    <div class="progress-bar bg-warning" role="progressbar" id="progressBar"></div>
                </div>
            </div>
        </div>

        <form
            action="{{ request()->route()->hasParameter('muzakki') ? route('muzakki.update', $muzakki) : route('profile.update') }}"
            method="POST" id="muzakkiEditForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Photo Section -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $muzakki->profile_photo ? asset('storage/' . $muzakki->profile_photo) : asset('images/profile-default.jpg') }}"
                                alt="Profile Photo" class="rounded-circle"
                                style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #e9ecef;"
                                id="profilePhotoPreview">
                        </div>
                    </div>
                    <p class="text-muted mb-3">Belum ada foto profil</p>
                    <button type="button" class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('profilePhotoInput').click()">
                        Ganti foto profil
                    </button>
                    <input type="file" id="profilePhotoInput" name="profile_photo" class="d-none" accept="image/*">
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label small text-muted">Nama<span
                                class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control border-0 border-bottom rounded-0 px-0 @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $muzakki->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="campaign_url" class="form-label small text-muted">URL List Campaign</label>
                        <div class="input-group border-0 border-bottom rounded-0">
                            <span class="input-group-text border-0 bg-transparent px-0">
                                <i class="bi bi-link-45deg text-success"></i>
                            </span>
                            <input type="url"
                                class="form-control border-0 rounded-0 @error('campaign_url') is-invalid @enderror"
                                id="campaign_url" name="campaign_url"
                                value="{{ old('campaign_url', $muzakki->campaign_url) }}" readonly
                                style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>
                        @error('campaign_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small text-muted">Email<span
                                class="text-danger">*</span></label>
                        <input type="email"
                            class="form-control border-0 border-bottom rounded-0 px-0 @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email', $muzakki->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold small">
                            No. Telepon<span class="text-danger">*</span>
                        </label>

                        <div class="d-flex align-items-center gap-2">
                            <input type="tel" id="phone" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', preg_replace('/^\+62|^62|^0/', '', $muzakki->phone ?? '')) }}"
                                placeholder="8xxxxxxxxxx" style="flex: 1;">


                            <button type="button"
                                class="btn btn-success rounded-pill px-4 py-2 text-white d-flex align-items-center"
                                id="verifyPhoneBtn">
                                <span id="verifyButtonText">Verifikasi</span>
                            </button>
                            <span id="verifyCheckmark" style="display: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                            </span>
                            </button>
                        </div>

                        @if (!$muzakki->phone_verified)
                            <div class="alert alert-warning border-0 mt-2 py-2 px-3 small" id="notVerifiedAlert">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Mohon verifikasi nomor telepon Anda
                            </div>
                        @else
                            <div class="alert alert-success border-0 mt-2 py-2 px-3 small" id="verifiedAlert">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Nomor telepon sudah diverifikasi
                            </div>
                        @endif

                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nik" class="form-label small text-muted">KTP<span
                                class="text-danger">*</span></label>
                        <div class="border rounded p-4 text-center" style="cursor: pointer;"
                            onclick="document.getElementById('ktpInput').click()">
                            <img id="ktpPreview" src="" alt="Preview KTP"
                                style="max-width: 100%; max-height: 200px; display: none;">
                            <div id="ktpPlaceholder">
                                <i class="bi bi-plus-circle text-success" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-0 small">Upload foto KTP</p>
                            </div>
                            <input type="file" id="ktpInput" name="ktp_photo" class="d-none" accept="image/*">
                        </div>
                        @if (!$muzakki->ktp_photo)
                            <div class="alert alert-warning border-0 mt-2 py-2 px-3 small">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Upload foto KTP Anda untuk verifikasi akun
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Personal Details -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-3">Informasi pribadi</h6>

                    <div class="mb-3">
                        <label for="gender" class="form-label small text-muted">Jenis kelamin<span
                                class="text-danger">*</span></label>
                        <select class="form-select border-0 border-bottom rounded-0 @error('gender') is-invalid @enderror"
                            id="gender" name="gender" required>
                            <option value="">----------</option>
                            <option value="male" {{ old('gender', $muzakki->gender) == 'male' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="female" {{ old('gender', $muzakki->gender) == 'female' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Tanggal lahir<span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-4">
                                <select class="form-select border-0 border-bottom rounded-0" name="birth_day">
                                    <option value="">Hari</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('birth_day', $muzakki->date_of_birth ? $muzakki->date_of_birth->day : '') == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select border-0 border-bottom rounded-0" name="birth_month">
                                    <option value="">Bulan</option>
                                    @php
                                        $months = [
                                            'Januari',
                                            'Februari',
                                            'Maret',
                                            'April',
                                            'Mei',
                                            'Juni',
                                            'Juli',
                                            'Agustus',
                                            'September',
                                            'Oktober',
                                            'November',
                                            'Desember',
                                        ];
                                    @endphp
                                    @foreach ($months as $index => $month)
                                        <option value="{{ $index + 1 }}"
                                            {{ old('birth_month', $muzakki->date_of_birth ? $muzakki->date_of_birth->month : '') == $index + 1 ? 'selected' : '' }}>
                                            {{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select border-0 border-bottom rounded-0" name="birth_year">
                                    <option value="">Tahun</option>
                                    @for ($i = date('Y'); $i >= 1940; $i--)
                                        <option value="{{ $i }}"
                                            {{ old('birth_year', $muzakki->date_of_birth ? $muzakki->date_of_birth->year : '') == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="occupation" class="form-label small text-muted">Profesi<span
                                class="text-danger">*</span></label>
                        <select
                            class="form-select border-0 border-bottom rounded-0 @error('occupation') is-invalid @enderror"
                            id="occupation" name="occupation">
                            <option value="">Pilih Profesi</option>
                            @php
                                $occupations = [
                                    'Karyawan',
                                    'Wiraswasta',
                                    'PNS',
                                    'Guru',
                                    'Dokter',
                                    'Perawat',
                                    'Tentara',
                                    'Polisi',
                                    'Petani',
                                    'Nelayan',
                                    'Pedagang',
                                    'Sopir',
                                    'Ojek Online',
                                    'Programmer',
                                    'Desainer',
                                    'Akuntan',
                                    'Mahasiswa',
                                    'Pelajar',
                                    'Ibu Rumah Tangga',
                                    'Pensiunan',
                                    'Seniman',
                                    'Musisi',
                                    'Atlet',
                                    'Pengacara',
                                    'Arsitek',
                                    'Lainnya',
                                ];
                            @endphp
                            @foreach ($occupations as $occupation)
                                <option value="{{ strtolower(str_replace(' ', '_', $occupation)) }}"
                                    {{ old('occupation', $muzakki->occupation) == strtolower(str_replace(' ', '_', $occupation)) ? 'selected' : '' }}>
                                    {{ $occupation }}
                                </option>
                            @endforeach
                        </select>
                        @error('occupation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label small text-muted">Negara<span
                                class="text-danger">*</span></label>
                        <select class="form-select border-0 border-bottom rounded-0 @error('country') is-invalid @enderror"
                            id="country" name="country" required>
                            <option value="">Pilih Negara</option>
                            <!-- We'll populate this dynamically with JavaScript -->
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="province" class="form-label small text-muted">Provinsi<span
                                class="text-danger">*</span></label>
                        <select
                            class="form-select border-0 border-bottom rounded-0 @error('province') is-invalid @enderror"
                            id="province" name="province">
                            <option value="">Pilih Provinsi</option>
                        </select>
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label small text-muted">Kota/Kabupaten<span
                                class="text-danger">*</span></label>
                        <select class="form-select border-0 border-bottom rounded-0 @error('city') is-invalid @enderror"
                            id="city" name="city">
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="district" class="form-label small text-muted">Kecamatan<span
                                class="text-danger">*</span></label>
                        <select class="form-select border-0 border-bottom rounded-0" id="district" name="district">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="village" class="form-label small text-muted">Kelurahan<span
                                class="text-danger">*</span></label>
                        <select class="form-select border-0 border-bottom rounded-0" id="village" name="village">
                            <option value="">Pilih Kelurahan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="postal_code" class="form-label small text-muted">Kode Pos<span
                                class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control border-0 border-bottom rounded-0 px-0 @error('postal_code') is-invalid @enderror"
                            id="postal_code" name="postal_code" value="{{ old('postal_code', $muzakki->postal_code) }}"
                            maxlength="5">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label small text-muted">Alamat<span
                                class="text-danger">*</span></label>
                        <textarea class="form-control border-0 border-bottom rounded-0 px-0 @error('address') is-invalid @enderror"
                            id="address" name="address" rows="3">{{ old('address', $muzakki->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="bio" class="form-label small text-muted">Biodata<span
                                class="text-danger">*</span></label>
                        <div class="border rounded p-2 mb-2">
                            <div class="btn-toolbar mb-2" role="toolbar">
                                <div class="btn-group btn-group-sm me-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatText('bold')">
                                        <i class="bi bi-type-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatText('italic')">
                                        <i class="bi bi-type-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatText('insertUnorderedList')">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatText('insertOrderedList')">
                                        <i class="bi bi-list-ol"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="formatText('createLink')">
                                        <i class="bi bi-link-45deg"></i>
                                    </button>
                                </div>
                            </div>
                            <div contenteditable="true" class="form-control border-0 @error('bio') is-invalid @enderror"
                                id="bio_editor" style="min-height: 120px; max-height: 300px; overflow-y: auto;">
                                {!! old(
                                    'bio',
                                    $muzakki->bio ??
                                        'Dengan membuat cerita yang singkat, kamu akan berkesan pada mendapatkan donasi yang lebih banyak.',
                                ) !!}
                            </div>
                            <textarea name="bio" id="bio" class="d-none">{{ old('bio', $muzakki->bio) }}</textarea>
                        </div>
                        <small class="text-muted">Dengan membuat cerita yang singkat, kamu akan berkesan pada mendapatkan
                            donasi yang lebih banyak.</small>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Section (only for admin) -->
            @if (request()->route()->hasParameter('muzakki') && in_array(auth()->user()->role, ['admin', 'staff']))
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">Status Akun</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" {{ old('is_active', $muzakki->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktifkan akun muzakki
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Phone Verification Status -->
            @if ($muzakki->phone_verified)
                <input type="hidden" name="phone_verified" value="1" id="phone_verified_input">
            @endif
        </form>
    </div>

    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" id="otpModalLabel">Verifikasi Nomor Telepon Anda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted mb-4">Masukkan kode OTP yang dikirim ke nomor Whatsapp <strong
                            id="displayPhone"></strong></p>

                    <div class="d-flex justify-content-between mb-3 gap-2">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1"
                            id="otp1">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1"
                            id="otp2">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1"
                            id="otp3">
                        <input type="text" class="form-control form-control-lg text-center otp-input" maxlength="1"
                            id="otp4">
                    </div>

                    <div class="text-center mb-4">
                        <p class="text-muted small mb-2">Belum menerima kode?
                            <a href="#" class="text-success" id="resendOtp" style="text-decoration: none;">
                                Kirim kode OTP (<span id="countdown">57</span> detik)
                            </a>
                        </p>
                    </div>

                    <button type="button" class="btn btn-success w-100 py-3 rounded-3" id="verifyOtpBtn" disabled>
                        Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">

    <style>
        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #6c757d;
        }

        .progress {
            background-color: #f0f0f0;
        }

        [contenteditable="true"]:focus {
            outline: none;
        }

        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
        }

        .iti {
            width: 100%;
        }

        #phone {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
        }

        .verification-btn {
            background-color: #0d6efd;
            border: none;
            color: white;
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            border-radius: 20px;
            transition: background-color 0.3s;
        }

        .verification-btn:hover {
            background-color: #0b5ed7;
            color: white;
        }

        /* OTP Modal Styles */
        .otp-input {
            width: 60px;
            height: 60px;
            font-size: 24px;
            font-weight: 600;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .otp-input:focus {
            transform: scale(1.05);
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .modal-content {
            border-radius: 16px;
        }

        .modal-header .btn-close {
            padding: 0.5rem;
        }

        /* Toast Notification Styles */
        .otp-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .otp-toast.show {
            transform: translateX(0);
        }

        .otp-toast-success {
            border-left: 4px solid #198754;
        }

        .otp-toast-warning {
            border-left: 4px solid #ffc107;
        }

        .otp-toast-info {
            border-left: 4px solid #0d6efd;
        }

        /* Shake animation for error */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .shake {
            animation: shake 0.3s ease;
        }

        /* Smooth button transitions */
        #verifyPhoneBtn,
        #verifyOtpBtn {
            transition: all 0.3s ease;
        }

        /* Loading spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize intl-tel-input
            const phoneInput = document.querySelector("#phone");
            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "id",
                separateDialCode: true,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
            });

            // Set initial width for progress bar
            const completionPercent = <?php echo calculateProfileCompletion($muzakki); ?>;
            document.getElementById('progressBar').style.width = completionPercent + '%';
            document.getElementById('profileCompletion').textContent = completionPercent + '%';
            document.getElementById('progressBar').style.transition = 'width 0.6s ease-in-out';

            // Set initial progress bar color based on completion
            const initialProgressBar = document.getElementById('progressBar');
            if (completionPercent < 30) {
                initialProgressBar.className = 'progress-bar bg-danger';
            } else if (completionPercent < 70) {
                initialProgressBar.className = 'progress-bar bg-warning';
            } else {
                initialProgressBar.className = 'progress-bar bg-success';
            }

            // Initialize KTP preview if exists
            const ktpPhotoUrl = "<?php echo $muzakki->ktp_photo ? asset('storage/' . $muzakki->ktp_photo) : ''; ?>";
            if (ktpPhotoUrl) {
                document.getElementById('ktpPreview').src = ktpPhotoUrl;
                document.getElementById('ktpPreview').style.display = 'block';
                document.getElementById('ktpPlaceholder').style.display = 'none';
            }

            // Format phone number: remove +62, 62, or 0 prefix
            function normalizePhoneNumber(phone) {
                let cleaned = phone.replace(/\D/g, '');

                if (cleaned.startsWith('62')) {
                    cleaned = cleaned.substring(2);
                } else if (cleaned.startsWith('0')) {
                    cleaned = cleaned.substring(1);
                }

                return cleaned;
            }

            // Set existing phone number - intlTelInput will handle formatting
            // We just need to ensure the value is set correctly before initialization
            const existingPhone = phoneInput.value;
            if (existingPhone) {
                // Don't normalize here as intlTelInput will handle it
                // Just ensure it's a valid number without duplicate prefixes
                let cleanPhone = existingPhone;
                // Remove any duplicate +62 prefixes
                while (cleanPhone.startsWith('+62+62')) {
                    cleanPhone = cleanPhone.substring(4); // Remove '+62' but keep the second +62
                }
                // If it starts with +6262, fix it
                if (cleanPhone.startsWith('+6262')) {
                    cleanPhone = '+62' + cleanPhone.substring(5);
                }
                // If it starts with 6262, fix it
                if (cleanPhone.startsWith('6262')) {
                    cleanPhone = '62' + cleanPhone.substring(4);
                }
                // Update the input value if it was changed
                if (cleanPhone !== existingPhone) {
                    phoneInput.value = cleanPhone;
                }
            }

            // Phone input handler - let intlTelInput handle most of the formatting
            phoneInput.addEventListener('input', function() {
                // intlTelInput handles the formatting, we just need to prevent duplicate prefixes
                let value = this.value;

                // Remove any duplicate +62 prefixes that might occur
                while (value.startsWith('+62+62')) {
                    value = value.substring(4); // Remove the first '+62'
                }

                // If it starts with +6262, fix it
                if (value.startsWith('+6262')) {
                    value = '+62' + value.substring(5);
                }

                // If it starts with 6262, fix it
                if (value.startsWith('6262')) {
                    value = '62' + value.substring(4);
                }

                // Update the input value if it was changed
                if (value !== this.value) {
                    this.value = value;
                }
            });

            // Verify phone button handler
            const verifyPhoneBtn = document.getElementById('verifyPhoneBtn');
            const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));

            // Improved verify phone button with better UX
            verifyPhoneBtn.addEventListener('click', function() {
                const phoneValue = phoneInput.value.trim();

                if (!phoneValue) {
                    showToast('Mohon masukkan nomor telepon', 'warning');
                    phoneInput.focus();
                    return;
                }

                // Show loading state
                const originalText = verifyPhoneBtn.innerHTML;
                verifyPhoneBtn.disabled = true;
                verifyPhoneBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

                // Use intl-tel-input to get the full number with international format for display
                const fullPhone = iti.getNumber(); // Get the full international format number
                document.getElementById('displayPhone').textContent = fullPhone;

                // Send OTP to backend (ensure phone is correctly formatted)
                let phoneForAPI = fullPhone;
                // Remove + prefix if present
                if (phoneForAPI.startsWith('+')) {
                    phoneForAPI = phoneForAPI.substring(1);
                }
                // Ensure it starts with 62
                if (!phoneForAPI.startsWith('62')) {
                    showToast('Format nomor telepon tidak valid. Harus dimulai dengan 62.', 'warning');
                    verifyPhoneBtn.disabled = false;
                    verifyPhoneBtn.innerHTML = originalText;
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/send-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            phone: phoneForAPI
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        verifyPhoneBtn.disabled = false;
                        verifyPhoneBtn.innerHTML = originalText;

                        if (data.success) {
                            // Show modal without alert
                            otpModal.show();

                            // Start smart countdown
                            startSmartCountdown();

                            // Show success toast
                            showToast('Kode OTP telah dikirim ke WhatsApp Anda', 'success');
                        } else {
                            showToast(data.message || 'Gagal mengirim OTP', 'warning');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        verifyPhoneBtn.disabled = false;
                        verifyPhoneBtn.innerHTML = originalText;
                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'warning');
                    });
            });

            // OTP Input handling with smart behavior
            const otpInputs = document.querySelectorAll('.otp-input');
            const verifyOtpBtn = document.getElementById('verifyOtpBtn');

            // Initialize smart OTP input behavior
            initializeSmartOTPInput();

            // Improved verify OTP function
            improvedVerifyOTP();

            // Smart countdown timer
            function startSmartCountdown() {
                let seconds = 60;
                const countdownElement = document.getElementById('countdown');
                const resendLink = document.getElementById('resendOtp');

                resendLink.style.pointerEvents = 'none';
                resendLink.style.opacity = '0.6';

                clearInterval(window.countdownInterval);

                window.countdownInterval = setInterval(function() {
                    seconds--;
                    countdownElement.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(window.countdownInterval);
                        resendLink.style.pointerEvents = 'auto';
                        resendLink.style.opacity = '1';
                        resendLink.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Kirim ulang kode';

                        // Show expiration notification
                        showToast('Kode OTP telah kedaluwarsa. Silakan kirim ulang.', 'warning');
                    }
                }, 1000);
            }

            // Resend OTP with improved UX
            document.getElementById('resendOtp').addEventListener('click', function(e) {
                e.preventDefault();

                const fullPhone = iti.getNumber(); // Get the full international format number

                // Show loading state on button
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
                this.style.pointerEvents = 'none';

                // Resend OTP to backend (ensure phone is correctly formatted)
                let phoneForAPI = fullPhone;
                // Remove + prefix if present
                if (phoneForAPI.startsWith('+')) {
                    phoneForAPI = phoneForAPI.substring(1);
                }
                // Ensure it starts with 62
                if (!phoneForAPI.startsWith('62')) {
                    showToast('Format nomor telepon tidak valid. Harus dimulai dengan 62.', 'warning');
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/send-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            phone: phoneForAPI
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('resendOtp').innerHTML = originalText;
                        document.getElementById('resendOtp').style.pointerEvents = 'auto';

                        if (data.success) {
                            // Clear OTP inputs
                            otpInputs.forEach(input => input.value = '');
                            otpInputs[0].focus();
                            verifyOtpBtn.disabled = true;

                            // Restart countdown
                            startSmartCountdown();

                            // Show success toast
                            showToast('Kode OTP baru telah dikirim', 'success');
                        } else {
                            showToast(data.message || 'Gagal mengirim ulang OTP', 'warning');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('resendOtp').innerHTML = originalText;
                        document.getElementById('resendOtp').style.pointerEvents = 'auto';
                        showToast('Terjadi kesalahan saat mengirim ulang OTP', 'warning');
                    });
            });

            // Profile photo preview
            document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('profilePhotoPreview').src = e.target.result;
                        // Update progress bar
                        calculateCompletion();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // KTP photo preview
            document.getElementById('ktpInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('ktpPreview').src = e.target.result;
                        document.getElementById('ktpPreview').style.display = 'block';
                        document.getElementById('ktpPlaceholder').style.display = 'none';
                        // Update progress bar
                        calculateCompletion();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Bio editor sync
            const bioEditor = document.getElementById('bio_editor');
            const bioInput = document.getElementById('bio');

            bioEditor.addEventListener('input', function() {
                bioInput.value = this.innerHTML;
                calculateCompletion(); // Add this to update progress when bio changes
            });

            // Set phone number format before form submission
            const form = document.getElementById('muzakkiEditForm');
            form.addEventListener('submit', function(e) {
                // Combine birth date fields into a single date_of_birth field
                const birthDay = document.querySelector('[name="birth_day"]').value;
                const birthMonth = document.querySelector('[name="birth_month"]').value;
                const birthYear = document.querySelector('[name="birth_year"]').value;

                if (birthDay && birthMonth && birthYear) {
                    // Create hidden input for date_of_birth
                    let dateOfBirthInput = document.getElementById('date_of_birth');
                    if (!dateOfBirthInput) {
                        dateOfBirthInput = document.createElement('input');
                        dateOfBirthInput.type = 'hidden';
                        dateOfBirthInput.id = 'date_of_birth';
                        dateOfBirthInput.name = 'date_of_birth';
                        form.appendChild(dateOfBirthInput);
                    }
                    // Format as YYYY-MM-DD for database storage
                    dateOfBirthInput.value =
                        `${birthYear}-${birthMonth.padStart(2, '0')}-${birthDay.padStart(2, '0')}`;
                }

                // Use intl-tel-input to get the full number with international format
                const fullNumber = iti.getNumber(); // Get the full international format number
                phoneInput.value = fullNumber;

                // Set country_name in a hidden field
                const countrySelect = document.getElementById('country');
                if (countrySelect && countrySelect.value) {
                    let countryNameInput = document.getElementById('country_name');
                    if (!countryNameInput) {
                        countryNameInput = document.createElement('input');
                        countryNameInput.type = 'hidden';
                        countryNameInput.id = 'country_name';
                        countryNameInput.name = 'country_name';
                        form.appendChild(countryNameInput);
                    }
                    countryNameInput.value = countrySelect.value;
                }

                // Note: campaign_url is now auto-generated by backend observer
                // No need to manually set it from frontend

                // Update progress bar after form submission
                setTimeout(calculateCompletion, 1000);
            });

            // Postal code formatting
            const postalInput = document.getElementById('postal_code');
            postalInput.addEventListener('input', function() {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');

                // Limit to 5 characters
                if (this.value.length > 5) {
                    this.value = this.value.slice(0, 5);
                }
            });

            // Calculate profile completion
            setTimeout(calculateCompletion, 100);

            // Add event listeners to update progress bar when fields change
            const formFields = ['name', 'email', 'phone', 'gender', 'occupation', 'province', 'city', 'district',
                'village', 'postal_code', 'address', 'bio'
            ];
            formFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', calculateCompletion);
                    field.addEventListener('change', calculateCompletion);
                }
            });

            // Add event listeners for select fields
            const selectFields = ['gender', 'occupation', 'province', 'city', 'district', 'village', 'country'];
            selectFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('change', calculateCompletion);
                }
            });

            // Add event listeners for date of birth fields
            const dateFields = ['birth_day', 'birth_month', 'birth_year'];
            dateFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('change', calculateCompletion);
                }
            });

            // Clear OTP inputs when modal is closed
            document.getElementById('otpModal').addEventListener('hidden.bs.modal', function() {
                otpInputs.forEach(input => input.value = '');
                verifyOtpBtn.disabled = true;
                clearInterval(window.countdownInterval);
            });

            // Region dropdown functions
            const countrySelect = document.querySelector('#country');
            const provinceSelect = document.querySelector('#province');
            const citySelect = document.querySelector('#city');
            const districtSelect = document.querySelector('#district');
            const villageSelect = document.querySelector('#village');

            function resetDropdown(select, placeholder) {
                select.innerHTML = `<option value="">${placeholder}</option>`;
            }

            function fetchCountries() {
                fetch('/regions/countries')
                    .then(res => res.json())
                    .then(data => {
                        resetDropdown(countrySelect, 'Pilih Negara');
                        data.forEach(country => {
                            const option = document.createElement('option');
                            option.value = country.name;
                            option.textContent = country.name;
                            countrySelect.appendChild(option);
                        });

                        // Set value if existing data
                        const savedCountry = "{{ $muzakki->country ?? '' }}";
                        if (savedCountry) {
                            countrySelect.value = savedCountry;
                            if (savedCountry.toLowerCase() === 'indonesia') {
                                fetchProvinces();
                            }
                        } else {
                            // Set default to Indonesia if no country is selected
                            countrySelect.value = 'Indonesia';
                            fetchProvinces();
                        }
                    })
                    .catch(err => console.error('Gagal memuat negara:', err));
            }

            function fetchProvinces() {
                fetch('/regions/provinces/indonesia')
                    .then(res => res.json())
                    .then(data => {
                        resetDropdown(provinceSelect, 'Pilih Provinsi');
                        data.forEach(prov => {
                            const option = document.createElement('option');
                            option.value = prov.id;
                            option.textContent = prov.name;
                            provinceSelect.appendChild(option);
                        });

                        // Set value if existing data
                        const savedProvince = `{!! $muzakki->province ?? '' !!}`;
                        if (savedProvince) {
                            // Find the option with matching text content (trimmed for whitespace)
                            const options = provinceSelect.options;
                            for (let i = 0; i < options.length; i++) {
                                if (options[i].text.trim().toLowerCase() === savedProvince.trim()
                                    .toLowerCase()) {
                                    provinceSelect.selectedIndex = i;
                                    // Trigger change event to load cities
                                    const event = new Event('change');
                                    provinceSelect.dispatchEvent(event);
                                    break;
                                }
                            }
                        }
                    })
                    .catch(err => console.error('Gagal memuat provinsi:', err));
            }

            function fetchCities(provinceId) {
                if (!provinceId) return;

                fetch(`/regions/cities/${provinceId}`)
                    .then(res => res.json())
                    .then(data => {
                        resetDropdown(citySelect, 'Pilih Kota/Kabupaten');
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });

                        // Set value if existing data
                        const savedCity = `{!! $muzakki->city ?? '' !!}`;
                        if (savedCity) {
                            // Find the option with matching text content (trimmed for whitespace)
                            const options = citySelect.options;
                            for (let i = 0; i < options.length; i++) {
                                if (options[i].text.trim().toLowerCase() === savedCity.trim().toLowerCase()) {
                                    citySelect.selectedIndex = i;
                                    // Trigger change event to load districts
                                    const event = new Event('change');
                                    citySelect.dispatchEvent(event);
                                    break;
                                }
                            }
                        }
                    })
                    .catch(err => console.error('Gagal memuat kota:', err));
            }

            function fetchDistricts(cityId) {
                if (!cityId) return;

                fetch(`/regions/districts/${cityId}`)
                    .then(res => res.json())
                    .then(data => {
                        resetDropdown(districtSelect, 'Pilih Kecamatan');
                        data.forEach(dist => {
                            const option = document.createElement('option');
                            option.value = dist.id;
                            option.textContent = dist.name;
                            districtSelect.appendChild(option);
                        });

                        // Set value if existing data
                        const savedDistrict = `{!! $muzakki->district ?? '' !!}`;
                        if (savedDistrict) {
                            // Find the option with matching text content (trimmed for whitespace)
                            const options = districtSelect.options;
                            for (let i = 0; i < options.length; i++) {
                                if (options[i].text.trim().toLowerCase() === savedDistrict.trim()
                                    .toLowerCase()) {
                                    districtSelect.selectedIndex = i;
                                    // Trigger change event to load villages
                                    const event = new Event('change');
                                    districtSelect.dispatchEvent(event);
                                    break;
                                }
                            }
                        }
                    })
                    .catch(err => console.error('Gagal memuat kecamatan:', err));
            }

            function fetchVillages(districtId) {
                if (!districtId) return;

                fetch(`/regions/villages/${districtId}`)
                    .then(res => res.json())
                    .then(data => {
                        resetDropdown(villageSelect, 'Pilih Kelurahan');
                        data.forEach(village => {
                            const option = document.createElement('option');
                            option.value = village.id;
                            option.textContent = village.name;
                            villageSelect.appendChild(option);
                        });

                        // Set value if existing data
                        const savedVillage = `{!! $muzakki->village ?? '' !!}`;
                        if (savedVillage) {
                            // Find the option with matching text content (trimmed for whitespace)
                            const options = villageSelect.options;
                            for (let i = 0; i < options.length; i++) {
                                if (options[i].text.trim().toLowerCase() === savedVillage.trim()
                                    .toLowerCase()) {
                                    villageSelect.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                    })
                    .catch(err => console.error('Gagal memuat kelurahan:', err));
            }

            // Event listeners for cascading dropdowns
            // When country is changed
            countrySelect.addEventListener('change', function() {
                const val = this.value.toLowerCase();

                // Save the country name in a hidden field
                let countryNameInput = document.getElementById('country_name');
                if (!countryNameInput) {
                    countryNameInput = document.createElement('input');
                    countryNameInput.type = 'hidden';
                    countryNameInput.id = 'country_name';
                    countryNameInput.name = 'country_name';
                    form.appendChild(countryNameInput);
                }
                countryNameInput.value = this.value; // Save the actual country name

                if (val === 'indonesia') {
                    // Show the province, city, district, village, and postal code dropdowns
                    document.querySelector('#province').closest('.mb-3').style.display = 'block';
                    document.querySelector('#city').closest('.mb-3').style.display = 'block';
                    document.querySelector('#district').closest('.mb-3').style.display = 'block';
                    document.querySelector('#village').closest('.mb-3').style.display = 'block';
                    document.querySelector('#postal_code').closest('.mb-3').style.display = 'block';
                    fetchProvinces();
                } else {
                    // Hide the province, city, district, village, and postal code dropdowns
                    document.querySelector('#province').closest('.mb-3').style.display = 'none';
                    document.querySelector('#city').closest('.mb-3').style.display = 'none';
                    document.querySelector('#district').closest('.mb-3').style.display = 'none';
                    document.querySelector('#village').closest('.mb-3').style.display = 'none';
                    document.querySelector('#postal_code').closest('.mb-3').style.display = 'none';

                    // Reset dropdowns
                    resetDropdown(provinceSelect, 'Pilih Provinsi');
                    resetDropdown(citySelect, 'Pilih Kota/Kabupaten');
                    resetDropdown(districtSelect, 'Pilih Kecamatan');
                    resetDropdown(villageSelect, 'Pilih Kelurahan');
                }
            });

            // When province is changed
            provinceSelect.addEventListener('change', function() {
                if (this.value) {
                    // Save the province name in a hidden field
                    const provinceName = this.options[this.selectedIndex].textContent;
                    let provinceNameInput = document.getElementById('province_name');
                    if (!provinceNameInput) {
                        provinceNameInput = document.createElement('input');
                        provinceNameInput.type = 'hidden';
                        provinceNameInput.id = 'province_name';
                        provinceNameInput.name = 'province_name';
                        form.appendChild(provinceNameInput);
                    }
                    provinceNameInput.value = provinceName;

                    fetchCities(this.value);
                } else {
                    resetDropdown(citySelect, 'Pilih Kota/Kabupaten');
                    resetDropdown(districtSelect, 'Pilih Kecamatan');
                    resetDropdown(villageSelect, 'Pilih Kelurahan');
                }
            });

            // When city is changed
            citySelect.addEventListener('change', function() {
                if (this.value) {
                    // Save the city name in a hidden field
                    const cityName = this.options[this.selectedIndex].textContent;
                    let cityNameInput = document.getElementById('city_name');
                    if (!cityNameInput) {
                        cityNameInput = document.createElement('input');
                        cityNameInput.type = 'hidden';
                        cityNameInput.id = 'city_name';
                        cityNameInput.name = 'city_name';
                        form.appendChild(cityNameInput);
                    }
                    cityNameInput.value = cityName;

                    fetchDistricts(this.value);
                } else {
                    resetDropdown(districtSelect, 'Pilih Kecamatan');
                    resetDropdown(villageSelect, 'Pilih Kelurahan');
                }
            });

            // When district is changed
            districtSelect.addEventListener('change', function() {
                if (this.value) {
                    // Save the district name in a hidden field
                    const districtName = this.options[this.selectedIndex].textContent;
                    let districtNameInput = document.getElementById('district_name');
                    if (!districtNameInput) {
                        districtNameInput = document.createElement('input');
                        districtNameInput.type = 'hidden';
                        districtNameInput.id = 'district_name';
                        districtNameInput.name = 'district_name';
                        form.appendChild(districtNameInput);
                    }
                    districtNameInput.value = districtName;

                    fetchVillages(this.value);

                    // Get the selected district name
                    const selectedOption = this.options[this.selectedIndex];
                    const districtNameForPostal = selectedOption.textContent;

                    // Validate and suggest postal code based on district only
                    validatePostalCodeByDistrict(districtNameForPostal);
                } else {
                    resetDropdown(villageSelect, 'Pilih Kelurahan');
                }
            });

            // When village is changed
            villageSelect.addEventListener('change', function() {
                if (this.value) {
                    // Save the village name in a hidden field
                    const villageName = this.options[this.selectedIndex].textContent;
                    let villageNameInput = document.getElementById('village_name');
                    if (!villageNameInput) {
                        villageNameInput = document.createElement('input');
                        villageNameInput.type = 'hidden';
                        villageNameInput.id = 'village_name';
                        villageNameInput.name = 'village_name';
                        form.appendChild(villageNameInput);
                    }
                    villageNameInput.value = villageName;

                    // Get the selected district and village names
                    const districtSelect = document.querySelector('#district');
                    const districtOption = districtSelect.options[districtSelect.selectedIndex];
                    const districtName = districtOption.textContent;

                    const villageOption = this.options[this.selectedIndex];
                    const villageNameForPostal = villageOption.textContent;

                    // Validate postal code based on both district and village
                    validatePostalCodeByDistrictAndVillage(districtName, villageNameForPostal);
                }
            });

            // Validate postal code based on district name only
            function validatePostalCodeByDistrict(districtName) {
                fetch('/regions/validate-postal-code', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            district: districtName
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.suggestion) {
                            // Auto-populate the postal code field with the suggestion only if field is empty
                            const postalInput = document.getElementById('postal_code');
                            const oldValue = postalInput.value;

                            if (!oldValue || oldValue.length === 0) {
                                postalInput.value = data.suggestion;
                                showPostalCodeFeedback('Kode pos ' + data.suggestion +
                                    ' diisi secara otomatis berdasarkan kecamatan ' + districtName,
                                    'success');
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Error validating postal code:', err);
                    });
            }

            // Validate postal code based on both district and village
            function validatePostalCodeByDistrictAndVillage(districtName, villageName) {
                fetch('/regions/validate-postal-code', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            district: districtName,
                            village: villageName
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.postal_code) {
                            // Auto-populate the postal code field with the specific village code
                            const postalInput = document.getElementById('postal_code');
                            postalInput.value = data.postal_code;
                            showPostalCodeFeedback(data.message, 'success');
                        } else if (data.success && data.suggestion) {
                            // Fallback to district-level suggestion
                            const postalInput = document.getElementById('postal_code');
                            const oldValue = postalInput.value;

                            if (!oldValue || oldValue.length === 0) {
                                postalInput.value = data.suggestion;
                                showPostalCodeFeedback('Kode pos ' + data.suggestion +
                                    ' diisi secara otomatis berdasarkan kecamatan ' + districtName,
                                    'success');
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Error validating postal code:', err);
                    });
            }

            // Show feedback for postal code validation
            function showPostalCodeFeedback(message, type) {
                // Remove any existing feedback
                const existingFeedback = document.querySelector('#postal_code').closest('.mb-3').querySelector(
                    '.postal-feedback');
                if (existingFeedback) {
                    existingFeedback.remove();
                }

                // Create feedback element
                const feedback = document.createElement('div');
                feedback.className = 'postal-feedback small mt-1 ' + (type === 'success' ? 'text-success' :
                    'text-warning');
                feedback.textContent = message;

                // Insert after the postal code input
                document.getElementById('postal_code').closest('.mb-3').appendChild(feedback);

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    if (feedback.parentNode) {
                        feedback.remove();
                    }
                }, 5000);
            }

            // Validate postal code input in real-time
            document.getElementById('postal_code').addEventListener('blur', function() {
                const postalCode = this.value.trim();
                if (postalCode.length > 0 && postalCode.length !== 5) {
                    showPostalCodeFeedback('Kode pos harus terdiri dari 5 digit angka', 'warning');
                    return;
                }

                if (postalCode.length === 5 && /^\d+$/.test(postalCode)) {
                    // Get the selected district and village names for validation context
                    const districtSelect = document.querySelector('#district');
                    const villageSelect = document.querySelector('#village');

                    if (districtSelect && districtSelect.value) {
                        const districtOption = districtSelect.options[districtSelect.selectedIndex];
                        const districtName = districtOption.textContent;

                        let validationData = {
                            district: districtName
                        };

                        // Include village if selected
                        if (villageSelect && villageSelect.value) {
                            const villageOption = villageSelect.options[villageSelect.selectedIndex];
                            const villageName = villageOption.textContent;
                            validationData.village = villageName;
                        }

                        // Validate the entered postal code
                        fetch('/regions/validate-postal-code', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify(validationData)
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // If we have a specific postal code for village, check against it
                                    if (data.postal_code) {
                                        if (parseInt(postalCode) !== data.postal_code) {
                                            showPostalCodeFeedback('Kode pos ' + postalCode +
                                                ' mungkin tidak sesuai dengan kelurahan ' +
                                                validationData.village + '. Seharusnya: ' + data
                                                .postal_code, 'warning');
                                        } else {
                                            // Valid code, remove any previous warnings
                                            const existingFeedback = document.querySelector(
                                                '#postal_code').closest('.mb-3').querySelector(
                                                '.postal-feedback');
                                            if (existingFeedback && existingFeedback.classList.contains(
                                                    'text-warning')) {
                                                existingFeedback.remove();
                                            }
                                        }
                                    }
                                    // If we have specific postal codes list, check against it
                                    else if (data.postal_codes && Array.isArray(data.postal_codes)) {
                                        // Convert postal code to integer for comparison
                                        const postalCodeInt = parseInt(postalCode);
                                        const isValid = data.postal_codes.some(code => parseInt(
                                            code) === postalCodeInt);

                                        if (!isValid) {
                                            showPostalCodeFeedback('Kode pos ' + postalCode +
                                                ' mungkin tidak sesuai dengan kecamatan ' +
                                                districtName + '. Valid: ' + data.postal_codes.join(
                                                    ', '), 'warning');
                                        } else {
                                            // Valid code, remove any previous warnings
                                            const existingFeedback = document.querySelector(
                                                '#postal_code').closest('.mb-3').querySelector(
                                                '.postal-feedback');
                                            if (existingFeedback && existingFeedback.classList.contains(
                                                    'text-warning')) {
                                                existingFeedback.remove();
                                            }
                                        }
                                    }
                                }
                            })
                            .catch(err => {
                                console.error('Error validating postal code:', err);
                            });
                    }
                } else if (postalCode.length > 0) {
                    showPostalCodeFeedback('Kode pos harus terdiri dari 5 digit angka', 'warning');
                }
            });

            // Initial load - hide province, city, district, village, and postal code if country is not Indonesia
            fetchCountries();

            // Add a small delay to ensure country is set properly
            setTimeout(function() {
                const savedCountry = "{{ $muzakki->country ?? '' }}";
                if (savedCountry && savedCountry.toLowerCase() !== 'indonesia') {
                    document.querySelector('#province').closest('.mb-3').style.display = 'none';
                    document.querySelector('#city').closest('.mb-3').style.display = 'none';
                    document.querySelector('#district').closest('.mb-3').style.display = 'none';
                    document.querySelector('#village').closest('.mb-3').style.display = 'none';
                    document.querySelector('#postal_code').closest('.mb-3').style.display = 'none';
                } else if (savedCountry && savedCountry.toLowerCase() === 'indonesia') {
                    // Make sure province dropdown is visible for Indonesia
                    document.querySelector('#province').closest('.mb-3').style.display = 'block';
                    document.querySelector('#city').closest('.mb-3').style.display = 'block';
                    document.querySelector('#district').closest('.mb-3').style.display = 'block';
                    document.querySelector('#village').closest('.mb-3').style.display = 'block';
                    document.querySelector('#postal_code').closest('.mb-3').style.display = 'block';

                    // Load provinces and set the selected value
                    fetchProvinces();
                }

                // Ensure country value is set correctly
                if (savedCountry) {
                    countrySelect.value = savedCountry;
                }
            }, 500);

            // Add phone_verified input if user is already verified
            if (<?php echo $muzakki->phone_verified ? 'true' : 'false'; ?>) {
                const phoneVerifiedInput = document.createElement('input');
                phoneVerifiedInput.type = 'hidden';
                phoneVerifiedInput.name = 'phone_verified';
                phoneVerifiedInput.value = '1';
                phoneVerifiedInput.id = 'phone_verified_input';
                form.appendChild(phoneVerifiedInput);
            }

            // Ensure all required hidden inputs exist before form load
            // Create phone_verified input if phone is already verified
            const isPhoneVerified = <?php echo $muzakki->phone_verified ? 'true' : 'false'; ?>;
            if (isPhoneVerified) {
                let phoneVerifiedInput = document.getElementById('phone_verified_input');
                if (!phoneVerifiedInput) {
                    phoneVerifiedInput = document.createElement('input');
                    phoneVerifiedInput.type = 'hidden';
                    phoneVerifiedInput.name = 'phone_verified';
                    phoneVerifiedInput.value = '1';
                    phoneVerifiedInput.id = 'phone_verified_input';
                    form.appendChild(phoneVerifiedInput);
                }
            }

            // Create country_name input with existing value
            const existingCountry = "{{ $muzakki->country ?? 'Indonesia' }}";
            if (existingCountry) {
                let countryNameInput = document.getElementById('country_name');
                if (!countryNameInput) {
                    countryNameInput = document.createElement('input');
                    countryNameInput.type = 'hidden';
                    countryNameInput.id = 'country_name';
                    countryNameInput.name = 'country_name';
                    countryNameInput.value = existingCountry;
                    form.appendChild(countryNameInput);
                }
            }

            // ===================================
            // SMART OTP INPUT BEHAVIOR
            // ===================================
            function initializeSmartOTPInput() {
                const otpInputs = document.querySelectorAll('.otp-input');

                otpInputs.forEach((input, index) => {
                    // Auto-advance on input
                    input.addEventListener('input', function(e) {
                        const value = e.target.value;

                        // Only allow numbers
                        if (!/^\d*$/.test(value)) {
                            e.target.value = '';
                            return;
                        }

                        // Auto-advance to next input
                        if (value && index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }

                        checkOtpComplete();
                    });

                    // Smart backspace behavior
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Backspace') {
                            if (!e.target.value && index > 0) {
                                // Move to previous input if current is empty
                                otpInputs[index - 1].focus();
                                otpInputs[index - 1].value = '';
                            }
                        } else if (e.key === 'ArrowLeft' && index > 0) {
                            otpInputs[index - 1].focus();
                        } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    });

                    // Enhanced paste behavior
                    input.addEventListener('paste', function(e) {
                        e.preventDefault();
                        const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');

                        for (let i = 0; i < pastedData.length && index + i < otpInputs
                            .length; i++) {
                            otpInputs[index + i].value = pastedData[i];
                        }

                        const lastIndex = Math.min(index + pastedData.length - 1, otpInputs.length -
                            1);
                        otpInputs[lastIndex].focus();

                        checkOtpComplete();
                    });

                    // Select all on focus for easier editing
                    input.addEventListener('focus', function() {
                        this.select();
                    });
                });
            }

            // ===================================
            // AUTO-VERIFY WHEN COMPLETE
            // ===================================
            function checkOtpComplete() {
                const otpInputs = document.querySelectorAll('.otp-input');
                const verifyOtpBtn = document.getElementById('verifyOtpBtn');
                const allFilled = Array.from(otpInputs).every(input => input.value.length === 1);

                verifyOtpBtn.disabled = !allFilled;

                // Auto-submit if all filled
                if (allFilled) {
                    // Add small delay for more natural UX
                    setTimeout(() => {
                        verifyOtpBtn.click();
                    }, 300);
                }
            }

            // ===================================
            // IMPROVED VERIFY OTP
            // ===================================
            function improvedVerifyOTP() {
                const verifyOtpBtn = document.getElementById('verifyOtpBtn');
                const otpInputs = document.querySelectorAll('.otp-input');

                verifyOtpBtn.addEventListener('click', function() {
                    const otp = Array.from(otpInputs).map(input => input.value).join('');

                    // Show loading state
                    const originalText = verifyOtpBtn.innerHTML;
                    verifyOtpBtn.disabled = true;
                    verifyOtpBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

                    fetch('/verify-otp', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                otp: otp
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Success animation
                                verifyOtpBtn.innerHTML =
                                    '<i class="bi bi-check-circle-fill me-2"></i>Berhasil!';
                                verifyOtpBtn.classList.add('btn-success');

                                setTimeout(() => {
                                    const otpModal = bootstrap.Modal.getInstance(document
                                        .getElementById('otpModal'));
                                    otpModal.hide();

                                    // Update UI
                                    updateVerificationStatus();

                                    showToast('Nomor WhatsApp berhasil diverifikasi!',
                                        'success');
                                }, 1000);
                            } else {
                                // Error state
                                verifyOtpBtn.innerHTML = originalText;
                                verifyOtpBtn.disabled = false;

                                // Shake animation for error
                                otpInputs.forEach(input => {
                                    input.classList.add('shake');
                                    input.value = '';
                                });

                                setTimeout(() => {
                                    otpInputs.forEach(input => input.classList.remove('shake'));
                                    otpInputs[0].focus();
                                }, 500);

                                showToast('Kode OTP salah. Silakan coba lagi.', 'warning');
                            }
                        })
                        .catch(error => {
                            verifyOtpBtn.innerHTML = originalText;
                            verifyOtpBtn.disabled = false;
                            showToast('Terjadi kesalahan. Silakan coba lagi.', 'warning');
                        });
                });
            }

            // ===================================
            // UPDATE VERIFICATION STATUS
            // ===================================
            function updateVerificationStatus() {
                const verifyBtn = document.getElementById('verifyPhoneBtn');
                const verifyText = document.getElementById('verifyButtonText');
                const verifyCheckmark = document.getElementById('verifyCheckmark');

                verifyText.style.display = 'none';
                verifyCheckmark.style.display = 'inline-block';
                verifyBtn.style.backgroundColor = '#198754'; // Bootstrap success color
                verifyBtn.disabled = true;

                // Show the verified alert and hide the warning alert
                const warningAlert = document.querySelector('.alert-warning');
                if (warningAlert) {
                    warningAlert.style.display = 'none';
                }

                const verifiedAlert = document.getElementById('verifiedAlert');
                if (verifiedAlert) {
                    verifiedAlert.style.display = 'block';
                } else {
                    // Create the verified alert if it doesn't exist
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success border-0 mt-2 py-2 px-3 small';
                    alertDiv.id = 'verifiedAlert';
                    alertDiv.innerHTML =
                        '<i class="bi bi-check-circle-fill me-1"></i> Nomor telepon sudah diverifikasi';
                    document.querySelector('[for="phone"]').parentNode.appendChild(alertDiv);
                }

                // Create the not verified alert if it doesn't exist (for future use)
                const notVerifiedAlert = document.getElementById('notVerifiedAlert');
                if (!notVerifiedAlert) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-warning border-0 mt-2 py-2 px-3 small';
                    alertDiv.id = 'notVerifiedAlert';
                    alertDiv.innerHTML =
                        '<i class="bi bi-exclamation-triangle me-1"></i> Mohon verifikasi nomor telepon Anda';
                    document.querySelector('[for="phone"]').parentNode.appendChild(alertDiv);
                    alertDiv.style.display = 'none'; // Hide it initially since we're verified
                }

                // Update the phone_verified field in the form
                let phoneVerifiedInput = document.getElementById('phone_verified_input');
                if (!phoneVerifiedInput) {
                    phoneVerifiedInput = document.createElement('input');
                    phoneVerifiedInput.type = 'hidden';
                    phoneVerifiedInput.name = 'phone_verified';
                    phoneVerifiedInput.id = 'phone_verified_input';
                    document.getElementById('muzakkiEditForm').appendChild(phoneVerifiedInput);
                }
                phoneVerifiedInput.value = '1';
            }

            // ===================================
            // NON-INTRUSIVE NOTIFICATIONS
            // ===================================
            function showToast(message, type = 'info') {
                // Remove existing toast if any
                const existingToast = document.querySelector('.otp-toast');
                if (existingToast) {
                    existingToast.remove();
                }

                const toast = document.createElement('div');
                toast.className = `otp-toast otp-toast-${type}`;
                toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill'} me-2"></i>
                    <span>${message}</span>
                </div>
            `;

                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => toast.classList.add('show'), 10);

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        });

        function formatText(command) {
            document.execCommand(command, false, null);
            document.getElementById('bio_editor').focus();
        }

        function calculateCompletion() {
            const fields = [
                'name', 'email', 'phone', 'gender', 'address',
                'city', 'province', 'district', 'village',
                'postal_code', 'country', 'campaign_url',
                'profile_photo', 'ktp_photo', 'bio',
                'occupation', 'date_of_birth'
            ];

            let filled = 0;
            let total = fields.length;

            fields.forEach(field => {
                const element = document.getElementById(field) || document.querySelector(`[name="${field}"]`);

                // Special handling for select fields - check if a valid option is selected
                if (element) {
                    if (element.tagName === 'SELECT') {
                        // For village select, make sure a valid option is selected (not the placeholder)
                        if (field === 'village') {
                            if (element.value && element.value.trim() !== '' && element.selectedIndex > 0 && element
                                .value !== 'Pilih Kelurahan') {
                                filled++;
                            }
                        } else {
                            // For other select fields, make sure a valid option is selected (not the placeholder)
                            if (element.value && element.value.trim() !== '' && element.selectedIndex > 0) {
                                filled++;
                            }
                        }
                    } else {
                        // For text fields, check if they have a value
                        if (element.value && element.value.trim() !== '') {
                            filled++;
                        }
                    }
                }
            });

            const percentage = Math.round((filled / total) * 100);

            // Update progress bar
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            if (progressBar) {
                progressBar.style.width = percentage + '%';
                progressBar.style.transition = 'width 0.6s ease-in-out';
            }

            if (progressText) {
                progressText.textContent = percentage + '%';
            }

            // Update progress bar color based on completion percentage
            if (progressBar) {
                if (percentage < 30) {
                    progressBar.style.backgroundColor = '#dc3545'; // Red
                } else if (percentage < 70) {
                    progressBar.style.backgroundColor = '#ffc107'; // Yellow
                } else {
                    progressBar.style.backgroundColor = '#28a745'; // Green
                }
            }

            return percentage;
        }
    </script>
@endpush

@php
    function calculateProfileCompletion($muzakki)
    {
        // This should match exactly what's in the Muzakki model's getProfileCompletenessAttribute method
        $fields = [
            'name' => $muzakki->name,
            'email' => $muzakki->email,
            'phone' => $muzakki->phone,
            'gender' => $muzakki->gender,
            'address' => $muzakki->address,
            'city' => $muzakki->city,
            'province' => $muzakki->province,
            'district' => $muzakki->district,
            'village' => $muzakki->village,
            'postal_code' => $muzakki->postal_code,
            'country' => $muzakki->country,
            'campaign_url' => $muzakki->campaign_url,
            'profile_photo' => $muzakki->profile_photo,
            'ktp_photo' => $muzakki->ktp_photo,
            'bio' => $muzakki->bio,
            'occupation' => $muzakki->occupation,
            'date_of_birth' => $muzakki->date_of_birth,
        ];

        $filledFields = 0;
        $totalFields = count($fields);

        foreach ($fields as $value) {
            if (!is_null($value) && $value !== '') {
                $filledFields++;
            }
        }

        return $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
    }
@endphp
