@extends('layouts.main')

@section('content')
<div class="relative bg-gradient-to-br from-green-900 via-green-800 to-emerald-700 min-h-screen flex items-center justify-center overflow-hidden">

    <div class="absolute inset-0 opacity-20" style="background-image: url('{{ asset("img/masjid.webp") }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>

    <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-green-800/70 to-emerald-700/80"></div>

    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/10"></div>

    <div class="relative z-10 w-full max-w-md mx-auto px-6">
        <div class="backdrop-blur-md bg-white/10 border border-white/20 rounded-3xl shadow-2xl p-8 animate-fadeInUp">
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mb-4 shadow-lg animate-fadeInDown">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L3.09 8.26L12 22L20.91 8.26L12 2Z" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-white mb-2 animate-fadeInDown delay-300">
                    SIPZIS
                </h1>
                <p class="text-green-200 text-xs mt-1 animate-fadeInDown delay-700">
                    Masuk ke dashboard administrasi
                </p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                <div class="animate-fadeInUp delay-500">
                    <label for="email" class="block text-sm font-medium text-green-100 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input id="email" type="email"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent backdrop-blur-sm transition-all duration-300 @error('email') border-red-400 @enderror"
                        name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email Anda"
                        required autocomplete="email" autofocus>
                    @error('email')
                    <span class="text-red-300 text-sm mt-1 block">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="animate-fadeInUp delay-700">
                    <label for="password" class="block text-sm font-medium text-green-100 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input id="password" type="password"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent backdrop-blur-sm transition-all duration-300 @error('password') border-red-400 @enderror pr-12"
                            name="password"
                            placeholder="Masukkan password Anda"
                            required autocomplete="current-password">
                        <button class="absolute inset-y-0 right-0 flex items-center pr-3 bg-transparent border-0 text-white cursor-pointer" type="button" id="togglePassword" style="right: 20px;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <span class="text-red-300 text-sm mt-1 block">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="flex items-center animate-fadeInUp delay-900">
                    <input class="w-4 h-4 text-green-600 bg-white/20 border-white/30 rounded focus:ring-green-400 focus:ring-2"
                        type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="ml-2 text-sm text-green-100" for="remember">
                        Ingat saya
                    </label>
                </div>

                <div class="animate-fadeInUp delay-1100">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 focus:ring-offset-transparent">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    @keyframes fadeInDown {
        0% {
            opacity: 0;
            transform: translateY(-30px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeInDown {
        animation: fadeInDown 0.8s ease-out;
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }

    .delay-300 {
        animation-delay: 0.3s;
        animation-fill-mode: both;
    }

    .delay-500 {
        animation-delay: 0.5s;
        animation-fill-mode: both;
    }

    .delay-700 {
        animation-delay: 0.7s;
        animation-fill-mode: both;
    }

    .delay-900 {
        animation-delay: 0.9s;
        animation-fill-mode: both;
    }

    .delay-1100 {
        animation-delay: 1.1s;
        animation-fill-mode: both;
    }

    .delay-1300 {
        animation-delay: 1.3s;
        animation-fill-mode: both;
    }

    .delay-1500 {
        animation-delay: 1.5s;
        animation-fill-mode: both;
    }

    .delay-1700 {
        animation-delay: 1.7s;
        animation-fill-mode: both;
    }

    /* Glass morphism effect */
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }

    /* Input focus effects */
    input:focus {
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    /* Password visibility toggle */
    .btn-outline-secondary:hover {
        opacity: 0.8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        if (togglePassword && password) {
            const icon = togglePassword.querySelector('i');
            
            if (icon) {
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
            }
        }
    });
</script>
@endsection