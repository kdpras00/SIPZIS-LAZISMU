<div class="relative bg-gradient-to-br from-green-900 via-green-800 to-emerald-700 min-h-screen flex items-center justify-center overflow-hidden"
    id="beranda">
    <!-- Mosque Background Image Overlay -->
    <div class="absolute inset-0 opacity-90"
        style="background-image: url('{{ asset('img/masjid.webp') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    </div>
    <!-- Green Gradient Overlay for blending -->
    <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-green-800/70 to-emerald-700/80"></div>

    <!-- Additional Dark Overlay for text readability -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20"></div>

    <!-- Main Content -->
    <div class="relative max-w-6xl mx-auto px-4 py-20 md:py-32 text-center text-white z-10">

        <!-- Main Heading -->
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-8 leading-tight animate-fadeInUp">
            <span class="bg-gradient-to-r from-white via-green-100 to-white bg-clip-text text-transparent">
                "Dan laksanakanlah salat, tunaikanlah zakat, dan rukuklah beserta orang yang rukuk."
            </span>
        </h1>

        <!-- Subtitle -->
        <p class="text-2xl md:text-3xl font-light mb-4 text-green-100 animate-fadeInUp delay-500">
            (QS. Al-Baqarah: 43)
        </p>

        <!-- Description -->
        <p class="text-lg md:text-xl max-w-3xl mx-auto mb-12 text-green-100 leading-relaxed animate-fadeInUp delay-700">
            Tunaikan kewajiban zakat Anda dengan mudah, transparan, dan sesuai syariat Islam melalui platform digital
            yang terpercaya.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fadeInUp delay-1000">
            <a href="{{ route('calculator.index') }}"
                class="no-underline group bg-white hover:bg-green-600 text-green-800 hover:text-white font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl flex items-center gap-3 min-w-[250px]">
                <svg class="w-6 h-6 transition-transform group-hover:rotate-12" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 2v16h10V4H7zm2 2h6v2H9V6zm0 4h6v2H9v-2zm0 4h6v2H9v-2z" />
                </svg>
                <span class="font-semibold tracking-wide">KALKULATOR ZAKAT</span>
            </a>
            <a href="{{ route('program') }}"
                class="no-underline group bg-white hover:bg-green-600 text-green-800 hover:text-white font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl flex items-center gap-3 min-w-[250px]">
                <svg class="w-6 h-6 transition-transform group-hover:rotate-12" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L3.09 8.26L12 22L20.91 8.26L12 2Z" />
                </svg>
                <span class="font-semibold tracking-wide">DONASI SEKARANG</span>
            </a>
        </div>


    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </div>
</div>

