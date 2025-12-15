@props(['webinar', 'isRegistered' => false])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full">
    <div class="relative">
        @if($webinar->banner_url)
            <div class="aspect-video w-full overflow-hidden">
                <img src="{{ $webinar->banner_url }}" alt="{{ $webinar->title }}" class="w-full h-full object-cover">
            </div>
        @else
            <div class="aspect-video w-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
        @endif
        @if($webinar->is_free)
            <span class="absolute top-3 right-3 inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500 text-white shadow-sm">
                Безкоштовно
            </span>
        @endif
    </div>

    <div class="p-5 flex flex-col flex-1">
        <div class="flex items-center gap-2 mb-3 min-h-[1.75rem]">
            @if($webinar->is_starting_soon)
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-600">
                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                    Скоро почнеться
                </span>
            @endif
        </div>

        <a href="{{ route('student.webinar.show', $webinar->slug) }}" class="block">
            <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2 min-h-[3rem] hover:text-teal-600 transition-colors">{{ $webinar->title }}</h3>
        </a>

        <div class="flex items-center gap-3 mb-4 min-h-[2.5rem]">
            @if($webinar->teacher)
                @if($webinar->teacher->avatar_url)
                    <img src="{{ $webinar->teacher->avatar_url }}" alt="{{ $webinar->teacher->full_name }}" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-medium">
                        {{ mb_substr($webinar->teacher->full_name, 0, 1) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $webinar->teacher->full_name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $webinar->teacher->position }}</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-2 text-sm mb-4">
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ $webinar->formatted_date }} {{ $webinar->formatted_time }}</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600 col-span-2">
                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span>{{ $webinar->formatted_duration }}</span>
            </div>
        </div>

        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
            @if(!$webinar->is_free)
                <div>
                    @if($webinar->old_price)
                        <div class="text-sm text-gray-500 line-through">{{ number_format($webinar->old_price, 0, ',', ' ') }} ₴</div>
                    @endif
                    <span class="text-2xl font-bold text-teal-600">{{ number_format($webinar->price, 0, ',', ' ') }} ₴</span>
                </div>
            @endif

            @if($isRegistered)
                <a href="{{ route('student.webinar.show', $webinar->slug) }}" class="ml-auto bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                    Переглянути
                </a>
            @else
                <button
                    type="button"
                    onclick="openRegisterModal('{{ $webinar->slug }}', '{{ addslashes($webinar->title) }}', '{{ $webinar->teacher->full_name }}', '{{ $webinar->starts_at->translatedFormat('d.m.Y') }} о {{ $webinar->formatted_time }}', '{{ $webinar->formatted_duration }}', '{{ $webinar->available_spots !== null ? $webinar->available_spots : 'необмежено' }}', '{{ number_format($webinar->price, 0, ',', ' ') }} ₴', '{{ $webinar->banner_url ?? '' }}', {{ $webinar->is_free ? 'true' : 'false' }})"
                    class="ml-auto bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition"
                >
                    Зареєструватися
                </button>
            @endif
        </div>
    </div>
</div>
