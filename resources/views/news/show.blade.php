@extends('layouts.main')

@section('navbar')
@include('partials.navbarHome')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('berita') }}" class="inline-flex items-center text-green-600 hover:text-green-800 mb-6 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Berita
        </a>

        <article class="bg-white rounded-xl shadow-md overflow-hidden">
            {{-- Header with Image --}}
            <header class="relative">
                @if($news->image)
                @php
                // Jika link sudah URL penuh (misal dari CDN), langsung pakai
                $imageUrl = Str::startsWith($news->image, ['http://', 'https://'])
                ? $news->image
                : Storage::url($news->image);
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $news->title }}" class="w-full h-96 object-cover">
                @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>
                @endif


                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
                    <div class="flex items-center text-white mb-3">
                        <span class="text-sm">{{ $news->formatted_date }}</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight">{{ $news->title }}</h1>
                </div>
            </header>

            {{-- Author Info --}}
            <div class="px-8 pt-8 pb-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center mr-4 shadow-sm">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $news->author->name ?? 'Admin' }}</p>
                        <p class="text-sm text-gray-500">Penulis</p>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="px-10 md:px-16 lg:px-20 py-12">
                <div class="article-content text-gray-700 text-base leading-loose text-justify">
                    @foreach(preg_split('/\r\n|\r|\n/', $news->content) as $paragraph)
                    @if(trim($paragraph) !== '')
                    <p>{{ $paragraph }}</p>
                    @endif
                    @endforeach
                </div>
            </div>

        </article>

        {{-- Navigation --}}
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('berita') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Berita Lainnya
            </a>

            {{-- Share Buttons (Optional) --}}
            <div class="flex gap-3">
                <a 
                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="w-10 h-10 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center transition-colors shadow-sm"
                    title="Share to Facebook"
                >
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a 
                    href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' ' . Request::url()) }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="w-10 h-10 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center transition-colors shadow-sm"
                    title="Share to WhatsApp"
                >
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a 
                    href="https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}&text={{ urlencode($news->title) }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="w-10 h-10 rounded-full bg-sky-500 hover:bg-sky-600 text-white flex items-center justify-center transition-colors shadow-sm"
                    title="Share to Twitter"
                >
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles for Better Typography --}}
<style>
    .article-content {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 1.05rem;
        line-height: 1.85;
        color: #2d2d2d;
        text-align: justify;
        text-justify: inter-word;
        word-spacing: 0.05em;
    }

    /* Paragraf umum */
    .article-content p {
        margin-bottom: 1rem;
        line-height: 1.9;
        text-align: justify;
        text-indent: 2rem;
        color: #374151;
    }

    /* Hanya paragraf pertama yang tanpa inden */
    .article-content p:first-child {
        text-indent: 0;
    }

    /* Hilangkan inden jika ada jeda topik (setelah elemen lain) */
    .article-content h2+p,
    .article-content h3+p,
    .article-content blockquote+p,
    .article-content ul+p,
    .article-content ol+p {
        text-indent: 0;
    }

    /* Heading */
    .article-content h2,
    .article-content h3,
    .article-content h4,
    .article-content h5,
    .article-content h6 {
        text-align: left !important;
        text-indent: 0;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        color: #111827;
        font-weight: 700;
        line-height: 1.3;
    }

    /* Penebalan & penekanan teks */
    .article-content strong {
        font-weight: 600;
        color: #1f2937;
    }

    .article-content em {
        font-style: italic;
    }

    /* Daftar (list) */
    .article-content ul,
    .article-content ol {
        margin-left: 2.5rem;
        margin-bottom: 1.5rem;
        text-indent: 0;
    }

    .article-content li {
        margin-bottom: 0.75rem;
        line-height: 1.8;
        text-align: justify !important;
    }

    /* Kutipan (blockquote) */
    .article-content blockquote {
        border-left: 4px solid #10b981;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #4b5563;
        background-color: #f9fafb;
        text-align: justify !important;
    }
</style>

@endsection