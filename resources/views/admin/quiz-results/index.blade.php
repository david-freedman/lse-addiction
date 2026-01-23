@extends('admin.layouts.admin')

@section('title', 'Результати квізів')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Результати квізів</h1>
    <p class="text-gray-500">Перегляд результатів квізів та опитувань</p>
</div>

<div class="flex gap-4 mb-6">
    <a href="{{ route('admin.quiz-results.index', array_merge(request()->except('tab', 'status', 'page'), ['tab' => 'quizzes'])) }}"
       class="rounded-xl border px-5 py-3 text-sm font-medium transition
           {{ $viewModel->currentTab() === 'quizzes'
               ? 'border-brand-500 bg-brand-50 text-brand-700'
               : 'border-gray-200 bg-white text-gray-700 hover:border-brand-300' }}">
        Квізи
    </a>
    <a href="{{ route('admin.quiz-results.index', array_merge(request()->except('tab', 'status', 'page'), ['tab' => 'surveys'])) }}"
       class="rounded-xl border px-5 py-3 text-sm font-medium transition
           {{ $viewModel->currentTab() === 'surveys'
               ? 'border-brand-500 bg-brand-50 text-brand-700'
               : 'border-gray-200 bg-white text-gray-700 hover:border-brand-300' }}">
        Опитування
    </a>
</div>

