<div class="relative bg-gradient-to-br from-green-900 via-green-800 to-emerald-700 min-h-screen overflow-hidden"
    id="berita">
    <!-- Mosque Background Image Overlay -->
    <div class="absolute inset-0 opacity-90"
        style="background-image: url('{{ asset('img/masjid.webp') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; will-change: transform;">
    </div>
    <!-- Green Gradient Overlay for blending -->
    <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-green-800/70 to-emerald-700/80"></div>

    <!-- Additional Dark Overlay for text readability -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20"></div>

    <div class="relative z-10 py-20">
        <div class="container mx-auto px-4 py-16">

            <!-- Enhanced Main News -->
            <div class="relative bg-white/95 rounded-3xl shadow-lg p-10 mb-12 border border-white/20">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-green-50/50 via-emerald-50/50 to-teal-50/50 rounded-3xl">
                </div>
                <div class="relative z-10">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl md:text-5xl font-black text-green-800 mb-4">Berita Zakat, Infaq & Sedekah
                        </h2>
                        <p class="text-lg text-green-700 max-w-2xl mx-auto">Update terbaru seputar program zakat, infaq,
                            dan sedekah yang telah tersalurkan untuk membantu sesama</p>
                        <div class="flex justify-center mt-4">
                            <div
                                class="w-24 h-1 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-full">
                            </div>
                        </div>
                    </div>

                    <!-- News Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($news as $item)
                            <div
                                class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                                <div class="relative">
                                    @php
                                        $rawImage = trim($item->image ?? '');
                                        // Cek apakah image adalah URL penuh (CDN)
                                        $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                        // Tentukan URL akhir
                                        $imageUrl = $isFullUrl
                                            ? $rawImage
                                            : (!empty($rawImage)
                                                ? Storage::url($rawImage)
                                                : 'https://via.placeholder.com/400x250');
                                    @endphp

                                    <img src="{{ $imageUrl }}" alt="{{ $item->title }}"
                                        class="w-full h-48 object-cover">

                                    <div class="absolute top-4 left-4">
                                        @php
                                            $categoryColors = [
                                                'zakat' => 'from-green-500 to-green-700',
                                                'infaq' => 'from-blue-500 to-blue-700',
                                                'sedekah' => 'from-purple-500 to-purple-700',
                                            ];
                                            $textColors = [
                                                'zakat' => 'text-green-600',
                                                'infaq' => 'text-blue-600',
                                                'sedekah' => 'text-purple-600',
                                            ];
                                            $bgColors = [
                                                'zakat' => 'bg-green-50',
                                                'infaq' => 'bg-blue-50',
                                                'sedekah' => 'bg-purple-50',
                                            ];
                                            $hoverColors = [
                                                'zakat' => 'hover:text-green-800',
                                                'infaq' => 'hover:text-blue-800',
                                                'sedekah' => 'hover:text-purple-800',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-block bg-gradient-to-r {{ $categoryColors[$item->category] ?? 'from-gray-500 to-gray-700' }} text-white text-xs px-4 py-2 rounded-full uppercase font-bold tracking-wide shadow-lg">
                                            {{ $item->category_label }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $item->title }}
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $item->excerpt }}</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-2">
                                                <i class="fas fa-user text-green-600 text-sm"></i>
                                            </div>
                                            <span
                                                class="text-sm text-gray-600">{{ $item->author->name ?? 'Admin' }}</span>
                                        </div>
                                        <span
                                            class="text-xs text-gray-500">{{ $item->created_at->format('d M Y') }}</span>
                                    </div>

                                    <!-- Read More Link -->
                                    <div class="mt-4">
                                        <a href="{{ route('news.show', $item->slug) }}"
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
                        @empty
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-newspaper text-5xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-500">Tidak ada berita tersedia</h3>
                                <p class="text-gray-400 mt-2">Saat ini belum ada berita yang dapat ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if (isset($news) && $news->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $news->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Styles moved to tailwind.config.js for better performance -->
