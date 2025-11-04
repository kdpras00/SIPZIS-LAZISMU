@extends('layouts.main')

@section('title', 'Pembayaran ' . ($payment->programType ? $payment->programType->name : ($payment->program_category ?
    ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum')))

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
    <!-- Success Section -->
    <div class="relative bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 overflow-hidden min-h-screen">
        <div class="relative container mx-auto px-4 py-16">
            <div class="max-w-4xl mx-auto">
                <!-- Success Header -->
                <div class="text-center mb-12 mt-16">
                    @if ($status === 'completed')
                        <h1 class="text-5xl font-bold text-green-600 mb-3">Pembayaran Berhasil!</h1>
                        <p class="text-gray-700">Terima kasih, pembayaran Anda telah kami terima.</p>
                    @elseif ($status === 'pending')
                        <h1 class="text-5xl font-bold text-yellow-600 mb-3">Menunggu Konfirmasi</h1>
                        <p class="text-gray-700">Pembayaran Anda sedang diproses. Mohon tunggu konfirmasi.</p>
                    @else
                        <h1 class="text-5xl font-bold text-red-600 mb-3">Pembayaran Gagal</h1>
                        <p class="text-gray-700">Maaf, terjadi kesalahan saat memproses pembayaran.</p>
                    @endif
                </div>


                <!-- Payment Details Card -->
                <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-8 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Detail
                            {{ $payment->programType ? $payment->programType->name : ($payment->program_category ? ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum') }}
                        </h2>
                        <div
                            class="px-4 py-2 rounded-full text-sm font-semibold
                        @if ($payment->status === 'completed') bg-green-100 text-green-800
                        @elseif($payment->status === 'pending')
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-red-100 text-red-800 @endif
                            ">
                            @if ($payment->status === 'completed')
                                <i class="fas fa-check-circle mr-1"></i> Pembayaran Berhasil
                            @elseif($payment->status === 'pending')
                                <i class="fas fa-clock mr-1"></i> Menunggu Pembayaran
                            @else
                                <i class="fas fa-times-circle mr-1"></i> Pembayaran Gagal
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Kode Pembayaran:</span>
                                <span class="font-semibold text-emerald-700">{{ $payment->payment_code }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">No. Kwitansi:</span>
                                <span class="font-semibold">{{ $payment->receipt_number }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Nama Donatur:</span>
                                <span class="font-semibold">{{ $payment->muzakki->name ?? 'Donatur Umum' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Jenis Program:</span>
                                <span
                                    class="font-semibold">{{ $payment->programType ? $payment->programType->name : ($payment->program_category ? ucfirst(str_replace('-', ' ', $payment->program_category)) : 'Donasi Umum') }}</span>
                            </div>
                        </div>

                        <div class="space-y-4">

                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Jumlah Dibayar:</span>
                                <span class="font-semibold text-emerald-700">Rp
                                    {{ number_format($payment->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Metode Pembayaran:</span>
                                <span class="font-semibold">{{ ucfirst($payment->payment_method) }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="font-semibold">
                                    {{ optional($payment->payment_date)->format('d F Y') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($payment->notes)
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-gray-700 mb-2">Catatan:</h4>
                            <p class="text-gray-600">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>

                <div
                    class="text-center mb-6 bg-green-50 border border-green-200 text-green-700 rounded-xl py-4 px-6 shadow-sm">
                    @if ($payment->status === 'completed')
                        Terima kasih, donasi Anda telah kami terima.
                    @elseif($payment->status === 'pending')
                        Terima kasih, donasi Anda sedang diproses. Silakan selesaikan pembayaran sesuai instruksi yang telah
                        dikirim.
                    @else
                        Maaf, terjadi kesalahan dalam proses pembayaran. Silakan coba lagi atau hubungi administrator.
                    @endif
                    Anda dapat mengunduh atau mencetak kwitansi pembayaran di bawah ini.
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('guest.payment.receipt.download', $payment->payment_code) }}"
                        class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white w-[280px] px-12 py-3 rounded-2xl
               hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-bold shadow-lg
               hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center whitespace-nowrap">
                        <i class="fas fa-download mr-2"></i>
                        Unduh Kwitansi
                    </a>

                    <a href="{{ route('guest.payment.receipt', $payment->payment_code) }}"
                        class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white w-[280px] px-12 py-3 rounded-2xl
               hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-bold shadow-lg
               hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center whitespace-nowrap">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Kwitansi
                    </a>

                    <a href="{{ route('guest.payment.create') }}"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white w-[280px] px-12 py-3 rounded-2xl
               hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 font-bold shadow-lg
               hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center whitespace-nowrap">
                        <i class="fas fa-plus mr-2"></i>
                        Bayar Lagi
                    </a>

                    <a href="{{ route('home') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 text-white w-[280px] px-12 py-3 rounded-2xl
               hover:from-gray-600 hover:to-gray-700 transition-all duration-300 font-bold shadow-lg
               hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center whitespace-nowrap">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Islamic Quote -->
                <div class="mt-12 text-center">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-6 shadow-md">
                        <div class="text-emerald-700 font-semibold mb-2">
                            "وَمَن يُؤْتَ الْحِكْمَةَ فَقَدْ أُوتِيَ خَيْرًا كَثِيرًا"
                        </div>
                        <p class="text-gray-600 italic">
                            "Barangsiapa diberi hikmah, sungguh ia telah dianugerahi kebaikan yang banyak."
                        </p>
                        <p class="text-sm text-gray-500 mt-2">QS. Al-Baqarah: 269</p>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <style>
        @keyframes wiggle {

            0%,
            100% {
                transform: rotate(-3deg);
            }

            50% {
                transform: rotate(3deg);
            }
        }

        .animate-wiggle {
            animation: wiggle 0.5s ease-in-out infinite;
        }
    </style>
@endsection
