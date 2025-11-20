@extends('layouts.app')

@section('page-title', 'Detail Berita - ' . $news->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Detail Berita</h2>
        <p class="text-muted">{{ $news->title }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('news.show', $news->slug) }}" class="btn btn-outline-success" target="_blank">
            <i class="bi bi-eye"></i> Lihat Publik
        </a>
        @if(!$news->is_published)
        <form action="{{ route('admin.news.toggle-publish', $news) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline-warning">
                <i class="bi bi-check-circle"></i> Publikasikan
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- News Content Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-newspaper"></i> Konten Berita</h5>
                    @if($news->is_published)
                    <span class="badge bg-success fs-6">Published</span>
                    @else
                    <span class="badge bg-warning fs-6">Draft</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Featured Image -->
                @if($news->image)
                <div class="mb-4">
                    @php
                    $rawImage = trim($news->image ?? '');
                    // Cek apakah image adalah URL penuh (CDN)
                    $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                    // Tentukan URL akhir
                    $imageUrl = $isFullUrl
                    ? $rawImage
                    : Storage::url($news->image);
                    @endphp
                    <img src="{{ $imageUrl }}"
                        alt="{{ $news->title }}"
                        class="img-fluid rounded shadow-sm"
                        style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
                @endif

                <!-- Title and Meta -->
                <div class="mb-4">
                    <h1 class="display-6 fw-bold text-dark mb-3">{{ $news->title }}</h1>

                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        <div class="text-muted">
                            <i class="bi bi-person"></i> {{ $news->author->name }}
                        </div>

                        <div class="text-muted">
                            <i class="bi bi-calendar"></i> {{ $news->formatted_date }}
                        </div>

                        @if($news->created_at != $news->updated_at)
                        <div class="text-muted">
                            <i class="bi bi-pencil"></i> Diperbarui {{ $news->updated_at->format('d M Y H:i') }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Excerpt -->
                @if($news->excerpt)
                <div class="mb-4">
                    <div class="alert alert-info">
                        <h6 class="mb-2"><i class="bi bi-info-circle"></i> Ringkasan</h6>
                        <p class="mb-0">{{ $news->excerpt }}</p>
                    </div>
                </div>
                @endif

                <!-- Content -->
                <div class="article-content">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>
        </div>

        <!-- SEO Information Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-search"></i> Informasi SEO</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted" style="width: 25%;">Slug URL:</td>
                        <td class="fw-semibold">{{ $news->slug }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Link Publik:</td>
                        <td>
                            <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="text-decoration-none">
                                {{ route('news.show', $news->slug) }}
                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Preview:</td>
                        <td>{{ Str::limit($news->excerpt ?: $news->content, 150) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Publication Status Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-{{ $news->is_published ? 'success' : 'warning' }} text-white">
                <h6 class="mb-0">
                    <i class="bi bi-{{ $news->is_published ? 'check-circle' : 'clock' }}"></i>
                    Status Publikasi
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($news->is_published)
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h4 class="text-success mt-2">Telah Dipublikasikan</h4>
                    <p class="text-muted">Berita ini sudah dapat dilihat publik</p>
                    @else
                    <i class="bi bi-clock-fill text-warning" style="font-size: 3rem;"></i>
                    <h4 class="text-warning mt-2">Draft</h4>
                    <p class="text-muted">Berita ini belum dipublikasikan</p>
                    @endif
                </div>

                <div class="d-grid gap-2">
                    @if($news->is_published)
                    <form action="{{ route('admin.news.toggle-publish', $news) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                            <i class="bi bi-eye-slash"></i> Batalkan Publikasi
                        </button>
                    </form>
                    <a href="{{ route('news.show', $news->slug) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="bi bi-eye"></i> Lihat di Situs Publik
                    </a>
                    @else
                    <form action="{{ route('admin.news.toggle-publish', $news) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check-circle"></i> Publikasikan Sekarang
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Article Statistics Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Statistik Artikel</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted">Jumlah Kata:</td>
                        <td class="fw-semibold">{{ str_word_count(strip_tags($news->content)) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Karakter:</td>
                        <td class="fw-semibold">{{ strlen(strip_tags($news->content)) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dibuat:</td>
                        <td class="fw-semibold">{{ $news->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @if($news->created_at != $news->updated_at)
                    <tr>
                        <td class="text-muted">Diperbarui:</td>
                        <td class="fw-semibold">{{ $news->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Edit Berita
                    </a>

                    <a href="{{ route('admin.news.create') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-plus-circle"></i> Buat Berita Baru
                    </a>

                    <button type="button" class="btn btn-outline-info btn-sm" onclick="copyToClipboard()">
                        <i class="bi bi-clipboard"></i> Salin Link Publik
                    </button>

                    <hr>
                    <form action="{{ route('admin.news.destroy', $news) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash"></i> Hapus Berita
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .article-content {
        line-height: 1.8;
        font-size: 16px;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin: 1.5rem 0;
    }

    .article-content h1,
    .article-content h2,
    .article-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #2d3748;
    }

    .article-content blockquote {
        border-left: 4px solid #10b981;
        background: #f0fdf4;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        border-radius: 0 8px 8px 0;
    }

    .article-content ul,
    .article-content ol {
        margin: 1rem 0;
        padding-left: 2rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyToClipboard() {
        const url = "{{ route('news.show', $news->slug) }}";
        navigator.clipboard.writeText(url).then(function() {
            // Create a temporary alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
            <i class="bi bi-check-circle"></i> Link berhasil disalin ke clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            document.body.appendChild(alert);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 3000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
            alert('Gagal menyalin link. Silakan salin manual.');
        });
    }
</script>
@endpush