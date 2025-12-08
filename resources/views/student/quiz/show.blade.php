@extends('layouts.app')

@section('title', $viewModel->quizTitle() . ' - ' . $viewModel->courseName())

@section('content')
<div class="bg-white border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-4">
                <a href="{{ $viewModel->backToCourseUrl() }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="text-sm font-medium">Назад до курсів</span>
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
        <form action="{{ $viewModel->submitUrl() }}" method="POST" class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl">
            @csrf

            @if(!$viewModel->canAttempt())
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-yellow-800">Ви вичерпали всі спроби</p>
                            @if($viewModel->bestScorePercentage() !== null)
                                <p class="text-sm text-yellow-700 mt-1">Ваш найкращий результат: {{ $viewModel->bestScorePercentage() }}%</p>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                @if($viewModel->attemptsRemaining() !== null)
                    <div class="mb-4 text-sm text-gray-600">
                        Залишилось спроб: <span class="font-semibold">{{ $viewModel->attemptsRemaining() }}</span>
                    </div>
                @endif
            @endif

            @foreach($viewModel->questions() as $index => $question)
                <div class="mb-8 bg-gray-50 rounded-xl p-6">
                    <div class="mb-4">
                        <p class="text-lg font-medium text-gray-900">
                            {{ $question->question_text }}
                        </p>
                    </div>

                    @switch($question->type->value)
                        @case('single_choice')
                            @include('student.quiz.partials.question-single-choice', ['question' => $question, 'questionIndex' => $index])
                            @break
                        @case('multiple_choice')
                            @include('student.quiz.partials.question-multiple-choice', ['question' => $question, 'questionIndex' => $index])
                            @break
                        @case('image_select')
                            @include('student.quiz.partials.question-image-select', ['question' => $question, 'questionIndex' => $index])
                            @break
                        @case('drag_drop')
                            @include('student.quiz.partials.question-drag-drop', ['question' => $question, 'questionIndex' => $index])
                            @break
                    @endswitch
                </div>
            @endforeach

            @if($viewModel->canAttempt())
                <button type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-teal-500 text-white font-medium rounded-lg hover:bg-teal-600 transition">
                    Перевірити відповіді
                </button>
            @endif
        </form>

        <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl border-t border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $viewModel->lessonName() }}</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                    <span>Модуль {{ $viewModel->moduleNumber() }}: {{ $viewModel->moduleName() }}</span>
                    <span class="text-gray-400">*</span>
                    <span>Урок {{ $viewModel->lessonNumber() }} з {{ $viewModel->totalLessonsInModule() }}</span>
                    @if($viewModel->duration())
                        <span class="text-gray-400">*</span>
                        <span>{{ $viewModel->duration() }}</span>
                    @endif
                </div>
            </div>

            @if($viewModel->description())
                <div class="mb-6">
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($viewModel->description())) !!}
                    </div>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                @if($viewModel->canNavigateToPrevious())
                    <a href="{{ $viewModel->previousLessonUrl() }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Попередній урок
                    </a>
                @endif

                @if($viewModel->hasPassed())
                    <button type="button"
                            disabled
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-green-500 rounded-lg cursor-default">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Пройдено
                    </button>
                @endif

                @if($viewModel->canNavigateToNext())
                    <a href="{{ $viewModel->nextLessonUrl() }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                        Наступний урок
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @include('student.partials.course-sidebar', ['modules' => $viewModel->modules(), 'currentModuleId' => $lesson->module_id])
</div>
@endsection
