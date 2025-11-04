@extends('layouts.main')

@section('title', 'Ringkasan Pembayaran - SIPZIS')

@section('content')
    <div class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">

            {{-- Logo --}}
            <div class="text-center mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="mx-auto h-12 mb-2">
                <h2 class="text-gray-600 text-sm">Ringkasan Pembayaran</h2>
            </div>

            {{-- Notification Area --}}
            <div id="notification-area" class="mb-4 hidden"></div>

            {{-- Nominal --}}
            <div class="text-center">
                <h2 class="text-gray-500 text-lg">Total Pembayaran</h2>
                <p class="text-4xl font-extrabold text-emerald-600 my-2">
                    Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-400">Kode: {{ $payment->payment_code }}</p>

                {{-- Add additional payment information --}}
                @if ($payment->program_category)
                    <p class="text-sm text-gray-500 mt-2">
                        Kategori: {{ ucfirst(str_replace('-', ' ', $payment->program_category)) }}
                    </p>
                @endif

                @if ($payment->midtrans_order_id)
                    <p class="text-xs text-gray-400 mt-1">
                        Order ID: {{ $payment->midtrans_order_id }}
                    </p>
                @endif
            </div>

            {{-- Countdown --}}
            @php $expiryTime = $payment->created_at->addHours(24); @endphp
            <div class="mt-4 text-center text-sm text-gray-500">
                <i class="far fa-clock mr-1"></i>
                Selesaikan pembayaran sebelum <br>
                <strong>{{ $expiryTime->isoFormat('dddd, D MMMM YYYY HH:mm') }}</strong>
            </div>

            {{-- Pilih Metode Pembayaran --}}
            <div class="mt-8">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">Pilih metode pembayaran</h3>

                <div class="space-y-2">
                    {{-- ATM & VA --}}
                    <p class="text-xs font-medium text-gray-400">ATM & Internet Banking</p>
                    <div class="border rounded-lg divide-y">
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="bca_va">
                            <img src="https://buatlogoonline.com/wp-content/uploads/2022/10/Logo-BCA-PNG-1536x1152.png"
                                class="h-5 mr-3">
                            <span>BCA Virtual Account</span>
                        </button>
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="bri_va">
                            <img src="https://buatlogoonline.com/wp-content/uploads/2022/10/Logo-Bank-BRI.png"
                                class="h-5 mr-3">
                            <span>BRI Virtual Account</span>
                        </button>
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="bni_va">
                            <img src="https://buatlogoonline.com/wp-content/uploads/2022/10/Logo-BNI.jpg" class="h-5 mr-3">
                            <span>BNI Virtual Account</span>
                        </button>
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="mandiri_va">
                            <img src="https://www.bankmandiri.co.id/documents/20143/44881086/ag-branding-logo-1.png/842d8cf8-b7fb-3014-9620-21f0f88d8377?t=1623309819034"
                                class="h-5 mr-3">
                            <span>Mandiri Virtual Account</span>
                        </button>
                    </div>

                    {{-- E-Wallet --}}
                    <p class="text-xs font-medium text-gray-400 mt-4">E-Wallet</p>
                    <div class="border rounded-lg divide-y">
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="gopay">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/00/Logo_Gopay.svg" class="h-5 mr-3">
                            <span>GoPay</span>
                        </button>
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="dana">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg"
                                class="h-5 mr-3">
                            <span>Dana</span>
                        </button>
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="shopeepay">
                            <img src="https://images.seeklogo.com/logo-png/40/1/shopee-pay-logo-png_seeklogo-406839.png"
                                class="h-5 mr-3">
                            <span>ShopeePay</span>
                        </button>
                    </div>

                    {{-- QRIS --}}
                    <p class="text-xs font-medium text-gray-400 mt-4">QRIS</p>
                    <div class="border rounded-lg">
                        <button class="w-full flex items-center px-4 py-3 hover:bg-gray-50 payment-method"
                            data-method="qris">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="h-5 mr-3">
                            <span>QRIS</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Leave / Check --}}
            <div class="mt-6 space-y-2">
                <button id="pay-button"
                    class="btn btn-success w-full bg-orange-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-orange-600 transition text-lg flex items-center justify-center gap-2"
                    disabled>
                    Bayar Sekarang
                </button>
                <div id="loadingSpinner" class="text-center mt-3 hidden">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2 text-gray-600">Menyiapkan pembayaran...</p>
                </div>

                <button id="leave-page-button"
                    class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition">
                    Tinggalkan Halaman Ini
                </button>
            </div>
        </div>
    </div>

    {{-- Midtrans Snap --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get configuration from data attributes
            const isProduction = document.body.dataset.midtransProduction === 'true';
            const clientKey = document.body.dataset.midtransClientKey;

            const snapScript = document.createElement('script');
            snapScript.src = isProduction ?
                'https://app.midtrans.com/snap/snap.js' :
                'https://app.sandbox.midtrans.com/snap/snap.js';
            snapScript.setAttribute('data-client-key', clientKey);
            snapScript.onload = function() {};
            snapScript.onerror = function() {
                showNotification('Gagal memuat sistem pembayaran. Silakan refresh halaman.', 'error');
            };
            document.head.appendChild(snapScript);
        });
    </script>
    <script>
        const notificationArea = document.getElementById('notification-area');
        const checkStatusButton = document.getElementById('check-status');
        const leavePageButton = document.getElementById('leave-page-button');
        const payButton = document.getElementById('pay-button');
        let selectedMethod = null;
        let isProcessing = false; // Flag to prevent multiple calls

        // Define checkPaymentStatus function in global scope so it can be called from Snap.js callbacks
        let checkPaymentStatus = null;

        // Add event listeners to payment method buttons
        document.querySelectorAll('.payment-method').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.payment-method').forEach(btn => {
                    btn.classList.remove('bg-blue-50', 'border-blue-500');
                });

                // Add active class to clicked button
                this.classList.add('bg-blue-50', 'border-blue-500');

                // Store selected method
                selectedMethod = this.getAttribute('data-method');

                // Enable pay button
                payButton.disabled = false;
                payButton.classList.remove('opacity-50', 'cursor-not-allowed');

                // Show notification
                // showNotification("Metode pembayaran " + selectedMethod + " dipilih. Klik 'Bayar Sekarang' untuk melanjutkan.", "info");
            });
        });

        // Add event listener to pay button
        payButton.addEventListener('click', function() {
            if (selectedMethod) {
                // Prevent multiple clicks
                if (isProcessing) {
                    showNotification('Pembayaran sedang diproses. Mohon tunggu sebentar.', 'warning');
                    return;
                }

                isProcessing = true;
                payButton.disabled = true;
                payButton.textContent = 'Memproses...';

                payWith(selectedMethod).finally(() => {
                    // Reset processing state after payment attempt
                    isProcessing = false;
                    payButton.disabled = false;
                    payButton.textContent = 'Bayar Sekarang';
                });
            } else {
                showNotification('Silakan pilih metode pembayaran terlebih dahulu.', 'error');
            }
        });

        function showNotification(message, type = 'info') {
            notificationArea.innerHTML = '';
            notificationArea.classList.remove('hidden');
            let bgColor, textColor, icon;
            switch (type) {
                case 'success':
                    bgColor = 'bg-green-100';
                    textColor = 'text-green-800';
                    icon = 'fa-check-circle';
                    break;
                case 'error':
                    bgColor = 'bg-red-100';
                    textColor = 'text-red-800';
                    icon = 'fa-exclamation-circle';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-100';
                    textColor = 'text-yellow-800';
                    icon = 'fa-exclamation-triangle';
                    break;
                default:
                    bgColor = 'bg-blue-100';
                    textColor = 'text-blue-800';
                    icon = 'fa-info-circle';
            }
            notificationArea.innerHTML += `
            <div class="flex items-center p-4 mb-2 rounded-lg ${bgColor} ${textColor}">
                <i class="fas ${icon} text-xl mr-3"></i>
                <div class="flex-1"><p class="font-medium">${message}</p></div>
                <button onclick="this.parentElement.classList.add('hidden')" class="text-xl">&times;</button>
            </div>`;
            if (type === 'info') setTimeout(() => notificationArea.classList.add('hidden'), 5000);
        }

        async function payWith(method) {
            showNotification("Memproses pembayaran dengan metode: " + method, "info");
            try {
                const res = await fetch('{{ route('guest.payment.getTokenCustom', $payment->payment_code) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        method
                    })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Gagal memuat Snap Token');

                if (data.snap_token) {
                    // Pastikan Snap.js sudah siap
                    setTimeout(() => {
                        if (typeof snap === 'undefined') {
                            showNotification('Sistem pembayaran belum siap. Silakan coba lagi.', 'error');
                            return;
                        }

                        // Jika popup sudah terbuka, cegah duplikasi
                        if (window.snapIsOpen) {
                            showNotification(
                                'Popup pembayaran sudah terbuka. Silakan selesaikan pembayaran yang sedang berlangsung.',
                                'warning');
                            return;
                        }

                        // Tandai popup terbuka
                        window.snapIsOpen = true;
                        window.snapHandled = false;

                        try {
                            snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    if (window.snapHandled) return;
                                    window.snapHandled = true;
                                    window.snapIsOpen = false;

                                    showNotification('Pembayaran berhasil! Terima kasih.',
                                        'success');
                                    setTimeout(() => {
                                        window.location.href =
                                            '{{ route('guest.payment.success', $payment->payment_code) }}';
                                    }, 3000);
                                },
                                onPending: function(result) {
                                    if (window.snapHandled) return;
                                    window.snapHandled = true;
                                    window.snapIsOpen = false;

                                    showNotification(
                                        'Pembayaran sedang diproses. Silakan tunggu konfirmasi.',
                                        'info');

                                    // Immediately trigger status check after returning from payment page
                                    // This helps catch payment that was completed while on payment page
                                    // The checkPaymentStatus function will be available after page redirects back to summary

                                    setTimeout(() => {
                                        window.location.href =
                                            '{{ route('guest.payment.summary', $payment->payment_code) }}';
                                    }, 1000); // Reduced from 3000ms to 1000ms for faster redirect
                                },
                                onError: function(result) {
                                    if (window.snapHandled) return;
                                    window.snapHandled = true;
                                    window.snapIsOpen = false;

                                    console.error('Snap error:', result);
                                    showNotification('Terjadi kesalahan saat memproses pembayaran.',
                                        'error');
                                },
                                onClose: function() {
                                    if (window.snapHandled) return;
                                    window.snapHandled = true;
                                    window.snapIsOpen = false;

                                    showNotification(
                                        'Popup pembayaran ditutup. Jika sudah bayar, tunggu konfirmasi.',
                                        'warning');
                                }
                            });
                        } catch (error) {
                            window.snapIsOpen = false;
                            console.error('Snap.js error:', error);
                            showNotification('Terjadi kesalahan pada sistem pembayaran. Silakan coba lagi.',
                                'error');
                        }
                    }, 800); // Delay 800ms agar Snap.js benar-benar siap
                }

            } catch (err) {
                showNotification('Error: ' + err.message, 'error');
            }
        }

        // Check if elements exist before adding event listeners
        if (checkStatusButton) {
            checkStatusButton.addEventListener('click', async () => {
                const res = await fetch('{{ route('guest.payment.checkStatus', $payment->payment_code) }}');
                const data = await res.json();
                if (data.success) showNotification(data.message, 'info');
                else showNotification('Gagal cek status', 'error');
            });
        }

        if (leavePageButton) {
            leavePageButton.addEventListener('click', async () => {
                if (confirm('Yakin ingin meninggalkan halaman ini?')) {
                    const res = await fetch('{{ route('guest.payment.leavePage', $payment->payment_code) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if (data.success) window.location.href = '{{ route('home') }}';
                    else showNotification('Gagal memperbarui status.', 'error');
                }
            });
        }
    </script>
@endsection

@if ($payment->status === 'pending')
    <script>
        // Auto check every 1 second for faster detection in sandbox
        const paymentCode = "{{ $payment->payment_code }}";
        const checkUrl = "{{ route('guest.payment.checkStatus', $payment->payment_code) }}";
        let checkCount = 0;
        const maxChecks = 300; // Max ~5 minutes (300 checks * 1 second)

        // Define checkPaymentStatus function and assign to global variable
        checkPaymentStatus = async function() {
            try {
                checkCount++;

                const res = await fetch(checkUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    },
                    cache: 'no-store'
                });

                if (!res.ok) {
                    console.error('Check status failed:', res.status);
                    return;
                }

                const data = await res.json();

                if (data.status === 'completed') {
                    // Clear interval immediately to prevent multiple redirects
                    if (statusCheckInterval) clearInterval(statusCheckInterval);
                    // Immediate redirect to success page
                    window.location.href = "{{ route('guest.payment.success', $payment->payment_code) }}";
                    return;
                } else if (data.status === 'cancelled' || data.status === 'failed') {
                    if (statusCheckInterval) clearInterval(statusCheckInterval);
                    window.location.href = "{{ route('guest.payment.failed', $payment->payment_code) }}";
                    return;
                } else if (checkCount >= maxChecks) {
                    // Stop checking after max attempts
                    if (statusCheckInterval) clearInterval(statusCheckInterval);
                    showNotification(
                        'Pembayaran masih pending. Silakan refresh halaman atau hubungi support jika sudah bayar.',
                        'warning');
                }
            } catch (e) {
                console.error('Error checking payment status:', e);
                // Don't stop checking on error, just log it
            }
        }

        let statusCheckInterval;

        // Initial check immediately for fastest response
        checkPaymentStatus();

        // Then check every 1 second (very aggressive for sandbox environment)
        statusCheckInterval = setInterval(checkPaymentStatus, 1000);

        // Also check immediately after user returns from payment page (e.g., after closing Snap.js)
        // This helps catch payment status changes that happened while user was on payment page
        window.addEventListener('focus', function() {
            if (!statusCheckInterval) {
                checkPaymentStatus();
                statusCheckInterval = setInterval(checkPaymentStatus, 1000);
            }
        });
    </script>
@endif
