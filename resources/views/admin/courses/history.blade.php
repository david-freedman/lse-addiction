@extends('admin.layouts.admin')

@section('title', 'Історія змін — ' . $course->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Історія змін</h1>
    <a href="{{ route('admin.courses.show', $course) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
        Назад до курсу
    </a>
</div>

<div class="mb-6 rounded-xl border border-gray-200 bg-white p-4">
    <form method="GET" action="{{ route('admin.courses.history', $course) }}" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[150px]">
            <label class="mb-1 block text-xs font-medium text-gray-600">Тип сутності</label>
            <select name="subject_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Всі</option>
                @foreach($viewModel->subjectTypes() as $value => $label)
                    <option value="{{ $value }}" @selected($viewModel->filters()['subject_type'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="mb-1 block text-xs font-medium text-gray-600">Автор зміни</label>
            <select name="performed_by" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Всі</option>
                @foreach($viewModel->admins() as $admin)
                    <option value="{{ $admin->id }}" @selected($viewModel->filters()['performed_by'] == $admin->id)>{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[140px]">
            <label class="mb-1 block text-xs font-medium text-gray-600">Від</label>
            <input type="date" name="date_from" value="{{ $viewModel->filters()['date_from'] }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div class="min-w-[140px]">
            <label class="mb-1 block text-xs font-medium text-gray-600">До</label>
            <input type="date" name="date_to" value="{{ $viewModel->filters()['date_to'] }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Фільтрувати
            </button>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.courses.history', $course) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Скинути
                </a>
            @endif
        </div>
    </form>
</div>

@php $logs = $viewModel->logs(); @endphp

@if($logs->isEmpty())
    <div class="rounded-xl border border-gray-200 bg-white p-12 text-center">
        <p class="text-gray-500">Записів не знайдено</p>
    </div>
@else
    <div class="space-y-4">
            @foreach($logs as $log)
                @php
                    $subjectLabel = match($log->subject_type->value) {
                        'course' => 'Курс',
                        'module' => 'Модуль',
                        'lesson' => 'Урок',
                        'quiz' => 'Квіз',
                        default => $log->subject_type->value,
                    };
                    $activityLabel = match(true) {
                        str_contains($log->activity_type->value, 'created') => 'Створено',
                        str_contains($log->activity_type->value, 'updated') => 'Оновлено',
                        str_contains($log->activity_type->value, 'deleted') => 'Видалено',
                        str_contains($log->activity_type->value, 'reordered') => 'Пересортовано',
                        str_contains($log->activity_type->value, 'question.created') => 'Питання додано',
                        str_contains($log->activity_type->value, 'question.updated') => 'Питання оновлено',
                        str_contains($log->activity_type->value, 'question.deleted') => 'Питання видалено',
                        default => $log->activity_type->value,
                    };
                    $activityColor = match(true) {
                        str_contains($log->activity_type->value, 'created') => 'bg-success-100 text-success-700',
                        str_contains($log->activity_type->value, 'deleted') => 'bg-error-100 text-error-700',
                        str_contains($log->activity_type->value, 'reordered') => 'bg-purple-100 text-purple-700',
                        default => 'bg-brand-100 text-brand-700',
                    };
                    $properties = $log->properties ?? [];
                @endphp

                <div x-data="{ open: false }">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 transition hover:shadow-sm">
                        <div @if(!empty($properties)) @click="open = !open" @endif class="flex items-center justify-between {{ !empty($properties) ? 'cursor-pointer' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex rounded-md px-2 py-1 text-xs font-medium {{ $activityColor }}">
                                    {{ $subjectLabel }}: {{ $activityLabel }}
                                </span>
                                @if($log->performer)
                                    <span class="text-xs text-gray-500">{{ $log->performer->name }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400">{{ $log->created_at->format('d.m.Y H:i') }}</span>
                                @if(!empty($properties))
                                    <div class="rounded p-1 text-gray-400">
                                        <svg class="h-4 w-4 transition" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div x-show="open" x-collapse class="mt-4 border-t border-gray-100 pt-4">
                            @php
                                $fieldLabels = [
                                    'name' => 'Назва', 'description' => 'Опис', 'price' => 'Ціна',
                                    'old_price' => 'Стара ціна', 'discount_percentage' => 'Знижка %',
                                    'teacher_id' => 'Викладач', 'status' => 'Статус', 'type' => 'Тип',
                                    'starts_at' => 'Дата початку', 'label' => 'Мітка',
                                    'is_sequential' => 'Послідовне проходження',
                                    'requires_certificate_approval' => 'Модерація сертифікатів',
                                    'banner' => 'Банер', 'number' => 'Номер курсу',
                                    'unlock_rule' => 'Правило розблокування', 'order' => 'Порядок',
                                    'content' => 'Контент', 'video_url' => 'Відео URL',
                                    'qa_session_url' => 'QA URL', 'duration_minutes' => 'Тривалість (хв)',
                                    'is_final' => 'Підсумковий тест', 'allow_retake_after_pass' => 'Перездача після проходження',
                                    'passing_score' => 'Прохідний бал', 'max_attempts' => 'Макс. спроб',
                                    'time_limit_minutes' => 'Обмеження часу', 'question_text' => 'Текст питання',
                                    'points' => 'Бали',
                                ];
                            @endphp

                            @if(isset($properties['changes']))
                                <div class="space-y-3">
                                    @foreach($properties['changes'] as $field => $change)
                                        @php $fieldLabel = $fieldLabels[$field] ?? $field; @endphp

                                        @if($field === 'tags')
                                            <div>
                                                <span class="text-xs font-medium text-gray-600">Теги:</span>
                                                <div class="mt-1 flex flex-wrap gap-1">
                                                    @foreach($change['added'] ?? [] as $tag)
                                                        <span class="inline-flex rounded-full bg-success-100 px-2 py-0.5 text-xs text-success-700">+ {{ $tag }}</span>
                                                    @endforeach
                                                    @foreach($change['removed'] ?? [] as $tag)
                                                        <span class="inline-flex rounded-full bg-error-100 px-2 py-0.5 text-xs text-error-700">- {{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif($field === 'banner')
                                            <div>
                                                <span class="text-xs font-medium text-gray-600">{{ $fieldLabel }}:</span>
                                                <div class="mt-1 flex gap-4">
                                                    @if($change['old'])
                                                        <div class="text-center">
                                                            <span class="text-xs text-gray-400">Було</span>
                                                            @if(Storage::disk('public')->exists($change['old']))
                                                                <img src="{{ Storage::url($change['old']) }}" class="mt-1 h-20 w-32 rounded object-cover" alt="old">
                                                            @else
                                                                <div class="mt-1 flex h-20 w-32 items-center justify-center rounded bg-gray-100 text-xs text-gray-400">Файл видалено</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if($change['new'])
                                                        <div class="text-center">
                                                            <span class="text-xs text-gray-400">Стало</span>
                                                            @if(Storage::disk('public')->exists($change['new']))
                                                                <img src="{{ Storage::url($change['new']) }}" class="mt-1 h-20 w-32 rounded object-cover" alt="new">
                                                            @else
                                                                <div class="mt-1 flex h-20 w-32 items-center justify-center rounded bg-gray-100 text-xs text-gray-400">Файл видалено</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif(in_array($field, ['description', 'content']) && (strlen($change['old'] ?? '') > 100 || strlen($change['new'] ?? '') > 100))
                                            <div>
                                                <span class="text-xs font-medium text-gray-600">{{ $fieldLabel }}:</span>
                                                <div class="mt-1 grid grid-cols-2 gap-4">
                                                    <div class="rounded border border-error-200 bg-error-50 p-3">
                                                        <span class="mb-1 block text-xs font-medium text-error-600">Було</span>
                                                        <div class="max-h-48 overflow-y-auto text-xs text-gray-700 whitespace-pre-wrap">{{ $change['old'] }}</div>
                                                    </div>
                                                    <div class="rounded border border-success-200 bg-success-50 p-3">
                                                        <span class="mb-1 block text-xs font-medium text-success-600">Стало</span>
                                                        <div class="max-h-48 overflow-y-auto text-xs text-gray-700 whitespace-pre-wrap">{{ $change['new'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-xs font-medium text-gray-600">{{ $fieldLabel }}:</span>
                                                <span class="text-xs text-error-600 line-through">{{ is_bool($change['old'] ?? null) ? ($change['old'] ? 'Так' : 'Ні') : $change['old'] }}</span>
                                                <svg class="h-3 w-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                                <span class="text-xs text-success-600 font-medium">{{ is_bool($change['new'] ?? null) ? ($change['new'] ? 'Так' : 'Ні') : $change['new'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            @if(isset($properties['attributes']))
                                <div class="space-y-1">
                                    @foreach($properties['attributes'] as $field => $value)
                                        @if(!is_array($value))
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-xs font-medium text-gray-600">{{ $fieldLabels[$field] ?? $field }}:</span>
                                                <span class="text-xs text-gray-800">{{ is_bool($value) ? ($value ? 'Так' : 'Ні') : $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            @if(isset($properties['reorder']))
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="mb-1 block text-xs font-medium text-gray-500">Було:</span>
                                        <ol class="list-decimal list-inside space-y-0.5">
                                            @foreach($properties['reorder']['old'] as $item)
                                                <li class="text-xs text-gray-600">{{ $item['name'] }}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div>
                                        <span class="mb-1 block text-xs font-medium text-gray-500">Стало:</span>
                                        <ol class="list-decimal list-inside space-y-0.5">
                                            @foreach($properties['reorder']['new'] as $item)
                                                <li class="text-xs text-gray-600">{{ $item['name'] }}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            @endif

                            @if(isset($properties['questions']))
                                <div class="space-y-2">
                                    @if(isset($properties['questions']['added']))
                                        @foreach($properties['questions']['added'] as $q)
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex rounded-full bg-success-100 px-2 py-0.5 text-xs text-success-700">+</span>
                                                <span class="text-xs text-gray-700">{{ $q['text'] ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(isset($properties['questions']['removed']))
                                        @foreach($properties['questions']['removed'] as $q)
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex rounded-full bg-error-100 px-2 py-0.5 text-xs text-error-700">-</span>
                                                <span class="text-xs text-gray-700 line-through">{{ $q['text'] ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(isset($properties['questions']['updated']))
                                        @foreach($properties['questions']['updated'] as $q)
                                            <div class="space-y-1">
                                                @foreach($q['changes'] ?? [] as $qField => $qChange)
                                                    <div class="flex items-baseline gap-2">
                                                        <span class="text-xs font-medium text-gray-600">{{ $fieldLabels[$qField] ?? $qField }}:</span>
                                                        <span class="text-xs text-error-600 line-through">{{ $qChange['old'] }}</span>
                                                        <svg class="h-3 w-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                                        <span class="text-xs text-success-600 font-medium">{{ $qChange['new'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
@endif
@endsection
