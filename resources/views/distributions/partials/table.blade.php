@if($distributions->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Distribusi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mustahik</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($distributions as $distribution)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full p-2 mr-2">
                            <i class="bi bi-hand-thumbs-up text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold">{{ $distribution->distribution_code }}</div>
                            @if($distribution->location)
                            <small class="text-gray-500">{{ $distribution->location }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-semibold">{{ $distribution->mustahik->name }}</div>
                    <small class="text-gray-500">{{ ucfirst(str_replace('_', ' ', $distribution->mustahik->category)) }}</small>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($distribution->program_name)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">{{ $distribution->program_name }}</span>
                    @else
                    <span class="text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @switch($distribution->distribution_type)
                        @case('cash')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Tunai</span>
                            @break
                        @case('goods')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">Barang</span>
                            @break
                        @case('voucher')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Voucher</span>
                            @break
                        @case('service')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Layanan</span>
                            @break
                        @default
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucwords($distribution->distribution_type) }}</span>
                    @endswitch
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-bold">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</div>
                    @if($distribution->goods_description)
                    <small class="text-gray-500">{{ Str::limit($distribution->goods_description, 30) }}</small>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($distribution->is_received)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sudah Diterima</span>
                        @if($distribution->received_date)
                        <br><small class="text-gray-500">{{ $distribution->received_date->format('d M Y') }}</small>
                        @endif
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Diterima</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $distribution->distribution_date->format('d M Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('distributions.show', $distribution) }}" class="inline-flex items-center px-2 py-1 border border-cyan-300 rounded text-cyan-700 hover:bg-cyan-50" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('distributions.receipt', $distribution) }}" class="inline-flex items-center px-2 py-1 border border-green-300 rounded text-green-700 hover:bg-green-50" title="Kwitansi" target="_blank">
                            <i class="bi bi-receipt"></i>
                        </a>
                        <a href="{{ route('distributions.edit', $distribution) }}" class="inline-flex items-center px-2 py-1 border border-blue-300 rounded text-blue-700 hover:bg-blue-50" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if(!$distribution->is_received)
                        <button type="button" class="inline-flex items-center px-2 py-1 border border-yellow-300 rounded text-yellow-700 hover:bg-yellow-50" title="Tandai Diterima" onclick="showMarkReceivedModal({{ $distribution->id }}, '{{ $distribution->mustahik->name }}')">
                            <i class="bi bi-check-circle"></i>
                        </button>
                        <form action="{{ route('distributions.destroy', $distribution) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus distribusi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 rounded text-red-700 hover:bg-red-50" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($distributions->hasPages())
<div class="px-6 py-4 bg-white border-t border-gray-200">
    {{ $distributions->withQueryString()->links() }}
</div>
@endif

@else
<div class="text-center py-12">
    <i class="bi bi-inbox text-6xl text-gray-400 mb-3 block"></i>
    <h5 class="text-gray-600 text-lg font-semibold mb-2">Tidak ada data distribusi</h5>
    <p class="text-gray-500 mb-4">Belum ada distribusi zakat yang tercatat dalam sistem atau sesuai kriteria pencarian</p>
    <a href="{{ route('distributions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        <i class="bi bi-plus-circle mr-2"></i> Tambah Distribusi Pertama
    </a>
</div>
@endif

<!-- Mark as Received Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" id="markReceivedModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h5 class="text-lg font-semibold">Tandai Sebagai Diterima</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeMarkReceivedModal()">
                <i class="bi bi-x-lg"></i>
            </button>
            </div>
            <form id="markReceivedForm" method="POST">
                @csrf
                @method('PATCH')
            <div class="mt-4">
                <p class="text-gray-700 mb-4">Konfirmasi bahwa distribusi untuk <strong id="mustahikNameModal"></strong> telah diterima?</p>
                    
                <div class="mb-4">
                    <label for="received_by_name" class="block text-sm font-medium text-gray-700 mb-1">Diterima Oleh</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="received_by_name" name="received_by_name" placeholder="Nama penerima (opsional)">
                </div>
                
                <div class="mb-4">
                    <label for="received_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Penerimaan</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="received_notes" name="received_notes" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-2 pt-4 border-t">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50" onclick="closeMarkReceivedModal()">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Tandai Diterima</button>
                </div>
            </form>
    </div>
</div>

<script>
// Show mark received modal function
function showMarkReceivedModal(distributionId, mustahikName) {
    document.getElementById('mustahikNameModal').textContent = mustahikName;
    document.getElementById('markReceivedForm').action = `/distributions/${distributionId}/mark-received`;
    
    // Clear form fields
    document.getElementById('received_by_name').value = '';
    document.getElementById('received_notes').value = '';
    
    // Show modal
    document.getElementById('markReceivedModal').classList.remove('hidden');
}

function closeMarkReceivedModal() {
    document.getElementById('markReceivedModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('markReceivedModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkReceivedModal();
}
});
</script>
