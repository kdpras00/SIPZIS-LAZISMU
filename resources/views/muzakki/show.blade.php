@extends('layouts.app')

@section('page-title', 'Detail Muzakki - ' . $muzakki->name)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Detail Muzakki</h2>
            <p class="text-gray-600">Informasi lengkap dan riwayat pembayaran zakat</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('muzakki.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-blue-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('muzakki.edit', $muzakki) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <a href="{{ route('payments.create', ['muzakki_id' => $muzakki->id]) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pembayaran
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Muzakki Profile Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                    <h5 class="text-lg font-semibold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Pribadi
                    </h5>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div
                            class="bg-blue-100 rounded-full p-4 inline-flex items-center justify-center w-20 h-20 mx-auto mb-3">
                            <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mt-3 mb-2">{{ $muzakki->name }}</h4>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $muzakki->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $muzakki->is_active ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex border-b border-gray-100 pb-3">
                            <div class="text-gray-600 w-24 flex-shrink-0">NIK</div>
                            <div class="text-gray-900">{{ $muzakki->nik ?: '-' }}</div>
                        </div>
                        <div class="flex border-b border-gray-100 pb-3">
                            <div class="text-gray-600 w-24 flex-shrink-0">Email</div>
                            <div class="text-gray-900">{{ $muzakki->email ?: '-' }}</div>
                        </div>
                        <div class="flex border-b border-gray-100 pb-3">
                            <div class="text-gray-600 w-24 flex-shrink-0">Telepon</div>
                            <div class="text-gray-900">{{ $muzakki->phone ?: '-' }}</div>
                        </div>
                        <div class="flex border-b border-gray-100 pb-3">
                            <div class="text-gray-600 w-24 flex-shrink-0">Gender</div>
                            <div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $muzakki->gender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $muzakki->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </div>
                        </div>
                        @if ($muzakki->date_of_birth)
                            <div class="flex border-b border-gray-100 pb-3">
                                <div class="text-gray-600 w-24 flex-shrink-0">Tanggal Lahir</div>
                                <div class="text-gray-900">{{ $muzakki->date_of_birth->format('d F Y') }}</div>
                            </div>
                            <div class="flex border-b border-gray-100 pb-3">
                                <div class="text-gray-600 w-24 flex-shrink-0">Usia</div>
                                <div class="text-gray-900">{{ $muzakki->age ?? '-' }} tahun</div>
                            </div>
                        @endif
                        <div class="flex border-b border-gray-100 pb-3">
                            <div class="text-gray-600 w-24 flex-shrink-0">Pekerjaan</div>
                            <div class="text-gray-900">
                                {{ $muzakki->occupation ? ucwords(str_replace('_', ' ', $muzakki->occupation)) : '-' }}
                            </div>
                        </div>
                        @if ($muzakki->monthly_income)
                            <div class="flex">
                                <div class="text-gray-600 w-24 flex-shrink-0">Pendapatan</div>
                                <div class="text-gray-900">Rp
                                    {{ number_format($muzakki->monthly_income, 0, ',', '.') }}/bulan</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="bg-cyan-600 text-white px-6 py-3 rounded-t-lg">
                    <h6 class="text-base font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Alamat
                    </h6>
                </div>
                <div class="p-6">
                    @if ($muzakki->address || $muzakki->city || $muzakki->province)
                        <address class="text-gray-700 not-italic mb-0">
                            @if ($muzakki->address)
                                {{ $muzakki->address }}<br>
                            @endif
                            @if ($muzakki->city || $muzakki->province)
                                {{ $muzakki->city }}{{ $muzakki->city && $muzakki->province ? ', ' : '' }}{{ $muzakki->province }}<br>
                            @endif
                            @if ($muzakki->postal_code)
                                {{ $muzakki->postal_code }}
                            @endif
                        </address>
                    @else
                        <p class="text-gray-500 mb-0">Alamat belum diisi</p>
                    @endif
                </div>
            </div>

            <!-- Account Information -->
            @if ($muzakki->user)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="bg-green-600 text-white px-6 py-3 rounded-t-lg">
                        <h6 class="text-base font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Akun Pengguna
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex border-b border-gray-100 pb-3">
                                <div class="text-gray-600 w-24 flex-shrink-0">Status</div>
                                <div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $muzakki->user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $muzakki->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex border-b border-gray-100 pb-3">
                                <div class="text-gray-600 w-24 flex-shrink-0">Role</div>
                                <div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($muzakki->user->role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="text-gray-600 w-24 flex-shrink-0">Terdaftar</div>
                                <div class="text-gray-900">{{ $muzakki->user->created_at->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Zakat Statistics and Payments -->
        <div class="lg:col-span-2">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-600 text-white rounded-lg shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="text-blue-100 text-sm font-medium mb-2">Total Zakat</h6>
                                <h4 class="text-2xl font-bold mb-0">Rp
                                    {{ number_format($stats['total_zakat'], 0, ',', '.') }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-blue-100 text-xs mt-2 opacity-75">Sepanjang masa</p>
                    </div>
                </div>
                <div class="bg-green-600 text-white rounded-lg shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="text-green-100 text-sm font-medium mb-2">Total Transaksi</h6>
                                <h4 class="text-2xl font-bold mb-0">{{ number_format($stats['payment_count']) }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-green-100 text-xs mt-2 opacity-75">Pembayaran selesai</p>
                    </div>
                </div>
                <div class="bg-cyan-600 text-white rounded-lg shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="text-cyan-100 text-sm font-medium mb-2">Terakhir Bayar</h6>
                                <h4 class="text-2xl font-bold mb-0">
                                    @if ($stats['last_payment'])
                                        {{ $stats['last_payment']->payment_date->diffForHumans() }}
                                    @else
                                        -
                                    @endif
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-cyan-200" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        @if ($stats['last_payment'])
                            <p class="text-cyan-100 text-xs mt-2 opacity-75">
                                {{ $stats['last_payment']->payment_date->format('d M Y') }}</p>
                        @else
                            <p class="text-cyan-100 text-xs mt-2 opacity-75">Belum ada pembayaran</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-white border-b border-gray-200 px-6 py-4 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat Pembayaran Donasi
                        </h5>
                        <a href="{{ route('payments.index', ['search' => $muzakki->name]) }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="overflow-hidden">
                    @if ($recentPayments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Kode Pembayaran</th>
                                        <th scope="col" class="px-6 py-3">Jenis Zakat</th>
                                        <th scope="col" class="px-6 py-3">Jumlah</th>
                                        <th scope="col" class="px-6 py-3">Metode</th>
                                        <th scope="col" class="px-6 py-3">Tanggal</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($recentPayments as $payment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="bg-blue-100 rounded-full p-2 mr-3">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">
                                                            {{ $payment->payment_code }}</div>
                                                        @if ($payment->receipt_number)
                                                            <div class="text-xs text-gray-500">No:
                                                                {{ $payment->receipt_number }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                                    {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">Rp
                                                    {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                                                @if ($payment->zakat_amount != $payment->paid_amount)
                                                    <div class="text-xs text-gray-500">Zakat: Rp
                                                        {{ number_format($payment->zakat_amount, 0, ',', '.') }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @switch($payment->payment_method)
                                                    @case('cash')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tunai</span>
                                                    @break

                                                    @case('transfer')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Transfer</span>
                                                    @break

                                                    @case('online')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Online</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($payment->payment_method) }}</span>
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-gray-900">{{ $payment->payment_date->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $payment->payment_date->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @switch($payment->status)
                                                    @case('completed')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                                    @break

                                                    @case('pending')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                                    @break

                                                    @case('cancelled')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($payment->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('payments.show', $payment->payment_code) }}"
                                                        class="inline-flex items-center p-2 text-sm font-medium text-center text-cyan-600 hover:text-cyan-800 hover:bg-cyan-50 rounded-lg focus:ring-4 focus:outline-none focus:ring-cyan-300"
                                                        title="Detail">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    @if ($payment->status === 'completed')
                                                        <a href="{{ route('payments.receipt', $payment) }}"
                                                            class="inline-flex items-center p-2 text-sm font-medium text-center text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg focus:ring-4 focus:outline-none focus:ring-green-300"
                                                            title="Kwitansi" target="_blank">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    @if (auth()->user()->role === 'admin')
                                                        <a href="{{ route('payments.edit', $payment) }}"
                                                            class="inline-flex items-center p-2 text-sm font-medium text-center text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg focus:ring-4 focus:outline-none focus:ring-blue-300"
                                                            title="Edit">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pembayaran</h5>
                            <p class="text-gray-500 mb-4">Muzakki ini belum memiliki riwayat pembayaran zakat</p>
                            <a href="{{ route('payments.create', ['muzakki_id' => $muzakki->id]) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Pembayaran Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Modal for Status Toggle -->
    @if (in_array(auth()->user()->role, ['admin', 'staff']))
        <div id="statusModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Perubahan Status</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-hide="statusModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5">
                        <p class="text-gray-700 mb-4">Apakah Anda yakin ingin mengubah status muzakki ini menjadi
                            {{ $muzakki->is_active ? 'non-aktif' : 'aktif' }}?</p>
                        @if ($muzakki->user)
                            <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50">
                                <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                </svg>
                                <span class="sr-only">Info</span>
                                <div>Status akun pengguna juga akan ikut berubah.</div>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button type="button" data-modal-hide="statusModal"
                            class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                            Batal
                        </button>
                        <form action="{{ route('muzakki.toggle-status', $muzakki) }}" method="POST"
                            class="inline ml-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="py-2.5 px-5 text-sm font-medium text-white {{ $muzakki->is_active ? 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-300' : 'bg-green-600 hover:bg-green-700 focus:ring-green-300' }} rounded-lg focus:outline-none focus:ring-4">
                                {{ $muzakki->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Flowbite modal initialization would be handled by Flowbite library
            // If using Flowbite, ensure it's included in your app.js or layout
        });
    </script>
@endpush
