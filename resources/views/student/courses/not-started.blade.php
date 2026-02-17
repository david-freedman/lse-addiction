@extends('layouts.app')

@section('title', $viewModel->name())

@section('content')
<div>
    <div class="mb-6">
        <x-breadcrumbs :items="[
            ['title' => 'Мої курси', 'url' => route('student.my-courses')],
            ['title' => $viewModel->name()],
        ]" />
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        @if($course->banner)
            <div class="w-full h-64 overflow-hidden">
                <img src="{{ $course->banner_url }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
            </div>
        @else
            <div class="w-full h-64 bg-gradient-to-br from-teal-400 to-teal-600"></div>
        @endif

        <div class="p-6">
            @if($course->tags->isNotEmpty())
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach($course->tags as $tag)
                        <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-xs rounded-full">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $viewModel->name() }}</h1>

            @if($viewModel->description())
                <p class="text-gray-600 mb-4">{{ $viewModel->description() }}</p>
            @endif

            @if($viewModel->teacherName())
                <div class="flex items-center gap-1.5 text-sm text-gray-600 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>{{ $viewModel->teacherName() }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-teal-50 border border-teal-200 rounded-xl p-8 text-center">
        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">Курс ще не розпочався</h2>
        @if($course->starts_at)
            <p class="text-gray-600 mb-1">Початок курсу:</p>
            <p class="text-2xl font-semibold text-teal-600 mb-4">
                {{ $course->starts_at->locale('uk')->isoFormat('D MMMM YYYY, HH:mm') }}
            </p>
        @endif
        <p class="text-gray-500 text-sm mb-6">Вміст курсу стане доступним після початку. Очікуйте на старт!</p>
        <a href="{{ route('student.my-courses') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-500 hover:bg-teal-600 text-white font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Мої курси
        </a>
    </div>
</div>
@endsection
