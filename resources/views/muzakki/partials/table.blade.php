@if ($muzakki->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="width: 28%;">Nama</th>
                    <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="width: 12%;">Kategori</th>
                    <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="width: 12%;">Telepon</th>
                    <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="width: 12%;">Kota</th>
                    <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="width: 12%;">Status Verifikasi</th>
                    <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="width: 12%;">Terdaftar</th>
                    <th class="px-2 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        style="width: 12%;">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($muzakki as $item)
                    <tr class="hover:bg-gray-50 transition-colors {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full p-1.5 mr-2 flex-shrink-0">
                                    <i class="fas fa-user text-green-600 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 truncate">{{ $item->name }}</div>
                                    @if ($item->nik)
                                        <small class="text-gray-500 text-xs">NIK: {{ $item->nik }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-3">
                            @if ($item->occupation)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 whitespace-nowrap">{{ ucwords(str_replace('_', ' ', $item->occupation)) }}</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-2 py-3 text-gray-900 text-sm whitespace-nowrap">{{ $item->phone ?: '-' }}</td>
                        <td class="px-2 py-3 text-gray-900 text-sm whitespace-nowrap">{{ $item->city ?: '-' }}</td>
                        <td class="px-2 py-3">
                            @if ($item->is_active)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white whitespace-nowrap">Terverifikasi</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-500 text-white whitespace-nowrap">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-2 py-3 text-gray-900 text-sm whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-2 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('muzakki.show', $item->id) }}"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('muzakki.edit', $item->id) }}"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('muzakki.toggle-status', $item->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                        title="Toggle Status">
                                        <i class="bi bi-toggle-{{ $item->is_active ? 'on' : 'off' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('muzakki.destroy', $item->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (isset($pagination))
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-gray-600 text-sm">
                    Menampilkan {{ $pagination['from'] ?? 1 }} sampai {{ $pagination['to'] ?? count($muzakki) }} dari
                    {{ $pagination['total'] ?? count($muzakki) }} data
                </div>
                @if ($pagination['last_page'] > 1)
                    <nav>
                        <ul class="flex items-center space-x-1">
                            @if ($pagination['current_page'] > 1)
                                <li>
                                    <a class="pagination-link px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
                                        href="#" data-page="{{ $pagination['current_page'] - 1 }}">‹</a>
                                </li>
                            @endif

                            @for ($i = 1; $i <= $pagination['last_page']; $i++)
                                <li>
                                    <a class="pagination-link px-3 py-2 text-sm font-medium {{ $pagination['current_page'] == $i ? 'text-white bg-blue-600 border border-blue-600' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' }} rounded-lg transition-colors cursor-pointer"
                                        href="#" data-page="{{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($pagination['current_page'] < $pagination['last_page'])
                                <li>
                                    <a class="pagination-link px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
                                        href="#" data-page="{{ $pagination['current_page'] + 1 }}">›</a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    @endif
@else
    <div class="text-center py-12">
        <i class="fas fa-inbox text-6xl text-gray-400 mb-4 block"></i>
        <h5 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada data muzakki</h5>
        <p class="text-gray-500 mb-4">Tidak ada muzakki yang sesuai dengan kriteria pencarian</p>
        <button type="button" id="clear-search"
            class="px-4 py-2 border border-blue-600 text-blue-700 rounded-lg hover:bg-blue-50 transition-colors">
            <i class="fas fa-redo mr-2"></i> Reset Pencarian
        </button>
    </div>
@endif
