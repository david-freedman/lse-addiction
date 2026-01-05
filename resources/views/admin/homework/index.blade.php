@extends('admin.layouts.admin')

@section('title', 'Перевірка домашніх робіт')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Перевірка домашніх робіт</h1>
    <p class="text-gray-500">Роботи студентів на перевірку</p>
</div>

@php $counts = $viewModel->statusCounts(); @endphp
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('admin.homework.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
       class="rounded-xl border px-4 py-2.5 text-sm font-medium transition
           {{ $viewModel->currentStatus() === 'pending' || $viewModel->currentStatus() === null
               ? 'border-warning-500 bg-warning-50 text-warning-700'
               : 'border-gray-200 bg-white text-gray-700 hover:border-warning-300' }}">
        На перевірку
        <span class="ml-1.5 rounded-full bg-warning-100 px-2 py-0.5 text-xs font-semibold text-warning-700">
            {{ $counts['pending'] }}
        </span>
    </a>
    <a href="{{ route('admin.homework.index', array_merge(request()->except('status', 'page'), ['status' => 'revision_requested'])) }}"
       class="rounded-xl border px-4 py-2.5 text-sm font-medium transition
           {{ $viewModel->currentStatus() === 'revision_requested'
               ? 'border-info-500 bg-info-50 text-info-700'
               : 'border-gray-200 bg-white text-gray-700 hover:border-info-300' }}">
        Потребує доопрацювання
        <span class="ml-1.5 rounded-full bg-info-100 px-2 py-0.5 text-xs font-semibold text-info-700">
            {{ $counts['revision_requested'] }}
        </span>
    </a>
    <a href="{{ route('admin.homework.index', array_merge(request()->except('status', 'page'), ['status' => 'reviewed'])) }}"
       class="rounded-xl border px-4 py-2.5 text-sm font-medium transition
           {{ $viewModel->currentStatus() === 'reviewed'
               ? 'border-success-500 bg-success-50 text-success-700'
               : 'border-gray-200 bg-white text-gray-700 hover:border-success-300' }}">
        Перевірено
        <span class="ml-1.5 rounded-full bg-success-100 px-2 py-0.5 text-xs font-semibold text-success-700">
            {{ $counts['reviewed'] }}
        </span>
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white mb-6"
     x-data="{
        courseId: {{ $viewModel->filters->course_id ?? 'null' }},
        moduleId: {{ $viewModel->filters->module_id ?? 'null' }},
        lessonId: {{ $viewModel->filters->lesson_id ?? 'null' }},
        modules: {{ json_encode($viewModel->modules()) }},
        lessons: {{ json_encode($viewModel->lessons()) }},
        loading: false,

        async loadModules() {
            if (!this.courseId) {
                this.modules = [];
                this.moduleId = null;
                this.lessons = [];
                this.lessonId = null;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/admin/homework/modules/${this.courseId}`);
                this.modules = await response.json();
                this.moduleId = null;
                this.lessons = [];
                this.lessonId = null;
            } finally {
                this.loading = false;
            }
        },

        async loadLessons() {
            if (!this.moduleId) {
                this.lessons = [];
                this.lessonId = null;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/admin/homework/lessons/${this.moduleId}`);
                this.lessons = await response.json();
                this.lessonId = null;
            } finally {
                this.loading = false;
            }
        }
     }">
    <form method="GET" class="p-4 border-b border-gray-200">
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
                <select name="lesson_id" x-model="lessonId" :disabled="!moduleId || loading"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white disabled:opacity-50">
                    <option value="">Всі уроки</option>
                    <template x-for="lesson in lessons" :key="lesson.id">
                        <option :value="lesson.id" x-text="lesson.name"></option>
                    </template>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.homework.index', ['status' => $viewModel->currentStatus()]) }}"
                   class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
            <a href="{{ route('admin.homework.export', request()->query()) }}"
               class="ml-auto inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Експорт
            </a>
        </div>
    </form>

    @if($viewModel->hasNoSubmissions())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Немає зданих робіт</h3>
            <p class="mt-2 text-gray-500">Поки студенти не здавали домашніх завдань</p>
        </div>
    @elseif($viewModel->submissions()->isEmpty())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">
                @if($viewModel->currentStatus() === 'pending')
                    Всі роботи перевірено
                @else
                    Нічого не знайдено
                @endif
            </h3>
            <p class="mt-2 text-gray-500">
                @if($viewModel->currentStatus() === 'pending')
                    Немає робіт, що очікують на перевірку
                @else
                    Спробуйте змінити фільтри
                @endif
            </p>
        </div>
    @else
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Студент</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Домашнє завдання</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Статус</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Здано</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($viewModel->submissions() as $submission)
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.homework.submissions.show', $submission) }}'">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-medium">
                                    {{ $submission->student->initials }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $submission->student->name }} {{ $submission->student->surname }}</p>
                                    <p class="text-sm text-gray-500">{{ $submission->student->email }}</p>
                                    @if($submission->attempt_number > 1)
                                        <span class="text-xs text-gray-400">Спроба #{{ $submission->attempt_number }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-medium text-gray-900">{{ $submission->homework->lesson->name }}</span>
                                    @if($submission->homework->is_required)
                                        <span class="inline-flex items-center rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-700">Обов'язкове</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 truncate max-w-md">
                                    {{ $submission->homework->lesson->module->course->name }} / {{ $submission->homework->lesson->module->name }}
                                </p>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium @switch($submission->status->value) @case('pending') bg-warning-50 text-warning-700 @break @case('revision_requested') bg-info-50 text-info-700 @break @case('approved') bg-success-50 text-success-700 @break @case('rejected') bg-error-50 text-error-700 @break @endswitch">{{ $submission->status->label() }}</span>
                            @if($submission->is_late)
                                <svg class="inline-block w-4 h-4 ml-1 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            <span title="{{ $submission->submitted_at->format('d.m.Y H:i') }}">
                                {{ $submission->submitted_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.homework.submissions.show', $submission) }}"
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
            {{ $viewModel->submissions()->links() }}
        </div>
    @endif
</div>
@endsection
