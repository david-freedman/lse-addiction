@extends('admin.layouts.admin')

@section('title', 'Студенти')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">Студенти</h1>
        <p class="mt-1 text-sm text-gray-500">Всього: {{ $viewModel->totalCount() }}</p>
    </div>
    <a href="{{ route('admin.students.create') }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        + Додати студента
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form method="GET" class="mb-6" x-data="{ showFilters: {{ $viewModel->isFiltered() ? 'true' : 'false' }} }">
        <div class="mb-4 flex items-center gap-3">
            <input
                type="text"
                name="search"
                value="{{ $viewModel->filters()->search }}"
                placeholder="Пошук по імені, email, телефону..."
                class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            >
            <button type="button" @click="showFilters = !showFilters" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <span x-show="!showFilters">Показати фільтри</span>
                <span x-show="showFilters">Сховати фільтри</span>
            </button>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.students.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
        </div>

        <div x-show="showFilters" x-transition class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Курс</label>
                <select name="course_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі курси</option>
                    @foreach($viewModel->courses() as $course)
                        <option value="{{ $course->id }}" {{ $viewModel->filters()->course_id == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Дата створення (від)</label>
                <input
                    type="text"
                    name="created_from"
                    x-datepicker
                    value="{{ $viewModel->filters()->created_from?->format('d.m.Y') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Дата створення (до)</label>
                <input
                    type="text"
                    name="created_to"
                    x-datepicker
                    value="{{ $viewModel->filters()->created_to?->format('d.m.Y') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    name="is_new"
                    id="is_new"
                    value="1"
                    {{ $viewModel->filters()->is_new ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                >
                <label for="is_new" class="ml-2 text-sm font-medium text-gray-700">Тільки нові (останні 7 днів)</label>
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    name="only_deleted"
                    id="only_deleted"
                    value="1"
                    {{ $viewModel->filters()->only_deleted ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                >
                <label for="only_deleted" class="ml-2 text-sm font-medium text-gray-700">Показати видалених</label>
            </div>
        </div>
    </form>

    @if($viewModel->hasNoStudents())
        <div class="py-12 text-center">
            <p class="text-gray-500">Студентів не знайдено</p>
        </div>
    @else
        <div x-data="{ selectedStudents: [] }">
            <div x-show="selectedStudents.length > 0" class="mb-4 flex items-center gap-3 rounded-lg bg-brand-50 p-3">
                <span class="text-sm font-medium text-brand-900">Вибрано: <span x-text="selectedStudents.length"></span></span>
                <form action="{{ route('admin.students.bulk-delete') }}" method="POST" class="inline" onsubmit="return confirm('Видалити вибраних студентів?')">
                    @csrf
                    <template x-for="id in selectedStudents">
                        <input type="hidden" name="student_ids[]" :value="id">
                    </template>
                    <button type="submit" class="rounded-lg bg-error-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-error-600">
                        Видалити вибраних
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input
                                    type="checkbox"
                                    @change="selectedStudents = $event.target.checked ? [{{ $viewModel->students()->pluck('id')->implode(',') }}] : []"
                                    class="h-4 w-4 rounded border-gray-300 text-brand-600"
                                >
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">ПІБ</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Email / Телефон</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Курси</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Прогрес</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Останній вхід</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Реєстрація</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Дії</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($viewModel->students() as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <input
                                        type="checkbox"
                                        :value="{{ $student->id }}"
                                        x-model="selectedStudents"
                                        class="h-4 w-4 rounded border-gray-300 text-brand-600"
                                    >
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($student->profile_photo)
                                            <img src="{{ Storage::url($student->profile_photo) }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-bold text-gray-600">
                                                {{ mb_substr($student->name, 0, 1) }}{{ mb_substr($student->surname, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $student->name }} {{ $student->surname }}</p>
                                            @if($student->trashed())
                                                <span class="text-xs text-danger-600">Видалений</span>
                                            @elseif($student->isNew())
                                                <span class="text-xs text-success-600">Новий</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-900">{{ $student->email->value }}</p>
                                    <p class="text-sm text-gray-500">{{ $student->phone->value }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-900">{{ $student->courses_count }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($student->courses->isNotEmpty())
                                        @php
                                            $totalLessons = $student->courses->sum('pivot.total_lessons');
                                            $completedLessons = $student->courses->sum('pivot.lessons_completed');
                                            $percentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-20 overflow-hidden rounded-full bg-gray-200">
                                                <div class="h-full bg-brand-500" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-600">{{ $percentage }}%</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-600">{{ $student->last_login_at?->format('d.m.Y H:i') ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-600">{{ $student->created_at->format('d.m.Y') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.students.show', $student) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                            Переглянути
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $viewModel->students()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
