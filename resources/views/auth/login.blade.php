@extends('layouts.main')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-green-900">
        <div class="w-full max-w-md px-6">
            <div class="bg-white rounded-lg shadow-md p-8">
                <!-- Logo & Title -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">SIPZIS</h1>
                    <p class="text-gray-500 text-sm">Masuk untuk melanjutkan</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4" id="loginForm">
                    @csrf

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

                    <!-- Password Input -->
                    <div>
                        <div class="relative">
                            <input id="password" type="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror pr-12"
                                name="password" placeholder="Password" required autocomplete="current-password">
                            <button
                                class="absolute inset-y-0 right-0 flex items-center pr-3 bg-transparent border-0 text-gray-500 cursor-pointer"
                                type="button" id="togglePassword" style=" right: 20px;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-right">
                        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                        Masuk
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-400">atau</span>
                    </div>
                </div>

                <!-- Social Login Buttons -->
                <div class="space-y-3">
                    <!-- reCAPTCHA v3: token will be generated on submit -->
                    <button type="button" id="googleLogin"
                        class="w-full flex items-center justify-center gap-3 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 px-6 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        Google
                    </button>

                    <button type="button" id="googleLoginRedirect"
                        class="hidden w-full flex items-center justify-center gap-3 border border-dashed border-gray-300 hover:bg-gray-50 text-gray-500 py-3 px-6 rounded-lg transition-colors duration-200 text-sm">
                        <i class="bi bi-box-arrow-up-right text-base"></i>
                        Buka login Google di tab baru
                    </button>

                    <div id="socialLoginAlert"
                        class="hidden mt-3 text-sm border rounded-lg px-4 py-3 bg-gray-50 border-gray-200 text-gray-700"></div>


                    {{-- <a href="/auth/facebook" class="w-full flex items-center justify-center gap-3 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 px-6 rounded-lg transition-colors duration-200 no-underline">
                    <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                    Facebook
                </a> --}}

                </div>

                <!-- Register Link -->
                <p class="text-center text-sm text-gray-600 mt-6">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 font-medium">
                        Daftar
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/11.0.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/11.0.2/firebase-auth-compat.js"></script>
    <!-- Google reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <!-- Firebase Configuration -->
    <script>
        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.app_id') }}",
            measurementId: "{{ config('services.firebase.measurement_id') }}",
        };

        // Initialize Firebase
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
            firebase.auth().useDeviceLanguage();
        }
    </script>

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
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const icon = togglePassword.querySelector('i');
            const loginForm = document.getElementById('loginForm');
            const googleLoginBtn = document.getElementById('googleLogin');
            const socialLoginAlert = document.getElementById('socialLoginAlert');
            const googleLoginRedirectBtn = document.getElementById('googleLoginRedirect');
            const RECAPTCHA_STORAGE_KEY = 'firebase_login_recaptcha';
            const alertBaseClasses = ['mt-3', 'text-sm', 'border', 'rounded-lg', 'px-4', 'py-3'];
            const alertVariantClasses = {
                info: ['bg-blue-50', 'border-blue-200', 'text-blue-800'],
                warning: ['bg-yellow-50', 'border-yellow-200', 'text-yellow-800'],
                error: ['bg-red-50', 'border-red-200', 'text-red-800'],
                success: ['bg-green-50', 'border-green-200', 'text-green-800'],
            };

            const resetAlertClasses = () => {
                if (!socialLoginAlert) {
                    return;
                }
                socialLoginAlert.className = '';
                alertBaseClasses.forEach(cls => socialLoginAlert.classList.add(cls));
            };

            const showSocialLoginAlert = (variant, message) => {
                if (!socialLoginAlert) {
                    alert(message);
                    return;
                }
                resetAlertClasses();
                (alertVariantClasses[variant] || alertVariantClasses.error).forEach(cls => socialLoginAlert.classList.add(cls));
                socialLoginAlert.textContent = message;
                socialLoginAlert.classList.remove('hidden');
            };

            const hideSocialLoginAlert = () => {
                if (!socialLoginAlert) {
                    return;
                }
                resetAlertClasses();
                socialLoginAlert.classList.add('hidden');
                socialLoginAlert.textContent = '';
            };

            const toggleFallbackButton = (show = false) => {
                if (!googleLoginRedirectBtn) {
                    return;
                }
                googleLoginRedirectBtn.classList.toggle('hidden', !show);
            };

            const storeRedirectRecaptchaToken = (token) => {
                if (!token) return;
                try {
                    sessionStorage.setItem(RECAPTCHA_STORAGE_KEY, token);
                } catch (err) {
                    console.warn('Gagal menyimpan token reCAPTCHA:', err);
                }
            };

            const consumeRedirectRecaptchaToken = () => {
                try {
                    const token = sessionStorage.getItem(RECAPTCHA_STORAGE_KEY);
                    if (token) {
                        sessionStorage.removeItem(RECAPTCHA_STORAGE_KEY);
                        return token;
                    }
                } catch (err) {
                    console.warn('Gagal mengambil token reCAPTCHA:', err);
                }
                return '';
            };

            hideSocialLoginAlert();
            toggleFallbackButton(false);

            // Generate reCAPTCHA v3 token before normal form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitForm = () => loginForm.submit();
                try {
                    if (!window.grecaptcha || !window.grecaptcha.execute) {
                        alert('Memuat reCAPTCHA... silakan coba lagi.');
                        return false;
                    }
                    if (!recaptchaSiteKey) {
                        alert('Site key reCAPTCHA tidak terbaca. Coba refresh atau hubungi admin.');
                        return false;
                    }
                    window.grecaptcha.ready(function() {
                        window.grecaptcha.execute(recaptchaSiteKey, {
                                action: 'login'
                            })
                            .then(function(token) {
                                let tokenInput = loginForm.querySelector(
                                    'input[name="g-recaptcha-response"]');
                                if (!tokenInput) {
                                    tokenInput = document.createElement('input');
                                    tokenInput.type = 'hidden';
                                    tokenInput.name = 'g-recaptcha-response';
                                    loginForm.appendChild(tokenInput);
                                }
                                tokenInput.value = token;

                                let actionInput = loginForm.querySelector(
                                    'input[name="g-recaptcha-action"]');
                                if (!actionInput) {
                                    actionInput = document.createElement('input');
                                    actionInput.type = 'hidden';
                                    actionInput.name = 'g-recaptcha-action';
                                    loginForm.appendChild(actionInput);
                                }
                                actionInput.value = 'login';

                                submitForm();
                            })
                            .catch(function(err) {
                                console.error('reCAPTCHA execute error:', err);
                                alert('Validasi reCAPTCHA gagal. Coba lagi.');
                            });
                    });
                } catch (err) {
                    console.error('reCAPTCHA setup failed:', err);
                    alert('Validasi reCAPTCHA gagal. Coba lagi.');
                    return false;
                }
            });

            // Handle redirect result (fallback flow)
            firebase.auth().getRedirectResult()
                .then((result) => {
                    if (result && result.user) {
                        const storedToken = consumeRedirectRecaptchaToken();
                        if (!storedToken) {
                            showSocialLoginAlert('warning',
                                'Sesi login Google kadaluarsa. Silakan klik tombol Google lagi.');
                            return;
                        }
                        handleFirebaseLogin(result.user, storedToken);
                    }
                })
                .catch((error) => {
                    consumeRedirectRecaptchaToken();
                    console.error('Error from getRedirectResult:', error);
                });

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the eye icon
                if (type === 'password') {
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });

            // Firebase Google Login
            let isLoggingIn = false; // flag
            const popupFallbackCodes = ['auth/popup-blocked', 'auth/web-storage-unsupported', 'auth/internal-error'];
            const coopMessageRegex = /Cross-Origin-Opener-Policy|COOP/i;
            const googleAuthFriendlyMessages = {
                'auth/user-cancelled': 'Login Google dibatalkan oleh Anda.',
                'auth/popup-closed-by-user': 'Jendela login Google ditutup sebelum selesai.',
                'auth/cancelled-popup-request': 'Permintaan login sebelumnya dibatalkan. Silakan coba lagi.',
                'auth/popup-blocked': 'Browser memblokir pop-up login Google.',
                'auth/web-storage-unsupported': 'Browser tidak mengizinkan penyimpanan yang dibutuhkan Google Auth.',
                'auth/internal-error': 'Browser memblokir proses popup Google.',
                'auth/network-request-failed': 'Tidak dapat tersambung ke Google. Periksa koneksi internet Anda.',
            };

            const shouldOfferRedirect = (error) => {
                if (!error) return false;
                if (popupFallbackCodes.includes(error.code)) return true;
                return coopMessageRegex.test(error.message || '');
            };

            const startGoogleLogin = (recaptchaToken, useRedirect = false) => {
                const provider = new firebase.auth.GoogleAuthProvider();
                provider.setCustomParameters({
                    prompt: 'consent select_account'
                });

                if (useRedirect) {
                    firebase.auth().signInWithRedirect(provider)
                        .catch((error) => {
                            const friendlyMessage = googleAuthFriendlyMessages[error.code] || 'Login dengan Google gagal. Silakan coba beberapa saat lagi.';
                            console.error('Error during Google redirect login:', error);
                            showSocialLoginAlert('error', friendlyMessage);
                        })
                        .finally(() => {
                            isLoggingIn = false;
                        });
                    return;
                }

                firebase.auth().signInWithPopup(provider)
                    .then((result) => {
                        const user = result.user;
                        handleFirebaseLogin(user, recaptchaToken);
                    })
                    .catch((error) => {
                        const friendlyMessage = googleAuthFriendlyMessages[error.code] || 'Login dengan Google gagal. Silakan coba beberapa saat lagi.';

                        if (error.code === 'auth/user-cancelled') {
                            console.info('Google login dibatalkan oleh pengguna.');
                            showSocialLoginAlert('info', friendlyMessage);
                            return;
                        }

                        if (shouldOfferRedirect(error)) {
                            showSocialLoginAlert('warning',
                                'Popup Google diblokir oleh browser. Klik "Buka login Google di tab baru".');
                            toggleFallbackButton(true);
                            return;
                        }

                        console.error('Error during Google login:', error);
                        showSocialLoginAlert('error', friendlyMessage);
                    })
                    .finally(() => {
                        isLoggingIn = false; // reset flag setelah selesai
                    });
            };

            const executeGoogleLogin = (useRedirect = false) => {
                if (isLoggingIn) return; // cegah klik ganda
                isLoggingIn = true;
                hideSocialLoginAlert();
                toggleFallbackButton(false);

                if (!window.grecaptcha || !window.grecaptcha.execute) {
                    isLoggingIn = false;
                    alert('Memuat reCAPTCHA... silakan coba lagi.');
                    return;
                }
                window.grecaptcha.ready(function() {
                    if (!recaptchaSiteKey) {
                        isLoggingIn = false;
                        alert('Site key reCAPTCHA tidak terbaca. Coba refresh atau hubungi admin.');
                        return;
                    }
                    window.grecaptcha.execute(recaptchaSiteKey, {
                            action: 'login'
                        })
                        .then(function(token) {
                            if (useRedirect) {
                                storeRedirectRecaptchaToken(token);
                            }
                            startGoogleLogin(token, useRedirect);
                        })
                        .catch(function(err) {
                            console.error('reCAPTCHA execute error:', err);
                            isLoggingIn = false;
                            alert('Validasi reCAPTCHA gagal. Coba lagi.');
                        });
                });
            };

            googleLoginBtn.addEventListener('click', function() {
                executeGoogleLogin(false);
            });

            if (googleLoginRedirectBtn) {
                googleLoginRedirectBtn.addEventListener('click', function() {
                    executeGoogleLogin(true);
                });
            }

            // Handle Firebase login and integrate with Laravel
            function handleFirebaseLogin(user, recaptchaResponse) {
                // Send user data to Laravel backend
                fetch('/firebase-login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            email: user.email,
                            name: user.displayName,
                            firebase_uid: user.uid,
                            'g-recaptcha-response': recaptchaResponse || ''
                        })
                    })
                    .then(async response => {
                        const data = await response.json();

                        if (data.two_factor_required) {
                            window.location.href = data.redirect || '/two-factor/verify';
                            return;
                        }

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Login gagal.');
                        }

                        window.location.href = data.redirect || '/';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showSocialLoginAlert('error', error.message || 'Terjadi kesalahan saat login.');
                    });
            }
        });
    </script>
@endsection
