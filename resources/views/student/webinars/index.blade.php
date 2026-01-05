@extends('layouts.app')

@section('title', 'Мої вебінари')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Мої вебінари</h1>
        <p class="text-gray-600">Вебінари, на які ви зареєстровані</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Всього вебінарів</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->totalCount() }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Майбутні</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->upcomingCount() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Завершені</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->pastCount() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex space-x-2 mb-6 border-b border-gray-200">
            <a href="{{ route('student.my-webinars') }}"
               class="px-4 py-2 -mb-px {{ $viewModel->isShowingAll() ? 'border-b-2 border-teal-500 text-teal-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                Всі вебінари ({{ $viewModel->totalCount() }})
            </a>
            <a href="{{ route('student.my-webinars', ['status' => 'upcoming']) }}"
               class="px-4 py-2 -mb-px {{ $viewModel->isFilteredBy('upcoming') ? 'border-b-2 border-teal-500 text-teal-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                Майбутні ({{ $viewModel->upcomingCount() }})
            </a>
            <a href="{{ route('student.my-webinars', ['status' => 'past']) }}"
               class="px-4 py-2 -mb-px {{ $viewModel->isFilteredBy('past') ? 'border-b-2 border-teal-500 text-teal-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                Завершені ({{ $viewModel->pastCount() }})
            </a>
        </div>

        @if($viewModel->hasNoWebinars())
            <div class="text-center py-12">
                <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-600 mb-4">Немає вебінарів для відображення</p>
                <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white font-medium rounded-lg transition">
                    Переглянути каталог
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($viewModel->webinars() as $webinar)
                    <x-student.webinar-card :webinar="$viewModel->getWebinarCardData($webinar)" />
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
