@props(['course'])

<div class="bg-white rounded-xl border border-gray-200 p-5">
    <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2">{{ $course->name }}</h3>

    <div class="mb-4">
        <div class="flex items-center justify-between text-sm mb-2">
            <span class="text-gray-500">Уроків пройдено: {{ $course->lessonsCompleted }}/{{ $course->totalLessons }}</span>
            <span class="font-medium {{ $course->progressPercentage === 100 ? 'text-emerald-600' : 'text-teal-500' }}">{{ $course->progressPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="{{ $course->progressPercentage === 100 ? 'bg-emerald-500' : 'bg-teal-500' }} h-2 rounded-full transition-all duration-500" style="width: {{ $course->progressPercentage }}%"></div>
        </div>
    </div>

    @if($course->progressPercentage === 100)
        <a href="{{ $course->continueUrl }}" class="flex items-center justify-center gap-2 w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-2.5 px-4 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            Переглянути
        </a>
    @else
        <a href="{{ $course->continueUrl }}" class="flex items-center justify-center gap-2 w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-2.5 px-4 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
            Продовжити
        </a>
    @endif
</div>
