{{-- resources/views/muzakki/partials/notifications-popup.blade.php --}}

@if($notifications->count() > 0)
<div class="space-y-3">
    {{-- Mark all as read button --}}
    <form action="{{ route('notifications.markAsRead') }}" method="POST" class="text-right mb-4">
        @csrf
        <button type="submit" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors duration-200">
            Tandai semua sebagai dibaca
        </button>
    </form>

    {{-- Notifications List --}}
    <div class="space-y-3">
        @foreach($notifications as $notification)
        <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-sm {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
            <div class="flex items-start">
                {{-- Icon --}}
                <div class="flex-shrink-0 mt-1">
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
                    <div class="h-12 w-12 rounded-full bg-{{ $colorClass }}-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-{{ $colorClass }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
                        </svg>
                    </div>
                </div>

                {{-- Content --}}
                <div class="ml-4 flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <h4 class="text-base font-semibold text-gray-900 truncate">
                            {{ $notification->title }}
                        </h4>
                        @if(!$notification->is_read)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2 flex-shrink-0">
                            Baru
                        </span>
                        @endif
                    </div>
                    <div class="mt-1">
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                            {{ $notification->message }}
                        </p>
                    </div>
                    <div class="mt-2 flex items-center text-xs text-gray-500">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- View All Link --}}
    <div class="pt-3 border-t border-gray-200">
        <a href="{{ route('notifications.index') }}" class="text-base font-medium text-green-600 hover:text-green-700 flex items-center justify-center transition-colors duration-200">
            Lihat semua notifikasi
            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>
@else
{{-- Empty State --}}
<div class="text-center py-8">
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100">
        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
    </div>
    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada notifikasi</h3>
    <p class="mt-2 text-base text-gray-500">Anda tidak memiliki notifikasi saat ini.</p>
    <div class="mt-6">
        <a href="{{ route('program') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
            Lakukan Donasi
        </a>
    </div>
</div>
@endif