<!-- Campaigns Terbaru Section -->
<div class="py-16 bg-gradient-to-br from-gray-50 via-white to-green-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12 animate-fadeInUp">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Campaigns Terbaru</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Temukan campaign zakat, infaq, dan sedekah terbaru yang
                sedang berjalan</p>
        </div>

        <!-- Slider Container -->
        <div class="relative">
            <!-- Navigation Buttons -->
            <button id="campaigns-prev"
                class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-green-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                aria-label="Previous campaign">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button id="campaigns-next"
                class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-green-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                aria-label="Next campaign">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Slider Wrapper -->
            <div class="group">
                <div id="campaigns-slider"
                    class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scrollbar-hide scroll-smooth">
                    @foreach (\App\Models\Campaign::published()->latest()->take(10)->get() as $campaign)
                        <div class="flex-shrink-0 w-72 snap-start">
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 card-hover h-full flex flex-col">
                                @if ($campaign->photo)
                                    <div class="h-40 overflow-hidden">
                                        @php
                                            $rawImage = trim($campaign->photo ?? '');
                                            // Cek apakah image adalah URL penuh (CDN)
                                            $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                            // Tentukan URL akhir
                                            $imageUrl = $isFullUrl ? $rawImage : asset('storage/' . $campaign->photo);
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="Campaign: {{ $campaign->title }}"
                                            class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                @else
                                    <div class="h-40 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4 flex flex-col flex-grow">
                                    <!-- Category Badge -->
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        <span
                                            class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-800">
                                            {{ $campaign->program_category ?? 'Zakat' }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-800 mb-1 line-clamp-2 flex-grow-0">
                                        {{ $campaign->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex-grow">
                                        {{ Str::limit(strip_tags($campaign->description), 80) }}</p>

                                    <!-- Donor count and time remaining -->
                                    <div class="text-xs text-gray-500 mb-3">
                                        <span>{{ $campaign->donors_count ?? 0 }} donatur</span>
                                        <span class="mx-1">·</span>
                                        @if ($campaign->end_date)
                                            @if ($campaign->remaining_days > 0)
                                                <span>{{ $campaign->remaining_days }} hari lagi</span>
                                            @elseif($campaign->remaining_days == 0)
                                                <span>Hari terakhir</span>
                                            @elseif($campaign->remaining_days == -1)
                                                @if ($campaign->status == 'completed')
                                                    <span>Selesai</span>
                                                @else
                                                    <span>Waktu Habis</span>
                                                @endif
                                            @endif
                                        @else
                                            <span>Tidak ada batas waktu</span>
                                        @endif
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-3 flex-grow-0 relative">
                                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Terkumpul</span>
                                            <span>{{ 'Rp ' . number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                        </div>
                                        @php
                                            $progress = $campaign->progress_percentage;
                                            if ($progress > 100) {
                                                $progress = 100;
                                            }

                                            // Determine progress bar color based on percentage
                                            $progressBarColor = 'bg-green-600';
                                            if ($progress < 30) {
                                                $progressBarColor = 'bg-blue-500';
                                            } elseif ($progress < 70) {
                                                $progressBarColor = 'bg-yellow-500';
                                            }
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full progress-bar {{ $progressBarColor }}"
                                                style="width: <?php echo number_format($progress, 0); ?>%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-600 mt-1">
                                            <span>Target:
                                                {{ 'Rp ' . number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                            <span>{{ number_format($campaign->progress_percentage, 1) }}%</span>
                                        </div>

                                    </div>

                                    <a href="{{ route('campaigns.show', [$campaign->program_category, $campaign]) }}"
                                        class="inline-block w-full text-center bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1.5 px-3 rounded-lg transition-colors duration-300 flex-grow-0">
                                        Lihat Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Slider Indicators -->
                <div class="flex justify-center mt-4 space-x-2 campaigns-indicators">
                    <!-- Indicators will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('campaigns.index', 'all') }}"
                class="inline-flex items-center gap-2 bg-white border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-green-500 font-bold py-2.5 px-6 rounded-full transition-all duration-300 transform hover:scale-105 text-sm group">
                Lihat Semua Campaign
                <svg class="w-4 h-4 animate-bounce-x" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>


    <!-- Berita Terbaru Section -->
    <div class="py-16 bg-gradient-to-br from-white via-green-50 to-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Berita Terbaru</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Ikuti perkembangan terbaru tentang kegiatan zakat
                    dan program sosial</p>
            </div>

            <!-- Slider Container -->
            <div class="relative">
                <!-- Navigation Buttons -->
                <button id="news-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-green-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    aria-label="Previous news">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <button id="news-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-green-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    aria-label="Next news">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </button>

                <!-- Slider Wrapper -->
                <div class="group">
                    <div id="news-slider"
                        class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scrollbar-hide scroll-smooth">
                        @foreach (\App\Models\News::published()->latest()->take(10)->get() as $news)
                            <div class="flex-shrink-0 w-72 snap-start">
                                <div
                                    class="bg-gray-50 rounded-lg shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1 card-hover">
                                    @if ($news->image)
                                        <div class="h-40 overflow-hidden">
                                            @php
                                                $rawImage = trim($news->image ?? '');
                                                // Cek apakah image adalah URL penuh (CDN)
                                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                                // Tentukan URL akhir
                                                $imageUrl = $isFullUrl ? $rawImage : asset('storage/' . $news->image);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="News: {{ $news->title }}"
                                                class="w-full h-full object-cover" loading="lazy">
                                        </div>
                                    @else
                                        <div class="h-40 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <div class="flex items-center text-xs text-gray-500 mb-1.5">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $news->created_at->format('d M Y') }}</span>
                                        </div>
                                        <!-- Category Badge -->
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            <span
                                                class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                                Berita
                                            </span>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-800 mb-2 line-clamp-2">
                                            {{ $news->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $news->excerpt }}</p>
                                        <a href="{{ route('news.show', $news->slug) }}"
                                            class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center">
                                            Baca Selengkapnya
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Slider Indicators -->
                    <div class="flex justify-center mt-4 space-x-2 news-indicators">
                        <!-- Indicators will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('news.all') }}"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-full transition-all duration-300 transform hover:scale-105 text-sm group">
                    Lihat Semua Berita
                    <svg class="w-4 h-4 animate-bounce-x" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Artikel Terbaru Section -->
    <div class="py-16 bg-gradient-to-br from-gray-50 via-white to-emerald-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Artikel Terbaru</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Temukan artikel edukasi tentang zakat, infaq, dan
                    sedekah</p>
            </div>

            <!-- Slider Container -->
            <div class="relative">
                <!-- Navigation Buttons -->
                <button id="artikel-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-green-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    aria-label="Previous article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <button id="artikel-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-green-600 hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    aria-label="Next article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </button>

                <!-- Slider Wrapper -->
                <div class="group">
                    <div id="artikel-slider"
                        class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scrollbar-hide scroll-smooth">
                        @foreach (\App\Models\Artikel::published()->latest()->take(10)->get() as $artikel)
                            <div class="flex-shrink-0 w-72 snap-start">
                                <div
                                    class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 card-hover">
                                    @if ($artikel->image)
                                        <div class="h-40 overflow-hidden">
                                            @php
                                                $rawImage = trim($artikel->image ?? '');
                                                // Cek apakah image adalah URL penuh (CDN)
                                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                                // Tentukan URL akhir
                                                $imageUrl = $isFullUrl
                                                    ? $rawImage
                                                    : asset('storage/' . $artikel->image);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="Article: {{ $artikel->title }}"
                                                class="w-full h-full object-cover" loading="lazy">
                                        </div>
                                    @else
                                        <div class="h-40 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <div class="flex items-center text-xs text-gray-500 mb-1.5">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $artikel->created_at->format('d M Y') }}</span>
                                        </div>
                                        <!-- Category Badge -->
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            <span
                                                class="text-xs font-semibold px-2 py-1 rounded-full bg-purple-100 text-purple-800">
                                                Artikel
                                            </span>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-800 mb-2 line-clamp-2">
                                            {{ $artikel->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $artikel->excerpt }}</p>
                                        <a href="{{ route('artikel.show', $artikel->slug) }}"
                                            class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center">
                                            Baca Selengkapnya
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Slider Indicators -->
                    <div class="flex justify-center mt-4 space-x-2 artikel-indicators">
                        <!-- Indicators will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('artikel.all') }}"
                    class="inline-flex items-center gap-2 bg-white border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-green-500 font-bold py-2.5 px-6 rounded-full transition-all duration-300 transform hover:scale-105 text-sm group">
                    Lihat Semua Artikel
                    <svg class="w-4 h-4 animate-bounce-x" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- CHATBOT FLOATING BUTTON + POPUP -->
    <div id="chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3">

        <!-- Popup Chat (Awalnya disembunyikan) -->
        <div id="chatbot-popup"
            class="hidden flex-col bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-80 max-h-[500px] border border-emerald-200 overflow-hidden">
            <div class="bg-emerald-600 text-white p-3 font-bold text-center">
                Asisten Zakat
            </div>
            <div id="chat-messages"
                class="flex-1 p-3 overflow-y-auto flex flex-col text-sm text-gray-800 chat-messages-container">
                <div class="text-center text-gray-400 text-xs animate-fadeInUp">Mulai percakapan...</div>
            </div>
            <div class="p-3 border-t border-gray-200 flex">
                <input id="chat-input" type="text" placeholder="Ketik pesan..."
                    class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                <button id="send-btn"
                    class="ml-2 bg-emerald-600 text-white px-3 py-2 rounded-lg hover:bg-emerald-700 transition">Kirim</button>
            </div>
        </div>

        <!-- Tombol Chat -->
        <button id="chatbot-button"
            class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-full p-4 shadow-lg transition">
            💬
        </button>
    </div>
    
    <!-- Intercept Puter API errors BEFORE loading Puter.js -->
    <script>
        (function() {
            // Suppress 401 errors from Puter whoami endpoint (not critical)
            // Override console methods to filter out Puter 401 errors
            const originalConsoleError = console.error;
            const originalConsoleWarn = console.warn;
            
            console.error = function(...args) {
                const message = args.join(' ');
                if ((message.includes('api.puter.com') || message.includes('puter.com')) && 
                    (message.includes('401') || message.includes('Unauthorized') || message.includes('whoami'))) {
                    // Silently ignore Puter whoami 401 errors
                    return;
                }
                originalConsoleError.apply(console, args);
            };
            
            console.warn = function(...args) {
                const message = args.join(' ');
                if ((message.includes('api.puter.com') || message.includes('puter.com')) && 
                    (message.includes('401') || message.includes('Unauthorized') || message.includes('whoami'))) {
                    // Silently ignore Puter whoami 401 warnings
                    return;
                }
                originalConsoleWarn.apply(console, args);
            };

            // Override XMLHttpRequest to intercept Puter API calls
            const OriginalXHR = window.XMLHttpRequest;
            window.XMLHttpRequest = function() {
                const xhr = new OriginalXHR();
                const originalOpen = xhr.open;
                
                xhr.open = function(method, url, ...rest) {
                    if (typeof url === 'string' && url.includes('api.puter.com/whoami')) {
                        // Intercept whoami calls - mark this request
                        xhr._isPuterWhoami = true;
                        xhr._originalUrl = url;
                    }
                    return originalOpen.apply(this, [method, url, ...rest]);
                };
                
                const originalSend = xhr.send;
                xhr.send = function(...args) {
                    if (xhr._isPuterWhoami) {
                        // Suppress all error handlers for whoami endpoint
                        const originalOnError = xhr.onerror;
                        const originalOnLoad = xhr.onload;
                        
                        xhr.onerror = function(e) {
                            // Silently suppress errors
                            e.preventDefault && e.preventDefault();
                            e.stopPropagation && e.stopPropagation();
                            return false;
                        };
                        
                        xhr.onload = function() {
                            if (xhr.status === 401 || xhr.status === 0) {
                                // Silently handle 401 or failed requests
                                return;
                            }
                            if (originalOnLoad) {
                                originalOnLoad.call(this);
                            }
                        };
                        
                        // Override status to prevent 401 from being visible
                        Object.defineProperty(xhr, 'status', {
                            get: function() {
                                if (this._isPuterWhoami && this._actualStatus === 401) {
                                    return 200; // Return fake success status
                                }
                                return this._actualStatus || 200;
                            },
                            set: function(value) {
                                this._actualStatus = value;
                            }
                        });
                    }
                    return originalSend.apply(this, args);
                };
                
                return xhr;
            };

            // Also suppress fetch errors for Puter whoami (handled by fetch API)
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                const url = args[0];
                // Check if this is a Puter whoami request
                if (typeof url === 'string' && url.includes('api.puter.com/whoami')) {
                    // Intercept the request completely - return a successful response immediately
                    // This prevents the actual network request and the 401 error
                    return Promise.resolve(new Response(JSON.stringify({}), {
                        status: 200,
                        statusText: 'OK',
                        ok: true,
                        headers: new Headers({
                            'Content-Type': 'application/json'
                        })
                    }));
                }
                // For all other requests, use the original fetch
                return originalFetch.apply(this, args);
            };

            // Suppress network errors from Puter whoami endpoint
            window.addEventListener('error', function(e) {
                if (e.message && (e.message.includes('401') || e.message.includes('Unauthorized')) && 
                    (e.filename && (e.filename.includes('api.puter.com') || e.filename.includes('puter.com')) || 
                     e.target && e.target.src && e.target.src.includes('api.puter.com'))) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }, true);
        })();
    </script>
    
    <!-- Puter.js API untuk Claude AI -->
    <script src="https://js.puter.com/v2/"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- Custom CSS for Animations -->
    <style>
        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInDown {
            animation: fadeInDown 0.3s ease-out forwards;
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.3s ease-out forwards;
        }

        /* Gradient text effect */
        .bg-clip-text {
            background-clip: text;
            -webkit-background-clip: text;
        }

        /* Glass morphism effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Floating chat icon */
        .fixed.bottom-6.right-6 {
            right: 1.5rem !important;
            bottom: 1.5rem !important;
        }

        #chatbot-popup {
            width: 320px !important;
            max-width: 90vw;
        }

        .chat-messages-container {
            max-height: 350px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        /* Custom scrollbar for chat */
        .chat-messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 10px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0;
        }

        /* Chat message bubbles */
        .message-user {
            align-self: flex-end;
            background-color: #10B981;
            color: white;
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 18px;
            border-top-left-radius: 18px;
            border-top-right-radius: 4px;
        }

        .message-bot {
            align-self: flex-start;
            background-color: #F3F4F6;
            color: #1F2937;
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 18px;
            border-top-right-radius: 18px;
            border-top-left-radius: 4px;
        }

        .message-bubble {
            max-width: 85%;
            padding: 10px 14px;
            margin-bottom: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.3s ease-out forwards;
        }

        /* Add bounce animation for arrows */
        @keyframes bounce-x {

            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(4px);
            }
        }

        .animate-bounce-x {
            animation: bounce-x 1s infinite;
        }

        /* Enhanced card hover effect */
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Progress bar animation */
        .progress-bar {
            transition: width 0.6s ease-in-out;
        }

        /* Scroll snap alignment for better mobile experience */
        .snap-start {
            scroll-snap-align: center;
        }

        /* Hide scrollbar for sliders but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 767px) {
            .group:hover .opacity-0 {
                opacity: 0 !important;
            }
        }
    </style>

    <!-- JavaScript for Chatbot Functionality -->
    <script>
        // Chatbot functionality
        document.addEventListener("DOMContentLoaded", () => {
            // Wait for Puter.js to load
            function waitForPuter(callback, maxAttempts = 50) {
                let attempts = 0;
                const checkPuter = () => {
                    attempts++;
                    if (typeof puter !== 'undefined' && puter.ai) {
                        console.log('Puter.js loaded successfully');
                        callback();
                    } else if (attempts < maxAttempts) {
                        setTimeout(checkPuter, 100);
                    } else {
                        console.warn('Puter.js not loaded after maximum attempts');
                        // Initialize anyway, fallback will handle it
                        callback();
                    }
                };
                checkPuter();
            }


            // Check if puter is available, if not, wait for it
            function initializeChatbot() {
                const sendBtn = document.getElementById("send-btn");
                const input = document.getElementById("chat-input");
                const messages = document.getElementById("chat-messages");
                const chatbotBtn = document.getElementById('chatbot-button');
                const chatbotPopup = document.getElementById('chatbot-popup');

                // If any required element is missing, exit
                if (!sendBtn || !input || !messages || !chatbotBtn || !chatbotPopup) {
                    console.error('Chatbot elements not found');
                    return;
                }

                let isFirstOpen = true;

                // Improved scroll to bottom function with delay for better rendering
                function scrollToBottom() {
                    setTimeout(() => {
                        messages.scrollTop = messages.scrollHeight;
                    }, 100);
                }

                chatbotBtn.addEventListener('click', () => {
                    // Toggle tampilan chatbot (muncul/sembunyi)
                    const isHidden = chatbotPopup.classList.toggle('hidden');

                    if (!isHidden) {
                        // Chatbot baru dibuka
                        if (isFirstOpen) {
                            messages.innerHTML = '';
                            appendMessage(
                                `
                <p>Selamat datang di <strong>Asisten Zakat Virtual</strong>! 👋</p>
                <p>Saya siap membantu Anda dengan pertanyaan seputar <em>zakat</em>, cara pembayaran, program yang tersedia, dan informasi lainnya.</p>
                <p>Apa yang ingin Anda tanyakan hari ini?</p>
                `,
                                "bot"
                            );
                            isFirstOpen = false;
                        }

                        // Fokuskan ke input setelah chatbot muncul
                        setTimeout(() => input.focus(), 500);

                        // ❌ Tidak scroll otomatis ke bawah
                        // Jadi pesan selamat datang tetap kelihatan penuh
                    } else {
                        // Chatbot ditutup → bisa tambahkan efek jika mau
                        input.blur(); // opsional, supaya keyboard tertutup di mobile
                    }
                });


                // Function to append messages in HTML format
                function appendMessage(htmlContent, sender) {
                    const div = document.createElement("div");
                    div.classList.add("message-bubble", "animate-fadeInUp");

                    if (sender === "user") {
                        div.classList.add("message-user");
                    } else {
                        div.classList.add("message-bot");
                    }

                    // Set the HTML content properly
                    div.innerHTML = htmlContent;
                    messages.appendChild(div);

                    // Auto-scroll to bottom with improved behavior
                    scrollToBottom();
                }

                // Format response to HTML
                function formatResponseToHtml(text) {
                    // Validasi input
                    if (!text || typeof text !== 'string') {
                        return '<p>⚠️ Respon tidak valid dari AI. Silakan coba lagi.</p>';
                    }

                    try {
                        // Gunakan Marked.js untuk render Markdown jadi HTML
                        let html = marked.parse(text);

                        // Tambahkan styling agar tetap terlihat rapi
                        html = html.replace(/<h1>/g, '<h1 class="text-lg font-bold mb-2">')
                            .replace(/<h2>/g, '<h2 class="text-base font-semibold mb-1">')
                            .replace(/<ul>/g, '<ul class="list-disc pl-5 space-y-1">')
                            .replace(/<p>/g, '<p class="mb-2 leading-relaxed">');
                        return html;
                    } catch (error) {
                        console.error('Error parsing markdown:', error);
                        // Fallback: return as plain text with escaping
                        return '<p class="mb-2 leading-relaxed">' + text.replace(/</g, '&lt;').replace(/>/g,
                            '&gt;') + '</p>';
                    }
                }



                // Send message handler
                async function sendMessage() {
                    const userText = input.value.trim();
                    if (!userText) return;

                    // Display user message
                    appendMessage(`<p>${userText}</p>`, "user");
                    input.value = "";

                    // Show typing indicator
                    const loadingMsg = document.createElement("div");
                    loadingMsg.id = "typing-indicator";
                    loadingMsg.classList.add("message-bubble", "message-bot");
                    loadingMsg.innerHTML = '<p class="text-gray-400 italic text-xs">Mengetik...</p>';
                    messages.appendChild(loadingMsg);

                    // Scroll to show typing indicator
                    scrollToBottom();

                    try {
                        // Check if we should use custom responses for common zakat questions
                        let replyHtml = '';

                        if (userText.toLowerCase().includes('zakat') && userText.toLowerCase().includes(
                                'apa')) {
                            replyHtml = `
                            <p class="mb-2">Zakat adalah rukun Islam kelima yang wajib dilaksanakan oleh setiap Muslim yang memenuhi syarat.</p>
                            <p class="mb-2">Zakat berasal dari bahasa Arab yang berarti "bersih" atau "tumbuh". Zakat merupakan bentuk ibadah sekaligus sistem ekonomi dalam Islam yang bertujuan untuk membersihkan harta dan menyejahterakan umat.</p>
                            <p class="mb-2"><strong>Syarat wajib zakat:</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Muslim</li>
                                <li>Baligh (dewasa)</li>
                                <li>Merdeka (bukan budak)</li>
                                <li>Kaya (melebihi nisab)</li>
                                <li>Memiliki harta selama satu tahun (haul)</li>
                            </ul>
                        `;
                        } else if (userText.toLowerCase().includes('bayar') || userText.toLowerCase().includes(
                                'cara')) {
                            replyHtml = `
                            <p class="mb-2">Untuk membayar zakat melalui platform kami, Anda dapat mengikuti langkah-langkah berikut:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Klik tombol "BAYAR ZAKAT SEKARANG" di halaman utama</li>
                                <li>Pilih jenis zakat yang ingin Anda bayarkan</li>
                                <li>Isi formulir dengan data diri dan nominal zakat</li>
                                <li>Pilih metode pembayaran yang tersedia</li>
                                <li>Konfirmasi pembayaran dan simpan bukti transfer</li>
                            </ul>
                            <p class="mt-2">Pembayaran zakat bisa dilakukan kapan saja sepanjang tahun. Namun, banyak umat Muslim yang memilih membayarnya saat bulan Ramadhan karena keutamaannya.</p>
                        `;
                        } else if (userText.toLowerCase().includes('jenis') && (userText.toLowerCase().includes(
                                'zakat') || userText.toLowerCase().includes('macam'))) {
                            replyHtml = `
                            <p class="mb-2">Ada beberapa jenis zakat yang wajib dan sunnah dibayarkan:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Zakat Mal</strong> - Zakat atas harta yang dimiliki</li>
                                <li><strong>Zakat Fitrah</strong> - Zakat yang wajib dibayar saat Ramadhan</li>
                                <li><strong>Zakat Profesi</strong> - Zakat atas penghasilan/profesi</li>
                                <li><strong>Zakat Emas/Perak</strong> - Zakat atas kepemilikan logam mulia</li>
                                <li><strong>Zakat Perniagaan</strong> - Zakat atas aset perdagangan</li>
                                <li><strong>Zakat Pertanian</strong> - Zakat atas hasil pertanian</li>
                                <li><strong>Zakat Peternakan</strong> - Zakat atas hewan ternak</li>
                            </ul>
                            <p class="mt-2">Untuk memudahkan perhitungan, Anda dapat menggunakan Kalkulator Zakat yang tersedia di platform kami.</p>
                        `;
                        } else if (userText.toLowerCase().includes('manfaat') || userText.toLowerCase()
                            .includes('guna')) {
                            replyHtml = `
                            <p class="mb-2">Zakat memiliki manfaat besar bagi kedua belah pihak:</p>
                            <p class="mb-2"><strong>Bagi Muzakki (Pembayar Zakat):</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Membersihkan harta dari kotoran dan sifat kikir</li>
                                <li>Mendapatkan pahala dan ridha Allah SWT</li>
                                <li>Melatih sikap peduli terhadap sesama</li>
                                <li>Mendapat perlindungan dari bencana dan musibah</li>
                            </ul>
                            <p class="mt-2"><strong>Bagi Mustahik (Penerima Zakat):</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Memenuhi kebutuhan dasar hidup</li>
                                <li>Meningkatkan taraf hidup dan kesejahteraan</li>
                                <li>Mendapat kesempatan untuk berkembang secara ekonomi</li>
                                <li>Merasakan kepedulian dan kasih sayang dari sesama Muslim</li>
                            </ul>
                        `;
                        } else {
                            // For other questions, use the AI API if puter is available
                            if (typeof puter !== 'undefined' && puter.ai) {
                                try {
                                    // Tambahkan konteks sistem untuk fokus pada zakat
                                    const systemContext =
                                        "Kamu adalah asisten digital ahli dalam sistem pengelolaan zakat, infak, dan sedekah (ZIS). " +
                                        "Platform ini adalah SIPZIS (Sistem Informasi Pengelolaan Zakat). " +
                                        "Jawablah pertanyaan pengguna dengan sopan, informatif, dan sesuai syariat Islam. " +
                                        "Jika pertanyaan tidak terkait zakat/infak/sedekah, arahkan kembali ke topik zakat dengan sopan.\n\n" +
                                        "Pertanyaan pengguna: " + userText;

                                    const response = await puter.ai.chat(systemContext, {
                                        model: "claude-opus-4-1"
                                    });

                                    // Validasi dan ekstraksi response dengan aman
                                    let responseText = '';

                                    if (response && response.message) {
                                        if (response.message.content && Array.isArray(response.message
                                                .content) && response.message.content.length > 0) {
                                            // Format standar: response.message.content[0].text
                                            if (response.message.content[0].text) {
                                                responseText = response.message.content[0].text;
                                            } else if (typeof response.message.content[0] === 'string') {
                                                // Jika content[0] langsung string
                                                responseText = response.message.content[0];
                                            }
                                        } else if (response.message.content && typeof response.message
                                            .content === 'string') {
                                            // Jika content langsung string
                                            responseText = response.message.content;
                                        } else if (typeof response.message === 'string') {
                                            // Jika message langsung string
                                            responseText = response.message;
                                        }
                                    } else if (typeof response === 'string') {
                                        // Jika response langsung string
                                        responseText = response;
                                    }

                                    if (responseText) {
                                        appendMessage(formatResponseToHtml(responseText), "bot");
                                    } else {
                                        console.error('Unexpected response format from Puter API:', response);
                                        appendMessage(
                                            '<p>⚠️ Format respon dari AI tidak dikenali. Silakan coba lagi.</p>',
                                            "bot");
                                    }

                                } catch (apiError) {
                                    console.error('Puter API Error:', apiError);
                                    console.error('Error details:', {
                                        message: apiError.message,
                                        stack: apiError.stack,
                                        name: apiError.name
                                    });
                                    replyHtml =
                                        '<p>⚠️ Terjadi kesalahan saat menghubungi AI. Silakan coba lagi nanti.</p>';
                                    appendMessage(replyHtml, "bot");
                                }

                            } else {
                                // Fallback response if puter is not available
                                replyHtml =
                                    '<p>Maaf, saat ini saya tidak dapat mengakses AI. Harap tunggu sebentar atau refresh halaman.</p>';
                                appendMessage(replyHtml, "bot");

                            }
                        }

                        // Remove typing indicator safely
                        const typingIndicator = document.getElementById("typing-indicator");
                        if (typingIndicator) {
                            typingIndicator.remove();
                        }

                        if (replyHtml.trim() !== '') {
                            appendMessage(replyHtml, "bot");
                        }
                        // Focus input after bot response
                        setTimeout(() => input.focus(), 300);
                    } catch (err) {
                        // Remove typing indicator safely
                        const typingIndicator = document.getElementById("typing-indicator");
                        if (typingIndicator) {
                            typingIndicator.remove();
                        }

                        // Display error message
                        appendMessage(
                            '<p>⚠️ Terjadi kesalahan saat memproses pesan Anda. Silakan coba lagi nanti.</p>',
                            "bot");
                        console.error(err);

                        // Focus input after error
                        setTimeout(() => input.focus(), 300);
                    }
                }

                sendBtn.addEventListener("click", sendMessage);
                input.addEventListener("keydown", e => {
                    if (e.key === "Enter") sendMessage();
                });

                // Focus input when chat container is clicked
                document.querySelector('.p-3.border-t').addEventListener('click', () => {
                    input.focus();
                });

                // Ensure input remains focused when user interacts with chat
                messages.addEventListener('click', () => {
                    input.focus();
                });
            }

            // Wait for Puter.js to load, then initialize chatbot
            waitForPuter(() => {
                initializeChatbot();
            });
        });

        // Slider functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all sliders
            initSlider('campaigns');
            initSlider('news');
            initSlider('artikel');

            // Set up auto-scroll for sliders
            setupAutoScroll('campaigns');
            setupAutoScroll('news');
            setupAutoScroll('artikel');
        });

        function initSlider(sliderName) {
            const slider = document.getElementById(`${sliderName}-slider`);
            const prevBtn = document.getElementById(`${sliderName}-prev`);
            const nextBtn = document.getElementById(`${sliderName}-next`);
            const indicatorsContainer = document.querySelector(`.${sliderName}-indicators`);

            if (!slider || !prevBtn || !nextBtn) return;

            // Create indicators
            const items = slider.querySelectorAll('.flex-shrink-0');
            const itemCount = items.length;

            if (indicatorsContainer && itemCount > 0) {
                for (let i = 0; i < itemCount; i++) {
                    const indicator = document.createElement('span');
                    indicator.classList.add('w-2', 'h-2', 'rounded-full', 'cursor-pointer', 'transition-all',
                        'duration-300');
                    if (i === 0) {
                        indicator.classList.add('bg-green-600', 'w-4');
                    } else {
                        indicator.classList.add('bg-gray-300');
                    }
                    indicator.dataset.index = i;
                    indicatorsContainer.appendChild(indicator);

                    // Add click event to indicators
                    indicator.addEventListener('click', () => {
                        scrollToSlide(slider, i);
                        updateIndicators(indicatorsContainer, i);
                    });
                }
            }

            // Navigation button events
            prevBtn.addEventListener('click', () => {
                scrollSlider(slider, -1);
                updateIndicatorsOnScroll(slider, indicatorsContainer);
            });

            nextBtn.addEventListener('click', () => {
                scrollSlider(slider, 1);
                updateIndicatorsOnScroll(slider, indicatorsContainer);
            });

            // Update indicators when scrolling
            slider.addEventListener('scroll', () => {
                updateIndicatorsOnScroll(slider, indicatorsContainer);
            });

            // Hide navigation buttons on mobile
            function toggleNavigationButtons() {
                if (window.innerWidth < 768) { // md breakpoint
                    prevBtn.classList.add('hidden');
                    nextBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                    nextBtn.classList.remove('hidden');
                }
            }

            // Initial check
            toggleNavigationButtons();

            // Check on resize
            window.addEventListener('resize', toggleNavigationButtons);
        }

        function scrollSlider(slider, direction) {
            const scrollAmount = slider.clientWidth * 0.8; // Scroll by 80% of visible area
            slider.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        function scrollToSlide(slider, index) {
            const slideWidth = slider.querySelector('.flex-shrink-0').offsetWidth + 16; // width + gap
            slider.scrollTo({
                left: index * slideWidth,
                behavior: 'smooth'
            });
        }

        function updateIndicators(indicatorsContainer, activeIndex) {
            const indicators = indicatorsContainer.querySelectorAll('span');
            indicators.forEach((indicator, index) => {
                if (index === activeIndex) {
                    indicator.classList.remove('bg-gray-300');
                    indicator.classList.add('bg-green-600', 'w-4');
                } else {
                    indicator.classList.remove('bg-green-600', 'w-4');
                    indicator.classList.add('bg-gray-300', 'w-2');
                }
            });
        }

        function updateIndicatorsOnScroll(slider, indicatorsContainer) {
            if (!indicatorsContainer) return;

            const scrollLeft = slider.scrollLeft;
            const slideWidth = slider.querySelector('.flex-shrink-0').offsetWidth + 16; // width + gap
            const activeIndex = Math.round(scrollLeft / slideWidth);

            updateIndicators(indicatorsContainer, activeIndex);
        }

        function setupAutoScroll(sliderName) {
            const slider = document.getElementById(`${sliderName}-slider`);
            const nextBtn = document.getElementById(`${sliderName}-next`);

            if (!slider || !nextBtn) return;

            let autoScrollInterval;

            function startAutoScroll() {
                autoScrollInterval = setInterval(() => {
                    // Check if we're at the end of the slider
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        // Scroll to beginning
                        slider.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        // Scroll to next slide
                        scrollSlider(slider, 1);
                    }
                }, 5000); // 5 seconds
            }

            function stopAutoScroll() {
                if (autoScrollInterval) {
                    clearInterval(autoScrollInterval);
                }
            }

            // Start auto scroll
            startAutoScroll();

            // Stop auto scroll on hover
            slider.addEventListener('mouseenter', stopAutoScroll);
            slider.addEventListener('mouseleave', startAutoScroll);

            // Stop auto scroll when navigation buttons are used
            const prevBtn = document.getElementById(`${sliderName}-prev`);
            if (prevBtn) {
                prevBtn.addEventListener('click', stopAutoScroll);
            }
            nextBtn.addEventListener('click', stopAutoScroll);
        }
    </script>
