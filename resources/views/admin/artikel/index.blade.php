@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Artikel</h1>
            <a href="{{ route('admin.artikel.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>Tambah Artikel
            </a>
        </div>

        @include('components.alerts')

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($artikels as $artikel)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($artikel->image)
                                            @php
                                                $rawImage = trim($artikel->image ?? '');
                                                // Cek apakah image adalah URL penuh (CDN)
                                                $isFullUrl = filter_var($rawImage, FILTER_VALIDATE_URL);
                                                // Tentukan URL akhir
                                                $imageUrl = $isFullUrl ? $rawImage : Storage::url($artikel->image);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="Artikel Image"
                                                class="w-12 h-12 rounded-lg object-cover mr-3">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ Str::limit($artikel->title, 50) }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($artikel->excerpt, 80) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $artikel->author->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.artikel.toggle-publish', $artikel) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition duration-200
                                    @if ($artikel->is_published) bg-green-100 text-green-800 hover:bg-green-200
                                    @else bg-gray-100 text-gray-800 hover:bg-gray-200 @endif">
                                            <i
                                                class="fas fa-circle w-2 h-2 mr-1.5
                                        @if ($artikel->is_published) text-green-400
                                        @else text-gray-400 @endif"></i>
                                            {{ $artikel->is_published ? 'Published' : 'Draft' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $artikel->formatted_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.artikel.show', $artikel) }}"
                                            class="text-blue-600 hover:text-blue-900 transition duration-200">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.artikel.edit', $artikel) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.artikel.destroy', $artikel) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition duration-200">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <i class="fas fa-newspaper text-gray-300 text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">Belum ada artikel</p>
                                        <p class="text-sm">Tambahkan artikel pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($artikels->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $artikels->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
