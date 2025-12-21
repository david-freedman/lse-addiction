@extends('layouts.app')

@section('title', $viewModel->courseName())

@section('content')
<div>
    <div class="mb-6">
        <x-breadcrumbs :items="[
            ['title' => 'Мої курси', 'url' => route('student.my-courses')],
            ['title' => $viewModel->courseName()],
        ]" />
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $viewModel->courseName() }}</h1>
        @if($viewModel->courseDescription())
            <p class="text-gray-600 mb-4">{{ $viewModel->courseDescription() }}</p>
        @endif

        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
            @if($viewModel->teacherName())
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>{{ $viewModel->teacherName() }}</span>
                </div>
            @endif
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>{{ $viewModel->totalModules() }} модулів</span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>{{ $viewModel->totalLessons() }} уроків</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Загальний прогрес</span>
                    <span class="text-sm font-bold {{ $viewModel->progressPercentage() === 100 ? 'text-emerald-600' : 'text-teal-600' }}">{{ $viewModel->progressPercentage() }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="{{ $viewModel->progressPercentage() === 100 ? 'bg-emerald-500' : 'bg-teal-500' }} h-3 rounded-full transition-all duration-300" style="width: {{ $viewModel->progressPercentage() }}%"></div>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                {{ $viewModel->lessonsCompleted() }} / {{ $viewModel->totalLessons() }} уроків
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @foreach($viewModel->modules() as $module)
            <div class="bg-white rounded-xl shadow-md overflow-hidden {{ !$module->isUnlocked ? 'opacity-75' : '' }}">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center {{ $module->isUnlocked ? 'bg-teal-100' : 'bg-gray-100' }}">
                                @if(!$module->isUnlocked)
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                @elseif($module->iconType === 'quiz')
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <a href="{{ route('student.modules.show', ['course' => $viewModel->courseSlug(), 'module' => $module->id]) }}"
                                   class="text-lg font-bold text-gray-900 hover:text-teal-600 transition-colors">
                                    Модуль {{ $module->order + 1 }}: {{ $module->name }}
                                </a>
                                @if($module->progressPercentage === 100)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Завершено
                                    </span>
                                @elseif($module->progressPercentage > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                        В процесі
                                    </span>
                                @endif
                            </div>

                            @if($module->description)
                                <p class="text-sm text-gray-600 mb-3">{{ $module->description }}</p>
                            @endif

                            @if($module->isUnlocked)
                                <div class="mb-3">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-500">Прогрес модуля</span>
                                        <span class="text-xs font-medium text-gray-700">{{ $module->lessonsCompleted }}/{{ $module->totalLessons }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $module->progressPercentage === 100 ? 'bg-emerald-500' : 'bg-teal-500' }} h-2 rounded-full transition-all duration-300" style="width: {{ $module->progressPercentage }}%"></div>
                                    </div>
                                </div>

                                @if(!empty($module->lessons))
                                    <div class="space-y-1 mt-2">
                                        @foreach($module->lessons as $lesson)
                                            <a href="{{ route('student.lessons.show', ['course' => $viewModel->courseSlug(), 'lesson' => $lesson->id]) }}"
                                               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition hover:bg-gray-50 text-gray-700">
                                                <div class="flex-shrink-0">
                                                    @if($lesson->isCompleted)
                                                        <div class="w-5 h-5 flex items-center justify-center rounded-full bg-green-500 text-white">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-5 h-5 flex items-center justify-center rounded-full border-2 border-gray-300 text-gray-400">
                                                            @switch($lesson->type)
                                                                @case('quiz')
                                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    @break
                                                                @case('text')
                                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                    </svg>
                                                                    @break
                                                                @case('dicom')
                                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                                                    </svg>
                                                                    @break
                                                                @case('survey')
                                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                                    </svg>
                                                                    @break
                                                                @case('qa_session')
                                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                                    </svg>
                                                                    @break
                                                                @default
                                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M8 5v14l11-7z"/>
                                                                    </svg>
                                                            @endswitch
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm truncate">{{ $lesson->name }}</p>
                                                </div>
                                                @if($lesson->duration)
                                                    <span class="text-xs text-gray-500 flex-shrink-0">{{ $lesson->duration }}</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    {{ $module->unlockMessage }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
