@extends('layouts.main')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-green-900">
    <div class="w-full max-w-md px-6">
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-shield-lock text-3xl text-green-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Autentikasi Dua Faktor</h1>
                <p class="text-gray-500 text-sm">Masukkan kode 6 digit dari aplikasi Google Authenticator</p>
            </div>

            <!-- Verification Form -->
            <form method="POST" action="{{ route('two-factor.verify.post') }}" class="space-y-4" id="verifyForm">
                @csrf

                <!-- Code Input -->
                <div>
                    <input id="code" 
                           type="text" 
                           class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-center text-3xl tracking-widest font-mono @error('code') border-red-500 @enderror" 
                           name="code" 
                           placeholder="000000" 
                           maxlength="6"
                           pattern="[0-9]{6}"
                           required 
                           autocomplete="off"
                           autofocus>
                    @error('code')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                    Verifikasi
                </button>
            </form>

            <!-- Help Text -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Tidak memiliki akses ke aplikasi authenticator?<br>
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-medium">Kembali ke halaman login</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        
        // Format input to only accept numbers
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Auto-submit when 6 digits entered
        codeInput.addEventListener('input', function(e) {
            if (this.value.length === 6) {
                // Auto-submit after a short delay
                setTimeout(() => {
                    document.getElementById('verifyForm').submit();
                }, 300);
            }
        });

        // Focus on input
        codeInput.focus();
    });
</script>
@endpush
@endsection

