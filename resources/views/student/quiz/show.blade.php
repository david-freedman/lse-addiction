@extends('layouts.app')

@section('title', $viewModel->quizTitle() . ' - ' . $viewModel->courseName())

@section('content')
@php
    $questions = $viewModel->questions();
    $questionsData = $questions->map(fn($q) => [
        'id' => $q->id,
        'text' => $q->question_text,
        'type' => $q->type->value,
        'answers' => $q->answers->map(fn($a) => [
            'id' => $a->id,
            'text' => $a->answer_text,
            'image' => $a->answer_image,
            'category' => $a->category,
        ])->values()->toArray(),
    ])->values()->toArray();
@endphp

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

<div class="flex flex-col lg:flex-row" x-data="{ sidebarOpen: false, showQuizForm: {{ $viewModel->hasPassed() ? 'false' : 'true' }} }">
    <div class="flex-1 lg:pr-0">
        <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $viewModel->lessonName() }}</h2>
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                    <span>Модуль {{ $viewModel->moduleNumber() }}: {{ $viewModel->moduleName() }}</span>
                    <span class="text-gray-400">•</span>
                    <span>Урок {{ $viewModel->lessonNumber() }} з {{ $viewModel->totalLessonsInModule() }}</span>
                    @if($viewModel->duration())
                        <span class="text-gray-400">•</span>
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
        </div>

        @if($viewModel->hasPassed())
            <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl" x-show="!showQuizForm">
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <p class="font-medium text-green-800">Тест успішно пройдено!</p>
                                @if($viewModel->bestScorePercentage() !== null)
                                    <p class="text-sm text-green-700 mt-1">Ваш найкращий результат: {{ $viewModel->bestScorePercentage() }}%</p>
                                @endif
                            </div>
                        </div>
                        @if($viewModel->canAttempt())
                            <button type="button"
                                    @click="showQuizForm = true"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-teal-700 bg-teal-100 rounded-lg hover:bg-teal-200 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Пройти ще раз
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if(!$viewModel->canAttempt())
            <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl">
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-yellow-800">
                                @if($viewModel->isSurvey())
                                    Ви вже пройшли це опитування
                                @else
                                    Ви вичерпали всі спроби
                                @endif
                            </p>
                            @if(!$viewModel->isSurvey() && $viewModel->bestScorePercentage() !== null)
                                <p class="text-sm text-yellow-700 mt-1">Ваш найкращий результат: {{ $viewModel->bestScorePercentage() }}%</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div x-data="quizWizard({{ json_encode($questionsData) }})"
                 x-show="showQuizForm"
                 x-cloak
                 class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl border-t border-gray-200">

                @if(!$viewModel->isSurvey() && $viewModel->attemptsRemaining() !== null)
                    <div class="mb-4 text-sm text-gray-600">
                        Залишилось спроб: <span class="font-semibold">{{ $viewModel->attemptsRemaining() }}</span>
                    </div>
                @endif

                <div class="mb-6" x-show="!isReviewStep">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">
                            Питання <span x-text="currentStep + 1"></span> з <span x-text="totalQuestions"></span>
                        </span>
                        <span class="text-sm text-gray-500" x-text="progressPercent + '%'"></span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full transition-all duration-300"
                             :style="'width: ' + progressPercent + '%'"></div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 justify-center mb-6" x-show="!isReviewStep">
                    <template x-for="(q, i) in questions" :key="q.id">
                        <button type="button"
                                @click="goToQuestion(i)"
                                :class="{
                                    'bg-teal-500 text-white': currentStep === i,
                                    'bg-gray-200 text-gray-600 hover:bg-gray-300': currentStep !== i && !isAnswered(q.id),
                                    'bg-teal-100 text-teal-700 hover:bg-teal-200': currentStep !== i && isAnswered(q.id)
                                }"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors">
                            <span x-text="i + 1"></span>
                        </button>
                    </template>
                </div>

                @foreach($questions as $index => $question)
                    <div x-show="currentStep === {{ $index }}" x-cloak class="mb-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="mb-4">
                                <p class="text-lg font-medium text-gray-900">
                                    {{ $question->question_text }}
                                </p>
                            </div>

                            @switch($question->type->value)
                                @case('single_choice')
                                    @include('student.quiz.partials.question-single-choice', [
                                        'question' => $question,
                                        'questionIndex' => $index
                                    ])
                                    @break
                                @case('multiple_choice')
                                    @include('student.quiz.partials.question-multiple-choice', [
                                        'question' => $question,
                                        'questionIndex' => $index
                                    ])
                                    @break
                                @case('image_select')
                                    @include('student.quiz.partials.question-image-select', [
                                        'question' => $question,
                                        'questionIndex' => $index
                                    ])
                                    @break
                                @case('drag_drop')
                                    @include('student.quiz.partials.question-drag-drop', [
                                        'question' => $question,
                                        'questionIndex' => $index
                                    ])
                                    @break
                                @case('ordering')
                                    @include('student.quiz.partials.question-ordering', [
                                        'question' => $question,
                                        'questionIndex' => $index
                                    ])
                                    @break
                            @endswitch
                        </div>
                    </div>
                @endforeach

                <div x-show="isReviewStep" x-cloak class="mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Перегляд відповідей</h3>
                    <p class="text-sm text-gray-600 mb-6">Перевірте свої відповіді перед надсиланням. Натисніть на питання, щоб змінити відповідь.</p>

                    <div class="space-y-3">
                        <template x-for="(q, i) in questions" :key="q.id">
                            <div @click="goToQuestion(i)"
                                 class="p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors border border-gray-200">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 mb-1">
                                            <span class="text-teal-600" x-text="(i + 1) + '.'"></span>
                                            <span x-text="q.text"></span>
                                        </p>
                                        <p class="text-sm text-gray-600" x-html="getAnswerDisplayHtml(q)"></p>
                                    </div>
                                    <div class="shrink-0">
                                        <template x-if="isAnswered(q.id)">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Відповідь дано
                                            </span>
                                        </template>
                                        <template x-if="!isAnswered(q.id)">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                                                </svg>
                                                Не відповідено
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 mt-6">
                    <button type="button"
                            x-show="currentStep > 0"
                            @click="previous()"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Назад
                    </button>
                    <div x-show="currentStep === 0"></div>

                    <div class="flex gap-3">
                        <button type="button"
                                x-show="!isReviewStep && currentStep < totalQuestions - 1"
                                @click="next()"
                                class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                            Далі
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <button type="button"
                                x-show="!isReviewStep && currentStep === totalQuestions - 1"
                                @click="goToReview()"
                                class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                            Переглянути відповіді
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <button type="button"
                                x-show="isReviewStep"
                                @click="submitQuiz()"
                                class="inline-flex items-center px-8 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                            {{ $viewModel->isSurvey() ? 'Надіслати відповіді' : 'Завершити тест' }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form x-ref="quizForm" action="{{ $viewModel->submitUrl() }}" method="POST" class="hidden">
                    @csrf
                    <div x-ref="hiddenInputs"></div>
                </form>
            </div>
        @endif

        <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl border-t border-gray-200">
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

