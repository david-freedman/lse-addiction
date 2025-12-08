@extends('layouts.app')

@section('title', $viewModel->lessonName() . ' - ' . $viewModel->courseName())

@section('content')
<div class="flex flex-col lg:flex-row" x-data="{ activeTab: 'notes', sidebarOpen: false }">
    <div class="flex-1 lg:pr-0">
        <div class="px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ $viewModel->backToCourseUrl() }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition-colors">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="text-sm font-medium">Назад до курсів</span>
                    </a>
                    <h1 class="hidden lg:block text-lg font-semibold text-gray-900 truncate max-w-xl">
                        {{ $viewModel->courseName() }}
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600">Прогрес курсу:</span>
                    <span class="text-sm font-semibold text-teal-600">{{ $viewModel->courseProgressPercent() }}%</span>
                    <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full transition-all duration-300"
                             style="width: {{ $viewModel->courseProgressPercent() }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        @if($viewModel->isVideo() && $viewModel->embedUrl())
            <div class="bg-black">
                <div class="relative w-full" style="padding-top: 56.25%;">
                    <iframe
                        src="{{ $viewModel->embedUrl() }}"
                        class="absolute inset-0 w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        @endif

        <div class="px-4 sm:px-6 py-6">
            <div class="mb-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $viewModel->lessonName() }}</h2>
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                            <span>Модуль {{ $viewModel->moduleNumber() }}: {{ $viewModel->moduleName() }}</span>
                            <span class="text-gray-400">•</span>
                            <span>Урок {{ $viewModel->lessonNumber() }} з {{ $viewModel->totalLessonsInModule() }}</span>
                            @if($viewModel->duration())
                                <span class="text-gray-400">•</span>
                                <span>{{ $viewModel->duration() }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($viewModel->isDownloadable())
                            <button type="button" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </button>
                        @endif
                        <button type="button" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            @if($viewModel->description())
                <div class="mb-6">
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($viewModel->description())) !!}
                    </div>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-8">
                @if($viewModel->canNavigateToPrevious())
                    <a href="{{ $viewModel->previousLessonUrl() }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Попередній урок
                    </a>
                @endif

                @if($viewModel->isCompleted())
                    <button type="button"
                            disabled
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-green-500 rounded-lg cursor-default">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Завершено
                    </button>
                @else
                    <form action="{{ route('student.lessons.complete', [$course, $lesson]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Позначити як завершене
                        </button>
                    </form>
                @endif

                @if($viewModel->canNavigateToNext())
                    <a href="{{ $viewModel->nextLessonUrl() }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                        Наступний урок
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>

            <div class="border-t border-gray-200 pt-6">
                <div class="flex gap-4 border-b border-gray-200 mb-4">
                    <button @click="activeTab = 'notes'"
                            :class="activeTab === 'notes' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 -mb-px transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Нотатки
                    </button>
                    <button @click="activeTab = 'comments'"
                            :class="activeTab === 'comments' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 -mb-px transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Коментарі
                    </button>
                </div>

                <div x-show="activeTab === 'notes'" x-cloak>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ваші нотатки до цього уроку</label>
                        <textarea
                            rows="4"
                            placeholder="Додайте свої нотатки, ключові моменти або питання..."
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none"></textarea>
                    </div>
                    <button type="button"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                        Зберегти нотатки
                    </button>
                </div>

                <div x-show="activeTab === 'comments'" x-cloak>
                    <div class="mb-4">
                        <textarea
                            rows="3"
                            placeholder="Напишіть коментар або запитання..."
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none"></textarea>
                    </div>
                    <button type="button"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                        Надіслати коментар
                    </button>

                    <div class="text-center py-8 mt-6 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-sm">Поки немає коментарів. Будьте першим!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('student.partials.course-sidebar', ['modules' => $viewModel->modules(), 'currentModuleId' => $lesson->module_id])
</div>
@endsection
