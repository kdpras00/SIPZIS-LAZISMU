@extends('layouts.main')

@section('title', 'Page Not Found')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-cyan-50 flex items-center justify-center p-6">
    <div
        class="max-w-4xl w-full bg-white/90 backdrop-blur border border-emerald-100 rounded-3xl shadow-xl px-10 py-12 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-emerald-100 rounded-full opacity-60"></div>
            <div class="absolute -bottom-16 -right-16 w-60 h-60 bg-cyan-100 rounded-full opacity-60"></div>
        </div>

        <div class="relative">
            <div class="mx-auto w-32 h-32 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center mb-6">
                <svg viewBox="0 0 120 120" class="w-20 h-20 text-emerald-500">
                    <circle cx="60" cy="60" r="55" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="6 10"
                        stroke-linecap="round"></circle>
                    <path d="M40 50h12m16 0h12 M45 75c6 6 24 6 30 0" stroke="currentColor" stroke-width="4" stroke-linecap="round"
                        stroke-linejoin="round" fill="none"></path>
                </svg>
            </div>

            <p class="text-sm uppercase tracking-[0.4em] font-semibold text-emerald-500 mb-4">Error 404</p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Halaman Tidak Ditemukan</h1>
            <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed mb-10">
                Maaf, halaman yang Anda cari tidak tersedia atau sudah dipindahkan. Gunakan tombol di bawah ini untuk
                kembali ke beranda atau lanjutkan eksplorasi program donasi kami.
            </p>

            <div class="grid gap-4 md:grid-cols-2 max-w-2xl mx-auto">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-emerald-500 text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition-colors">
                    Kembali ke Beranda
                    <i class="bi bi-arrow-right-short text-2xl ml-1"></i>
                </a>
                <a href="{{ route('program') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-emerald-200 text-emerald-700 font-semibold hover:bg-emerald-50 transition-colors">
                    Lihat Program Donasi
                    <i class="bi bi-heart text-lg ml-2"></i>
                </a>
            </div>

            <div class="mt-10 text-sm text-gray-500">
                <p>Butuh bantuan? <a href="{{ route('home') }}#contact"
                        class="text-emerald-600 font-medium hover:underline">Hubungi tim SIPZIS</a></p>
            </div>
        </div>
    </div>
</section>
@endsection