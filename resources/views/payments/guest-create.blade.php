@extends('layouts.main')

@section('title', 'Donasi Program - SIPZIS')

{{-- Add CSS untuk intl-tel-input --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.css">
    <style>
        /* Optimized styles for intl-tel-input */
        .iti {
            width: 100%;
            display: block
        }

        .iti__country-list {
            z-index: 9999
        }

        .iti__tel-input {
            width: 100%;
            border: 2px solid #e5e7eb !important;
            border-radius: .75rem !important;
            padding: .75rem 1rem !important;
            font-size: 1rem;
            line-height: 1.5
        }

        .iti__tel-input:focus {
            border-color: #10b981 !important;
            outline: none !important;
            box-shadow: none !important
        }

        .iti__selected-flag {
            padding: 0 0 0 1rem !important;
            border-radius: .75rem 0 0 .75rem
        }

        .iti__tel-input.border-red-300 {
            border-color: #fca5a5 !important
        }

        .iti__tel-input.border-emerald-300 {
            border-color: #6ee7b7 !important
        }

        .iti--separate-dial-code .iti__tel-input,
        #phone_input.iti__tel-input,
        #phone_input_optional.iti__tel-input {
            padding-left: 90px !important
        }

        .iti--separate-dial-code .iti__selected-dial-code {
            margin-left: 10px !important;
            font-weight: 500;
            color: #374151;
            font-size: 1rem
        }

        .iti--separate-dial-code .iti__selected-flag {
            background-color: transparent !important;
            padding-right: 10px !important
        }

        .iti__flag-container {
            padding-right: 10px !important
        }
    </style>
@endpush

@section('content')
    <div
        class="relative bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 overflow-hidden min-h-screen flex items-center justify-center">
        {{-- Background decorative blobs --}}
        <div class="absolute inset-0">
            <div
                class="absolute top-0 left-0 w-40 h-40 bg-emerald-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse">
            </div>
            <div
                class="absolute top-0 right-0 w-40 h-40 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-40 h-40 bg-cyan-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse animation-delay-4000">
            </div>
        </div>

        <div class="relative container mx-auto px-4 py-16">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-8">

                    {{-- Program Information Header --}}
                    <div class="flex items-start sm:items-center gap-4 border-b border-gray-200 pb-6 mb-6">
                        @php
                            if (isset($program)) {
                                $imageUrl = $program->image_url;
                            } else {
                                $categoryProgram = \App\Models\Program::byCategory($programCategory)->first();
                                $imageUrl = $categoryProgram ? $categoryProgram->image_url : asset('img/masjid.webp');
                            }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Program {{ $displayTitle }}"
                            class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-lg shadow-md flex-shrink-0">

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Anda akan berdonasi untuk:</p>
                            <h2 class="text-lg sm:text-xl font-bold {{ $textColor }} leading-tight">
                                {{ $displayTitle }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">{{ $displaySubtitle }}</p>
                        </div>
                    </div>

                    <form id="donation-form" class="space-y-6" autocomplete="off">
                        @csrf

                        {{-- Hidden inputs --}}
                        <input type="hidden" name="program_category" value="{{ $programCategory }}" autocomplete="off">
                        @if (isset($program))
                            <input type="hidden" name="program_id" value="{{ $program->id }}" autocomplete="off">
                        @endif
                        <input type="hidden" name="zakat_type_id"
                            value="{{ request()->query('type') === 'profesi' ? 3 : (request()->query('type') === 'harta' ? 1 : (request()->query('type') === 'mal' ? 2 : '')) }}"
                            autocomplete="off">
                        <input type="hidden" name="program_type_id" id="program_type_id"
                            value="{{ request()->query('program_type_id') }}" autocomplete="off">
                        <input type="hidden" name="zakat_amount" id="zakat_amount" value="0" autocomplete="off">
                        <input type="hidden" name="paid_amount" id="paid_amount" value="0" autocomplete="off">

                        {{-- Hidden field untuk menyimpan nomor lengkap dengan country code --}}
                        <input type="hidden" name="donor_phone" id="donor_phone_full" autocomplete="off">

                        {{-- Nominal Donasi dengan Pilihan Cepat --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Nominal Donasi *</label>

                            <div class="mb-6">
                                <h3 class="text-gray-700 font-semibold mb-3">Nominal Donasi</h3>
                                <div class="flex flex-wrap gap-3 justify-center">
                                    <button type="button"
                                        class="quick-amount-btn bg-white text-green-800 border border-black-600 rounded-full px-6 py-3 font-semibold shadow-sm hover:bg-black-50 hover:shadow-md transition-all duration-200 ease-in-out"
                                        data-amount="10000">Rp 10.000</button>
                                    <button type="button"
                                        class="quick-amount-btn bg-white text-green-800 border border-black-600 rounded-full px-6 py-3 font-semibold shadow-sm hover:bg-black-50 hover:shadow-md transition-all duration-200 ease-in-out"
                                        data-amount="20000">Rp 20.000</button>
                                    <button type="button"
                                        class="quick-amount-btn bg-white text-green-800 border border-black-600 rounded-full px-6 py-3 font-semibold shadow-sm hover:bg-black-50 hover:shadow-md transition-all duration-200 ease-in-out"
                                        data-amount="50000">Rp 50.000</button>
                                    <button type="button"
                                        class="quick-amount-btn bg-white text-green-800 border border-black-600 rounded-full px-6 py-3 font-semibold shadow-sm hover:bg-black-50 hover:shadow-md transition-all duration-200 ease-in-out"
                                        data-amount="100000">Rp 100.000</button>
                                    <button type="button"
                                        class="quick-amount-btn bg-white text-green-800 border border-black rounded-full px-6 py-3 font-semibold shadow-sm hover:bg-gray-50 hover:shadow-md transition-all duration-200 ease-in-out"
                                        data-amount="custom">Lainnya</button>
                                </div>
                            </div>

                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">Rp.</span>
                                <input type="text" id="donation_amount_display" inputmode="numeric"
                                    oninput="formatAndSetValues(this)"
                                    class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:border-emerald-500 focus:outline-none transition-colors"
                                    placeholder="Masukkan nominal lain" required autocomplete="off">
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Minimal donasi Rp 10.000</p>
                        </div>

                        <input type="hidden" name="payment_method" value="" autocomplete="off">

                        {{-- Donor Information Fields --}}
                        @if (!isset($loggedInMuzakki))
                            {{-- Guest User Form Fields --}}
                            <div>
                                <label for="donor_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="donor_name" name="donor_name"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-emerald-500 focus:outline-none transition-colors"
                                    placeholder="Masukkan nama lengkap Anda" required autocomplete="off">
                            </div>

                            <div>
                                <label for="phone_input" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor HP/WhatsApp <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500 font-normal">(untuk notifikasi)</span>
                                </label>
                                <input type="tel" id="phone_input" placeholder="81234567890" required
                                    autocomplete="off">
                                <p class="text-xs text-gray-500 mt-1" id="phone_error"></p>
                            </div>

                            <div>
                                <label for="donor_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="donor_email" name="donor_email"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-emerald-500 focus:outline-none transition-colors"
                                    placeholder="email@contoh.com" required autocomplete="off">
                            </div>
                        @else
                            {{-- Logged in users --}}
                            @if (!$loggedInMuzakki->phone)
                                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-4 mb-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-yellow-800 mb-1">Nomor HP Belum Terdaftar</h4>
                                            <p class="text-sm text-yellow-700 mb-2">
                                                Untuk menerima notifikasi WhatsApp, silakan isi nomor HP di bawah atau
                                                <a href="{{ route('muzakki.edit', $loggedInMuzakki->id) }}"
                                                    class="underline font-semibold hover:text-yellow-900">
                                                    lengkapi profile Anda
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="phone_input_optional"
                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor HP/WhatsApp <span class="text-gray-500 text-xs font-normal">(opsional)</span>
                                    </label>
                                    <input type="tel" id="phone_input_optional" placeholder="81234567890 (opsional)"
                                        autocomplete="off">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Lewati jika tidak ingin notifikasi WhatsApp (notifikasi tetap dikirim via email)
                                    </p>
                                </div>

                                <input type="hidden" name="donor_name" value="{{ $loggedInMuzakki->name }}"
                                    autocomplete="off">
                                <input type="hidden" name="donor_email" value="{{ $loggedInMuzakki->email }}"
                                    autocomplete="off">
                            @else
                                <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-4 mb-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-emerald-800 mb-1">Donasi sebagai:
                                                {{ $loggedInMuzakki->name }}</h4>
                                            <p class="text-sm text-emerald-700">
                                                ✉️ Email: {{ $loggedInMuzakki->email }}<br>
                                                📱 WhatsApp: {{ $loggedInMuzakki->phone }}
                                            </p>
                                            <p class="text-xs text-emerald-600 mt-2">
                                                ✅ Notifikasi akan dikirim via WhatsApp & Email
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="donor_name" value="{{ $loggedInMuzakki->name }}"
                                    autocomplete="off">
                                <input type="hidden" id="donor_phone_hidden" value="{{ $loggedInMuzakki->phone }}"
                                    autocomplete="off">
                                <input type="hidden" name="donor_email" value="{{ $loggedInMuzakki->email }}"
                                    autocomplete="off">
                            @endif
                        @endif

                        {{-- Message/Doa field --}}
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Tulis pesan atau
                                doa</label>
                            <textarea name="notes" rows="4"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-emerald-500 focus:outline-none transition-colors"
                                placeholder="Tulis pesan atau doa Anda di sini..." autocomplete="off"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-yellow-500 text-white px-8 py-4 rounded-xl hover:bg-yellow-600 font-bold text-lg">
                                SELANJUTNYA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Add JS untuk intl-tel-input --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js" defer></script>
    <script>
        let iti, itiOptional;
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
            const phoneInput = document.querySelector("#phone_input");
            if (phoneInput) {
                iti = window.intlTelInput(phoneInput, {
                    ...itiConfig,
                    customPlaceholder: (p) => "e.g. " + p
                });
                phoneInput.addEventListener('blur', () => validatePhone(iti, phoneInput));
                phoneInput.addEventListener('input', () => {
                    document.getElementById('phone_error').textContent = '';
                    phoneInput.classList.remove('border-red-300', 'border-emerald-300');
                });
            }

            const phoneInputOptional = document.querySelector("#phone_input_optional");
            if (phoneInputOptional) {
                itiOptional = window.intlTelInput(phoneInputOptional, itiConfig);
                phoneInputOptional.addEventListener('blur', () => {
                    if (phoneInputOptional.value.trim()) validatePhone(itiOptional, phoneInputOptional);
                });
            }

            // Handle URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const amount = urlParams.get('amount');
            if (amount) {
                document.getElementById('donation_amount_display').value = new Intl.NumberFormat('id-ID').format(
                    amount);
                document.getElementById('paid_amount').value = amount;
                const btn = document.querySelector(`.quick-amount-btn[data-amount="${amount}"]`);
                if (btn) btn.classList.add('selected');
            }

            const programTypeId = urlParams.get('program_type_id');
            if (programTypeId) document.getElementById('program_type_id').value = programTypeId;

            const programCategory = urlParams.get('category');
            if (programCategory) document.querySelector('input[name="program_category"]').value = programCategory;

            const existingPhone = document.getElementById('donor_phone_hidden');
            if (existingPhone?.value) document.getElementById('donor_phone_full').value = existingPhone.value;
        });

        function validatePhone(itiInstance, inputElement) {
            if (!inputElement.value.trim()) return true;
            const isValid = itiInstance.isValidNumber();
            const errorEl = inputElement.id === 'phone_input' ? document.getElementById('phone_error') : null;

            inputElement.classList.toggle('border-red-300', !isValid);
            inputElement.classList.toggle('border-emerald-300', isValid);

            if (errorEl) {
                if (isValid) {
                    errorEl.textContent = '';
                } else {
                    const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
                    errorEl.textContent = errorMap[itiInstance.getValidationError()] || "Nomor tidak valid";
                    errorEl.classList.add('text-red-500');
                }
            }
            return isValid;
        }

        function formatAndSetValues(el) {
            const raw = el.value.replace(/\D/g, '');
            document.getElementById('paid_amount').value = raw || 0;
            if (raw) {
                el.value = new Intl.NumberFormat('id-ID').format(raw);
                document.querySelectorAll('.quick-amount-btn').forEach(b => b.classList.remove('selected'));
            } else {
                el.value = '';
            }
        }

        document.querySelectorAll('.quick-amount-btn').forEach(button => {
            button.addEventListener('click', function() {
                const amount = this.dataset.amount;
                if (amount === 'custom') {
                    document.getElementById('donation_amount_display').focus();
                    return;
                }
                document.getElementById('donation_amount_display').value = new Intl.NumberFormat('id-ID')
                    .format(amount);
                document.getElementById('paid_amount').value = amount;
                document.querySelectorAll('.quick-amount-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        document.getElementById('donation-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Validate phones
            const phoneInput = document.querySelector("#phone_input");
            if (phoneInput && iti) {
                if (!validatePhone(iti, phoneInput)) return alert('Mohon periksa kembali nomor telepon Anda');
                document.getElementById('donor_phone_full').value = iti.getNumber();
            }

            const phoneInputOptional = document.querySelector("#phone_input_optional");
            if (phoneInputOptional?.value.trim() && itiOptional) {
                if (!validatePhone(itiOptional, phoneInputOptional)) return alert(
                    'Mohon periksa kembali nomor telepon Anda');
                document.getElementById('donor_phone_full').value = itiOptional.getNumber();
            }

            submitButton.disabled = true;
            submitButton.innerHTML = 'Memproses...';

            try {
                const response = await fetch('{{ route('guest.payment.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': new FormData(this).get('_token'),
                        'Accept': 'application/json',
                    },
                    body: new FormData(this)
                });
                const data = await response.json();
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Terjadi kesalahan: ' + (data.message || 'Silakan cek kembali data Anda.'));
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Tidak dapat terhubung ke server. Silakan coba lagi.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    </script>
@endpush
