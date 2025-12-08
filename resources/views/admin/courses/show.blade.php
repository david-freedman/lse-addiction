@extends('admin.layouts.admin')

@section('title', $viewModel->name())

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">{{ $viewModel->name() }}</h1>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.courses.edit', $course) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Редагувати
        </a>
        <a href="{{ route('admin.courses.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            Назад до списку
        </a>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white overflow-hidden mb-6">
    @if($viewModel->bannerUrl())
        <div class="w-full aspect-[21/9] overflow-hidden bg-gray-100">
            <img src="{{ $viewModel->bannerUrl() }}" alt="{{ $viewModel->name() }}" class="h-full w-full object-cover">
        </div>
    @endif

    <div class="p-6">
        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <label class="mb-2 block text-xs font-medium text-gray-500 uppercase">Статус</label>
                @php
                    $statusClass = match($course->status->color()) {
                        'green' => 'bg-success-100 text-success-700',
                        'gray' => 'bg-gray-100 text-gray-700',
                        'blue' => 'bg-brand-100 text-brand-700',
                        'purple' => 'bg-purple-100 text-purple-700',
                        'orange' => 'bg-warning-100 text-warning-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $statusClass }}">
                    {{ $viewModel->statusLabel() }}
                </span>
            </div>

            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <label class="mb-2 block text-xs font-medium text-gray-500 uppercase">Ціна</label>
                <div class="flex items-center gap-2">
                    <p class="text-lg font-semibold text-gray-900">{{ $viewModel->price() }} ₴</p>
                    @if($course->has_discount)
                        <span class="text-sm text-gray-500 line-through">{{ number_format($course->old_price, 0, ',', ' ') }} ₴</span>
                    @endif
                </div>
                @if($course->has_discount)
                    <p class="mt-1 text-xs text-success-600">Знижка: {{ $course->formatted_discount_amount }}</p>
                @endif
            </div>

            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <label class="mb-2 block text-xs font-medium text-gray-500 uppercase">Викладач</label>
                <p class="text-sm font-medium text-gray-900">{{ $viewModel->teacherName() }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <label class="mb-2 block text-xs font-medium text-gray-500 uppercase">Записано учасників</label>
                <p class="text-lg font-semibold text-gray-900">{{ $viewModel->enrolledCount() }}</p>
            </div>
        </div>

        <div class="mb-6">
            <label class="mb-3 block text-sm font-medium text-gray-700">Опис</label>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700 leading-relaxed">
                {!! nl2br(e($viewModel->description())) !!}
            </div>
        </div>

        @if(!empty($viewModel->tags()))
            <div class="mb-6">
                <label class="mb-3 block text-sm font-medium text-gray-700">Теги</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($viewModel->tags() as $tag)
                        <span class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-sm text-brand-700">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="border-t border-gray-200 pt-4">
            <label class="mb-2 block text-xs font-medium text-gray-500 uppercase">Створено</label>
            <p class="text-sm text-gray-700">{{ $viewModel->createdAt() }}</p>
        </div>
    </div>
</div>

{{-- Modules/Lessons Tree --}}
<div class="rounded-2xl border border-gray-200 bg-white overflow-hidden mb-6">
    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Структура курсу</h2>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span>{{ $statistics['modules_count'] }} модулів</span>
                <span>{{ $statistics['lessons_count'] }} уроків</span>
            </div>
        </div>
        <a href="{{ route('admin.courses.edit', $course) }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Керувати модулями
        </a>
    </div>

    <div class="p-6">
        @if(count($tree) > 0)
            <div class="space-y-3">
                @foreach($tree as $module)
                    <div x-data="{ expanded: true }" class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="flex items-center gap-3 p-4 bg-gray-50 cursor-pointer hover:bg-gray-100 transition"
                             @click="expanded = !expanded">
                            <button type="button" class="text-gray-500 focus:outline-none">
                                <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <span class="font-medium text-gray-900">Модуль {{ $module['order'] + 1 }}: {{ $module['name'] }}</span>

                            @php
                                $moduleStatusClass = match($module['status']->color()) {
                                    'green' => 'bg-success-100 text-success-700',
                                    'purple' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $moduleStatusClass }}">
                                {{ $module['status']->label() }}
                            </span>

                            <span class="text-sm text-gray-500">{{ $module['lessons_count'] }} уроків</span>

                            @if($module['has_final_test'])
                                <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-700">
                                    Має тест
                                </span>
                            @endif

                            <div class="ml-auto">
                                <a href="{{ route('admin.lessons.index', [$course, $module['id']]) }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium" @click.stop>
                                    Уроки →
                                </a>
                            </div>
                        </div>

                        <div x-show="expanded" x-collapse class="border-t border-gray-200">
                            @if(count($module['lessons']) > 0)
                                <div class="divide-y divide-gray-100">
                                    @foreach($module['lessons'] as $lesson)
                                        <div class="flex items-center gap-3 px-4 py-3 pl-12 hover:bg-gray-50 transition">
                                            <span class="text-lg">{{ $lesson['type']->icon() }}</span>
                                            <span class="text-sm text-gray-900">{{ $lesson['name'] }}</span>

                                            @php
                                                $lessonStatusClass = match($lesson['status']->color()) {
                                                    'green' => 'bg-success-100 text-success-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $lessonStatusClass }}">
                                                {{ $lesson['status']->label() }}
                                            </span>

                                            <span class="text-xs text-gray-500">{{ $lesson['type']->label() }}</span>

                                            @if($lesson['duration'])
                                                <span class="text-xs text-gray-400">{{ $lesson['duration'] }}</span>
                                            @endif

                                            <a href="{{ route('admin.lessons.edit', [$course, $module['id'], $lesson['id']]) }}" class="ml-auto text-xs text-brand-600 hover:text-brand-700">
                                                Редагувати
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    Уроків ще немає.
                                    <a href="{{ route('admin.lessons.create', [$course, $module['id']]) }}" class="text-brand-600 hover:text-brand-700 font-medium">
                                        Додати урок
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="mt-4 text-gray-600">Модулів ще немає</p>
                <a href="{{ route('admin.courses.edit', $course) }}" class="mt-2 inline-block text-brand-600 hover:text-brand-700 font-medium">
                    Додати модулі
                </a>
            </div>
        @endif
    </div>
</div>

@if($course->students->isNotEmpty())
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Записані учасники ({{ $course->students->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ім'я</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата запису</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($course->students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $student->name }} {{ $student->surname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->email->value }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->pivot->enrolled_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->pivot->status === 'active')
                                    <span class="inline-flex rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-700">Активний</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Скасовано</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <p class="mt-4 text-gray-600">Учасників ще немає</p>
    </div>
@endif
@endsection
