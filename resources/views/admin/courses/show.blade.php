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
