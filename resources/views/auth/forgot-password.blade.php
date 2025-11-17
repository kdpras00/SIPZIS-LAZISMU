@extends('layouts.main')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-green-900">
        <div class="w-full max-w-md px-6">
            <div class="bg-white rounded-lg shadow-md p-8">
                <!-- Logo & Title -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">SIPZIS</h1>
                    <p class="text-gray-500 text-sm">Lupa Password</p>
                </div>

                <!-- Success Message -->
                @if(session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {!! session('status') !!}
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4" id="forgotPasswordForm">
                    @csrf

                    <p class="text-sm text-gray-600 mb-4">
                        Masukkan email Anda dan kami akan mengirimkan link untuk mereset password Anda.
                    </p>

                    <!-- Email Input -->
                    <div>
                        <input id="email" type="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email"
                            autofocus>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                        Kirim Link Reset Password
                    </button>
                </form>

                <!-- Back to Login Link -->
                <p class="text-center text-sm text-gray-600 mt-6">
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-medium">
                        ← Kembali ke Login
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Google reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recaptchaSiteKey = (function() {
                const fromBlade = '{{ config('services.recaptcha.site_key') }}';
                if (fromBlade && fromBlade.trim().length > 0 && fromBlade.indexOf('config(') === -1) {
                    return fromBlade.trim();
                }
                try {
                    const cfg = window.___grecaptcha_cfg || {};
                    const renderArr = cfg.render || [];
                    if (Array.isArray(renderArr) && renderArr.length > 0 && renderArr[0]) {
                        return renderArr[0];
                    }
                } catch (_) {}
                return '';
            })();

            const forgotPasswordForm = document.getElementById('forgotPasswordForm');

            // Generate reCAPTCHA v3 token before form submission
            forgotPasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Try to get reCAPTCHA token, but don't block if it fails
                const submitForm = () => {
                    forgotPasswordForm.submit();
                };

                // If reCAPTCHA is available, use it
                if (window.grecaptcha && window.grecaptcha.execute && recaptchaSiteKey) {
                    try {
                        window.grecaptcha.ready(function() {
                            window.grecaptcha.execute(recaptchaSiteKey, {
                                    action: 'password_reset'
                                })
                                .then(function(token) {
                                    let tokenInput = forgotPasswordForm.querySelector(
                                        'input[name="g-recaptcha-response"]');
                                    if (!tokenInput) {
                                        tokenInput = document.createElement('input');
                                        tokenInput.type = 'hidden';
                                        tokenInput.name = 'g-recaptcha-response';
                                        forgotPasswordForm.appendChild(tokenInput);
                                    }
                                    tokenInput.value = token;

                                    let actionInput = forgotPasswordForm.querySelector(
                                        'input[name="g-recaptcha-action"]');
                                    if (!actionInput) {
                                        actionInput = document.createElement('input');
                                        actionInput.type = 'hidden';
                                        actionInput.name = 'g-recaptcha-action';
                                        forgotPasswordForm.appendChild(actionInput);
                                    }
                                    actionInput.value = 'password_reset';

                                    submitForm();
                                })
                                .catch(function(err) {
                                    console.error('reCAPTCHA execute error:', err);
                                    // Submit anyway if reCAPTCHA fails
                                    submitForm();
                                });
                        });
                    } catch (err) {
                        console.error('reCAPTCHA setup failed:', err);
                        // Submit anyway if reCAPTCHA fails
                        submitForm();
                    }
                } else {
                    // Submit without reCAPTCHA if it's not available
                    submitForm();
                }
            });
        });
    </script>
@endsection
