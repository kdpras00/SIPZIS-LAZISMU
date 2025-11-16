@extends('layouts.app')

@section('page-title', 'Detail Distribusi - ' . $distribution->distribution_code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Detail Distribusi</h2>
        <p class="text-muted">{{ $distribution->distribution_code }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('distributions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('distributions.edit', $distribution) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('distributions.receipt', $distribution) }}" class="btn btn-outline-success" target="_blank">
            <i class="bi bi-receipt"></i> Kwitansi
        </a>
        @if(!$distribution->is_received)
        <button type="button" class="btn btn-outline-warning" onclick="showMarkReceivedModal()">
            <i class="bi bi-check-circle"></i> Tandai Diterima
        </button>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Distribution Details Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-hand-thumbs-up"></i> Informasi Distribusi</h5>
                    @if($distribution->is_received)
                        <span class="badge bg-success fs-6">Sudah Diterima</span>
                    @else
                        <span class="badge bg-warning fs-6">Belum Diterima</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 40%;">Kode Distribusi:</td>
                                <td class="fw-semibold">{{ $distribution->distribution_code }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Distribusi:</td>
                                <td>
                                    @switch($distribution->distribution_type)
                                        @case('cash')
                                            <span class="badge bg-success">Tunai</span>
                                            @break
                                        @case('goods')
                                            <span class="badge bg-info">Barang</span>
                                            @break
                                        @case('voucher')
                                            <span class="badge bg-warning">Voucher</span>
                                            @break
                                        @case('service')
                                            <span class="badge bg-primary">Layanan</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucwords($distribution->distribution_type) }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jumlah:</td>
                                <td class="fw-bold text-primary fs-5">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Distribusi:</td>
                                <td class="fw-semibold">{{ $distribution->distribution_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Lokasi:</td>
                                <td class="fw-semibold">{{ $distribution->location ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 40%;">Program:</td>
                                <td>
                                    @if($distribution->program_name)
                                        <span class="badge bg-info">{{ $distribution->program_name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dicatat Oleh:</td>
                                <td class="fw-semibold">{{ $distribution->distributedBy->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Dicatat:</td>
                                <td class="fw-semibold">{{ $distribution->created_at->format('d F Y H:i') }}</td>
                            </tr>
                            @if($distribution->is_received)
                            <tr>
                                <td class="text-muted">Tanggal Diterima:</td>
                                <td class="fw-semibold text-success">{{ $distribution->received_date?->format('d F Y H:i') ?? 'Tidak tercatat' }}</td>
                            </tr>
                            @endif
                            @if($distribution->received_by_name)
                            <tr>
                                <td class="text-muted">Diterima Oleh:</td>
                                <td class="fw-semibold">{{ $distribution->received_by_name }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                @if($distribution->goods_description)
                <div class="mt-3">
                    <h6 class="text-muted mb-2">Deskripsi Barang/Layanan:</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $distribution->goods_description }}
                    </div>
                </div>
                @endif
                
                @if($distribution->notes)
                <div class="mt-3">
                    <h6 class="text-muted mb-2">Catatan:</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $distribution->notes }}
                    </div>
                </div>
                @endif
                
                @if($distribution->received_notes)
                <div class="mt-3">
                    <h6 class="text-muted mb-2">Catatan Penerimaan:</h6>
                    <div class="p-3 bg-success bg-opacity-10 rounded border border-success">
                        {{ $distribution->received_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- History Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Distribusi Dicatat</h6>
                            <p class="mb-1">{{ $distribution->distributedBy->name ?? 'Sistem' }} mencatat distribusi ini</p>
                            <small class="text-muted">{{ $distribution->created_at->format('d F Y H:i') }}</small>
                        </div>
                    </div>
                    
                    @if($distribution->updated_at != $distribution->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Data Diperbarui</h6>
                            <p class="mb-1">Informasi distribusi diperbarui</p>
                            <small class="text-muted">{{ $distribution->updated_at->format('d F Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                    
                    @if($distribution->is_received)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Distribusi Diterima</h6>
                            <p class="mb-1">Dikonfirmasi telah diterima {{ $distribution->received_by_name ? 'oleh ' . $distribution->received_by_name : '' }}</p>
                            <small class="text-muted">{{ $distribution->received_date?->format('d F Y H:i') ?? 'Tanggal tidak tercatat' }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Mustahik Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-person-heart"></i> Informasi Mustahik</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-circle display-4 text-primary"></i>
                    </div>
                </div>
                
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width: 35%;">Nama:</td>
                        <td class="fw-semibold">{{ $distribution->mustahik->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">NIK:</td>
                        <td class="fw-semibold">{{ $distribution->mustahik->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kategori:</td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ ucfirst(str_replace('_', ' ', $distribution->mustahik->category)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Telepon:</td>
                        <td class="fw-semibold">{{ $distribution->mustahik->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email:</td>
                        <td class="fw-semibold">{{ $distribution->mustahik->email ?? '-' }}</td>
                    </tr>
                </table>
                
                @if($distribution->mustahik->address)
                <div class="mt-3">
                    <h6 class="text-muted mb-2">Alamat:</h6>
                    <p class="small">{{ $distribution->mustahik->address }}</p>
                </div>
                @endif
                
                <div class="d-grid">
                    <a href="{{ route('mustahik.show', $distribution->mustahik) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye"></i> Lihat Profile Mustahik
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$distribution->is_received)
                    <button type="button" class="btn btn-success btn-sm" onclick="showMarkReceivedModal()">
                        <i class="bi bi-check-circle"></i> Tandai Sudah Diterima
                    </button>
                    @endif
                    
                    <a href="{{ route('distributions.edit', $distribution) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Edit Distribusi
                    </a>
                    
                    <a href="{{ route('distributions.receipt', $distribution) }}" class="btn btn-info btn-sm" target="_blank">
                        <i class="bi bi-receipt"></i> Cetak Kwitansi
                    </a>
                    
                    <a href="{{ route('distributions.create', ['mustahik' => $distribution->mustahik->id]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-plus-circle"></i> Distribusi Lagi ke Mustahik Ini
                    </a>
                    
                    @if(!$distribution->is_received)
                    <hr>
                    <form action="{{ route('distributions.destroy', $distribution) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus distribusi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash"></i> Hapus Distribusi
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Received Modal -->
<div class="modal fade" id="markReceivedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tandai Sebagai Diterima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('distributions.mark-received', $distribution) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Konfirmasi bahwa distribusi untuk <strong>{{ $distribution->mustahik->name }}</strong> telah diterima?</p>
                    
                    <div class="mb-3">
                        <label for="received_by_name" class="form-label">Diterima Oleh</label>
                        <input type="text" class="form-control" id="received_by_name" name="received_by_name" placeholder="Nama penerima (opsional)">
                    </div>
                    
                    <div class="mb-3">
                        <label for="received_notes" class="form-label">Catatan Penerimaan</label>
                        <textarea class="form-control" id="received_notes" name="received_notes" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Tandai Diterima</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    padding: 0 0 20px 40px;
}

.timeline-marker {
    position: absolute;
    left: 8px;
    top: 4px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 0.25rem;
    color: #495057;
}

.timeline-content p {
    margin-bottom: 0.25rem;
    color: #6c757d;
    font-size: 0.9rem;
}
</style>
@endpush

@push('scripts')
<script>
function showMarkReceivedModal() {
    new bootstrap.Modal(document.getElementById('markReceivedModal')).show();
}
</script>
@endpush