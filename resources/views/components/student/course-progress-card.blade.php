@props(['course'])

<div class="bg-white rounded-xl border {{ $course->hasStarted ? 'border-gray-200' : 'border-teal-200' }} p-5 {{ $course->hasStarted ? '' : 'opacity-90' }}">
    <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2">{{ $course->name }}</h3>

    @if(! $course->hasStarted)
        @if($course->formattedStartsAt)
            <div class="flex items-center gap-2 bg-teal-50 border border-teal-200 rounded-lg px-3 py-2 mb-4">
                <svg class="w-4 h-4 text-teal-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div>
                    <span class="text-xs text-teal-700 block">Початок курсу</span>
                    <span class="text-sm font-semibold text-teal-800">{{ $course->formattedStartsAt }}</span>
                </div>
            </div>
        @endif

        <a href="{{ $course->continueUrl }}" class="flex items-center justify-center gap-2 w-full bg-teal-100 hover:bg-teal-200 text-teal-800 border border-teal-300 font-medium py-2.5 px-4 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Очікується старт
        </a>
    @else
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
    @endif
</div>