@if($viewModel->currentTab() === 'quizzes')
    @php $stats = $viewModel->statistics(); @endphp
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-500">Всього спроб</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-500">Відсоток успішних</p>
            <p class="text-2xl font-bold text-success-600">{{ $stats['pass_rate'] }}%</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-500">Середній результат</p>
            <p class="text-2xl font-bold text-brand-600">{{ $stats['avg_score'] }}%</p>
        </div>
    </div>

    @php $counts = $viewModel->statusCounts(); @endphp
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.quiz-results.index', array_merge(request()->except('status', 'page'))) }}"
           class="rounded-xl border px-4 py-2.5 text-sm font-medium transition
               {{ $viewModel->currentStatus() === null
                   ? 'border-brand-500 bg-brand-50 text-brand-700'
                   : 'border-gray-200 bg-white text-gray-700 hover:border-brand-300' }}">
            Всі
            <span class="ml-1.5 rounded-full bg-brand-100 px-2 py-0.5 text-xs font-semibold text-brand-700">
                {{ $counts['all'] }}
            </span>
        </a>
        <a href="{{ route('admin.quiz-results.index', array_merge(request()->except('status', 'page'), ['status' => 'passed'])) }}"
           class="rounded-xl border px-4 py-2.5 text-sm font-medium transition
               {{ $viewModel->currentStatus() === 'passed'
                   ? 'border-success-500 bg-success-50 text-success-700'
                   : 'border-gray-200 bg-white text-gray-700 hover:border-success-300' }}">
            Успішні
            <span class="ml-1.5 rounded-full bg-success-100 px-2 py-0.5 text-xs font-semibold text-success-700">
                {{ $counts['passed'] }}
            </span>
        </a>
        <a href="{{ route('admin.quiz-results.index', array_merge(request()->except('status', 'page'), ['status' => 'failed'])) }}"
           class="rounded-xl border px-4 py-2.5 text-sm font-medium transition
               {{ $viewModel->currentStatus() === 'failed'
                   ? 'border-error-500 bg-error-50 text-error-700'
                   : 'border-gray-200 bg-white text-gray-700 hover:border-error-300' }}">
            Не пройшли
            <span class="ml-1.5 rounded-full bg-error-100 px-2 py-0.5 text-xs font-semibold text-error-700">
                {{ $counts['failed'] }}
            </span>
        </a>
    </div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white mb-6"
     x-data="{
        tab: '{{ $viewModel->currentTab() }}',
        courseId: {{ $viewModel->filters->course_id ?? 'null' }},
        moduleId: {{ $viewModel->filters->module_id ?? 'null' }},
        lessonId: {{ $viewModel->filters->lesson_id ?? 'null' }},
        quizId: {{ $viewModel->filters->quiz_id ?? 'null' }},
        modules: {{ json_encode($viewModel->modules()) }},
        lessons: {{ json_encode($viewModel->lessons()) }},
        quizzes: {{ json_encode($viewModel->quizzes()) }},
        loading: false,

        async loadModules() {
            if (!this.courseId) {
                this.modules = [];
                this.moduleId = null;
                this.lessons = [];
                this.lessonId = null;
                this.quizzes = [];
                this.quizId = null;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/admin/quiz-results/modules/${this.courseId}?tab=${this.tab}`);
                this.modules = await response.json();
                this.moduleId = null;
                this.lessons = [];
                this.lessonId = null;
                this.quizzes = [];
                this.quizId = null;
            } finally {
                this.loading = false;
            }
        },

        async loadLessons() {
            if (!this.moduleId) {
                this.lessons = [];
                this.lessonId = null;
                this.quizzes = [];
                this.quizId = null;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/admin/quiz-results/lessons/${this.moduleId}?tab=${this.tab}`);
                this.lessons = await response.json();
                this.lessonId = null;
                this.quizzes = [];
                this.quizId = null;
            } finally {
                this.loading = false;
            }
        },

        async loadQuizzes() {
            if (!this.lessonId) {
                this.quizzes = [];
                this.quizId = null;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/admin/quiz-results/quizzes/${this.lessonId}?tab=${this.tab}`);
                this.quizzes = await response.json();
                this.quizId = null;
            } finally {
                this.loading = false;
            }
        }
     }">
    <form method="GET" class="p-4 border-b border-gray-200">
        <input type="hidden" name="tab" value="{{ $viewModel->currentTab() }}">
        @if($viewModel->currentStatus())
            <input type="hidden" name="status" value="{{ $viewModel->currentStatus() }}">
        @endif
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px] max-w-[300px]">
                <input type="text" name="search" value="{{ $viewModel->filters->search }}"
                       placeholder="Пошук студента..."
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
            <div class="min-w-[180px]">
                <select name="course_id" x-model="courseId" @change="loadModules()"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі курси</option>
                    @foreach($viewModel->courses() as $course)
                        <option value="{{ $course->id }}" @selected($viewModel->filters->course_id == $course->id)>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[180px]">
                <select name="module_id" x-model="moduleId" @change="loadLessons()" :disabled="!courseId || loading"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white disabled:opacity-50">
                    <option value="">Всі модулі</option>
                    <template x-for="module in modules" :key="module.id">
                        <option :value="module.id" x-text="module.name"></option>
                    </template>
                </select>
            </div>
            <div class="min-w-[180px]">
                <select name="lesson_id" x-model="lessonId" @change="loadQuizzes()" :disabled="!moduleId || loading"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white disabled:opacity-50">
                    <option value="">Всі уроки</option>
                    <template x-for="lesson in lessons" :key="lesson.id">
                        <option :value="lesson.id" x-text="lesson.name"></option>
                    </template>
                </select>
            </div>
            <div class="min-w-[180px]">
                <select name="quiz_id" x-model="quizId" :disabled="!lessonId || loading"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white disabled:opacity-50">
                    <option value="">{{ $viewModel->currentTab() === 'surveys' ? 'Всі опитування' : 'Всі квізи' }}</option>
                    <template x-for="quiz in quizzes" :key="quiz.id">
                        <option :value="quiz.id" x-text="quiz.title"></option>
                    </template>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
            @if($viewModel->filters->isFiltered())
                <a href="{{ route('admin.quiz-results.index', ['tab' => $viewModel->currentTab(), 'status' => $viewModel->currentStatus()]) }}"
                   class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
            <a href="{{ route('admin.quiz-results.export', request()->query()) }}"
               class="ml-auto inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Експорт
            </a>
        </div>
    </form>

    @if($viewModel->hasNoAttempts())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">
                {{ $viewModel->currentTab() === 'surveys' ? 'Немає пройдених опитувань' : 'Немає пройдених квізів' }}
            </h3>
            <p class="mt-2 text-gray-500">
                {{ $viewModel->currentTab() === 'surveys' ? 'Студенти ще не проходили опитування' : 'Студенти ще не проходили квізи' }}
            </p>
        </div>
    @elseif($viewModel->attempts()->isEmpty())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Нічого не знайдено</h3>
            <p class="mt-2 text-gray-500">Спробуйте змінити фільтри</p>
        </div>
    @else
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Студент</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">
                        {{ $viewModel->currentTab() === 'surveys' ? 'Опитування' : 'Квіз' }}
                    </th>
                    @if($viewModel->currentTab() === 'quizzes')
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Бали</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">%</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Статус</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Спроба</th>
                    @endif
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">
                        {{ $viewModel->currentTab() === 'surveys' ? 'Завершено' : 'Дата' }}
                    </th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($viewModel->attempts() as $attempt)
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.quiz-attempts.show', $attempt) }}'">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-medium">
                                    {{ $attempt->student->initials }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $attempt->student->name }} {{ $attempt->student->surname }}</p>
                                    <p class="text-sm text-gray-500">{{ $attempt->student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ $attempt->quiz->title }}</p>
                                <p class="text-sm text-gray-500 truncate max-w-md">
                                    {{ $attempt->quiz->quizzable->module->course->name }} / {{ $attempt->quiz->quizzable->module->name }} / {{ $attempt->quiz->quizzable->name }}
                                </p>
                            </div>
                        </td>
                        @if($viewModel->currentTab() === 'quizzes')
                            <td class="px-4 py-3 text-gray-900">
                                {{ number_format($attempt->score, 1) }} / {{ number_format($attempt->max_score, 1) }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $attempt->scorePercentage }}%
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $attempt->passed ? 'bg-success-50 text-success-700' : 'bg-error-50 text-error-700' }}">
                                    {{ $attempt->passed ? 'Пройшов' : 'Не пройшов' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                #{{ $attempt->attempt_number }}
                            </td>
                        @endif
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            <span title="{{ $attempt->completed_at->format('d.m.Y H:i') }}">
                                {{ $attempt->completed_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.quiz-attempts.show', $attempt) }}"
                               class="text-brand-600 hover:text-brand-700 font-medium text-sm"
                               onclick="event.stopPropagation()">
                                Переглянути
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-200">
            {{ $viewModel->attempts()->links() }}
        </div>
    @endif
</div>
@endsection
