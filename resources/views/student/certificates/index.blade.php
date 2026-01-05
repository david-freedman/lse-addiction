@extends('layouts.app')

@section('title', 'Мої сертифікати')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Мої сертифікати</h1>
        <p class="text-gray-600">Переглядайте та завантажуйте сертифікати про завершення курсів</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Всього сертифікатів</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->totalCount() }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-600 text-sm mb-1">Останній отриманий</p>
                    @if($viewModel->lastCertificate())
                        <p class="text-lg font-bold text-gray-900 truncate">{{ $viewModel->lastCertificate()->course->name }}</p>
                        <p class="text-sm text-gray-500">{{ $viewModel->lastCertificate()->formatted_issued_at }}</p>
                    @else
                        <p class="text-gray-400">Немає</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Всього навчальних годин</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->formattedTotalStudyHours() }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <form method="GET" action="{{ route('student.certificates') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $viewModel->currentSearch() }}"
                       placeholder="Пошук сертифікатів за назвою курсу або категорією..."
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white">
            </div>
            @if($viewModel->isFiltered())
                <a href="{{ route('student.certificates') }}"
                   class="px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition">
                    Скинути
                </a>
            @endif
        </form>
    </div>

    @if($viewModel->hasNoCertificates())
        <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <svg class="mx-auto w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <p class="text-gray-600">
                @if($viewModel->isFiltered())
                    Сертифікати не знайдені
                @else
                    У вас поки немає сертифікатів. Завершіть курс, щоб отримати сертифікат!
                @endif
            </p>
        </div>
    @else
        <div class="grid gap-6" style="grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));">
            @foreach($viewModel->certificates() as $certificate)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition">
                    <div class="relative p-6 text-center text-white overflow-hidden">
                        @if($certificate->course->banner_url)
                            <div class="absolute inset-0" style="background-image: url('{{ $certificate->course->banner_url }}'); background-size: cover; background-position: center; filter: blur(4px); transform: scale(1.05);"></div>
                            <div class="absolute inset-0 bg-black/40"></div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-teal-500"></div>
                        @endif
                        <div class="relative">
                            <div class="absolute top-0 right-0 -mt-3 -mr-3">
                                <span class="inline-flex items-center gap-1 rounded-full {{ $certificate->grade_level->badgeClasses() }} px-3 py-1.5 text-sm font-semibold shadow-sm">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $certificate->grade_level->label() }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-1">Сертифікат</h3>
                            <p class="text-sm text-white/80 mb-2">про завершення курсу</p>
                            <p class="font-semibold text-sm leading-tight">{{ $certificate->course->name }}</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <h4 class="font-semibold text-gray-900 mb-3">{{ $certificate->course->name }}</h4>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Викладач: {{ $certificate->course->teacher?->full_name ?? 'Не вказано' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Отримано: {{ $certificate->formatted_issued_at }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span>Номер: {{ $certificate->certificate_number }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg mb-4">
                            <span class="text-sm text-gray-600">Навчальних годин</span>
                            <span class="font-bold text-teal-600">{{ $certificate->formatted_study_hours }}</span>
                        </div>

                        <a href="{{ route('student.certificates.download', $certificate) }}"
                           class="flex items-center justify-center gap-2 w-full py-2 px-3 border border-teal-500 text-teal-600 text-sm font-medium rounded-lg hover:bg-teal-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Завантажити PDF
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
