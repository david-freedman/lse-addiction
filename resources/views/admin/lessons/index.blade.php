@extends('admin.layouts.admin')

@section('title', 'Уроки модуля: ' . $module->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('admin.modules.index', $course) }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Назад до модулів
        </a>
        <h1 class="text-title-xl font-bold text-gray-900 mt-1">Уроки модуля: {{ $module->name }}</h1>
    </div>
    <a href="{{ route('admin.lessons.create', [$course, $module]) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Додати урок
    </a>
</div>

@if($viewModel->hasNoLessons())
    <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <p class="mt-4 text-gray-600">Уроків ще немає.</p>
    </div>
@else
    <div id="lessons-list" class="space-y-2">
        @foreach($viewModel->lessons() as $lesson)
            <div class="lesson-card flex items-center gap-4 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition" data-id="{{ $lesson->id }}">
                <span class="handle cursor-move text-gray-400 hover:text-gray-600">⋮⋮</span>
                <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-xl">{{ $lesson->type->icon() }}</span>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $lesson->name }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $lesson->type->label() }}
                        @if($lesson->duration_minutes)
                            • {{ $lesson->formatted_duration }}
                        @endif
                        @if($lesson->hasHomework())
                            • <span class="text-amber-600">ДЗ</span>
                        @endif
                    </p>
                </div>
                @php
                    $statusClass = match($lesson->status->color()) {
                        'green' => 'bg-success-100 text-success-700',
                        'gray' => 'bg-gray-100 text-gray-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                    {{ $lesson->status->label() }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.lessons.edit', [$course, $module, $lesson]) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50">
                        Редагувати
                    </a>
                    <form action="{{ route('admin.lessons.destroy', [$course, $module, $lesson]) }}" method="POST" class="inline" onsubmit="return confirm('Видалити урок?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-error-600 transition hover:bg-error-50">
                            Видалити
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const list = document.getElementById('lessons-list');
    if (list) {
        new Sortable(list, {
            handle: '.handle',
            animation: 150,
            onEnd: async function(evt) {
                const ids = [...document.querySelectorAll('.lesson-card')].map(el => el.dataset.id);
                const response = await fetch('{{ route('admin.lessons.reorder', [$course, $module]) }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: ids })
                });
                if (response.ok) {
                    location.reload();
                }
            }
        });
    }
});
</script>
@endpush
