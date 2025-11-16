@extends('layouts.guest')

@section('page-title', 'Kwitansi Distribusi Zakat - ' . $distribution->distribution_code)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <!-- Card Container -->
            <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h6 class="text-lg font-semibold text-gray-800">Kwitansi Distribusi Zakat</h6>
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 print:hidden">
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
                            <h4 class="text-2xl font-bold text-gray-900 mb-3">KWITANSI DISTRIBUSI ZAKAT</h4>
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
                                                <td class="py-2.5 pr-6 align-top" style="width: 40%;"><strong class="text-gray-700">Nomor Distribusi:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $distribution->distribution_code }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Tanggal:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $distribution->distribution_date->format('d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Status:</strong></td>
                                                <td class="py-2.5">
                                                    @if($distribution->is_received)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Sudah Diterima</span>
                                                    @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Belum Diterima</span>
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
                                                <td class="py-2.5 pr-6 align-top" style="width: 40%;"><strong class="text-gray-700">Jenis Distribusi:</strong></td>
                                                <td class="py-2.5 text-gray-900">
                                                    @switch($distribution->distribution_type)
                                                        @case('cash')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tunai</span>
                                                            @break
                                                        @case('goods')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Barang</span>
                                                            @break
                                                        @case('voucher')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Voucher</span>
                                                            @break
                                                        @case('service')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Layanan</span>
                                                            @break
                                                        @default
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucwords($distribution->distribution_type) }}</span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Program:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $distribution->program_name ?: 'Distribusi Umum' }}</td>
                                            </tr>
                                            @if($distribution->location)
                                            <tr>
                                                <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Lokasi:</strong></td>
                                                <td class="py-2.5 text-gray-900">{{ $distribution->location }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Mustahik Info -->
                        <div class="mb-8">
                            <h6 class="text-base font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Informasi Mustahik</h6>
                            <table class="w-full">
                                <tbody>
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top" style="width: 30%;"><strong class="text-gray-700">Nama:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $distribution->mustahik->name }}</td>
                                    </tr>
                                    @if($distribution->mustahik->nik)
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">NIK:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $distribution->mustahik->nik }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Kategori:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ ucfirst(str_replace('_', ' ', $distribution->mustahik->category)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Alamat:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $distribution->mustahik->address }}</td>
                                    </tr>
                                    @if($distribution->mustahik->phone)
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Telepon:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $distribution->mustahik->phone }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Distribution Details -->
                        <div class="mb-8">
                            <h6 class="text-base font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Detail Distribusi</h6>
                            <table class="w-full">
                                <tbody>
                                    @if($distribution->goods_description)
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top" style="width: 30%;"><strong class="text-gray-700">Deskripsi:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $distribution->goods_description }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Jumlah Distribusi:</strong></td>
                                        <td class="py-2.5">
                                            <h5 class="text-xl font-bold text-green-600">
                                                Rp {{ number_format($distribution->amount, 0, ',', '.') }}
                                            </h5>
                                            <p class="text-sm text-gray-600 italic mt-1">
                                                {{ \App\Helpers\Terbilang::currencyCapitalized($distribution->amount) }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 pr-6 align-top"><strong class="text-gray-700">Diserahkan Oleh:</strong></td>
                                        <td class="py-2.5 text-gray-900">{{ $distribution->distributedBy->name ?? 'Admin SIPZIS' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Status Received -->
                       

                        <!-- Notes -->
                        @if($distribution->notes)
                        <div class="mb-8">
                            <h6 class="text-base font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Catatan</h6>
                            <p class="text-gray-700 leading-relaxed">{{ $distribution->notes }}</p>
                        </div>
                        @endif

                        <!-- Signature -->
                        <div class="mt-16 signature-section signature-section-right">
                            <div class="grid grid-cols-2 gap-12">
                                <div class="text-center signature-box">
                                    <p class="font-semibold text-gray-800 mb-12 text-base">Petugas Distribusi</p>
                                    <div class="mb-6">
                                        <p class="text-gray-600 text-sm">___________________________</p>
                                    </div>
                                    <p class="text-gray-700 text-sm">{{ $distribution->distributedBy->name ?? 'Admin SIPZIS' }}</p>
                                    <!-- <p class="text-gray-500 text-xs mt-1">{{ $distribution->distribution_date->format('d F Y') }}</p> -->
                                </div>
                                <div class="text-center signature-box">
                                    <p class="font-semibold text-gray-800 mb-12 text-base">Penerima</p>
                                    <div class="mb-6">
                                        <p class="text-gray-600 text-sm">___________________________</p>
                                    </div>
                                    <p class="text-gray-700 text-sm">{{ $distribution->mustahik->name }}</p>
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
