@extends('admin.layouts.admin')

@section('title', 'Групи студентів')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">Групи студентів</h1>
        <p class="mt-1 text-sm text-gray-500">Всього: {{ $viewModel->totalCount() }}</p>
    </div>
    <a href="{{ route('admin.student-groups.create') }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        + Створити групу
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form method="GET" class="mb-6">
        <div class="flex items-center gap-3">
            <input
                type="text"
                name="search"
                value="{{ $viewModel->filters()->search }}"
                placeholder="Пошук по назві групи..."
                class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            >
            <select name="course_id" class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                <option value="">Всі курси</option>
                @foreach($viewModel->courses() as $course)
                    <option value="{{ $course->id }}" {{ $viewModel->filters()->course_id == $course->id ? 'selected' : '' }}>
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.student-groups.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
        </div>
    </form>

    @if($viewModel->hasNoGroups())
        <div class="py-12 text-center">
            <p class="text-gray-500">Груп не знайдено</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Назва</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Курс</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Студентів</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Створив</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Дата</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Дії</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewModel->groups() as $group)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">{{ $group->name }}</p>
                                @if($group->description)
                                    <p class="text-sm text-gray-500">{{ Str::limit($group->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($group->course)
                                    <span class="text-sm text-gray-900">{{ $group->course->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-900">{{ $group->students_count }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($group->creator)
                                    <span class="text-sm text-gray-900">{{ $group->creator->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $group->created_at->format('d.m.Y') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.student-groups.show', $group) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                        Переглянути
                                    </a>
                                    <a href="{{ route('admin.student-groups.edit', $group) }}" class="text-sm font-medium text-gray-600 hover:text-gray-700">
                                        Редагувати
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $viewModel->groups()->links() }}
        </div>
    @endif
</div>
@endsection
