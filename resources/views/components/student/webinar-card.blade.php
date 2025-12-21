@props(['webinar'])

<div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col h-full">
    <div class="min-h-[1.75rem] mb-3">
        @if($webinar->isLive ?? false)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-600">
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                Йде зараз
            </span>
        @elseif($webinar->isStartingSoon ?? false)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-600">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                Скоро почнеться
            </span>
        @elseif($webinar->isCompleted ?? false)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Завершено
            </span>
        @elseif($webinar->isUpcoming ?? false)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-600">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Заплановано
            </span>
        @endif
    </div>

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

    <div class="mt-auto">
        @if($webinar->isRegistered)
            @if($webinar->isLive ?? false)
                <a href="{{ route('student.webinar.show', $webinar->slug) }}" class="flex items-center justify-center gap-2 w-full bg-rose-500 hover:bg-rose-600 text-white font-medium py-2.5 px-4 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Приєднатись
                </a>
            @else
                <a href="{{ route('student.webinar.show', $webinar->slug) }}" class="flex items-center justify-center gap-2 w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-2.5 px-4 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Переглянути
                </a>
            @endif
        @else
            <button
                type="button"
                onclick="openRegisterModal('{{ $webinar->slug }}', '{{ addslashes($webinar->title) }}', '{{ addslashes($webinar->teacherName) }}', '{{ $webinar->formattedDatetime }}', '{{ $webinar->formattedDuration }}', '{{ $webinar->availableSpots ?? 'Необмежено' }}', '{{ $webinar->price }}', '{{ $webinar->bannerUrl }}', {{ $webinar->isFree ? 'true' : 'false' }})"
                class="flex items-center justify-center gap-2 w-full border-2 border-rose-400 text-rose-400 hover:bg-rose-50 font-medium py-2.5 px-4 rounded-lg transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Зареєструватися
            </button>
        @endif
    </div>
</div>
