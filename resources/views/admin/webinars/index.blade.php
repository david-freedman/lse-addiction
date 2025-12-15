@extends('admin.layouts.admin')

@section('title', 'Вебінари')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Вебінари</h1>
    <a href="{{ route('admin.webinars.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Створити вебінар
    </a>
</div>

{{-- Filters --}}
<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4" x-data="{ showFilters: {{ $viewModel->isFiltered() ? 'true' : 'false' }} }">
    <form method="GET" action="{{ route('admin.webinars.index') }}">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $viewModel->filters()->search }}"
                    placeholder="Пошук по назві вебінару..."
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>
            <button type="button" @click="showFilters = !showFilters" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Фільтри
                @if($viewModel->isFiltered())
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand-500 text-xs text-white">!</span>
                @endif
            </button>
            <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Шукати
            </button>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.webinars.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">
                    Скинути
                </a>
            @endif
        </div>

        <div x-show="showFilters" x-collapse class="mt-4 grid grid-cols-1 gap-4 border-t border-gray-200 pt-4 md:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Статус</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі статуси</option>
                    @foreach($viewModel->statuses() as $status)
                        <option value="{{ $status->value }}" {{ $viewModel->filters()->status?->value === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Викладач</label>
                <select name="teacher_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі викладачі</option>
                    @foreach($viewModel->teachers() as $teacher)
                        <option value="{{ $teacher->id }}" {{ $viewModel->filters()->teacher_id == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Дата від</label>
                <input
                    type="date"
                    name="date_from"
                    value="{{ $viewModel->filters()->date_from?->format('Y-m-d') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Дата до</label>
                <input
                    type="date"
                    name="date_to"
                    value="{{ $viewModel->filters()->date_to?->format('Y-m-d') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>
        </div>
    </form>
</div>

@if($viewModel->hasNoWebinars())
    <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        <p class="mt-4 text-gray-600">Вебінарів ще немає.</p>
    </div>
@else
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Назва</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Викладач</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата/Час</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Учасники</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ціна</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Дії</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($viewModel->webinars() as $webinar)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.webinars.show', $webinar) }}" class="font-medium text-brand-600 hover:text-brand-700 hover:underline">
                                    {{ $webinar->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $webinar->teacher?->full_name ?? 'Не вказано' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                <div class="text-sm">{{ $webinar->starts_at->format('d.m.Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $webinar->starts_at->format('H:i') }} ({{ $webinar->duration_minutes }} хв)</div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <a href="{{ route('admin.webinars.show', $webinar) }}" class="text-brand-600 hover:text-brand-700 hover:underline">
                                    {{ $webinar->active_registrations_count ?? 0 }}
                                    @if($webinar->max_participants)
                                        / {{ $webinar->max_participants }}
                                    @endif
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($webinar->price > 0)
                                    <span class="font-medium text-gray-900">{{ number_format($webinar->price, 0, ',', ' ') }} ₴</span>
                                @else
                                    <span class="text-success-600 font-medium">Безкоштовно</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = match($webinar->status->color()) {
                                        'green' => 'bg-success-100 text-success-700',
                                        'gray' => 'bg-gray-100 text-gray-700',
                                        'blue' => 'bg-brand-100 text-brand-700',
                                        'purple' => 'bg-purple-100 text-purple-700',
                                        'red' => 'bg-error-100 text-error-700',
                                        'orange' => 'bg-warning-100 text-warning-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                    {{ $webinar->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.webinars.edit', $webinar) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50">
                                        Редагувати
                                    </a>
                                    <form action="{{ route('admin.webinars.destroy', $webinar) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цей вебінар?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-error-600 transition hover:bg-error-50">
                                            Видалити
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $viewModel->webinars()->links() }}
    </div>
@endif
@endsection