@push('scripts')
<script>
function quizWizard(questionsData) {
    return {
        questions: questionsData,
        totalQuestions: questionsData.length,
        currentStep: 0,
        isReviewStep: false,
        answers: {},

        init() {
            this.questions.forEach(q => {
                if (q.type === 'single_choice') {
                    this.answers[q.id] = { selected: null };
                } else if (q.type === 'multiple_choice' || q.type === 'image_select') {
                    this.answers[q.id] = { selected: [] };
                } else if (q.type === 'ordering') {
                    this.answers[q.id] = { order: [] };
                } else if (q.type === 'drag_drop') {
                    this.answers[q.id] = { categories: {} };
                }
            });

            this.$watch('currentStep', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        },

        get progressPercent() {
            return Math.round(((this.currentStep + 1) / this.totalQuestions) * 100);
        },

        goToQuestion(index) {
            this.currentStep = index;
            this.isReviewStep = false;
        },

        goToReview() {
            this.isReviewStep = true;
            this.currentStep = this.totalQuestions;
        },

        next() {
            if (this.currentStep < this.totalQuestions - 1) {
                this.currentStep++;
            }
        },

        previous() {
            if (this.isReviewStep) {
                this.isReviewStep = false;
                this.currentStep = this.totalQuestions - 1;
            } else if (this.currentStep > 0) {
                this.currentStep--;
            }
        },

        isAnswered(questionId) {
            const answer = this.answers[questionId];
            if (!answer) return false;

            if (answer.selected !== undefined) {
                if (Array.isArray(answer.selected)) {
                    return answer.selected.length > 0;
                }
                return answer.selected !== null;
            }

            if (answer.order !== undefined) {
                return answer.order.length > 0;
            }

            if (answer.categories !== undefined) {
                return Object.values(answer.categories).some(arr => arr.length > 0);
            }

            return false;
        },

        getAnswerDisplayHtml(question) {
            const answer = this.answers[question.id];
            if (!answer || !this.isAnswered(question.id)) {
                return '<span class="text-yellow-600 italic">Відповідь не надано</span>';
            }

            if (question.type === 'single_choice') {
                const selectedAnswer = question.answers.find(a => a.id === answer.selected);
                return selectedAnswer ? `→ ${selectedAnswer.text}` : '';
            }

            if (question.type === 'multiple_choice' || question.type === 'image_select') {
                const selectedTexts = answer.selected
                    .map(id => question.answers.find(a => a.id === id)?.text)
                    .filter(Boolean);
                return selectedTexts.length ? `→ ${selectedTexts.join(', ')}` : '';
            }

            if (question.type === 'ordering') {
                const orderedTexts = answer.order
                    .map((id, i) => {
                        const ans = question.answers.find(a => a.id === id);
                        return ans ? `${i + 1}. ${ans.text}` : null;
                    })
                    .filter(Boolean);
                return orderedTexts.length ? orderedTexts.join('<br>') : '';
            }

            if (question.type === 'drag_drop') {
                const parts = [];
                Object.entries(answer.categories).forEach(([category, ids]) => {
                    if (ids.length > 0) {
                        const texts = ids.map(id => question.answers.find(a => a.id === id)?.text).filter(Boolean);
                        parts.push(`<strong>${category}:</strong> ${texts.join(', ')}`);
                    }
                });
                return parts.length ? parts.join('<br>') : '';
            }

            return '';
        },

        setSingleAnswer(questionId, answerId) {
            this.answers[questionId].selected = answerId;
        },

        toggleMultipleAnswer(questionId, answerId) {
            const arr = this.answers[questionId].selected;
            const idx = arr.indexOf(answerId);
            if (idx === -1) {
                arr.push(answerId);
            } else {
                arr.splice(idx, 1);
            }
        },

        setOrderingAnswer(questionId, orderedIds) {
            this.answers[questionId].order = orderedIds;
        },

        setDragDropAnswer(questionId, categories) {
            this.answers[questionId].categories = categories;
        },

        submitQuiz() {
            const container = this.$refs.hiddenInputs;
            container.innerHTML = '';

            this.questions.forEach(q => {
                const answer = this.answers[q.id];

                if (q.type === 'single_choice' && answer.selected !== null) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${q.id}][selected][]`;
                    input.value = answer.selected;
                    container.appendChild(input);
                }

                if ((q.type === 'multiple_choice' || q.type === 'image_select') && answer.selected.length > 0) {
                    answer.selected.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `answers[${q.id}][selected][]`;
                        input.value = id;
                        container.appendChild(input);
                    });
                }

                if (q.type === 'ordering' && answer.order.length > 0) {
                    answer.order.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `answers[${q.id}][order][]`;
                        input.value = id;
                        container.appendChild(input);
                    });
                }

                if (q.type === 'drag_drop') {
                    Object.entries(answer.categories).forEach(([category, ids]) => {
                        ids.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `answers[${q.id}][categories][${category}][]`;
                            input.value = id;
                            container.appendChild(input);
                        });
                    });
                }
            });

            this.$refs.quizForm.submit();
        }
    }
}
</script>
@endpush
@endsection
