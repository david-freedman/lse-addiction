@extends('admin.layouts.admin')

@section('title', 'Статистика')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Статистика</h1>
    <form method="GET" class="flex items-center gap-2">
        <select name="period" onchange="this.form.submit()" class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm">
            @foreach($viewModel->periods() as $value => $label)
                <option value="{{ $value }}" {{ $viewModel->period() === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Всього студентів</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $viewModel->totalStudents() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Активних студентів</p>
        <p class="mt-2 text-3xl font-bold text-blue-600">{{ $viewModel->activeStudents() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Завершено курсів</p>
        <p class="mt-2 text-3xl font-bold text-green-600">{{ $viewModel->completedCourses() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Середній прогрес</p>
        <p class="mt-2 text-3xl font-bold text-brand-600">{{ $viewModel->averageCompletion() }}%</p>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Спроб тестів</p>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $viewModel->totalQuizAttempts() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Середній бал</p>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $viewModel->averageQuizScore() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Відсоток успішних</p>
        <p class="mt-2 text-2xl font-bold text-green-600">{{ $viewModel->quizPassRate() }}%</p>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <h3 class="mb-4 text-lg font-bold text-gray-900">Статистика по курсах</h3>

    @if($viewModel->hasCourseStats())
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Курс</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Записано</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">В процесі</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Завершили</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Сер. прогрес</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Дії</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewModel->courseStats() as $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">{{ $stat['course']->name }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-900">{{ $stat['enrolled'] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-blue-600">{{ $stat['in_progress'] }}</span>
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
                            <td class="px-4 py-3">
                                <div class="flex justify-end">
                                    <a href="{{ route('admin.progress.course', $stat['course']) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                        Детальніше
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="py-8 text-center">
            <p class="text-gray-500">Немає даних для відображення</p>
        </div>
    @endif
</div>
@endsection
