@extends('admin.layouts.admin')

@section('title', 'Сертифікати')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Сертифікати</h1>
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

        <div x-show="showFilters" x-collapse class="mt-4 grid grid-cols-1 gap-4 border-t border-gray-200 pt-4 md:grid-cols-3">
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
                <select name="only_revoked" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі</option>
                    <option value="0" {{ $viewModel->filters()->only_revoked === false ? 'selected' : '' }}>Активні</option>
                    <option value="1" {{ $viewModel->filters()->only_revoked === true ? 'selected' : '' }}>Скасовані</option>
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
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                        <tr class="hover:bg-gray-50 transition {{ $certificate->trashed() ? 'bg-gray-50 opacity-60' : '' }}">
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
                                @if($certificate->trashed())
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-error-100 text-error-700">
                                        Скасований
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-success-100 text-success-700">
                                        Активний
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.certificates.download', $certificate) }}" target="_blank" class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50" title="Завантажити PDF">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    @if($certificate->trashed())
                                        <form action="{{ route('admin.certificates.restore', $certificate) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-success-600 transition hover:bg-success-50">
                                                Відновити
                                            </button>
                                        </form>
                                    @else
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
