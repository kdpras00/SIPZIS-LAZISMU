@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
    <!-- Custom styles for SIPZIS design -->
    <style>
        /* Override the default layout background */
        body {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%) !important;
            min-height: 100vh;
        }

        .main-content {
            background: transparent !important;
        }

        /* Dashboard specific background */
        .dashboard-container {
            position: relative;
            min-height: calc(100vh - 120px);
        }

        .dashboard-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('img/masjid.webp') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.1;
            z-index: 1;
        }

        .dashboard-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.9) 0%, rgba(6, 95, 70, 0.8) 50%, rgba(4, 120, 87, 0.9) 100%);
            z-index: 2;
        }

        .dashboard-content {
            position: relative;
            z-index: 3;
        }

        /* Animation keyframes */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }

        .delay-200 {
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }

        .delay-400 {
            animation-delay: 0.4s;
            animation-fill-mode: both;
        }

        .delay-600 {
            animation-delay: 0.6s;
            animation-fill-mode: both;
        }

        .delay-800 {
            animation-delay: 0.8s;
            animation-fill-mode: both;
        }

        .delay-1200 {
            animation-delay: 1.2s;
            animation-fill-mode: both;
        }

        .delay-1400 {
            animation-delay: 1.4s;
            animation-fill-mode: both;
        }

        .delay-1600 {
            animation-delay: 1.6s;
            animation-fill-mode: both;
        }

        .delay-1800 {
            animation-delay: 1.8s;
            animation-fill-mode: both;
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-content p-4">
            <!-- Header Section -->
            <div class="mb-4 animate-fadeInUp">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            Dashboard
                        </h1>
                    </div>
                    <div class="text-right">
                        <div class="bg-white rounded-lg shadow-sm p-3 inline-block">
                            <div class="flex flex-col items-end gap-2">
                                <div class="text-gray-500 text-sm">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </div>
                                <div class="font-semibold text-gray-800 text-2xl">
                                    <i class="fas fa-clock mr-2 text-green-600"></i>
                                    <span id="dynamic-clock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function updateClock() {
                            const clock = document.getElementById('dynamic-clock');
                            if (clock) {
                                const now = new Date();
                                const indonesiaTime = new Date(now.toLocaleString("en-US", {
                                    timeZone: "Asia/Jakarta"
                                }));
                                const pad = n => n < 10 ? '0' + n : n;
                                const h = pad(indonesiaTime.getHours());
                                const m = pad(indonesiaTime.getMinutes());
                                const s = pad(indonesiaTime.getSeconds());
                                clock.textContent = `${h}:${m}:${s}`;
                            }
                        }
                        setInterval(updateClock, 1000);
                        updateClock();
                    </script>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Total Zakat Card -->
                <div class="mb-4">
                    <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-3xl animate-fadeInUp min-h-[180px]"
                        style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);">
                        <div class="flex justify-between items-start h-full">
                            <div class="flex flex-col justify-between h-full">
                                <div>
                                    <p class="text-green-100 text-xs uppercase mb-2">Total Donasi {{ date('Y') }}</p>
                                    <h3 class="text-white font-bold text-2xl mb-1">
                                        Rp {{ number_format($stats['total_payments_this_year'], 0, ',', '.') }}
                                    </h3>
                                </div>
                                <small class="text-green-200 mt-2">
                                    +{{ number_format($stats['total_payments_this_month'], 0, ',', '.') }} bulan ini
                                </small>
                            </div>
                            <div
                                class="bg-green-500 bg-opacity-75 p-3 rounded-xl min-w-[50px] min-h-[50px] flex items-center justify-center">
                                <i class="fas fa-coins text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribusi Card -->
                <div class="mb-4">
                    <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-3xl animate-fadeInUp delay-200 min-h-[180px]"
                        style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);">
                        <div class="flex justify-between items-start h-full">
                            <div class="flex flex-col justify-between h-full">
                                <div>
                                    <p class="text-green-100 text-xs uppercase mb-2">Distribusi {{ date('Y') }}</p>
                                    <h3 class="text-white font-bold text-2xl mb-1">
                                        Rp {{ number_format($stats['total_distributions_this_year'], 0, ',', '.') }}
                                    </h3>
                                </div>
                                <small class="text-green-200 mt-2">
                                    {{ number_format($stats['total_distributions_this_month'], 0, ',', '.') }} bulan ini
                                </small>
                            </div>
                            <div
                                class="bg-yellow-500 bg-opacity-75 p-3 rounded-xl min-w-[50px] min-h-[50px] flex items-center justify-center">
                                <i class="fas fa-hands-helping text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Muzakki Card -->
                <div class="mb-4">
                    <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-3xl animate-fadeInUp delay-400 min-h-[180px]"
                        style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(79, 70, 229, 0.2) 100%);">
                        <div class="flex justify-between items-start h-full">
                            <div class="flex flex-col justify-between h-full">
                                <div>
                                    <p class="text-green-100 text-xs uppercase mb-2">Total Muzakki</p>
                                    <h3 class="text-white font-bold text-2xl mb-1">
                                        {{ number_format($stats['total_muzakki']) }}
                                    </h3>
                                </div>
                                <small class="text-green-200 mt-2">
                                    Aktif terdaftar
                                </small>
                            </div>
                            <div
                                class="bg-blue-500 bg-opacity-75 p-3 rounded-xl min-w-[50px] min-h-[50px] flex items-center justify-center">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mustahik Card -->
                <div class="mb-4">
                    <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-3xl animate-fadeInUp delay-600 min-h-[180px]"
                        style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(219, 39, 119, 0.2) 100%);">
                        <div class="flex justify-between items-start h-full">
                            <div class="flex flex-col justify-between h-full">
                                <div>
                                    <p class="text-green-100 text-xs uppercase mb-2">Mustahik Aktif</p>
                                    <h3 class="text-white font-bold text-2xl mb-1">
                                        {{ number_format($stats['total_mustahik']) }}
                                    </h3>
                                </div>
                                <small class="text-green-200 mt-2">
                                    @if ($stats['pending_mustahik'] > 0)
                                        {{ $stats['pending_mustahik'] }} menunggu verifikasi
                                    @else
                                        Semua terverifikasi
                                    @endif
                                </small>
                            </div>
                            <div
                                class="bg-purple-500 bg-opacity-75 p-3 rounded-xl min-w-[50px] min-h-[50px] flex items-center justify-center">
                                <i class="fas fa-heart text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Charts Section -->
                <div class="lg:col-span-2 mb-4">
                    <div
                        class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 animate-fadeInUp delay-800">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-500 bg-opacity-75 p-3 rounded-xl mr-3">
                                <i class="fas fa-chart-line text-white text-lg"></i>
                            </div>
                            <h4 class="text-white font-bold mb-0 text-lg">Grafik Pembayaran Zakat {{ date('Y') }}</h4>
                        </div>
                        <div class="bg-gray-900 bg-opacity-75 rounded-xl p-3">
                            <canvas id="paymentsChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="lg:col-span-1 mb-4">
                    <div
                        class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 animate-fadeInUp delay-800">
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-500 bg-opacity-75 p-3 rounded-xl mr-3">
                                <i class="fas fa-bolt text-white text-lg"></i>
                            </div>
                            <h4 class="text-white font-bold mb-0 text-lg">Saldo Donasi</h4>
                        </div>

                        <!-- Balance Info -->
                        <hr class="border-white border-opacity-25 my-4">
                        <div class="text-center">
                            @php $balance = $stats['total_payments_this_year'] - $stats['total_distributions_this_year']; @endphp
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <h3
                                    class="text-xl font-bold mb-0 {{ $balance > 0 ? 'text-green-500' : ($balance < 0 ? 'text-red-500' : 'text-gray-400') }}">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <!-- Recent Payments -->
                <div class="mb-4">
                    <div
                        class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl shadow-2xl text-white transition-all duration-300 animate-fadeInUp delay-1200">
                        <div
                            class="bg-transparent border-b border-white border-opacity-25 flex justify-between items-center p-4">
                            <div class="flex items-center">
                                <div class="bg-green-500 bg-opacity-75 p-3 rounded-xl mr-3">
                                    <i class="fas fa-credit-card text-white text-lg"></i>
                                </div>
                                <h4 class="text-white font-bold mb-0 text-lg">
                                    Pembayaran Terbaru
                                </h4>
                            </div>
                            <a href="{{ route('payments.index') }}"
                                class="no-underline px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg text-sm">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="p-0">
                            @forelse($recentPayments as $payment)
                                <div class="flex justify-between items-center p-4 border-b border-white border-opacity-10">
                                    <div>
                                        <h6 class="text-white font-semibold mb-1">
                                            {{ $payment->muzakki?->name ?? ($payment->is_guest_payment ? 'Donatur Tamu' : 'Tidak Diketahui') }}
                                        </h6>
                                        <p class="text-green-200 text-sm mb-0">
                                            {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-white font-bold">Rp
                                            {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                                        <p class="text-green-200 text-sm mb-0">
                                            {{ $payment->payment_date->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center">
                                    <div
                                        class="bg-white bg-opacity-10 rounded-full p-3 inline-flex items-center justify-center mb-3 w-16 h-16">
                                        <i class="fas fa-inbox text-white text-xl"></i>
                                    </div>
                                    <p class="text-green-200 mb-0">Belum ada pembayaran</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Distributions -->
                <div class="mb-4">
                    <div
                        class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl shadow-2xl text-white transition-all duration-300 animate-fadeInUp delay-1400">
                        <div
                            class="bg-transparent border-b border-white border-opacity-25 flex justify-between items-center p-4">
                            <div class="flex items-center">
                                <div class="bg-blue-500 bg-opacity-75 p-3 rounded-xl mr-3">
                                    <i class="fas fa-hands-helping text-white text-lg"></i>
                                </div>
                                <h4 class="text-white font-bold mb-0 text-lg">
                                    Distribusi Terbaru
                                </h4>
                            </div>
                            <a href="{{ route('distributions.index') }}"
                                class="no-underline px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg text-sm">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="p-0">
                            @forelse($recentDistributions as $distribution)
                                <div class="flex justify-between items-center p-4 border-b border-white border-opacity-10">
                                    <div>
                                        <h6 class="text-white font-semibold mb-1">
                                            {{ $distribution->mustahik?->name ?? 'Tidak Diketahui' }}</h6>
                                        <div class="flex items-center gap-2">
                                            @if ($distribution->mustahik)
                                                <span
                                                    class="inline-block px-3 py-1 text-xs font-semibold leading-none text-white text-center whitespace-nowrap align-baseline rounded-full bg-white bg-opacity-25 border border-white border-opacity-30">
                                                    {{ ucfirst($distribution->mustahik->category) }}
                                                </span>
                                            @endif
                                            <p class="text-green-200 text-sm mb-0">{{ $distribution->distribution_type }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-white font-bold">Rp
                                            {{ number_format($distribution->amount, 0, ',', '.') }}</div>
                                        <p class="text-green-200 text-sm mb-0">
                                            {{ $distribution->distribution_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center">
                                    <div
                                        class="bg-white bg-opacity-10 rounded-full p-3 inline-flex items-center justify-center mb-3 w-16 h-16">
                                        <i class="fas fa-inbox text-white text-xl"></i>
                                    </div>
                                    <p class="text-green-200 mb-0">Belum ada distribusi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Zakat Type Statistics -->
                <div class="mb-4">
                    <div
                        class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 animate-fadeInUp delay-1600">
                        <div class="bg-transparent border-b border-white border-opacity-25 flex items-center p-4">
                            <div class="bg-gray-500 bg-opacity-75 p-3 rounded-xl mr-3">
                                <i class="fas fa-chart-pie text-white text-lg"></i>
                            </div>
                            <h4 class="text-white font-bold mb-0 text-lg">
                                Pembayaran per Jenis Zakat
                            </h4>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                @forelse($programTypeStats as $stat)
                                    <div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white bg-opacity-10 rounded-lg">
                                            <div>
                                                <h6 class="text-white font-semibold mb-0">
                                                    {{ $stat->programType->name ?? 'Donasi Umum' }}</h6>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-white font-bold">Rp
                                                    {{ number_format($stat->total, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div>
                                        <div class="text-center py-4">
                                            <div
                                                class="bg-white bg-opacity-10 rounded-full p-3 inline-flex items-center justify-center mb-3 w-16 h-16">
                                                <i class="fas fa-chart-pie text-white text-xl"></i>
                                            </div>
                                            <p class="text-green-200 mb-0">Belum ada data pembayaran</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mustahik Category Statistics -->
                <div class="mb-4">
                    <div
                        class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-4 shadow-2xl text-white transition-all duration-300 animate-fadeInUp delay-1800">
                        <div class="bg-transparent border-b border-white border-opacity-25 flex items-center p-4">
                            <div class="bg-yellow-500 bg-opacity-75 p-3 rounded-xl mr-3">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <h4 class="text-white font-bold mb-0 text-lg">
                                Mustahik per Kategori
                            </h4>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                @forelse($mustahikCategoryStats as $stat)
                                    <div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white bg-opacity-10 rounded-lg">
                                            <div>
                                                <h6 class="text-white font-semibold mb-0">
                                                    {{ \App\Models\Mustahik::CATEGORIES[$stat->category] ?? ucfirst($stat->category) }}
                                                </h6>
                                            </div>
                                            <div class="text-right">
                                                <span class="bg-green-500 text-white px-3 py-1 rounded-full font-semibold">
                                                    {{ $stat->count }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div>
                                        <div class="text-center py-4">
                                            <div
                                                class="bg-white bg-opacity-10 rounded-full p-3 inline-flex items-center justify-center mb-3 w-16 h-16">
                                                <i class="fas fa-users text-white text-xl"></i>
                                            </div>
                                            <p class="text-green-200 mb-0">Belum ada data mustahik</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Payments Chart with dark theme
            const ctx = document.getElementById('paymentsChart');
            if (ctx) {
                const chartData = <?php echo json_encode($chartData); ?>;
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Pembayaran Zakat (Rp)',
                            data: chartData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#ffffff',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)',
                                    borderColor: 'rgba(255, 255, 255, 0.3)'
                                },
                                ticks: {
                                    color: '#ffffff',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)',
                                    borderColor: 'rgba(255, 255, 255, 0.3)'
                                },
                                ticks: {
                                    color: '#ffffff',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    },
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        },
                        elements: {
                            point: {
                                hoverRadius: 8
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
