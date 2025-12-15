@props(['course'])

<div class="bg-white rounded-xl border border-gray-200 p-5">
    <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2">{{ $course->name }}</h3>

    <div class="mb-4">
        <div class="flex items-center justify-between text-sm mb-2">
            <span class="text-gray-500">Уроків пройдено: {{ $course->lessonsCompleted }}/{{ $course->totalLessons }}</span>
            <span class="font-medium text-teal-500">{{ $course->progressPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-teal-500 h-2 rounded-full transition-all duration-500" style="width: {{ $course->progressPercentage }}%"></div>
        </div>
    </div>

    <a href="{{ $course->continueUrl }}" class="flex items-center justify-center gap-2 w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-2.5 px-4 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Продовжити
    </a>
</div>
