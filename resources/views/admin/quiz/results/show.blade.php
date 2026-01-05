@extends('admin.layouts.admin')

@section('title', 'Деталі спроби')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.quizzes.results', $viewModel->attempt()->quiz) }}" class="text-sm text-gray-500 hover:text-gray-700">
        ← Назад до результатів
    </a>
    <h1 class="text-title-xl font-bold text-gray-900 mt-1">Деталі спроби</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Інформація про спробу</h3>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-gray-500">Студент</dt>
                <dd class="font-medium text-gray-900">{{ $viewModel->attempt()->student->full_name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Email</dt>
                <dd class="text-gray-900">{{ $viewModel->attempt()->student->email }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Номер спроби</dt>
                <dd class="font-medium text-gray-900">#{{ $viewModel->attempt()->attempt_number }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Дата</dt>
                <dd class="text-gray-900">{{ $viewModel->attempt()->completed_at?->format('d.m.Y H:i') }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Час проходження</dt>
                <dd class="text-gray-900">{{ gmdate('i:s', $viewModel->attempt()->time_spent_seconds) }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Прохідний бал</dt>
                <dd class="text-gray-900">{{ $viewModel->attempt()->quiz->passing_score }}%</dd>
            </div>
        </dl>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Результат</h3>
        <div class="text-center">
            <p class="text-4xl font-bold {{ $viewModel->attempt()->passed ? 'text-success-600' : 'text-error-600' }}">
                {{ $viewModel->attempt()->score_percentage }}%
            </p>
            <p class="text-gray-500 mt-1">
                {{ $viewModel->attempt()->score }}/{{ $viewModel->attempt()->max_score }} балів
            </p>
            <p class="mt-4">
                @if($viewModel->attempt()->passed)
                    <span class="inline-flex items-center rounded-full bg-success-50 px-4 py-2 text-sm font-medium text-success-700">
                        ✓ Здано
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-error-50 px-4 py-2 text-sm font-medium text-error-700">
                        ✗ Не здано
                    </span>
                @endif
            </p>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Відповіді на питання</h3>

    <div class="space-y-4">
        @foreach($viewModel->questionsWithAnswers() as $index => $item)
            <div class="rounded-lg border {{ $item['is_correct'] ? 'border-success-200 bg-success-50' : 'border-error-200 bg-error-50' }} p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $item['is_correct'] ? 'bg-success-200 text-success-700' : 'bg-error-200 text-error-700' }} text-sm font-medium">
                            {{ $index + 1 }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white px-2.5 py-0.5 text-xs font-medium text-gray-600">
                            {{ $item['question']->type->label() }}
                        </span>
                    </div>
                    <span class="text-sm font-medium {{ $item['is_correct'] ? 'text-success-700' : 'text-error-700' }}">
                        {{ $item['points_earned'] }}/{{ $item['max_points'] }} {{ trans_choice('бал|бали|балів', $item['max_points']) }}
                    </span>
                </div>

                <p class="text-gray-900 font-medium mb-3">{{ $item['question']->question_text }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Відповідь студента:</p>
                        <p class="{{ $item['is_correct'] ? 'text-success-700' : 'text-error-700' }} font-medium">
                            {{ $item['student_answer'] }}
                        </p>
                    </div>
                    @if(!$item['is_correct'])
                        <div>
                            <p class="text-gray-500 mb-1">Правильна відповідь:</p>
                            <p class="text-success-700 font-medium">{{ $item['correct_answer'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
