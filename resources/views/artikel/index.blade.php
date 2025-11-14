@extends('layouts.main')

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
    <div class="artikel-page">
        <div class="artikel-bg">
            <!-- Mosque Background Image Overlay -->
            <div class="absolute inset-0 opacity-90"
                style="background-image: url('{{ asset('img/masjid.webp') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; will-change: transform;">
            </div>
            <!-- Green Gradient Overlay for blending -->
            <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-green-800/70 to-emerald-700/80"></div>
            <!-- Additional Dark Overlay for text readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20"></div>
            <!-- Animated Background Elements (removed) -->
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>

            <div class="artikel-content">
                <div class="container mx-auto px-4 py-16">
                    <!-- Enhanced Page Header -->
                    <!-- <div class="text-center mb-20 relative">
                                <h1 class="artikel-title">
                                    <span class="artikel-title-text">Artikel SIPZ</span>
                                    <div class="title-pulse"></div>
                                </h1>
                                <div class="max-w-4xl mx-auto">
                                    <p class="artikel-subtitle">
                                        Zakat, Infaq, dan Shodaqoh
                                    </p>
                                    <p class="artikel-description">
                                        Temukan informasi terbaru seputar zakat, infaq, dan sedekah untuk membantu Anda memahami pentingnya kegiatan sosial ini.
                                    </p>
                                    <div class="artikel-indicators">
                                        <div class="indicator indicator-1"></div>
                                        <div class="indicator indicator-2"></div>
                                        <div class="indicator indicator-3"></div>
                                    </div>
                                </div>
                            </div> -->
                    <!-- Enhanced Main Articles -->
                    <div class="artikel-main">
                        <div class="artikel-main-overlay"></div>
                        <div class="artikel-main-content">
                            <div class="text-center mb-12">
                                <h2 class="artikel-section-title">Artikel Seputar Lazismu Banten</h2>
                                <p class="artikel-section-description">Temukan informasi terbaru seputar zakat, infaq, dan
                                    sedekah untuk membantu Anda memahami pentingnya kegiatan sosial ini.</p>
                                <div class="artikel-section-divider"></div>
                            </div>
                            <!-- Articles Grid -->
                            @if ($artikels->count() > 0)
                                <div class="artikel-grid">
                                    @foreach ($artikels as $artikel)
                                        <div class="artikel-card">
                                            <div class="artikel-card-image-container">
                                                @if ($artikel->image)
                                                    @php
                                                        // Cek apakah image adalah URL penuh (CDN) menggunakan filter_var untuk validasi yang lebih andal
                                                        $rawImage = trim($artikel->image ?? '');
                                                        $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                                        // Tentukan URL akhir
                                                        $imageUrl = $isFullUrl ? $rawImage : Storage::url($rawImage);
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" alt="{{ $artikel->title }}"
                                                        class="artikel-card-image">
                                                @else
                                                    <div class="artikel-card-placeholder">
                                                        <i class="fas fa-image artikel-placeholder-icon"></i>
                                                    </div>
                                                @endif
                                                <div class="artikel-card-category">
                                                    @php
                                                        $categoryColors = [
                                                            'zakat' => 'category-zakat',
                                                            'infaq' => 'category-infaq',
                                                            'sedekah' => 'category-sedekah',
                                                        ];
                                                        $textColors = [
                                                            'zakat' => 'text-zakat',
                                                            'infaq' => 'text-infaq',
                                                            'sedekah' => 'text-sedekah',
                                                        ];
                                                        $bgColors = [
                                                            'zakat' => 'bg-zakat',
                                                            'infaq' => 'bg-infaq',
                                                            'sedekah' => 'bg-sedekah',
                                                        ];
                                                        $hoverColors = [
                                                            'zakat' => 'hover-zakat',
                                                            'infaq' => 'hover-infaq',
                                                            'sedekah' => 'hover-sedekah',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="artikel-category-badge {{ $categoryColors[$artikel->category] ?? 'category-default' }}">
                                                        {{ $artikel->category_label }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="artikel-card-content">
                                                <h3 class="artikel-card-title line-clamp-2">
                                                    <a href="{{ route('artikel.show', $artikel->slug) }}"
                                                        class="artikel-card-title-link">
                                                        {{ $artikel->title }}
                                                    </a>
                                                </h3>
                                                <p class="artikel-card-excerpt line-clamp-3">
                                                    {{ $artikel->excerpt }}
                                                </p>
                                                <div class="artikel-card-footer">
                                                    <div class="artikel-card-author">
                                                        <div class="artikel-author-avatar">
                                                            <i class="fas fa-user artikel-author-icon"></i>
                                                        </div>
                                                        <span
                                                            class="artikel-author-name">{{ $artikel->author->name }}</span>
                                                    </div>
                                                    <a href="{{ route('artikel.show', $artikel->slug) }}"
                                                        class="artikel-read-more {{ $textColors[$artikel->category] ?? 'text-default' }} {{ $hoverColors[$artikel->category] ?? 'hover-default' }}">
                                                        Baca Selengkapnya
                                                        <svg class="artikel-read-more-icon" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                @if ($artikels->hasPages())
                                    <div class="artikel-pagination">
                                        {{ $artikels->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="artikel-empty">
                                    <div class="artikel-empty-icon">
                                        <svg class="artikel-empty-svg" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="artikel-empty-title">Belum Ada Artikel</h3>
                                    <p class="artikel-empty-description">Artikel akan segera hadir. Silakan kunjungi kembali
                                        nanti.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .artikel-page {
            min-height: 100vh;
            overflow: hidden;
        }

        .artikel-bg {
            position: relative;
            background: linear-gradient(to bottom right, #064e3b, #065f46, #047857);
            min-height: 100vh;
            overflow: hidden;
            will-change: transform;
        }

        /* Removed heavy blob animations for better performance */
        .blob {
            display: none;
        }

        .artikel-content {
            position: relative;
            z-index: 10;
            padding: 5rem 0;
        }

        .artikel-title {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            position: relative;
        }

        @media (min-width: 768px) {
            .artikel-title {
                font-size: 4.5rem;
            }
        }

        .artikel-title-text {
            background: linear-gradient(to right, #ffffff, #dcfce7, #d1fae5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 25px 25px rgba(0, 0, 0, 0.15);
        }

        .title-pulse {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            width: 1rem;
            height: 1rem;
            background-color: #4ade80;
            border-radius: 50%;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        .artikel-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        @media (min-width: 768px) {
            .artikel-subtitle {
                font-size: 1.875rem;
            }
        }

        .artikel-description {
            font-size: 1.25rem;
            font-weight: 500;
            color: #d1fae5;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .artikel-description {
                font-size: 1.5rem;
            }
        }

        .artikel-indicators {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .indicator {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            animation: bounce 1s infinite;
        }

        .indicator-1 {
            background-color: #4ade80;
        }

        .indicator-2 {
            background-color: #34d399;
            animation-delay: 0.1s;
        }

        .indicator-3 {
            background-color: #2dd4bf;
            animation-delay: 0.2s;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-0.5rem);
            }
        }

        .artikel-main {
            position: relative;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            margin-bottom: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .artikel-main-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(240, 253, 244, 0.5), rgba(236, 253, 245, 0.5), rgba(240, 253, 250, 0.5));
            border-radius: 1.5rem;
        }

        .artikel-main-content {
            position: relative;
            z-index: 10;
        }

        .artikel-section-title {
            font-size: 2.25rem;
            font-weight: 900;
            color: #166534;
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .artikel-section-title {
                font-size: 3rem;
            }
        }

        .artikel-section-description {
            font-size: 1.125rem;
            color: #166534;
            max-width: 42rem;
            margin-left: auto;
            margin-right: auto;
        }

        .artikel-section-divider {
            width: 6rem;
            height: 0.25rem;
            background: linear-gradient(to right, #16a34a, #059669, #0d9488);
            border-radius: 9999px;
            margin: 1rem auto 0;
        }

        .artikel-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .artikel-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .artikel-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .artikel-card {
            position: relative;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: box-shadow 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            will-change: box-shadow;
        }

        .artikel-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }

        .artikel-card-image-container {
            position: relative;
        }

        .artikel-card-image {
            width: 100%;
            height: 12rem;
            object-fit: cover;
        }

        .artikel-card-placeholder {
            width: 100%;
            height: 12rem;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .artikel-placeholder-icon {
            font-size: 2.25rem;
            color: #9ca3af;
        }

        .artikel-card-category {
            position: absolute;
            top: 1rem;
            left: 1rem;
        }

        .artikel-category-badge {
            display: inline-block;
            background: linear-gradient(to right, var(--category-from), var(--category-to));
            color: white;
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .category-zakat {
            --category-from: #16a34a;
            --category-to: #15803d;
        }

        .category-infaq {
            --category-from: #2563eb;
            --category-to: #1d4ed8;
        }

        .category-sedekah {
            --category-from: #9333ea;
            --category-to: #7e22ce;
        }

        .category-default {
            --category-from: #6b7280;
            --category-to: #374151;
        }

        .artikel-card-content {
            padding: 1.5rem;
        }

        .artikel-card-title {
            font-size: 1.25rem;
            font-weight: 900;
            color: #1f2937;
            margin-bottom: 0.75rem;
            line-height: 1.375;
        }

        .artikel-card-title-link {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        .artikel-card-title-link:hover {
            color: #16a34a;
        }

        .artikel-card-excerpt {
            color: #4b5563;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            line-height: 1.625;
        }

        .artikel-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .artikel-card-author {
            display: flex;
            align-items: center;
        }

        .artikel-author-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
        }

        .artikel-author-icon {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .artikel-author-name {
            font-size: 0.875rem;
            color: #4b5563;
        }

        .artikel-read-more {
            font-size: 0.875rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: color 0.2s;
        }

        .text-zakat {
            color: #16a34a;
        }

        .text-infaq {
            color: #2563eb;
        }

        .text-sedekah {
            color: #9333ea;
        }

        .text-default {
            color: #4b5563;
        }

        .hover-zakat:hover {
            color: #166534;
        }

        .hover-infaq:hover {
            color: #1e40af;
        }

        .hover-sedekah:hover {
            color: #7e22ce;
        }

        .hover-default:hover {
            color: #1f2937;
        }

        .artikel-read-more-icon {
            width: 1rem;
            height: 1rem;
            margin-left: 0.25rem;
            transition: transform 0.2s;
        }

        .artikel-read-more:hover .artikel-read-more-icon {
            transform: translateX(0.25rem);
        }

        .artikel-pagination {
            margin-top: 3rem;
            display: flex;
            justify-content: center;
        }

        .artikel-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem 0;
        }

        .artikel-empty-icon {
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .artikel-empty-svg {
            width: 4rem;
            height: 4rem;
            margin-left: auto;
            margin-right: auto;
        }

        .artikel-empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }

        .artikel-empty-description {
            color: #6b7280;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

@endsection
