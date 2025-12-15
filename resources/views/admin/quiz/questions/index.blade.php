@extends('admin.layouts.admin')

@section('title', 'Питання квізу')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.lessons.edit', [$viewModel->course(), $viewModel->module(), $viewModel->lesson()]) }}" class="text-sm text-gray-500 hover:text-gray-700">
        ← Назад до уроку
    </a>
    <h1 class="text-title-xl font-bold text-gray-900 mt-1">Питання квізу</h1>
    <p class="text-gray-500">{{ $viewModel->lesson()->name }}</p>
</div>

<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4 text-sm text-gray-600">
        <span>Всього питань: <strong>{{ $viewModel->questionsCount() }}</strong></span>
        <span>Всього балів: <strong>{{ $viewModel->totalPoints() }}</strong></span>
    </div>
    <a href="{{ route('admin.quiz.questions.create', [$viewModel->course(), $viewModel->module(), $viewModel->lesson()]) }}"
       class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        + Додати питання
    </a>
</div>

@if($viewModel->questions()->isEmpty())
    <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Немає питань</h3>
        <p class="mt-2 text-gray-500">Додайте перше питання до квізу</p>
        <a href="{{ route('admin.quiz.questions.create', [$viewModel->course(), $viewModel->module(), $viewModel->lesson()]) }}"
           class="mt-4 inline-block rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
            Додати питання
        </a>
    </div>
@else
    <div class="space-y-4" x-data="questionsList()" x-init="init()">
        @foreach($viewModel->questions() as $question)
            <div class="rounded-2xl border border-gray-200 bg-white p-4 hover:shadow-sm transition"
                 data-question-id="{{ $question->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-sm font-medium text-gray-600 cursor-move" title="Перетягніть для зміни порядку">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">
                                    {{ $question->type->label() }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $question->points }} {{ trans_choice('бал|бали|балів', $question->points) }}</span>
                            </div>
                            <p class="text-gray-900">{{ Str::limit($question->question_text, 150) }}</p>
                            <p class="text-sm text-gray-500 mt-1">Відповідей: {{ $question->answers->count() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.quiz.questions.edit', [$viewModel->course(), $viewModel->module(), $viewModel->lesson(), $question]) }}"
                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            Редагувати
                        </a>
                        <form action="{{ route('admin.quiz.questions.destroy', [$viewModel->course(), $viewModel->module(), $viewModel->lesson(), $question]) }}"
                              method="POST"
                              onsubmit="return confirm('Видалити це питання?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-error-300 px-3 py-1.5 text-sm font-medium text-error-600 transition hover:bg-error-50">
                                Видалити
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@push('scripts')
<script>
function questionsList() {
    return {
        init() {
            // Future: implement drag-drop reordering with Sortable.js
        }
    }
}
</script>
@endpush
@endsection
