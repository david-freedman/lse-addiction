@extends('admin.layouts.admin')

@section('title', 'Результати квізу')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Результати квізу</h1>
    <p class="text-gray-500">{{ $viewModel->quiz()->title ?? $viewModel->quiz()->quizzable?->name }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-4">
        <p class="text-sm text-gray-500">Всього спроб</p>
        <p class="text-2xl font-bold text-gray-900">{{ $viewModel->statistics()['total_attempts'] }}</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-4">
        <p class="text-sm text-gray-500">Відсоток успішних</p>
        <p class="text-2xl font-bold text-success-600">{{ $viewModel->statistics()['pass_rate'] }}%</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-4">
        <p class="text-sm text-gray-500">Середній бал</p>
        <p class="text-2xl font-bold text-brand-600">{{ $viewModel->statistics()['avg_score'] }}%</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-4">
        <p class="text-sm text-gray-500">Середній час</p>
        <p class="text-2xl font-bold text-gray-900">{{ $viewModel->statistics()['avg_time_formatted'] }}</p>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white mb-6">
    <form method="GET" class="p-4 border-b border-gray-200">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $viewModel->filters()->search }}"
                       placeholder="Пошук студента..."
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
            <div>
                <select name="passed" class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі результати</option>
                    <option value="1" {{ $viewModel->filters()->passed === true ? 'selected' : '' }}>Здані</option>
                    <option value="0" {{ $viewModel->filters()->passed === false ? 'selected' : '' }}>Не здані</option>
                </select>
            </div>
            <div>
                <input type="text" name="date_from" value="{{ $viewModel->filters()->date_from?->format('d.m.Y') }}"
                       placeholder="Від (дд.мм.рррр)"
                       class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
            <div>
                <input type="text" name="date_to" value="{{ $viewModel->filters()->date_to?->format('d.m.Y') }}"
                       placeholder="До (дд.мм.рррр)"
                       class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
            @if($viewModel->filters()->isFiltered())
                <a href="{{ route('admin.quizzes.results', $viewModel->quiz()) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
        </div>
    </form>

    @if($viewModel->attempts()->isEmpty())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Немає спроб</h3>
            <p class="mt-2 text-gray-500">Поки ніхто не проходив цей квіз</p>
        </div>
    @else
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Студент</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Спроба</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Результат</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Статус</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Час</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Дата</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($viewModel->attempts() as $attempt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ $attempt->student->full_name }}</p>
                                <p class="text-sm text-gray-500">{{ $attempt->student->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-900">
                            #{{ $attempt->attempt_number }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900">{{ $attempt->score }}/{{ $attempt->max_score }}</span>
                            <span class="text-gray-500">({{ $attempt->scorePercentage() }}%)</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($attempt->passed)
                                <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-700">
                                    Здано
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-700">
                                    Не здано
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ gmdate('i:s', $attempt->time_spent_seconds) }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $attempt->completed_at?->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.quiz-attempts.show', $attempt) }}"
                               class="text-brand-600 hover:text-brand-700 font-medium text-sm">
                                Деталі
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
