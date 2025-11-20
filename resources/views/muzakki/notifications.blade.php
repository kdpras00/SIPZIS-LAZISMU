@extends('layouts.main')

@section('title', 'Notifikasi - SIPZIS')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-white px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $notifications->total() }} notifikasi
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'all' ? 'active text-green-600 border-green-500' : '' }}">
                        Semua
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'payment']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'payment' ? 'active text-green-600 border-green-500' : '' }}">
                        Pembayaran {{ isset($notificationTypes['payment']) ? "({$notificationTypes['payment']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'distribution']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'distribution' ? 'active text-green-600 border-green-500' : '' }}">
                        Penyaluran {{ isset($notificationTypes['distribution']) ? "({$notificationTypes['distribution']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'program']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'program' ? 'active text-green-600 border-green-500' : '' }}">
                        Program {{ isset($notificationTypes['program']) ? "({$notificationTypes['program']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'account']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'account' ? 'active text-green-600 border-green-500' : '' }}">
                        Akun {{ isset($notificationTypes['account']) ? "({$notificationTypes['account']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'reminder']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'reminder' ? 'active text-green-600 border-green-500' : '' }}">
                        Pengingat {{ isset($notificationTypes['reminder']) ? "({$notificationTypes['reminder']->count})" : '' }}
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'message']) }}"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm {{ $filter === 'message' ? 'active text-green-600 border-green-500' : '' }}">
                        Pesan {{ isset($notificationTypes['message']) ? "({$notificationTypes['message']->count})" : '' }}
                    </a>
                </nav>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if(session()->has('notifications_success'))
                <div class="mb-4 p-4 rounded-lg border border-green-200 bg-green-50 text-green-800">
                    {{ session('notifications_success') }}
                </div>
                @endif

                @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors notification-item" data-type="{{ $notification->type }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @php
                                        $colorClass = [
                                        'payment' => 'green',
                                        'distribution' => 'blue',
                                        'program' => 'purple',
                                        'account' => 'yellow',
                                        'reminder' => 'orange',
                                        'message' => 'indigo'
                                        ][$notification->type] ?? 'gray';

                                        $iconPath = [
                                        'payment' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'distribution' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
                                        'program' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                                        'account' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                        'reminder' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'message' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'
                                        ][$notification->type] ?? 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z';
                                        @endphp
                                        <div class="h-10 w-10 rounded-full bg-{{ $colorClass }}-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-{{ $colorClass }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $notification->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $notification->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3 ml-14">
                                    <p class="text-sm text-gray-600">
                                        {{ $notification->message }}
                                    </p>
                                    @if(!$notification->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                                        Baru
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                @if($notification->notifiable_type === 'App\Models\ZakatPayment')
                                <a href="{{ route('payments.show', $notification->notifiable) }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Lihat Detail
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda tidak memiliki notifikasi saat ini.</p>
                    <div class="mt-6">
                        <a href="{{ route('program') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Lakukan Donasi
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality is now handled by links
    });
</script>
@endsection