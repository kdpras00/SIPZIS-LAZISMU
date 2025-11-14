<tr class="hover:bg-gray-50 transition-colors duration-150">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <img src="{{ $program->image_url }}" 
                 alt="{{ $program->name }}"
                 onerror="this.src='{{ asset('img/masjid.webp') }}';"
                 class="h-16 w-16 rounded-lg object-cover mr-4 flex-shrink-0">
            <div class="min-w-0">
                <div class="text-sm font-semibold text-gray-900 truncate">
                    {{ $program->name }}
                </div>
                <div class="text-sm text-gray-500 truncate mt-1">
                    {{ Str::limit($program->description, 50) }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            {{ $categoryName }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-center">
        <div class="text-sm font-medium text-gray-900">
            {{ $program->formatted_total_target }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-center">
        <div class="text-sm font-medium text-gray-900">
            {{ $program->formatted_total_collected }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center justify-center space-x-2">
            <span class="text-sm font-medium text-gray-700 w-12 text-right">
                {{ number_format($program->progress_percentage, 1) }}%
            </span>
            <div class="flex-1 max-w-xs">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ min($program->progress_percentage, 100) }}%"></div>
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-center">
        @if($program->status == 'active')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Active
            </span>
        @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                Inactive
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-center">
        <div class="flex items-center justify-center space-x-2">
            <a href="{{ route('admin.programs.edit', $program) }}" 
               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.programs.destroy', $program) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </td>
</tr>

