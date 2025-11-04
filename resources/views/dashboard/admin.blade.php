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

        /* Bootstrap card enhancements */
        .enhanced-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: white;
            transition: all 0.3s ease;
        }

        .enhanced-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }

        /* Statistics cards with gradients */
        .stat-card-emerald {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
        }

        .stat-card-yellow {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
        }

        .stat-card-blue {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(79, 70, 229, 0.2) 100%);
        }

        .stat-card-purple {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(219, 39, 119, 0.2) 100%);
        }

        /* Text colors for dark theme */
        .text-white-custom {
            color: #ffffff !important;
        }

        .text-green-100 {
            color: #dcfce7 !important;
        }

        .text-green-200 {
            color: #bbf7d0 !important;
        }

        /* Button styles */
        .btn-gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-green:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-blue:hover {
            background: linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            color: white;
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

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fadeInDown {
            animation: fadeInDown 0.8s ease-out;
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

        .delay-1000 {
            animation-delay: 1.0s;
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

        /* Glass morphism effect */
        .backdrop-blur-md {
            backdrop-filter: blur(12px);
        }

        /* Card hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        /* Adjusted card size */
        .stat-card-adjusted {
            min-height: 180px;
        }

        /* Reduced font sizes */
        .stat-card-adjusted h3 {
            font-size: 1.5rem !important;
        }

        .stat-card-adjusted .small {
            font-size: 0.8rem !important;
        }

        .stat-card-adjusted .fs-4 {
            font-size: 1.25rem !important;
        }

        .stat-card-adjusted .text-uppercase {
            font-size: 0.75rem !important;
        }

        /* Icon container */
        .icon-container {
            min-width: 50px;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Header text adjustment */
        .display-5 {
            font-size: 2rem !important;
        }

        /* Balance card text */
        .balance-text {
            font-size: 1.25rem !important;
        }

        /* Ensure Bootstrap cards work with our custom styles */
        .card.enhanced-card {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card.enhanced-card .card-header,
        .card.enhanced-card .card-body {
            background-color: transparent;
            border-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* Table styles for dark theme */
        .table-dark-theme {
            color: white;
        }

        .table-dark-theme thead th {
            color: #bbf7d0;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .table-dark-theme tbody td {
            color: #dcfce7;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .table-dark-theme tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-content p-4">
            <!-- Header Section -->
            <div class="row mb-4 animate-fadeInUp">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="display-5 fw-bold text-white-custom mb-2">
                                Dashboard
                            </h1>
                        </div>
                        <div class="text-end">
                            <div class="bg-white rounded-lg shadow-sm p-3 d-inline-block">
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <div class="text-muted small">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    </div>
                                    <div class="fw-semibold text-dark" style="font-size: 1.5rem;">
                                        <i class="fas fa-clock me-2 text-success"></i>
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
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <!-- Total Zakat Card -->
                <div class="col-md-3 mb-4">
                    <div class="card enhanced-card stat-card-emerald p-4 animate-fadeInUp stat-card-adjusted">
                        <div class="d-flex justify-content-between align-items-start h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <p class="text-green-100 text-uppercase small mb-2">Total Donasi {{ date('Y') }}</p>
                                    <h3 class="text-white-custom fw-bold mb-1">
                                        Rp {{ number_format($stats['total_payments_this_year'], 0, ',', '.') }}
                                    </h3>
                                </div>
                                <small class="text-green-200 mt-2">
                                    +{{ number_format($stats['total_payments_this_month'], 0, ',', '.') }} bulan ini
                                </small>
                            </div>
                            <div class="bg-success bg-opacity-75 p-3 rounded-3 icon-container">
                                <i class="fas fa-coins text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribusi Card -->
                <div class="col-md-3 mb-4">
                    <div class="card enhanced-card stat-card-yellow p-4 animate-fadeInUp delay-200 stat-card-adjusted">
                        <div class="d-flex justify-content-between align-items-start h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <p class="text-green-100 text-uppercase small mb-2">Distribusi {{ date('Y') }}</p>
                                    <h3 class="text-white-custom fw-bold mb-1">
                                        Rp {{ number_format($stats['total_distributions_this_year'], 0, ',', '.') }}
                                    </h3>
                                </div>
                                <small class="text-green-200 mt-2">
                                    {{ number_format($stats['total_distributions_this_month'], 0, ',', '.') }} bulan ini
                                </small>
                            </div>
                            <div class="bg-warning bg-opacity-75 p-3 rounded-3 icon-container">
                                <i class="fas fa-hands-helping text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Muzakki Card -->
                <div class="col-md-3 mb-4">
                    <div class="card enhanced-card stat-card-blue p-4 animate-fadeInUp delay-400 stat-card-adjusted">
                        <div class="d-flex justify-content-between align-items-start h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <p class="text-green-100 text-uppercase small mb-2">Total Muzakki</p>
                                    <h3 class="text-white-custom fw-bold mb-1">
                                        {{ number_format($stats['total_muzakki']) }}
                                    </h3>
                                </div>
                                <small class="text-green-200 mt-2">
                                    Aktif terdaftar
                                </small>
                            </div>
                            <div class="bg-info bg-opacity-75 p-3 rounded-3 icon-container">
                                <i class="fas fa-users text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mustahik Card -->
                <div class="col-md-3 mb-4">
                    <div class="card enhanced-card stat-card-purple p-4 animate-fadeInUp delay-600 stat-card-adjusted">
                        <div class="d-flex justify-content-between align-items-start h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <p class="text-green-100 text-uppercase small mb-2">Mustahik Aktif</p>
                                    <h3 class="text-white-custom fw-bold mb-1">
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
                            <div class="bg-secondary bg-opacity-75 p-3 rounded-3 icon-container">
                                <i class="fas fa-heart text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="row">
                <!-- Charts Section -->
                <div class="col-lg-8 mb-4">
                    <div class="card enhanced-card p-4 animate-fadeInUp delay-800">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-75 p-3 rounded-3 me-3">
                                <i class="fas fa-chart-line text-white fs-5"></i>
                            </div>
                            <h4 class="text-white-custom fw-bold mb-0">Grafik Pembayaran Zakat {{ date('Y') }}</h4>
                        </div>
                        <div class="bg-dark bg-opacity-75 rounded-3 p-3">
                            <canvas id="paymentsChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-lg-4 mb-4">
                    <div class="card enhanced-card p-4 animate-fadeInUp delay-800">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-warning bg-opacity-75 p-3 rounded-3 me-3">
                                <i class="fas fa-bolt text-white fs-5"></i>
                            </div>
                            <h4 class="text-white-custom fw-bold mb-0">Saldo Donasi</h4>
                        </div>

                        <!-- Balance Info -->
                        <hr class="border-white border-opacity-25 my-4">
                        <div class="text-center">
                            @php $balance = $stats['total_payments_this_year'] - $stats['total_distributions_this_year']; @endphp
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <h3
                                    class="balance-text fw-bold mb-0 {{ $balance > 0 ? 'text-success' : ($balance < 0 ? 'text-danger' : 'text-muted') }}">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities Grid -->
            <div class="row mb-4">
                <!-- Recent Payments -->
                <div class="col-lg-6 mb-4">
                    <div class="card enhanced-card shadow-xl animate-fadeInUp delay-1200">
                        <div
                            class="card-header bg-transparent border-white border-opacity-25 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-75 p-3 rounded-3 me-3">
                                    <i class="fas fa-credit-card text-white fs-5"></i>
                                </div>
                                <h4 class="text-white-custom fw-bold mb-0">
                                    Pembayaran Terbaru
                                </h4>
                            </div>
                            <a href="{{ route('payments.index') }}" class="btn btn-sm btn-gradient-green">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @forelse($recentPayments as $payment)
                                <div
                                    class="d-flex justify-content-between align-items-center p-4 border-bottom border-white border-opacity-10">
                                    <div>
                                        <h6 class="text-white-custom fw-semibold mb-1">
                                            {{ $payment->muzakki?->name ?? ($payment->is_guest_payment ? 'Donatur Tamu' : 'Tidak Diketahui') }}
                                        </h6>
                                        <p class="text-green-200 small mb-0">
                                            {{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}</p>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-white-custom fw-bold">Rp
                                            {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                                        <p class="text-green-200 small mb-0">{{ $payment->payment_date->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center">
                                    <div class="bg-white bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 64px; height: 64px;">
                                        <i class="fas fa-inbox text-white fs-4"></i>
                                    </div>
                                    <p class="text-green-200 mb-0">Belum ada pembayaran</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Distributions -->
                <div class="col-lg-6 mb-4">
                    <div class="card enhanced-card shadow-xl animate-fadeInUp delay-1400">
                        <div
                            class="card-header bg-transparent border-white border-opacity-25 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-75 p-3 rounded-3 me-3">
                                    <i class="fas fa-hands-helping text-white fs-5"></i>
                                </div>
                                <h4 class="text-white-custom fw-bold mb-0">
                                    Distribusi Terbaru
                                </h4>
                            </div>
                            <a href="{{ route('distributions.index') }}" class="btn btn-sm btn-gradient-blue">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @forelse($recentDistributions as $distribution)
                                <div
                                    class="d-flex justify-content-between align-items-center p-4 border-bottom border-white border-opacity-10">
                                    <div>
                                        <h6 class="text-white-custom fw-semibold mb-1">
                                            {{ $distribution->mustahik?->name ?? 'Tidak Diketahui' }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($distribution->mustahik)
                                                <span class="category-pill">
                                                    {{ ucfirst($distribution->mustahik->category) }}
                                                </span>
                                            @endif
                                            <p class="text-green-200 small mb-0">{{ $distribution->distribution_type }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-white-custom fw-bold">Rp
                                            {{ number_format($distribution->amount, 0, ',', '.') }}</div>
                                        <p class="text-green-200 small mb-0">
                                            {{ $distribution->distribution_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center">
                                    <div class="bg-white bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 64px; height: 64px;">
                                        <i class="fas fa-inbox text-white fs-4"></i>
                                    </div>
                                    <p class="text-green-200 mb-0">Belum ada distribusi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="row">
                <!-- Zakat Type Statistics -->
                <div class="col-lg-6 mb-4">
                    <div class="card enhanced-card p-4 shadow-xl animate-fadeInUp delay-1600">
                        <div class="card-header bg-transparent border-white border-opacity-25 d-flex align-items-center">
                            <div class="bg-secondary bg-opacity-75 p-3 rounded-3 me-3">
                                <i class="fas fa-chart-pie text-white fs-5"></i>
                            </div>
                            <h4 class="text-white-custom fw-bold mb-0">
                                Pembayaran per Jenis Zakat
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse($programTypeStats as $stat)
                                    <div class="col-12">
                                        <div
                                            class="d-flex justify-content-between align-items-center p-3 bg-white bg-opacity-10 rounded">
                                            <div>
                                                <h6 class="text-white-custom fw-semibold mb-0">
                                                    {{ $stat->programType->name ?? 'Donasi Umum' }}</h6>
                                            </div>
                                            <div class="text-end">
                                                <span class="text-white-custom fw-bold">Rp
                                                    {{ number_format($stat->total, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-4">
                                            <div class="bg-white bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width: 64px; height: 64px;">
                                                <i class="fas fa-chart-pie text-white fs-4"></i>
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
                <div class="col-lg-6 mb-4">
                    <div class="card enhanced-card p-4 shadow-xl animate-fadeInUp delay-1800">
                        <div class="card-header bg-transparent border-white border-opacity-25 d-flex align-items-center">
                            <div class="bg-warning bg-opacity-75 p-3 rounded-3 me-3">
                                <i class="fas fa-users text-white fs-5"></i>
                            </div>
                            <h4 class="text-white-custom fw-bold mb-0">
                                Mustahik per Kategori
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse($mustahikCategoryStats as $stat)
                                    <div class="col-12">
                                        <div
                                            class="d-flex justify-content-between align-items-center p-3 bg-white bg-opacity-10 rounded">
                                            <div>
                                                <h6 class="text-white-custom fw-semibold mb-0">
                                                    {{ \App\Models\Mustahik::CATEGORIES[$stat->category] ?? ucfirst($stat->category) }}
                                                </h6>
                                            </div>
                                            <div class="text-end">
                                                <span class="bg-success text-white px-3 py-1 rounded-pill fw-semibold">
                                                    {{ $stat->count }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-4">
                                            <div class="bg-white bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width: 64px; height: 64px;">
                                                <i class="fas fa-users text-white fs-4"></i>
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
    <style>
        .category-pill {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.75em;
            font-weight: 600;
            line-height: 1;
            color: #ffffff;
            /* Teks putih agar kontras */
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 50rem;
            /* Efek rounded-pill */
            background-color: rgba(255, 255, 255, 0.25);
            /* Background putih transparan yang lebih solid */
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
@endpush
