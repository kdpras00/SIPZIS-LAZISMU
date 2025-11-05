@extends('layouts.main')

@section('title', 'Zakat Calculator')

@section('navbar')
    @include('partials.navbarHome')
@endsection

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-16 mt-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Kalkulator Zakat</h1>
                    <p class="text-gray-600 text-lg">Hitung kewajiban zakat Anda dengan mudah dan akurat</p>
                </div>

                <!-- Main Calculator Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200">
                        <div class="flex">
                            <button type="button" onclick="switchTab('penghasilan')" id="tab-penghasilan"
                                class="tab-button active flex-1 py-5 px-6 text-center font-semibold border-b-4 border-green-500 text-green-600 bg-green-50 transition-all duration-300">
                                <i class="fas fa-user-tie mr-2"></i>Penghasilan
                            </button>
                            <button type="button" onclick="switchTab('harta')" id="tab-harta"
                                class="tab-button flex-1 py-5 px-6 text-center font-semibold border-b-4 border-transparent text-gray-500 transition-all duration-300">
                                <i class="fas fa-gem mr-2"></i>Harta
                            </button>
                        </div>
                    </div>

                    <!-- Calculator Content -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                        <!-- Left Side - Form (2 columns) -->
                        <div class="lg:col-span-2 p-8 border-r border-gray-200">
                            <!-- Penghasilan Content -->
                            <div id="content-penghasilan" class="calculator-content">
                                <div class="mb-6">
                                    <div class="flex gap-3 mb-6">
                                        <button type="button" onclick="togglePenghasilanType('perbulan')"
                                            id="penghasilan-perbulan"
                                            class="flex-1 py-3 px-4 bg-green-500 text-white rounded-lg font-semibold text-sm transition-all duration-300 hover:bg-green-600 shadow-sm">
                                            <i class="fas fa-calendar-alt mr-2"></i>Perbulan
                                        </button>
                                        <button type="button" onclick="togglePenghasilanType('pertahun')"
                                            id="penghasilan-pertahun"
                                            class="flex-1 py-3 px-4 bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition-all duration-300 hover:bg-green-600 shadow-sm">
                                            <i class="fas fa-calendar mr-2"></i>Pertahun
                                        </button>
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Komponen Zakat Penghasilan</h3>
                                    <p class="text-sm text-gray-600 mb-6">Silahkan isi dengan pendapatan bulanan Anda.
                                        Perhitungan Nishob tetap didasarkan pada nishob emas 85 gr yang dihitung bulanan</p>
                                </div>

                                <form id="penghasilanForm" class="space-y-5">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Pendapatan (Gaji bulanan)
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="income"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left" placeholder="0"
                                                oninput="formatNumber(this); calculatePenghasilan()">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Pendapatan lain bulanan (opsional)
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="otherIncome"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left" placeholder="0"
                                                oninput="formatNumber(this); calculatePenghasilan()">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Hutang/Cicilan bulanan (opsional)
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="debt"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left" placeholder="0"
                                                oninput="formatNumber(this); calculatePenghasilan()">
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <!-- Harta Content -->
                            <div id="content-harta" class="calculator-content hidden">
                                <div class="mb-6">
                                    <div class="flex gap-3 mb-6">
                                        <button type="button" onclick="toggleHartaType('perbulan')" id="harta-perbulan"
                                            class="flex-1 py-3 px-4 bg-green-500 text-white rounded-lg font-semibold text-sm transition-all duration-300 hover:bg-green-600 shadow-sm">
                                            <i class="fas fa-calendar-alt mr-2"></i>Perbulan (Simulasi)
                                        </button>
                                        <button type="button" onclick="toggleHartaType('pertahun')" id="harta-pertahun"
                                            class="flex-1 py-3 px-4 bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition-all duration-300 hover:bg-green-600 shadow-sm">
                                            <i class="fas fa-calendar mr-2"></i>Pertahun
                                        </button>
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Komponen Zakat Harta</h3>
                                    <p class="text-sm text-gray-600 mb-6">Khusus untuk harta yang telah tersimpan selama
                                        lebih dari 1 tahun (haul) dan mencapai batas tertentu (nisab). Zakat harta wajibnya
                                        hanya per tahun.</p>
                                </div>

                                <form id="hartaForm" class="space-y-5">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Deposito / Tabungan / Giro
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="savings"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left" placeholder="0"
                                                oninput="formatNumber(this); calculateHarta()">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Emas, perak, permata, atau sejenisnya
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="gold"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left"
                                                placeholder="0" oninput="formatNumber(this); calculateHarta()">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Nilai properti & kendaraan (bukan yang digunakan sehari-hari)
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="property"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left"
                                                placeholder="0" oninput="formatNumber(this); calculateHarta()">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Lainnya (saham, piutang, dan surat berharga lainnya)
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="other"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left"
                                                placeholder="0" oninput="formatNumber(this); calculateHarta()">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Hutang pribadi yang jatuh tempo tahun ini
                                        </label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                                            <span class="px-3 text-gray-500 text-sm">Rp</span>
                                            <input type="text" id="hartaDebt"
                                                class="w-full py-3 pr-3 outline-none rounded-r-lg text-left"
                                                placeholder="0" oninput="formatNumber(this); calculateHarta()">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Right Side - Result Panel (1 column) -->
                        <div class="lg:col-span-1 bg-gradient-to-b from-green-50 to-white p-8">
                            <!-- Penghasilan Result -->
                            <div id="result-penghasilan" class="result-content">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-green-200">Hasil
                                    Perhitungan</h3>
                                <p class="text-sm text-gray-600 mb-6">Berikut hasil perhitungan dan nilai zakat yang harus
                                    anda keluarkan</p>

                                <div class="space-y-4">
                                    <div class="bg-white rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Status</p>
                                        <p class="text-sm font-semibold text-gray-700" id="penghasilan-status">Tidak Wajib
                                            Membayar Zakat</p>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Total Harta</p>
                                        <p class="text-xl font-bold text-gray-800" id="penghasilan-total">Rp 0</p>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Nisab</p>
                                        <p class="text-base font-semibold text-gray-700" id="penghasilan-nisab">Rp
                                            17.051.489</p>
                                    </div>

                                    <div class="bg-green-50 rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Nilai Zakat</p>
                                        <p class="text-2xl font-bold text-green-600" id="penghasilan-zakat">Rp 0</p>
                                    </div>

                                    <button id="salurkanZakatPenghasilan"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg mt-3 text-lg">
                                        <i class="fas fa-hand-holding-heart mr-2"></i>Salurkan Zakat
                                    </button>
                                </div>
                            </div>

                            <!-- Harta Result -->
                            <div id="result-harta" class="result-content hidden">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-green-200">Hasil
                                    Perhitungan</h3>
                                <p class="text-sm text-gray-600 mb-6">Berikut hasil perhitungan dan nilai zakat yang harus
                                    anda keluarkan</p>

                                <div class="space-y-4">
                                    <div class="bg-white rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Status</p>
                                        <p class="text-sm font-semibold text-gray-700" id="harta-status">Tidak Wajib
                                            Membayar Zakat</p>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Total Harta</p>
                                        <p class="text-xl font-bold text-gray-800" id="harta-total">Rp 0</p>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Nisab</p>
                                        <p class="text-base font-semibold text-gray-700" id="harta-nisab">Rp 17.051.489
                                        </p>
                                    </div>

                                    <div class="bg-green-50 rounded-lg p-4 border border-green-200 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Nilai Zakat</p>
                                        <p class="text-2xl font-bold text-green-600" id="harta-zakat">Rp 0</p>
                                    </div>

                                    <button id="salurkanZakatHarta"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg mt-3 text-lg">
                                        <i class="fas fa-hand-holding-heart mr-2"></i>Salurkan Zakat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Penting</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border-l-4 border-green-500 pl-4 py-1">
                            <h4 class="font-semibold text-gray-800">Nisab Zakat</h4>
                            <p class="text-sm text-gray-600">Nisab zakat adalah batas minimum harta yang wajib dizakatkan,
                                yaitu setara 85 gram emas atau 595 gram perak. Nilai nisab menggunakan harga emas terkini
                                untuk perhitungan yang akurat.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-1">
                            <h4 class="font-semibold text-gray-800">Haul Zakat</h4>
                            <p class="text-sm text-gray-600">Haul adalah kepemilikan harta secara penuh selama satu tahun
                                hijriyah (354 hari).</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-1">
                            <h4 class="font-semibold text-gray-800">Zakat Harta</h4>
                            <p class="text-sm text-gray-600">Zakat harta wajib dibayar per tahun. Mode "per bulan" hanya
                                digunakan untuk simulasi pembayaran.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-1">
                            <h4 class="font-semibold text-gray-800">Harga Emas</h4>
                            <p class="text-sm text-gray-600">Perhitungan menggunakan harga emas terkini. Untuk akurasi
                                maksimal, harga emas diperbarui secara berkala.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        const GOLD_PRICE_PER_GRAM = 1000000;
        const NISAB_GOLD = 85;
        const NISAB_AMOUNT = NISAB_GOLD * GOLD_PRICE_PER_GRAM;
        const MONTHLY_NISAB = NISAB_AMOUNT / 12;
        const ZAKAT_RATE = 0.025;

        let penghasilanType = 'perbulan';
        let hartaType = 'perbulan';

        // Tab switching
        function switchTab(tab) {
            // Update tab buttons - use active class instead of individual classes
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-green-500', 'text-green-600', 'bg-green-50');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Set active tab
            const activeTab = document.getElementById('tab-' + tab);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('active', 'border-green-500', 'text-green-600', 'bg-green-50');

            // Switch content
            document.querySelectorAll('.calculator-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById('content-' + tab).classList.remove('hidden');

            // Switch result
            document.querySelectorAll('.result-content').forEach(result => {
                result.classList.add('hidden');
            });
            document.getElementById('result-' + tab).classList.remove('hidden');
        }

        // Toggle penghasilan type
        function togglePenghasilanType(type) {
            penghasilanType = type;
            const perbulanBtn = document.getElementById('penghasilan-perbulan');
            const pertahunBtn = document.getElementById('penghasilan-pertahun');

            if (type === 'perbulan') {
                perbulanBtn.classList.remove('bg-gray-200', 'text-gray-700');
                perbulanBtn.classList.add('bg-green-500', 'text-white');
                pertahunBtn.classList.remove('bg-green-500', 'text-white');
                pertahunBtn.classList.add('bg-gray-200', 'text-gray-700');
            } else {
                pertahunBtn.classList.remove('bg-gray-200', 'text-gray-700');
                pertahunBtn.classList.add('bg-green-500', 'text-white');
                perbulanBtn.classList.remove('bg-green-500', 'text-white');
                perbulanBtn.classList.add('bg-gray-200', 'text-gray-700');
            }

            calculatePenghasilan();
        }

        // Toggle harta type
        function toggleHartaType(type) {
            hartaType = type;
            const perbulanBtn = document.getElementById('harta-perbulan');
            const pertahunBtn = document.getElementById('harta-pertahun');

            if (type === 'perbulan') {
                perbulanBtn.classList.remove('bg-gray-200', 'text-gray-700');
                perbulanBtn.classList.add('bg-green-500', 'text-white');
                pertahunBtn.classList.remove('bg-green-500', 'text-white');
                pertahunBtn.classList.add('bg-gray-200', 'text-gray-700');
            } else {
                pertahunBtn.classList.remove('bg-gray-200', 'text-gray-700');
                pertahunBtn.classList.add('bg-green-500', 'text-white');
                perbulanBtn.classList.remove('bg-green-500', 'text-white');
                perbulanBtn.classList.add('bg-gray-200', 'text-gray-700');
            }

            calculateHarta();
        }

        // Format number
        function formatNumber(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                input.value = parseInt(value).toLocaleString('id-ID');
            } else {
                input.value = '';
            }
            // Recalculate based on the active tab
            if (document.getElementById('content-penghasilan') && !document.getElementById('content-penghasilan').classList
                .contains('hidden')) {
                calculatePenghasilan();
            } else {
                calculateHarta();
            }
        }

        // Parse formatted number
        function parseFormattedNumber(value) {
            if (!value) return 0;
            return parseFloat(value.replace(/\./g, '')) || 0;
        }

        // Format currency
        function formatCurrency(amount) {
            return amount.toLocaleString('id-ID');
        }

        // Calculate Penghasilan
        function calculatePenghasilan() {
            const income = parseFormattedNumber(document.getElementById('income').value);
            const otherIncome = parseFormattedNumber(document.getElementById('otherIncome').value);
            const debt = parseFormattedNumber(document.getElementById('debt').value);

            let totalIncome = income + otherIncome - debt;
            let nisabToUse = MONTHLY_NISAB;

            if (penghasilanType === 'pertahun') {
                nisabToUse = NISAB_AMOUNT;
            }

            let zakatAmount = 0;
            let status = 'Tidak Wajib Membayar Zakat';
            let statusColor = 'text-gray-700';

            if (totalIncome >= nisabToUse) {
                zakatAmount = totalIncome * ZAKAT_RATE;
                status = 'Wajib Membayar Zakat';
                statusColor = 'text-green-600';
            }

            document.getElementById('penghasilan-status').textContent = status;
            document.getElementById('penghasilan-status').className = 'text-sm font-semibold ' + statusColor;
            document.getElementById('penghasilan-total').textContent = 'Rp ' + formatCurrency(totalIncome);
            document.getElementById('penghasilan-nisab').textContent = 'Rp ' + formatCurrency(nisabToUse);
            document.getElementById('penghasilan-zakat').textContent = 'Rp ' + formatCurrency(zakatAmount);

            // Update the button text and destination based on status
            const salurkanBtn = document.getElementById('salurkanZakatPenghasilan');
            if (status === 'Wajib Membayar Zakat') {
                salurkanBtn.innerHTML = '<i class="fas fa-hand-holding-heart mr-2"></i>Salurkan Zakat';
                salurkanBtn.onclick = function() {
                    // Redirect to donation page with zakat amount and type
                    window.location.href = "{{ route('guest.payment.create') }}?category=zakat&amount=" + zakatAmount +
                        "&type=profesi";
                };
            } else {
                salurkanBtn.innerHTML = '<i class="fas fa-hands-helping mr-2"></i>Berinfak di Program Lain';
                salurkanBtn.onclick = function() {
                    // Redirect to program page with infaq tab selected
                    window.location.href = "/program?tab=infaq";
                };
            }
        }

        // Calculate Harta
        function calculateHarta() {
            const savings = parseFormattedNumber(document.getElementById('savings').value);
            const gold = parseFormattedNumber(document.getElementById('gold').value);
            const property = parseFormattedNumber(document.getElementById('property').value);
            const other = parseFormattedNumber(document.getElementById('other').value);
            const debt = parseFormattedNumber(document.getElementById('hartaDebt').value);

            let totalWealth = savings + gold + property + other - debt;
            let nisabToUse = NISAB_AMOUNT;

            let zakatAmount = 0;
            let status = 'Tidak Wajib Membayar Zakat';
            let statusColor = 'text-gray-700';

            // For Zakat Harta, it's only obligatory per year
            // If per month is selected, it's just for simulation
            if (hartaType === 'perbulan') {
                // For simulation, we'll show what it would be if annualized
                const annualizedWealth = totalWealth * 12;
                if (annualizedWealth >= nisabToUse) {
                    status = 'Wajib Membayar Zakat (Jika Per Tahun)';
                    statusColor = 'text-green-600';
                    // Show the annual zakat amount for simulation
                    zakatAmount = annualizedWealth * ZAKAT_RATE / 12; // Monthly equivalent
                } else {
                    status = 'Tidak Wajib Membayar Zakat (Simulasi Bulanan)';
                }
                // Display the actual monthly wealth value
                document.getElementById('harta-total').textContent = 'Rp ' + formatCurrency(totalWealth) + ' (Bulanan)';
            } else {
                // Actual yearly calculation
                if (totalWealth >= nisabToUse) {
                    zakatAmount = totalWealth * ZAKAT_RATE;
                    status = 'Wajib Membayar Zakat';
                    statusColor = 'text-green-600';
                } else {
                    const shortfall = nisabToUse - totalWealth;
                    status = 'Tidak Wajib Membayar Zakat';
                }
                document.getElementById('harta-total').textContent = 'Rp ' + formatCurrency(totalWealth) + ' (Tahunan)';
            }

            document.getElementById('harta-status').textContent = status;
            document.getElementById('harta-status').className = 'text-sm font-semibold ' + statusColor;
            document.getElementById('harta-nisab').textContent = 'Rp ' + formatCurrency(nisabToUse);
            document.getElementById('harta-zakat').textContent = 'Rp ' + formatCurrency(zakatAmount);

            // Update the button text and destination based on status
            const salurkanBtn = document.getElementById('salurkanZakatHarta');
            if (status === 'Wajib Membayar Zakat') {
                salurkanBtn.innerHTML = '<i class="fas fa-hand-holding-heart mr-2"></i>Salurkan Zakat';
                salurkanBtn.onclick = function() {
                    // Redirect to donation page with zakat amount and type
                    window.location.href = "{{ route('guest.payment.create') }}?category=zakat&amount=" + zakatAmount +
                        "&type=harta";
                };
            } else {
                salurkanBtn.innerHTML = '<i class="fas fa-hands-helping mr-2"></i>Berinfak di Program Lain';
                salurkanBtn.onclick = function() {
                    // Redirect to program page with infaq tab selected
                    window.location.href = "/program?tab=infaq";
                };
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            calculatePenghasilan();
            calculateHarta();
        });
    </script>

    <style>
        .tab-button {
            transition: all 0.3s ease;
        }

        /* Active tab styles */
        .tab-button.active {
            border-bottom-color: #22c55e !important;
            color: #16a34a !important;
            background-color: #f0fdf4 !important;
        }

        /* Inactive tab hover styles */
        .tab-button:not(.active):hover {
            color: #16a34a !important;
            background-color: #f0fdf4 !important;
            border-bottom-color: #22c55e !important;
        }

        /* Active tab hover styles */
        .tab-button.active:hover {
            background-color: #dcfce7 !important;
        }

        .calculator-content,
        .result-content {
            animation: fadeIn 0.3s ease;
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

        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
        }

        .rounded-xl {
            border-radius: 0.75rem;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
