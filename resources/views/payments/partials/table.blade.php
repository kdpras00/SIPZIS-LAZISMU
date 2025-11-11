@if ($payments->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Kode Pembayaran</th>
                    @if (auth()->user()->role !== 'muzakki')
                        <th scope="col" class="px-6 py-3">Muzakki</th>
                    @endif
                    <th scope="col" class="px-6 py-3">Jenis Zakat</th>
                    <th scope="col" class="px-6 py-3">Jumlah Bayar</th>
                    <th scope="col" class="px-6 py-3">Metode</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    @if (auth()->user()->role !== 'muzakki')
                        <th scope="col" class="px-6 py-3">Referensi</th>
                        <th scope="col" class="px-6 py-3">Midtrans</th>
                    @endif
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full p-2 mr-3">
                                    <i class="bi bi-credit-card text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $payment->payment_code }}</div>
                                    <small class="text-gray-500">{{ $payment->receipt_number }}</small>
                                </div>
                            </div>
                        </td>
                        @if (auth()->user()->role !== 'muzakki')
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-900">{{ $payment->muzakki->name }}</div>
                                @if ($payment->muzakki->phone)
                                    <small class="text-gray-500">{{ $payment->muzakki->phone }}</small>
                                @endif
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">Rp
                                {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                            @if ($payment->zakat_amount)
                                <small class="text-gray-500">Zakat: Rp
                                    {{ number_format($payment->zakat_amount, 0, ',', '.') }}</small>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($payment->payment_method)
                                @case('cash')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tunai</span>
                                @break

                                @case('transfer')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Transfer</span>
                                @break

                                @case('check')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Cek</span>
                                @break

                                @case('online')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Online</span>
                                @break

                                @case('midtrans')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Midtrans</span>
                                @break

                                @default
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucwords($payment->payment_method) }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($payment->status)
                                @case('pending')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                                @break

                                @case('completed')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                @break

                                @case('cancelled')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                @break

                                @default
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucwords($payment->status) }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                            {{ $payment->payment_date->format('d M Y') }}</td>

                        @if (auth()->user()->role !== 'muzakki')
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($payment->payment_reference)
                                    <small
                                        class="font-mono text-xs text-gray-600">{{ Str::limit($payment->payment_reference, 10) }}</small>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($payment->midtrans_payment_method)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ Str::limit($payment->midtrans_payment_method, 10) }}
                                    </span>
                                @elseif($payment->midtrans_order_id)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Ya</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        @endif

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('payments.show', $payment) }}"
                                    class="text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1.5"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('payments.receipt', $payment) }}"
                                    class="text-green-600 hover:text-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-lg p-1.5"
                                    title="Kwitansi" target="_blank">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                @if (auth()->user()->role !== 'muzakki')
                                    <a href="{{ route('payments.edit', $payment) }}"
                                        class="text-purple-600 hover:text-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-lg p-1.5"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($payment->status !== 'completed')
                                        <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-lg p-1.5"
                                                title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($payments->hasPages())
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            {{ $payments->withQueryString()->links() }}
        </div>
    @endif
@else
    <div class="text-center py-12 px-6">
        <i class="bi bi-inbox text-6xl text-gray-400 mb-4 block"></i>
        <h5 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pembayaran</h5>
        <p class="text-gray-600 mb-4">
            @if (auth()->user()->role === 'muzakki')
                Belum ada pembayaran zakat yang tercatat
            @else
                Tidak ada pembayaran zakat yang sesuai dengan kriteria pencarian
            @endif
        </p>
        @if (auth()->user()->role === 'muzakki')
            <a href="{{ route('muzakki.payments.create') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg focus:outline-none">
                <i class="bi bi-plus-circle mr-2"></i> Bayar Zakat Sekarang
            </a>
        @endif
    </div>
@endif
