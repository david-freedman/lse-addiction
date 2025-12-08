@extends('layouts.app')

@section('title', 'Модуль ' . $viewModel->moduleNumber() . ': ' . $viewModel->moduleName() . ' - ' . $viewModel->courseName())

@section('content')
<div class="bg-white border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-4">
                <a href="{{ $viewModel->backToCourseUrl() }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="text-sm font-medium">Назад до курсу</span>
                </a>
                <h1 class="hidden lg:block text-lg font-semibold text-gray-900 truncate max-w-xl">
                    {{ $viewModel->courseName() }}
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">Прогрес курсу:</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-teal-600">{{ $viewModel->courseProgressPercent() }}%</span>
                    <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full transition-all duration-300"
                             style="width: {{ $viewModel->courseProgressPercent() }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row" x-data="{ sidebarOpen: false }">
    <div class="flex-1 lg:pr-0">
        <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-6xl">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Модуль {{ $viewModel->moduleNumber() }}: {{ $viewModel->moduleName() }}
                </h2>
                @if($viewModel->moduleDescription())
                    <p class="text-gray-600">{{ $viewModel->moduleDescription() }}</p>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Прогрес модуля</h3>
                        <p class="text-3xl font-bold text-teal-500 mb-4">{{ $viewModel->progressPercentage() }}%</p>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-500 h-2 rounded-full transition-all duration-500" style="width: {{ $viewModel->progressPercentage() }}%"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-8">
                        <div class="text-center">
                            <span class="text-2xl font-bold text-teal-500">{{ $viewModel->lessonsCompleted() }}</span>
                            <p class="text-sm text-gray-500">Завершено</p>
                        </div>
                        <div class="text-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $viewModel->totalLessons() }}</span>
                            <p class="text-sm text-gray-500">Всього</p>
                        </div>
                    </div>
                    @if($viewModel->hasContinueLesson())
                        <div>
                            <a href="{{ $viewModel->continueUrl() }}"
                               class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-6 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Продовжити навчання
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Уроки модуля</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($viewModel->lessons() as $lesson)
                        @include('student.modules.partials.lesson-card', ['lesson' => $lesson])
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('student.partials.course-sidebar', ['modules' => $viewModel->modules()])
</div>
@endsection
