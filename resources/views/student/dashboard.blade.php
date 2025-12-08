@extends('layouts.app')

@section('title', 'Дашборд')

@section('content')
<div class="space-y-6">
    @if($viewModel->hasCourse())
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('student.my-courses') }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Назад до курсів
            </a>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $viewModel->courseName() }}</h1>
            <p class="text-gray-600">{{ $viewModel->courseDescription() }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                <div class="flex-1">
                    <h2 class="text-sm font-medium text-gray-500 mb-2">Загальний прогрес курсу</h2>
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
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Модулі курсу</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($viewModel->modules() as $module)
                    @include('student.dashboard.partials.module-card', ['module' => $module])
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Ви ще не записані на жоден курс</h2>
            <p class="text-gray-600 mb-6">Перегляньте каталог курсів та оберіть те, що вас цікавить</p>
            <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-6 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Переглянути каталог курсів
            </a>
        </div>
    @endif
</div>
@endsection
