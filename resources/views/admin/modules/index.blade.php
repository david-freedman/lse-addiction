@extends('admin.layouts.admin')

@section('title', 'Модулі курсу: ' . $course->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('admin.courses.show', $course) }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Назад до курсу
        </a>
        <h1 class="text-title-xl font-bold text-gray-900 mt-1">Модулі курсу</h1>
        <p class="text-gray-500">{{ $course->name }}</p>
    </div>
    <a href="{{ route('admin.modules.create', $course) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Додати модуль
    </a>
</div>

@if($modules->isEmpty())
    <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <p class="mt-4 text-gray-600">Модулів ще немає.</p>
    </div>
@else
    <div id="modules-list" class="space-y-2">
        @foreach($modules as $module)
            <div class="module-card flex items-center gap-4 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition" data-id="{{ $module->id }}">
                <span class="handle cursor-move text-gray-400 hover:text-gray-600">⋮⋮</span>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $module->order + 1 }}. {{ $module->name }}</p>
                    <p class="text-sm text-gray-500">{{ $module->lessons_count }} уроків</p>
                </div>
                @php
                    $statusClass = match($module->status->color()) {
                        'green' => 'bg-success-100 text-success-700',
                        'purple' => 'bg-purple-100 text-purple-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                    {{ $module->status->label() }}
                </span>
                @if($module->has_final_test)
                    <span class="inline-flex rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700">Тест</span>
                @endif
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.lessons.index', [$course, $module]) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50">
                        Уроки
                    </a>
                    <a href="{{ route('admin.modules.edit', [$course, $module]) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-100">
                        Редагувати
                    </a>
                    <form action="{{ route('admin.modules.destroy', [$course, $module]) }}" method="POST" class="inline" onsubmit="return confirm('Видалити модуль? Всі уроки модуля також будуть видалені.')">
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
    const list = document.getElementById('modules-list');
    if (list) {
        new Sortable(list, {
            handle: '.handle',
            animation: 150,
            onEnd: async function(evt) {
                const ids = [...document.querySelectorAll('.module-card')].map(el => el.dataset.id);
                await fetch('{{ route('admin.modules.reorder', $course) }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: ids })
                });
            }
        });
    }
});
</script>
@endpush
