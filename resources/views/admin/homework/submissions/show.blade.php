@extends('admin.layouts.admin')

@section('title', 'Перегляд роботи')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.homework.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
        ← Назад до списку
    </a>
    <h1 class="text-title-xl font-bold text-gray-900 mt-1">Перегляд роботи</h1>
    <p class="text-gray-500">{{ $viewModel->lesson()->name }}</p>
    <p class="text-sm text-gray-400 mt-1">
        {{ $viewModel->course()->name }} / {{ $viewModel->module()->name }}
    </p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-medium text-lg">
                        {{ $viewModel->student()->initials }}
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $viewModel->student()->name }} {{ $viewModel->student()->surname }}</h2>
                        <p class="text-gray-500">{{ $viewModel->student()->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                        @switch($viewModel->submission->status->value)
                            @case('pending') bg-warning-50 text-warning-700 @break
                            @case('revision_requested') bg-info-50 text-info-700 @break
                            @case('approved') bg-success-50 text-success-700 @break
                            @case('rejected') bg-error-50 text-error-700 @break
                        @endswitch
                    ">
                        {{ $viewModel->submission->status->label() }}
                    </span>
                    @if($viewModel->submission->is_late)
                        <p class="text-sm text-warning-600 mt-1">Здано після дедлайну</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm border-t border-gray-200 pt-4">
                <div>
                    <p class="text-gray-500">Спроба</p>
                    <p class="font-medium text-gray-900">#{{ $viewModel->submission->attempt_number }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Здано</p>
                    <p class="font-medium text-gray-900">{{ $viewModel->submission->submitted_at->format('d.m.Y H:i') }}</p>
                </div>
                @if($viewModel->submission->reviewed_at)
                    <div>
                        <p class="text-gray-500">Перевірено</p>
                        <p class="font-medium text-gray-900">{{ $viewModel->submission->reviewed_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Перевірив</p>
                        <p class="font-medium text-gray-900">{{ $viewModel->submission->reviewer?->name ?? '—' }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($viewModel->submission->text_response)
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Текстова відповідь</h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($viewModel->submission->text_response)) !!}
                </div>
            </div>
        @endif

        @if($viewModel->submission->hasFiles())
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Прикріплені файли ({{ $viewModel->submission->filesCount() }})</h3>
                <div class="space-y-2">
                    @foreach($viewModel->submission->files as $index => $file)
                        <a href="{{ route('admin.homework.submissions.download', [$viewModel->submission, $index]) }}"
                           class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm text-gray-700">{{ basename($file) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($viewModel->submission->feedback)
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Коментар викладача</h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($viewModel->submission->feedback)) !!}
                </div>
            </div>
        @endif

        @if($viewModel->previousAttempts()->isNotEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Попередні спроби</h3>
                <div class="space-y-2">
                    @foreach($viewModel->previousAttempts() as $attempt)
                        <a href="{{ route('admin.homework.submissions.show', $attempt) }}"
                           class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            <div>
                                <span class="text-sm font-medium text-gray-900">Спроба #{{ $attempt->attempt_number }}</span>
                                <span class="text-sm text-gray-500 ml-2">{{ $attempt->submitted_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                @switch($attempt->status->value)
                                    @case('pending') bg-warning-50 text-warning-700 @break
                                    @case('revision_requested') bg-info-50 text-info-700 @break
                                    @case('approved') bg-success-50 text-success-700 @break
                                    @case('rejected') bg-error-50 text-error-700 @break
                                @endswitch
                            ">
                                {{ $attempt->status->label() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="lg:col-span-1">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 sticky top-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Оцінювання</h3>

            <form action="{{ route('admin.homework.submissions.review', $viewModel->submission) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Статус *</label>
                        <select name="status" required
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                            @foreach($viewModel->statuses() as $status)
                                <option value="{{ $status->value }}"
                                    {{ $viewModel->submission->status === $status ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Оцінка (макс. {{ $viewModel->homework()->max_points }})
                        </label>
                        <input type="number" name="score"
                               value="{{ old('score', $viewModel->submission->score) }}"
                               min="0" max="{{ $viewModel->homework()->max_points }}"
                               class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                        @if($viewModel->homework()->passing_score)
                            <p class="mt-1 text-xs text-gray-500">
                                Прохідний: {{ $viewModel->homework()->getPassingScorePoints() }} балів ({{ $viewModel->homework()->passing_score }}%)
                            </p>
                        @endif
                        @error('score')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Коментар</label>
                        <textarea name="feedback" rows="4"
                                  class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">{{ old('feedback', $viewModel->submission->feedback) }}</textarea>
                        @error('feedback')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                        Зберегти оцінку
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
