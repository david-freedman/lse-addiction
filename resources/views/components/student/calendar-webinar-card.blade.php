@props(['webinar'])

<div data-webinar-id="{{ $webinar->id }}" class="bg-white rounded-lg p-4 border border-gray-200 hover:border-purple-300 transition cursor-pointer" onclick="window.location.href='{{ route('student.webinar.show', $webinar->slug) }}'">
    <h4 class="text-sm font-semibold text-gray-900 mb-3 line-clamp-2">{{ $webinar->title }}</h4>

    <div class="flex items-center gap-2 mb-3">
        @if($webinar->teacherPhotoUrl)
            <img src="{{ $webinar->teacherPhotoUrl }}" alt="{{ $webinar->teacherName }}" class="w-8 h-8 rounded-full object-cover">
        @else
            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-medium">
                {{ mb_substr($webinar->teacherName, 0, 1) }}
            </div>
        @endif
        <span class="text-xs text-gray-600">{{ $webinar->teacherName }}</span>
        <span class="text-xs text-gray-400 ml-auto flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ $webinar->participantsCount }}
        </span>
    </div>

    <div class="flex items-center gap-4 text-xs text-gray-500">
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ $webinar->formattedDate }}
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $webinar->formattedTime }}
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            {{ $webinar->formattedDuration }}
        </span>
    </div>
</div>
