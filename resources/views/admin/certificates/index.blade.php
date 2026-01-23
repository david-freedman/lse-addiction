@extends('admin.layouts.admin')

@section('title', 'Сертифікати')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Сертифікати</h1>
    @if($viewModel->pendingCount() > 0)
        <a href="{{ route('admin.certificates.pending') }}" class="inline-flex items-center gap-2 rounded-lg bg-warning-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-warning-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Очікують модерації: {{ $viewModel->pendingCount() }}
        </a>
    @endif
</div>

<div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4" x-data="{ showFilters: {{ $viewModel->isFiltered() ? 'true' : 'false' }} }">
    <form method="GET" action="{{ route('admin.certificates.index') }}">
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
                <a href="{{ route('admin.certificates.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100">
                    Скинути
                </a>
            @endif
        </div>

        <div x-show="showFilters" x-collapse class="mt-4 grid grid-cols-1 gap-4 border-t border-gray-200 pt-4 md:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Курс</label>
                <select name="course_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі курси</option>
                    @foreach($viewModel->courses() as $course)
                        <option value="{{ $course->id }}" {{ $viewModel->filters()->course_id == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Студент</label>
                <select name="student_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі студенти</option>
                    @foreach($viewModel->students() as $student)
                        <option value="{{ $student->id }}" {{ $viewModel->filters()->student_id == $student->id ? 'selected' : '' }}>
                            {{ $student->surname }} {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Статус</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі</option>
                    @foreach(\App\Domains\Certificate\Enums\CertificateStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $viewModel->filters()->status === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

@if($viewModel->hasNoCertificates())
    <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>
        <p class="mt-4 text-gray-600">Сертифікатів ще немає.</p>
    </div>
@else
    <div x-data="{ selectedIds: [] }" class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div x-show="selectedIds.length > 0" class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-6 py-3">
            <span class="text-sm text-gray-600">Вибрано: <span x-text="selectedIds.length"></span></span>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('admin.certificates.publish') }}" class="inline">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="certificate_ids[]" :value="id">
                    </template>
                    <button type="submit" class="rounded-lg bg-success-500 px-4 py-1.5 text-sm font-medium text-white transition hover:bg-success-600">
                        Опублікувати
                    </button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" @change="selectedIds = $event.target.checked ? [...document.querySelectorAll('input[name=cert_checkbox]')].map(e => e.value) : []" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номер</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Студент</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Курс</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Оцінка</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата видачі</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Дії</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($viewModel->certificates() as $certificate)
                        @php $status = $certificate->getStatus(); @endphp
                        <tr class="hover:bg-gray-50 transition {{ $certificate->isRevoked() ? 'bg-gray-50 opacity-60' : '' }}">
                            <td class="px-6 py-4">
                                @if($certificate->isPending())
                                    <input type="checkbox" name="cert_checkbox" value="{{ $certificate->id }}" x-model="selectedIds" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                @endif
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $status->badgeClasses() }}">
                                    {{ $status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.certificates.preview', $certificate) }}" target="_blank" class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50" title="Переглянути PDF">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    @if($certificate->isPending())
                                        <form action="{{ route('admin.certificates.publish') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="certificate_ids[]" value="{{ $certificate->id }}">
                                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-success-600 transition hover:bg-success-50">
                                                Опублікувати
                                            </button>
                                        </form>
                                    @endif
                                    @if($certificate->isRevoked())
                                        <form action="{{ route('admin.certificates.restore', $certificate) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-success-600 transition hover:bg-success-50">
                                                Відновити
                                            </button>
                                        </form>
                                    @elseif($certificate->isPublished())
                                        <form action="{{ route('admin.certificates.revoke', $certificate) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете скасувати цей сертифікат?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-error-600 transition hover:bg-error-50">
                                                Скасувати
                                            </button>
                                        </form>
                                    @endif
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
