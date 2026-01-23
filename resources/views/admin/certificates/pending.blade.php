@extends('admin.layouts.admin')

@section('title', 'Сертифікати на модерації')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.certificates.index') }}" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-title-xl font-bold text-gray-900">Сертифікати на модерації</h1>
    </div>
</div>

<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4">
    <form method="GET" action="{{ route('admin.certificates.pending') }}">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $viewModel->filters()->search }}"
                    placeholder="Пошук по номеру, студенту або курсу..."
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>
            <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Шукати
            </button>
            @if($viewModel->filters()->search)
                <a href="{{ route('admin.certificates.pending') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">
                    Скинути
                </a>
            @endif
        </div>
    </form>
</div>

@if($viewModel->hasNoCertificates())
    <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="mt-4 text-gray-600">Всі сертифікати опубліковано!</p>
        <a href="{{ route('admin.certificates.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm text-brand-600 hover:underline">
            Переглянути всі сертифікати
        </a>
    </div>
@else
    <div x-data="{ selectedIds: [], selectAll: false }" class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-200 bg-warning-50 px-6 py-3">
            <span class="text-sm text-warning-700">
                Сертифікати очікують вашої перевірки перед публікацією
            </span>
            <form method="POST" action="{{ route('admin.certificates.publish') }}" class="inline" x-show="selectedIds.length > 0">
                @csrf
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="certificate_ids[]" :value="id">
                </template>
                <button type="submit" class="rounded-lg bg-success-500 px-4 py-1.5 text-sm font-medium text-white transition hover:bg-success-600">
                    Опублікувати вибрані (<span x-text="selectedIds.length"></span>)
                </button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" x-model="selectAll" @change="selectedIds = selectAll ? [...document.querySelectorAll('input[name=cert_checkbox]')].map(e => e.value) : []" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номер</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Студент</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Курс</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Оцінка</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата видачі</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Дії</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($viewModel->certificates() as $certificate)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="cert_checkbox" value="{{ $certificate->id }}" x-model="selectedIds" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm text-gray-900">{{ $certificate->certificate_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.students.show', $certificate->student) }}" class="font-medium text-brand-600 hover:text-brand-700 hover:underline">
                                    {{ $certificate->student->surname }} {{ $certificate->student->name }}
                                </a>
                                <div class="text-xs text-gray-500">{{ $certificate->student->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.courses.show', $certificate->course) }}" class="text-gray-900 hover:text-brand-600 hover:underline">
                                    {{ $certificate->course->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $certificate->grade_level->badgeClasses() }}">
                                    {{ $certificate->grade_level->label() }} ({{ number_format($certificate->grade, 0) }}%)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $certificate->issued_at->format('d.m.Y') }}
                                @if($certificate->issuedBy)
                                    <div class="text-xs text-gray-500">{{ $certificate->issuedBy->name }}</div>
                                @else
                                    <div class="text-xs text-gray-500">Автоматично</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.certificates.preview', $certificate) }}" target="_blank" class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50" title="Переглянути PDF">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.certificates.publish') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="certificate_ids[]" value="{{ $certificate->id }}">
                                        <button type="submit" class="rounded-lg bg-success-500 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-success-600">
                                            Опублікувати
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
        {{ $viewModel->certificates()->links() }}
    </div>
@endif
@endsection
