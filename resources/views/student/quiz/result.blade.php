@extends('layouts.app')

@section('title', 'Результат: ' . $viewModel->quizTitle() . ' - ' . $viewModel->courseName())

@section('content')
<div class="bg-white border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-4">
                <a href="{{ $viewModel->backToModuleUrl() }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="text-sm font-medium">Назад до модулю</span>
                </a>
                <h1 class="hidden lg:block text-lg font-semibold text-gray-900 truncate max-w-xl">
                    {{ $viewModel->courseName() }}
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">Прогрес курсу:</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-teal-600">{{ $viewModel->courseProgressPercent() }}%</span>
                    <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full transition-all duration-300"
                             style="width: {{ $viewModel->courseProgressPercent() }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row" x-data="{ sidebarOpen: false }">
    <div class="flex-1 lg:pr-0">
        <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-4xl">
            <div class="text-center mb-8">
                @if($viewModel->isSurvey())
                    <div class="w-20 h-20 mx-auto mb-4 bg-teal-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Дякуємо за відповіді!</h2>
                    <p class="text-gray-600">Ваші відповіді успішно збережено</p>
                @elseif($result->passed)
                    <div class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Вітаємо! Квіз пройдено</h2>
                    <p class="text-gray-600">Ви успішно пройшли цей квіз</p>
                @else
                    <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Квіз не пройдено</h2>
                    <p class="text-gray-600">Спробуйте ще раз, щоб покращити результат</p>
                @endif
            </div>

            @if(!$viewModel->isSurvey())
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-3xl font-bold {{ $result->passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $result->scorePercentage() }}%
                        </p>
                        <p class="text-sm text-gray-600">Ваш результат</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $result->score }}/{{ $result->maxScore }}</p>
                        <p class="text-sm text-gray-600">Балів</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $viewModel->passingScore() }}%</p>
                        <p class="text-sm text-gray-600">Прохідний бал</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">
                            @if($attempt->time_spent_seconds)
                                {{ gmdate('i:s', $attempt->time_spent_seconds) }}
                            @else
                                --:--
                            @endif
                        </p>
                        <p class="text-sm text-gray-600">Час</p>
                    </div>
                </div>
            </div>
            @endif

            @if($viewModel->showCorrectAnswers())
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Детальні результати</h3>
                    @foreach($viewModel->questions() as $question)
                        @php
                            $questionResult = $result->questionResults[$question->id] ?? null;
                            $isCorrect = $questionResult['correct'] ?? false;
                        @endphp
                        <div class="mb-4 p-4 rounded-lg {{ $isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($isCorrect)
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium {{ $isCorrect ? 'text-green-900' : 'text-red-900' }}">
                                        {{ $question->question_text }}
                                    </p>
                                    @if(!$isCorrect && isset($questionResult['correctAnswers']))
                                        <p class="text-sm {{ $isCorrect ? 'text-green-700' : 'text-red-700' }} mt-1">
                                            Правильні відповіді:
                                            @foreach($question->answers->whereIn('id', $questionResult['correctAnswers']) as $answer)
                                                {{ $answer->answer_text }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                @if(!$viewModel->isSurvey() && $viewModel->canAttempt())
                    <a href="{{ route('student.quiz.show', [$course, $lesson]) }}"
                       class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Спробувати ще раз
                    </a>
                @endif

                @if($viewModel->canNavigateToNext())
                    <a href="{{ $viewModel->nextLessonUrl() }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium {{ $result->passed ? 'text-white bg-teal-500 hover:bg-teal-600' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50' }} rounded-lg transition">
                        Наступний урок
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif

                <a href="{{ $viewModel->backToCourseUrl() }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    До списку курсів
                </a>
            </div>
        </div>
    </div>

    @include('student.partials.course-sidebar', ['modules' => $viewModel->modules(), 'currentModuleId' => $lesson->module_id])
</div>
@endsection
