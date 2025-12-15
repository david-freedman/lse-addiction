@extends('admin.layouts.admin')

@section('title', 'Прогрес: ' . $course->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">{{ $course->name }}</h1>
        <p class="mt-1 text-sm text-gray-500">Статистика прогресу по курсу</p>
    </div>
    <a href="{{ route('admin.progress.dashboard') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
        Назад до статистики
    </a>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Записано</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $viewModel->enrolledCount() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Не розпочали</p>
        <p class="mt-2 text-3xl font-bold text-gray-600">{{ $viewModel->notStartedCount() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">В процесі</p>
        <p class="mt-2 text-3xl font-bold text-blue-600">{{ $viewModel->inProgressCount() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Завершили</p>
        <p class="mt-2 text-3xl font-bold text-green-600">{{ $viewModel->completedCount() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Сер. прогрес</p>
        <p class="mt-2 text-3xl font-bold text-brand-600">{{ $viewModel->averageProgress() }}%</p>
    </div>
</div>

@if($viewModel->hasModules())
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-4 text-lg font-bold text-gray-900">Прогрес по модулях</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Модуль</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Розпочали</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Завершили</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Сер. прогрес</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewModel->moduleStats() as $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">{{ $stat['module']->name }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-900">{{ $stat['total'] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-green-600">{{ $stat['completed'] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-20 overflow-hidden rounded-full bg-gray-200">
                                        <div class="h-full bg-brand-500" style="width: {{ $stat['avg_progress'] }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $stat['avg_progress'] }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <h3 class="mb-4 text-lg font-bold text-gray-900">Прогрес студентів</h3>

    @if($viewModel->hasStudentProgress())
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Студент</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Статус</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Прогрес</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Розпочато</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Завершено</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Дії</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewModel->studentProgress() as $progress)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                @if($progress->student)
                                    <a href="{{ route('admin.students.show', $progress->student) }}" class="font-medium text-gray-900 hover:text-brand-600">
                                        {{ $progress->student->surname }} {{ $progress->student->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                    {{ $progress->status->color() === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $progress->status->color() === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $progress->status->color() === 'gray' ? 'bg-gray-100 text-gray-700' : '' }}
                                ">
                                    {{ $progress->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-20 overflow-hidden rounded-full bg-gray-200">
                                        <div class="h-full bg-brand-500" style="width: {{ $progress->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ round($progress->progress_percentage, 1) }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $progress->started_at?->format('d.m.Y') ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $progress->completed_at?->format('d.m.Y') ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end">
                                    @if($progress->student)
                                        <a href="{{ route('admin.progress.tree', ['course_id' => $course->id, 'student_id' => $progress->student->id]) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                            Детальніше
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $viewModel->studentProgress()->links() }}
        </div>
    @else
        <div class="py-8 text-center">
            <p class="text-gray-500">Немає даних про прогрес студентів</p>
        </div>
    @endif
</div>
@endsection
