@if($mustahik->count() > 0)
<div>
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Nama</th>
                <th scope="col" class="px-6 py-3">Kategori</th>
                <th scope="col" class="px-6 py-3">Telepon</th>
                <th scope="col" class="px-6 py-3">Kota</th>
                <th scope="col" class="px-6 py-3">Status Verifikasi</th>
                <th scope="col" class="px-6 py-3">Terdaftar</th>
                <th scope="col" class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mustahik as $item)
            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-3">
                            <i class="bi bi-person-heart text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $item->name }}</div>
                            @if($item->nik)
                            <small class="text-gray-500">NIK: {{ $item->nik }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ ucfirst(str_replace('_', ' ', $item->category)) }}</span>
                </td>
                <td class="px-6 py-4">{{ $item->phone ?: '-' }}</td>
                <td class="px-6 py-4">{{ $item->city ?: '-' }}</td>
                <td class="px-6 py-4">
                    @switch($item->verification_status)
                        @case('pending')
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                            @break
                        @case('verified')
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Terverifikasi</span>
                            @break
                        @case('rejected')
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">Ditolak</span>
                            @break
                        @default
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $item->verification_status }}</span>
                    @endswitch
                </td>
                <td class="px-6 py-4">{{ $item->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('mustahik.show', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('mustahik.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($item->verification_status === 'pending')
                        <button type="button" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Verifikasi" onclick="showVerifyModal({{ $item->id }}, '{{ $item->name }}')">
                            <i class="bi bi-check-circle"></i>
                        </button>
                        @endif
                        <form action="{{ route('mustahik.toggle-status', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Toggle Status">
                                <i class="bi bi-toggle-{{ $item->is_active ? 'on' : 'off' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('mustahik.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
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

@if(isset($pagination))
<div class="px-6 py-4 bg-white border-t border-gray-200">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $pagination['from'] ?? 1 }} sampai {{ $pagination['to'] ?? count($mustahik) }} dari {{ $pagination['total'] ?? count($mustahik) }} data
        </div>
        @if($pagination['last_page'] > 1)
        <nav>
            <ul class="inline-flex items-center -space-x-px">
                @if($pagination['current_page'] > 1)
                    <li>
                        <a href="#" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700" data-page="{{ $pagination['current_page'] - 1 }}">‹</a>
                    </li>
                @endif
                
                @for($i = 1; $i <= $pagination['last_page']; $i++)
                    <li>
                        <a href="#" class="px-3 py-2 leading-tight {{ $pagination['current_page'] == $i ? 'text-blue-600 bg-blue-50 border border-blue-300 hover:bg-blue-100 hover:text-blue-700' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700' }}" data-page="{{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
                
                @if($pagination['current_page'] < $pagination['last_page'])
                    <li>
                        <a href="#" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700" data-page="{{ $pagination['current_page'] + 1 }}">›</a>
                    </li>
                @endif
            </ul>
        </nav>
        @endif
    </div>
</div>
@elseif($mustahik->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200">
    {{ $mustahik->withQueryString()->links() }}
</div>
@endif

@else
<div class="text-center py-12">
    <i class="bi bi-inbox text-6xl text-gray-400 mb-4 block"></i>
    <h5 class="text-lg font-medium text-gray-500 mb-2">Tidak ada data mustahik</h5>
    <p class="text-sm text-gray-400 mb-4">Belum ada mustahik yang terdaftar dalam sistem atau sesuai kriteria pencarian</p>
    <a href="{{ route('mustahik.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
        <i class="bi bi-plus-circle mr-2"></i> Tambah Mustahik Pertama
    </a>
</div>
@endif

<script>
// Verify modal function
function showVerifyModal(id, name) {
    if(confirm(`Verifikasi mustahik: ${name}?`)) {
        // Create a form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/mustahik/${id}/verify`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="status" value="verified">
        `;
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>