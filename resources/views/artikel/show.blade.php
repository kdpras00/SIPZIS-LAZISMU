@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('artikel.all') }}" class="inline-flex items-center text-green-600 hover:text-green-800 mb-6">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Artikel
        </a>

        <article class="bg-white rounded-xl shadow-md overflow-hidden">
            <header class="relative">
                <!-- @if($artikel->image)
                <img src="{{ Storage::url($artikel->image) }}" alt="{{ $artikel->title }}" class="w-full h-96 object-cover">
                @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>
                @endif -->
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
                <img src="{{ $imageUrl }}" alt="{{ $artikel->title }}" class="w-full h-96 object-cover">
                @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>
                @endif

                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
                    <div class="flex justify-start items-center text-white mb-3">
                        <span>{{ $artikel->formatted_date }}</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $artikel->title }}</h1>
                </div>
            </header>

            <div class="p-8">
                <div class="flex items-center border-b border-gray-200 pb-6 mb-8">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $artikel->author->name }}</p>
                            <p class="text-sm text-gray-500">Penulis</p>
                        </div>
                    </div>
                </div>

                <div class="prose max-w-none leading-relaxed text-gray-700 text-lg">
                    <div class="space-y-4">
                        {!! nl2br(e($artikel->content)) !!}
                    </div>
                </div>
            </div>
        </article>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('artikel.all') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Artikel Lainnya
            </a>
        </div>
    </div>
</div>
@endsection