<template x-if="type === 'survey'">
    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-gray-50 p-4 mt-2">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Налаштування опитування</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Макс. спроб</label>
                <input type="number" name="quiz_max_attempts"
                       value="{{ old('quiz_max_attempts', $lesson->quiz->max_attempts ?? '') }}"
                       min="1" placeholder="Без обмежень"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                @error('quiz_max_attempts')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ліміт часу (хвилин)</label>
                <input type="number" name="quiz_time_limit_minutes"
                       value="{{ old('quiz_time_limit_minutes', $lesson->quiz->time_limit_minutes ?? '') }}"
                       min="1" placeholder="Без обмежень"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                @error('quiz_time_limit_minutes')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>
        </div>

        @if(!isset($lesson) || !$lesson->quiz)
            <div class="mt-4 p-3 rounded-lg bg-blue-50 text-sm text-blue-700">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Після збереження уроку ви зможете додати питання до опитування.</span>
                </div>
            </div>
        @endif

        @if(isset($lesson) && $lesson->quiz)
            <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Питань: <span class="font-medium">{{ $lesson->quiz->questions()->count() }}</span>
                    @if($lesson->quiz->attempts()->count() > 0)
                        <span class="mx-2">•</span>
                        Відповідей: <span class="font-medium">{{ $lesson->quiz->attempts()->count() }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @if($lesson->quiz->attempts()->count() > 0)
                        <a href="{{ route('admin.quizzes.results', $lesson->quiz) }}"
                           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Результати
                        </a>
                    @endif
                    <a href="{{ route('admin.quiz.questions.index', [$course, $module, $lesson]) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Редагувати питання
                    </a>
                </div>
            </div>
        @endif
    </div>
</template>
