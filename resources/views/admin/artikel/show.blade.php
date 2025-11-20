@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.artikel.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Artikel</h1>
    </div>

    @include('components.alerts')

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Article Header -->
        <div class="relative">
            @if($artikel->image)
            @php
            $rawImage = trim($artikel->image ?? '');
            // Cek apakah image adalah URL penuh (CDN)
            $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
            // Tentukan URL akhir
            $imageUrl = $isFullUrl
            ? $rawImage
            : Storage::url($artikel->image);
            @endphp
            <img src="{{ $imageUrl }}" alt="Article Image" class="w-full h-96 object-cover">
            @else
            <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                <i class="fas fa-image text-gray-400 text-6xl"></i>
            </div>
            @endif

            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                <h1 class="text-3xl font-bold text-white mb-2">{{ $artikel->title }}</h1>
                <div class="flex flex-wrap items-center text-white/90">
                    <span>{{ $artikel->formatted_date }}</span>
                    <span class="mx-3">•</span>
                    <span>Oleh {{ $artikel->author->name }}</span>
                </div>
            </div>
        </div>

        <!-- Article Content -->
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($artikel->is_published) bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        <i class="fas fa-circle w-2 h-2 mr-2
                            @if($artikel->is_published) text-green-400
                            @else text-gray-400 @endif"></i>
                        {{ $artikel->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.artikel.edit', $artikel) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.artikel.destroy', $artikel) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="prose max-w-none">
                {!! $artikel->content !!}
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <a href="{{ route('admin.artikel.edit', $artikel) }}" class="btn btn-primary">
            <i class="fas fa-edit mr-2"></i>Edit Artikel
        </a>
    </div>
</div>
@endsection