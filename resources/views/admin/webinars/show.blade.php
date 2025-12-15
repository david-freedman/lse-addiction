@extends('admin.layouts.admin')

@section('title', $viewModel->title())

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">{{ $viewModel->title() }}</h1>
    <a href="{{ route('admin.webinars.edit', $viewModel->webinar()) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Редагувати
    </a>
</div>

<div class="rounded-xl border border-gray-200 bg-white p-6 mb-6">
    <div class="flex gap-6">
        @if($viewModel->bannerUrl())
            <div class="shrink-0 w-72 h-48 rounded-lg overflow-hidden bg-gray-100">
                <img src="{{ $viewModel->bannerUrl() }}" alt="{{ $viewModel->title() }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="flex-1 min-w-0 flex flex-col">
            <div class="flex items-center gap-6 mb-3">
                @php
                    $statusClass = match($viewModel->statusColor()) {
                        'green' => 'bg-success-100 text-success-700',
                        'gray' => 'bg-gray-100 text-gray-700',
                        'blue' => 'bg-brand-100 text-brand-700',
                        'purple' => 'bg-purple-100 text-purple-700',
                        'red' => 'bg-error-100 text-error-700',
                        'orange' => 'bg-warning-100 text-warning-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $statusClass }}">
                    {{ $viewModel->statusLabel() }}
                </span>
                <div class="flex items-center gap-2">
                    @if($viewModel->oldPrice())
                        <span class="text-sm text-gray-400 line-through">{{ $viewModel->oldPrice() }}</span>
                    @endif
                    <span class="text-lg font-semibold {{ $viewModel->price() === 'Безкоштовно' ? 'text-success-600' : 'text-gray-900' }}">
                        {{ $viewModel->price() }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-6 mb-2 text-sm">
                <div>
                    <span class="text-gray-500">Викладач:</span>
                    <span class="font-medium text-gray-900 ml-1">{{ $viewModel->teacherName() }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Учасників:</span>
                    <span class="font-semibold text-gray-900 ml-1">
                        {{ $viewModel->activeCount() }}
                        @if($viewModel->maxParticipants())
                            / {{ $viewModel->maxParticipants() }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-6 mb-4 text-sm">
                <div>
                    <span class="text-gray-500">Дата:</span>
                    <span class="font-medium text-gray-900 ml-1">{{ $viewModel->startsAt() }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Тривалість:</span>
                    <span class="font-medium text-gray-900 ml-1">{{ $viewModel->formattedDuration() }}</span>
                </div>
            </div>

            @if($viewModel->meetingUrl())
                <div class="mb-4 text-sm">
                    <span class="text-gray-500">Посилання:</span>
                    <a href="{{ $viewModel->meetingUrl() }}" target="_blank" class="font-medium text-brand-600 hover:text-brand-700 ml-1">
                        {{ $viewModel->meetingUrl() }}
                    </a>
                </div>
            @endif

            @if($viewModel->description())
                <div class="border-t border-gray-100 pt-4">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $viewModel->description() }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

@php
    $activeRegistrations = $viewModel->activeRegistrations();
    $cancelledRegistrations = $viewModel->cancelledRegistrations();
@endphp

@if($activeRegistrations->isNotEmpty())
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden mb-6">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Зареєстровані учасники ({{ $activeRegistrations->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ім'я</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата реєстрації</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Присутність</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activeRegistrations as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.students.show', $student) }}" class="text-brand-600 hover:text-brand-700">
                                    {{ $student->name }} {{ $student->surname }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->email->value }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->pivot->registered_at?->format('d.m.Y H:i') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->pivot->attended_at)
                                    <span class="inline-flex rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-700">Був присутній</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Не відмічено</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center mb-6">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <p class="mt-4 text-gray-600">Зареєстрованих учасників ще немає</p>
    </div>
@endif

@if($cancelledRegistrations->isNotEmpty())
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Скасовані реєстрації ({{ $cancelledRegistrations->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ім'я</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата реєстрації</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата скасування</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cancelledRegistrations as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.students.show', $student) }}" class="text-brand-600 hover:text-brand-700">
                                    {{ $student->name }} {{ $student->surname }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->email->value }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->pivot->registered_at?->format('d.m.Y H:i') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->pivot->cancelled_at?->format('d.m.Y H:i') ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
