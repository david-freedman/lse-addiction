@props(['webinar'])

<div class="bg-white rounded-xl border border-gray-200 p-5">
    @if($webinar->isStartingSoon)
        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-600 mb-3">
            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
            Скоро почнеться
        </span>
    @endif

    <a href="{{ route('student.webinar.show', $webinar->slug) }}" class="block font-semibold text-gray-900 mb-3 line-clamp-2 hover:text-teal-600 transition">{{ $webinar->title }}</a>

    <div class="flex items-center gap-3 mb-4">
        @if($webinar->teacherPhotoUrl)
            <img src="{{ $webinar->teacherPhotoUrl }}" alt="{{ $webinar->teacherName }}" class="w-8 h-8 rounded-full object-cover">
        @else
            <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-medium">
                {{ mb_substr($webinar->teacherName, 0, 1) }}
            </div>
        @endif
        <div>
            <p class="text-sm text-gray-500">{{ $webinar->teacherName }}</p>
            <p class="text-xs text-gray-400">{{ $webinar->participantsCount }} учасників</p>
        </div>
    </div>

    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
        <span class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ $webinar->formattedDate }}
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $webinar->formattedTime }}
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            {{ $webinar->formattedDuration }}
        </span>
    </div>

    @if($webinar->isRegistered)
        <a href="{{ route('student.webinar.show', $webinar->slug) }}" class="flex items-center justify-center gap-2 w-full border-2 border-teal-500 text-teal-500 hover:bg-teal-50 font-medium py-2.5 px-4 rounded-lg transition">
            Переглянути вебінар
        </a>
    @else
        <button
            type="button"
            onclick="openRegisterModal('{{ $webinar->slug }}', '{{ addslashes($webinar->title) }}', '{{ addslashes($webinar->teacherName) }}', '{{ $webinar->formattedDatetime }}', '{{ $webinar->formattedDuration }}', '{{ $webinar->availableSpots ?? 'Необмежено' }}', '{{ $webinar->price }}', '{{ $webinar->bannerUrl }}', {{ $webinar->isFree ? 'true' : 'false' }})"
            class="flex items-center justify-center gap-2 w-full border-2 border-rose-400 text-rose-400 hover:bg-rose-50 font-medium py-2.5 px-4 rounded-lg transition"
        >
            Зареєструватися
        </button>
    @endif
</div>
