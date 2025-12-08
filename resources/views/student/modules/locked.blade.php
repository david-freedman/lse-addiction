@extends('layouts.app')

@section('title', 'Модуль заблоковано - ' . $viewModel->courseName())

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

<div class="flex-1 flex items-center justify-center py-16">
    <div class="text-center max-w-md px-4">
        <div class="w-20 h-20 mx-auto mb-6 bg-amber-100 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 mb-2">Модуль заблоковано</h2>
        <p class="text-gray-600 mb-2">
            Модуль {{ $viewModel->moduleNumber() }}: {{ $viewModel->moduleName() }}
        </p>
        <p class="text-gray-500 mb-6">
            {{ $viewModel->unlockMessage() }}
        </p>

        <a href="{{ $viewModel->backToCourseUrl() }}"
           class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-6 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Повернутися до курсу
        </a>
    </div>
</div>
@endsection
