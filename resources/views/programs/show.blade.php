@extends('layouts.main')

@section('title', '{{ $program->name }} - SIPZIS')

@section('navbar')
@include('partials.navbarHome')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-b from-emerald-800 via-green-700 to-teal-800">
    <!-- Simple Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-64 h-64 bg-emerald-300 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-20 w-64 h-64 bg-teal-300 rounded-full blur-3xl"></div>
    </div>

    <div class="relative container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-4 mt-12">
            <a href="{{ route('program') }}"
                class="d-inline-flex align-items-center fw-semibold px-3 py-2 rounded"
                style=" color: white; text-decoration: none;  width: auto; display: inline-flex;">
                <svg width="20" height="20" fill="white" class="me-2" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 
                4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 
                8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
            </a>
        </div>



        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Image & Donation Box -->
                <div class="lg:col-span-1">
                    <!-- Program Image -->
                    <div class="rounded-xl overflow-hidden shadow-md mb-6">
                        <img src="{{ $program->image_url }}"
                            alt="{{ $program->name }}"
                            class="w-full h-64 object-cover"
                            onerror="this.onerror=null; this.src='{{ asset('img/masjid.webp') }}';">
                    </div>

                    <!-- Donation Card -->
                    <div class="bg-white border-2 border-emerald-100 rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Dana Terkumpul</h3>
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ number_format($program->progress_percentage, 1) }}%
                            </span>
                        </div>

                        <div class="mb-5">
                            <p class="text-3xl font-bold text-emerald-700 mb-1">
                                {{ $program->formatted_total_collected }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Target: <span class="font-semibold text-gray-700">{{ $program->formatted_total_target }}</span>
                            </p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-5">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2.5 rounded-full transition-all duration-500 ease-out"
                                    style="width: {{ min(100, number_format($program->progress_percentage, 1, '.', '')) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Program Details -->
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Program</h2>
                    <div class="text-gray-600 mb-6 leading-relaxed">
                        @if($program->description)
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($program->description)) !!}
                        </div>
                        @else
                        <p>Program ini bertujuan untuk memberikan manfaat kepada masyarakat yang membutuhkan. Setiap donasi yang Anda berikan akan digunakan secara optimal untuk tujuan yang telah ditentukan.</p>
                        @endif
                    </div>


                    <!-- CTA Button -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('guest.payment.create', ['program_id' => $program->id]) }}"
                            class="inline-flex items-center justify-center w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-3 rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all font-semibold shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                            </svg>
                            Salurkan Donasi Anda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Donatur Terbaru</h2>

            @php
            // Get payments by program_id (preferred) or fallback to program_category
            $recentDonations = \App\Models\ZakatPayment::where('status', 'completed')
                ->where(function($query) use ($program) {
                    $query->where('program_id', $program->id)
                          ->orWhere(function($q) use ($program) {
                              // Fallback for old payments without program_id
                              $q->whereNull('program_id')
                                ->where('program_category', $program->category);
                          });
                })
                ->latest('payment_date')
                ->limit(10)
                ->get();
            @endphp

            @if($recentDonations->count() > 0)
            <div class="space-y-3">
                @foreach($recentDonations as $donation)
                <div class="bg-emerald-50 rounded-lg p-4 hover:bg-emerald-100 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center">
                            <div class="bg-emerald-600 text-white p-3 rounded-full mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $donation->muzakki->name ?? 'Hamba Allah' }}</h3>
                                <p class="text-sm text-gray-600">{{ $donation->payment_date->format('d M Y') }} • {{ $donation->payment_date->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="bg-emerald-600 text-white px-4 py-2 rounded-lg">
                            <p class="font-bold">Rp {{ number_format($donation->paid_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <div class="bg-emerald-100 rounded-full p-6 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Donasi</h3>
                <p class="text-gray-600 mb-4">Jadilah yang pertama berdonasi untuk program ini.</p>
                <a href="{{ route('guest.payment.create', ['program_id' => $program->id]) }}"
                    class="inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition-colors font-semibold">
                    Mulai Berdonasi
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection