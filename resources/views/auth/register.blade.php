@extends('layouts.main')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-green-900 py-12">
        <div class="w-full max-w-md px-6">
            <div class="bg-white rounded-lg shadow-md p-8">
                <!-- Back Button & Title -->
                <div class="mb-8">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-green-600 hover:text-green-700 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Bergabung dengan SIPZIS</h1>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            name="name" value="{{ old('name') }}" placeholder="John Doe" required autocomplete="name"
                            autofocus>
                        @error('name')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            name="email" value="{{ old('email') }}" placeholder="johndoe@example.com" required
                            autocomplete="email">
                        @error('email')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- No. Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="country_code" id="country_code" value="+62">
                        <input id="phone" name="phone" type="tel" placeholder="81234567890" required
                            autocomplete="off">
                        <p class="text-xs text-gray-500 mt-1" id="phone_error"></p>
                        @error('phone')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="password" type="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror pr-12"
                                name="password" placeholder="Masukkan kata sandi" required autocomplete="new-password">
                            <button
                                class="absolute inset-y-0 right-0 flex items-center pr-4 bg-transparent border-0 text-gray-500 cursor-pointer"
                                type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="mt-2 space-y-1 text-xs text-gray-500">
                            <p class="flex items-center">
                                <span class="w-4 h-4 rounded-full border border-gray-300 mr-2" id="length-check"></span>
                                8 Karakter atau lebih
                            </p>
                            <p class="flex items-center">
                                <span class="w-4 h-4 rounded-full border border-gray-300 mr-2" id="capital-check"></span>
                                1 huruf kapital
                            </p>
                            <p class="flex items-center">
                                <span class="w-4 h-4 rounded-full border border-gray-300 mr-2" id="number-check"></span>
                                1 angka
                            </p>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" type="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent pr-12"
                                name="password_confirmation" placeholder="Masukkan ulang kata sandi" required
                                autocomplete="new-password">
                            <button
                                class="absolute inset-y-0 right-0 flex items-center pr-4 bg-transparent border-0 text-gray-500 cursor-pointer"
                                type="button" id="togglePasswordConfirm">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden fields with null values -->
                    <input type="hidden" name="nik" value="">
                    <input type="hidden" name="gender" value="">
                    <input type="hidden" name="address" value="">
                    <input type="hidden" name="city" value="">
                    <input type="hidden" name="province" value="">
                    <input type="hidden" name="occupation" value="">
                    <input type="hidden" name="monthly_income" value="">
                    <input type="hidden" name="date_of_birth" value="">

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 mt-6">
                        Daftar
                    </button>
                </form>

                <!-- Login Link -->
                <p class="text-center text-sm text-gray-600 mt-6">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-medium">
                        Masuk
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirm = document.getElementById('password_confirmation');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });

            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirm.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });

            // Password Validation
            const lengthCheck = document.getElementById('length-check');
            const capitalCheck = document.getElementById('capital-check');
            const numberCheck = document.getElementById('number-check');

            password.addEventListener('input', function() {
                const value = this.value;

                // Check length
                if (value.length >= 8) {
                    lengthCheck.classList.add('bg-green-500', 'border-green-500');
                    lengthCheck.classList.remove('border-gray-300');
                } else {
                    lengthCheck.classList.remove('bg-green-500', 'border-green-500');
                    lengthCheck.classList.add('border-gray-300');
                }

                // Check capital letter
                if (/[A-Z]/.test(value)) {
                    capitalCheck.classList.add('bg-green-500', 'border-green-500');
                    capitalCheck.classList.remove('border-gray-300');
                } else {
                    capitalCheck.classList.remove('bg-green-500', 'border-green-500');
                    capitalCheck.classList.add('border-gray-300');
                }

                // Check number
                if (/[0-9]/.test(value)) {
                    numberCheck.classList.add('bg-green-500', 'border-green-500');
                    numberCheck.classList.remove('border-gray-300');
                } else {
                    numberCheck.classList.remove('bg-green-500', 'border-green-500');
                    numberCheck.classList.add('border-gray-300');
                }
            });
        });
    </script>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.css">
    <style>
        /* Optimized styles for intl-tel-input */
        .iti {
            width: 100%;
            display: block;
        }

        .iti__country-list {
            z-index: 9999;
        }

        .iti__tel-input {
            width: 100%;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem !important;
            font-size: 1rem;
            line-height: 1.5;
        }

        .iti__tel-input:focus {
            border-color: #10b981 !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1) !important;
        }

        .iti__selected-flag {
            padding: 0 0 0 1rem !important;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .iti__tel-input.border-red-300 {
            border-color: #fca5a5 !important;
        }

        .iti__tel-input.border-emerald-300 {
            border-color: #6ee7b7 !important;
        }

        .iti--separate-dial-code .iti__tel-input,
        #phone.iti__tel-input {
            padding-left: 90px !important;
        }

        .iti--separate-dial-code .iti__selected-dial-code {
            margin-left: 10px !important;
            font-weight: 500;
            color: #374151;
            font-size: 1rem;
        }

        .iti--separate-dial-code .iti__selected-flag {
            background-color: transparent !important;
            padding-right: 10px !important;
        }

        .iti__flag-container {
            padding-right: 10px !important;
        }

        @error('phone')
            .iti__tel-input {
                border-color: #ef4444 !important;
            }
        @enderror
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js" defer></script>
    <script>
        let iti;
        const itiConfig = {
            initialCountry: "id",
            preferredCountries: ["id", "my", "sg"],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js",
            separateDialCode: true,
            autoPlaceholder: "aggressive",
            formatOnDisplay: true,
            nationalMode: false
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize intl-tel-input
            const phoneInput = document.getElementById('phone');
            const countryCodeInput = document.getElementById('country_code');

            if (phoneInput && window.intlTelInput) {
                iti = window.intlTelInput(phoneInput, {
                    ...itiConfig,
                    customPlaceholder: (p) => "e.g. " + p
                });

                // Update hidden country code on country change
                phoneInput.addEventListener('countrychange', function() {
                    const dialCode = '+' + iti.getSelectedCountryData().dialCode;
                    countryCodeInput.value = dialCode;
                });

                // Initialize country code on load
                const dialCode = '+' + iti.getSelectedCountryData().dialCode;
                countryCodeInput.value = dialCode;

                // Validate phone on blur
                phoneInput.addEventListener('blur', function() {
                    validatePhone(iti, phoneInput);
                });

                // Clear error on input
                phoneInput.addEventListener('input', function() {
                    document.getElementById('phone_error').textContent = '';
                    phoneInput.classList.remove('border-red-300', 'border-emerald-300');
                });

                // Prevent national format (0 prefix) for Indonesia only
                // Other countries will use their standard format
                let isUpdating = false;
                phoneInput.addEventListener('input', function() {
                    if (isUpdating) return;

                    const selectedCountry = iti.getSelectedCountryData();
                    if (selectedCountry.iso2 === 'id') {
                        const currentNumber = iti.getNumber();
                        // Check if it has leading 0 after +62 (national format)
                        if (currentNumber.match(/^\+620/)) {
                            isUpdating = true;
                            const cleanNumber = currentNumber.replace(/^\+620/, '+62').replace(/\D/g, '')
                                .replace(/^62/, '');
                            if (cleanNumber) {
                                iti.setNumber('+62' + cleanNumber);
                            }
                            setTimeout(() => {
                                isUpdating = false;
                            }, 100);
                        }
                    }
                });

                // Prevent national format when country changes
                phoneInput.addEventListener('countrychange', function() {
                    const selectedCountry = iti.getSelectedCountryData();
                    if (selectedCountry.iso2 === 'id') {
                        const currentNumber = iti.getNumber();
                        const cleanNumber = currentNumber.replace(/^\+62/, '').replace(/^0+/, '');
                        if (cleanNumber && cleanNumber !== currentNumber.replace(/^\+62/, '')) {
                            iti.setNumber('+62' + cleanNumber);
                        }
                    }
                });

                // Set old value if exists
                @if (old('phone'))
                    phoneInput.value = '{{ old('phone') }}';
                @endif
            }

            // Handle form submit
            const form = phoneInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (phoneInput && iti) {
                        // Validate phone before submit
                        if (!validatePhone(iti, phoneInput)) {
                            e.preventDefault();
                            return false;
                        }

                        // Update country code
                        const selected = iti.getSelectedCountryData();
                        const dialCode = '+' + selected.dialCode;
                        countryCodeInput.value = dialCode;

                        // Get E.164 format and extract national number
                        const e164 = iti.getNumber();

                        // Special handling for Indonesia: remove leading 0 if present
                        if (selected.iso2 === 'id') {
                            const cleanNumber = e164.replace(/^\+620/, '+62');
                            if (cleanNumber && cleanNumber.startsWith(dialCode)) {
                                phoneInput.value = cleanNumber.replace(dialCode, '');
                            } else if (e164 && e164.startsWith(dialCode)) {
                                phoneInput.value = e164.replace(dialCode, '');
                            }
                        } else {
                            // For other countries, use the number as-is
                            if (e164 && e164.startsWith(dialCode)) {
                                phoneInput.value = e164.replace(dialCode, '');
                            }
                        }
                    }
                });
            }
        });

        function validatePhone(itiInstance, inputElement) {
            if (!inputElement.value.trim()) {
                return true; // Allow empty for now, form validation will handle required
            }
            const isValid = itiInstance.isValidNumber();
            const errorEl = document.getElementById('phone_error');

            inputElement.classList.toggle('border-red-300', !isValid);
            inputElement.classList.toggle('border-emerald-300', isValid);

            if (errorEl) {
                if (isValid) {
                    errorEl.textContent = '';
                    errorEl.classList.remove('text-red-500');
                } else {
                    const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
                    const errorMsg = errorMap[itiInstance.getValidationError()] || "Nomor tidak valid";
                    errorEl.textContent = errorMsg;
                    errorEl.classList.add('text-red-500');
                }
            }
            return isValid;
        }
    </script>
@endpush
