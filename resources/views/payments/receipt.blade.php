@extends('layouts.app')

@section('page-title', 'Kwitansi Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <!-- Card Container -->
            <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h6 class="text-lg font-semibold text-gray-800">Kwitansi Pembayaran Donasi</h6>
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 print:hidden">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-8">
                    <div class="receipt-content pl-8">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <h4 class="text-2xl font-bold text-gray-900 mb-3">KWITANSI PEMBAYARAN DONASI</h4>
                            <hr class="border-gray-300">
                        </div>

                        <!-- Receipt Info -->
                        <div class="mb-8">
                            <h6 class="text-base font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Informasi Kwitansi</h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <table class="w-full">
                                        <tbody>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top" style="width: 40%;"><strong class="text-gray-700">Nomor Kwitansi:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $payment->receipt_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Tanggal:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $payment->payment_date->format('d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Status:</strong></td>
                                                <td class="py-2.5">
                                                    @if($payment->status == 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                                    @elseif($payment->status == 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                                    @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="w-full">
                                        <tbody>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top" style="width: 40%;"><strong class="text-gray-700">Kode Pembayaran:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $payment->payment_code }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Metode Pembayaran:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Tunai')) }}</td>
                                            </tr>
                                            @if($payment->payment_reference)
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Referensi:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $payment->payment_reference }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Donor Info -->
                        <div class="mb-8">
                            <h6 class="text-base font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Informasi Donatur</h6>
                            <table class="w-full">
                                <tbody>
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top" style="width: 30%;"><strong class="text-gray-700">Nama:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $payment->muzakki->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Email:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $payment->muzakki->email }}</td>
                                    </tr>
                                    @if($payment->muzakki->phone)
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Telepon:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $payment->muzakki->phone }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Payment Details -->
                        <div class="mb-8">
                            <h6 class="text-base font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Detail Pembayaran</h6>
                            <table class="w-full">
                                <tbody>
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top" style="width: 30%;"><strong class="text-gray-700">Jenis Program:</strong></td>
                                        <td class="py-2.5 text-gray-900">
                                            @if($payment->programType)
                                            {{ $payment->programType->name }}
                                            @else
                                            {{ ucfirst(str_replace('-', ' ', $payment->program_category ?? 'Umum')) }}
                                            @endif
                                        </td>
                                    </tr>
                                    @if($payment->zakatType)
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Jenis Zakat:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $payment->zakatType->name }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Jumlah Pembayaran:</strong></td>
                                        <td class="py-2.5">
                                            <h5 class="text-xl font-bold text-green-600">
                                                Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                                            </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Notes -->
                        @if($payment->notes)
                        <div class="mb-8">
                            <h6 class="text-base font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Catatan</h6>
                            <p class="text-gray-700 leading-relaxed">{{ $payment->notes }}</p>
                        </div>
                        @endif

                        <!-- Signature -->
                        <div class="mt-16 signature-section signature-section-right "> <!-- add shift right class -->
                            <div class="grid grid-cols-2 gap-12">
                                <div class="text-center signature-box">
                                    <p class="font-semibold text-gray-800 mb-12 text-base">Penerima</p>
                                    <div class="mb-6">
                                        <p class="text-gray-600 text-sm">___________________________</p>
                                    </div>
                                    <p class="text-gray-700 text-sm">Amil Zakat Lazismu Banten</p>
                                </div>
                                <div class="text-center signature-box">
                                    <p class="font-semibold text-gray-800 mb-12 text-base">Donatur</p>
                                    <div class="mb-6">
                                        <p class="text-gray-600 text-sm">___________________________</p>
                                    </div>
                                    <p class="text-gray-700 text-sm">{{ $payment->muzakki->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Signature Section - Always side by side */
    .signature-section {
        display: flex;
        width: 100%;
    }

    /* Geser ke kanan sedikit signature */
    .signature-section-right {
        justify-content: flex-end;
        padding-right: 0;
        margin-left: 10%;
    }

    .signature-section .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        width: 100%;
    }

    .signature-box {
        flex: 0 0 50%;
        max-width: 50%;
    }

    /* Print Styles */
    @media print {
        @page {
            margin: 1cm;
            size: A4;
        }

        body * {
            visibility: hidden;
        }

        .receipt-content,
        .receipt-content * {
            visibility: visible;
        }

        .receipt-content {
            position: absolute;
            left: 2rem;
            top: 0;
            width: calc(100% - 2rem);
            padding: 0;
        }

        /* Hide print button and card styling */
        button,
        .print\:hidden {
            display: none !important;
        }

        .bg-gray-50,
        .bg-white {
            background: white !important;
            box-shadow: none !important;
        }

        .rounded-lg,
        .shadow-md {
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        /* Ensure signature section stays side by side & tetap geser kanan */
        .signature-section {
            display: flex !important;
            width: 100% !important;
            margin-top: 4rem !important;
            page-break-inside: avoid;
            justify-content: flex-end !important;
            padding-right: 3rem !important;
        }

        .signature-section .grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 3rem !important;
            width: 100% !important;
        }

        .signature-box {
            flex: 0 0 50% !important;
            max-width: 50% !important;
            width: 50% !important;
            float: none !important;
            page-break-inside: avoid;
        }

        /* Ensure proper spacing for print */
        .mb-8 {
            margin-bottom: 1.5rem !important;
        }

        .mt-16 {
            margin-top: 4rem !important;
        }

        /* Better table alignment */
        table {
            border-collapse: collapse;
        }

        td {
            padding: 0.625rem 0 !important;
        }

        /* Prevent page breaks inside sections */
        .mb-8 {
            page-break-inside: avoid;
        }
    }
</style>
@endsection
