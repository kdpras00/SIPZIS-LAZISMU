<div class="relative bg-gradient-to-br from-green-900 via-green-800 to-emerald-700 min-h-screen" id="program">
    <!-- Hidden element to pass activeTab from Laravel to JavaScript -->
    @if(isset($activeTab))
    <div id="laravel-active-tab" data-tab="{{ $activeTab }}" style="display: none;"></div>
    @endif
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
            <!-- Enhanced Page Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                    Kategori Donasi
                </h1>
                <p class="text-xl text-white max-w-3xl mx-auto">
                    Pilih kategori donasi yang sesuai dengan nilai dan prioritas Anda
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex flex-wrap justify-center mb-12">
                <div class="inline-flex p-1 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/30">
                    <button class="tab-button active px-6 py-3 rounded-xl text-lg font-bold" data-tab="zakat">
                        <span class="text-amber-600">Zakat</span>
                    </button>
                    <button class="tab-button px-6 py-3 rounded-xl text-lg font-bold" data-tab="infaq">
                        <span class="text-green-600">Infaq</span>
                    </button>
                    <button class="tab-button px-6 py-3 rounded-xl text-lg font-bold" data-tab="shadaqah">
                        <span class="text-blue-600">Shadaqah</span>
                    </button>
                    <button class="tab-button px-6 py-3 rounded-xl text-lg font-bold" data-tab="pilar">
                        <span class="text-purple-600">Program Pilar</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Zakat Tab -->
                <div class="tab-panel active" id="zakat">
                    <div class="relative bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 mb-12 border border-white/20">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($zakatPrograms as $program)
                                <!-- {{ $program->name }} Category -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden block">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        // Use our new image_url accessor to handle both CDN and local images
                                        $imageUrl = $program->image_url ?? 'https://via.placeholder.com/400x300/cccccc/ffffff?text=' . urlencode($program->name);
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-4 left-4">
                                            <span class="inline-block bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full">Program Zakat</span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-xl font-black text-amber-800 mb-2">{{ $program->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">{{ $program->description }}</p>

                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Donasi Terkumpul</span>
                                                <span class="text-amber-600 font-bold">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-amber-500 h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}"></div>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 text-xs">Target: {{ $program->formatted_total_target }}</span>
                                                <span class="text-gray-700 font-semibold text-sm">{{ number_format($program->progress_percentage, 1) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Infaq Tab -->
                <div class="tab-panel" id="infaq">
                    <div class="relative bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 mb-12 border border-white/20">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($infaqPrograms as $program)
                                <!-- {{ $program->name }} -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden block">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        // Use our new image_url accessor to handle both CDN and local images
                                        $imageUrl = $program->image_url ?? 'https://via.placeholder.com/400x300/cccccc/ffffff?text=' . urlencode($program->name);
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-4 left-4">
                                            <span class="inline-block bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">Infaq</span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-xl font-black text-green-800 mb-2">{{ $program->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">{{ $program->description }}</p>

                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Donasi Terkumpul</span>
                                                <span class="text-green-600 font-bold">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-green-500 h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}"></div>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 text-xs">Target: {{ $program->formatted_total_target }}</span>
                                                <span class="text-gray-700 font-semibold text-sm">{{ number_format($program->progress_percentage, 1) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shadaqah Tab -->
                <div class="tab-panel" id="shadaqah">
                    <div class="relative bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 mb-12 border border-white/20">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($shadaqahPrograms as $program)
                                <!-- {{ $program->name }} -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden block">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        // Use our new image_url accessor to handle both CDN and local images
                                        $imageUrl = $program->image_url ?? 'https://via.placeholder.com/400x300/cccccc/ffffff?text=' . urlencode($program->name);
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-4 left-4">
                                            <span class="inline-block bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">Shadaqah</span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-xl font-black text-blue-800 mb-2">{{ $program->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">{{ $program->description }}</p>

                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Donasi Terkumpul</span>
                                                <span class="text-blue-600 font-bold">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-blue-500 h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}"></div>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 text-xs">Target: {{ $program->formatted_total_target }}</span>
                                                <span class="text-gray-700 font-semibold text-sm">{{ number_format($program->progress_percentage, 1) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Program Pilar Tab -->
                <div class="tab-panel" id="pilar">
                    <div class="relative bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 mb-12 border border-white/20">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($pilarPrograms as $program)
                                <!-- {{ $program->name }} Category -->
                                @php
                                // Create route to individual program page
                                $routeName = 'program.show';
                                $routeParams = $program->slug;
                                @endphp
                                <a href="{{ route($routeName, $routeParams) }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden block">
                                    <div class="relative h-48 overflow-hidden">
                                        @php
                                        // Use our new image_url accessor to handle both CDN and local images
                                        $imageUrl = $program->image_url ?? 'https://via.placeholder.com/400x300/cccccc/ffffff?text=' . urlencode($program->name);
                                        @endphp
                                        <div class="absolute inset-0 bg-cover bg-center" data-bg-url="{{ $imageUrl }}"></div>
                                        <div class="absolute top-4 left-4">
                                            <span class="inline-block bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full">Program Pilar</span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-xl font-black text-purple-800 mb-2">{{ $program->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">{{ $program->description }}</p>

                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Donasi Terkumpul</span>
                                                <span class="text-purple-600 font-bold">{{ $program->formatted_total_collected }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-purple-500 h-full rounded-full progress-bar" data-width="{{ $program->progress_percentage }}"></div>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 text-xs">Target: {{ $program->formatted_total_target }}</span>
                                                <span class="text-gray-700 font-semibold text-sm">{{ number_format($program->progress_percentage, 1) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Tab styles */
        .tab-button {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .tab-button:not(.active):hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Optimize for performance */
        #program {
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanels = document.querySelectorAll('.tab-panel');

            // Function to activate a specific tab
            function activateTab(tabId) {
                // Remove active class from all buttons and panels
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanels.forEach(panel => panel.classList.remove('active'));

                // Add active class to the selected button
                const activeButton = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
                if (activeButton) {
                    activeButton.classList.add('active');
                }

                // Show corresponding panel
                const activePanel = document.getElementById(tabId);
                if (activePanel) {
                    activePanel.classList.add('active');
                }
            }

            // Check if there's an activeTab parameter passed from the route
            const urlParams = new URLSearchParams(window.location.search);
            const activeTabParam = urlParams.get('tab');

            // Also check if activeTab is passed via the view data (Laravel)
            // We need to check if the variable exists before using it
            let laravelActiveTab = '';
            // Set active tab from Laravel if available
            if (document.getElementById('laravel-active-tab')) {
                laravelActiveTab = document.getElementById('laravel-active-tab').getAttribute('data-tab');
            }

            // Activate the appropriate tab based on route parameter or view data
            if (activeTabParam) {
                activateTab(activeTabParam);
            } else if (laravelActiveTab) {
                activateTab(laravelActiveTab);
            }

            // Add click event listeners to tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    activateTab(tabId);

                    // Update URL without page reload
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabId);
                    window.history.pushState({}, '', url);
                });
            });

            // Set progress bar widths dynamically
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                if (width) {
                    bar.style.width = width + '%';
                }
            });

            // Set background images dynamically
            const bgElements = document.querySelectorAll('.bg-cover[data-bg-url]');
            bgElements.forEach(element => {
                const bgUrl = element.getAttribute('data-bg-url');
                if (bgUrl) {
                    element.style.backgroundImage = 'url(' + bgUrl + ')';
                    element.style.backgroundSize = 'cover';
                    element.style.backgroundPosition = 'center';
                }
            });
        });
    </script>
</div>