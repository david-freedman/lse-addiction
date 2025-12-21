@extends('layouts.app')

@section('title', $viewModel->lessonName() . ' - ' . $viewModel->courseName())

@section('content')
<div class="flex flex-col lg:flex-row" x-data="lessonPage(@js($viewModel->studentNote()), '{{ $viewModel->saveNoteUrl() }}')">
    <div class="flex-1 lg:pr-0">
        <div class="px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <x-breadcrumbs :items="[
                    ['title' => 'Мої курси', 'url' => route('student.my-courses')],
                    ['title' => $viewModel->courseName(), 'url' => $viewModel->courseUrl(), 'class' => 'truncate max-w-32'],
                    ['title' => 'Модуль ' . $viewModel->moduleNumber(), 'url' => $viewModel->backToModuleUrl(), 'class' => 'truncate max-w-40'],
                    ['title' => 'Урок ' . $viewModel->lessonNumber(), 'class' => 'truncate max-w-48'],
                ]" />
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
                <div class="max-w-4xl mx-auto">
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
            </div>
        @elseif($viewModel->isDicom() && $viewModel->dicomUrl())
            <div class="px-4 sm:px-6 py-4">
                @include('student.lessons.partials.dicom-viewer')
            </div>
        @elseif($viewModel->isQaSession() && $viewModel->qaSessionUrl())
            <div class="px-4 sm:px-6 py-8">
                <div class="max-w-2xl mx-auto text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-teal-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Q&A Сесія</h3>
                    <p class="text-gray-600 mb-6">Натисніть кнопку нижче, щоб приєднатися до живої сесії питань та відповідей з викладачем.</p>
                    <a href="{{ $viewModel->qaSessionUrl() }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-8 py-3 text-base font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Перейти до сесії
                    </a>
                    <p class="text-sm text-gray-500 mt-4">Відкриється в новій вкладці</p>
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
                            Позначити як завершений
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

            @if($viewModel->hasHomework())
                <div class="mb-6 rounded-xl bg-white border border-gray-200 {{ $viewModel->isHomeworkRequired() && !$viewModel->isHomeworkPassed() ? 'border-l-4 border-l-amber-400' : '' }} p-5">
                    <div class="flex items-start gap-4">
                        <div class="bg-amber-50 text-amber-500 rounded-lg p-3 hidden sm:flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h3 class="font-semibold text-gray-900">Домашнє завдання</h3>
                                @if($viewModel->isHomeworkRequired())
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                        Обов'язкове
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                                        Необов'язкове
                                    </span>
                                @endif
                                @if($viewModel->isHomeworkPassed())
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                        Виконано
                                    </span>
                                @elseif($viewModel->latestSubmission()?->status->value === 'revision_requested')
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                        Потребує допрацювання
                                    </span>
                                @endif
                            </div>

                            @if($viewModel->isHomeworkRequired() && !$viewModel->isHomeworkPassed())
                                @if($viewModel->latestSubmission()?->status->value === 'revision_requested')
                                    <p class="text-sm text-blue-600">Допрацюйте завдання згідно коментарів викладача</p>
                                @else
                                    <p class="text-sm text-gray-600">Виконайте завдання для завершення уроку</p>
                                @endif
                            @elseif($viewModel->homework()->description)
                                <p class="text-sm text-gray-600">{{ Str::limit($viewModel->homework()->description, 100) }}</p>
                            @endif

                            <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-500">
                                <span>Балів: {{ $viewModel->homework()->max_points }}</span>
                                @if($viewModel->homework()->deadline_at)
                                    <span class="{{ $viewModel->homework()->isDeadlinePassed() ? 'text-red-600' : '' }}">
                                        Дедлайн: {{ $viewModel->homework()->deadline_at->format('d.m.Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <button @click="activeTab = 'homework'"
                                class="{{ $viewModel->isHomeworkPassed() ? 'bg-teal-500 hover:bg-teal-600' : ($viewModel->latestSubmission()?->status->value === 'pending' ? 'bg-amber-500 hover:bg-amber-600' : ($viewModel->latestSubmission()?->status->value === 'revision_requested' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-teal-500 hover:bg-teal-600')) }} text-white px-4 py-2 rounded-lg text-sm font-medium transition shrink-0">
                            @if($viewModel->isHomeworkPassed())
                                Переглянути
                            @elseif($viewModel->latestSubmission()?->status->value === 'pending')
                                На перевірці
                            @elseif($viewModel->latestSubmission()?->status->value === 'revision_requested')
                                Допрацювати
                            @else
                                Виконати
                            @endif
                        </button>
                    </div>
                </div>
            @endif

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
                        @if($viewModel->commentsCount() > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-medium rounded-full bg-teal-100 text-teal-700">
                                {{ $viewModel->commentsCount() }}
                            </span>
                        @endif
                    </button>
                    @if($viewModel->hasHomework())
                        <button @click="activeTab = 'homework'"
                                :class="activeTab === 'homework' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 -mb-px transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Домашнє завдання
                            @if($viewModel->isHomeworkPassed())
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </button>
                    @endif
                </div>

                <div x-show="activeTab === 'notes'" x-cloak>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ваші нотатки до цього уроку</label>
                        <textarea
                            x-model="noteContent"
                            rows="4"
                            placeholder="Додайте свої нотатки, ключові моменти або питання..."
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none"
                            :disabled="noteSaving"></textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button"
                                @click="saveNote()"
                                :disabled="noteSaving"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="noteSaving">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="noteSaving ? 'Збереження...' : 'Зберегти нотатки'"></span>
                        </button>

                        <template x-if="noteSaved">
                            <span class="inline-flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Збережено
                            </span>
                        </template>

                        <template x-if="noteError">
                            <span class="text-sm text-red-600" x-text="noteError"></span>
                        </template>
                    </div>
                </div>

                <div x-show="activeTab === 'comments'" x-cloak>
                    <form action="{{ $viewModel->storeCommentUrl() }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <textarea
                                name="content"
                                rows="3"
                                placeholder="Напишіть коментар або запитання..."
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none"
                                required></textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                            Надіслати коментар
                        </button>
                    </form>

                    @if($viewModel->comments()->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-sm">Поки немає коментарів. Будьте першим!</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($viewModel->comments() as $comment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-teal-100 text-teal-700 rounded-full flex items-center justify-center font-medium text-sm">
                                            {{ $comment->authorInitials() }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium text-gray-900">{{ $comment->authorName() }}</span>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm whitespace-pre-line">{{ $comment->content }}</p>
                                        </div>
                                    </div>

                                    @if($comment->replies->isNotEmpty())
                                        <div class="mt-4 ml-13 space-y-3 border-l-2 border-gray-100 pl-4">
                                            @foreach($comment->replies as $reply)
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 w-8 h-8 {{ $reply->isFromUser() ? 'bg-blue-100 text-blue-700' : 'bg-teal-100 text-teal-700' }} rounded-full flex items-center justify-center font-medium text-xs">
                                                        {{ $reply->authorInitials() }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="font-medium text-gray-900">{{ $reply->authorName() }}</span>
                                                            @if($reply->isFromUser())
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                    Викладач
                                                                </span>
                                                            @endif
                                                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-gray-700 text-sm whitespace-pre-line">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($viewModel->hasHomework())
                    <div x-show="activeTab === 'homework'" x-cloak>
                        @include('student.homework.partials.homework-section')
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('student.partials.course-sidebar', ['modules' => $viewModel->modules(), 'currentModuleId' => $lesson->module_id])
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('lessonPage', (initialNote, saveUrl) => ({
        activeTab: 'notes',
        sidebarOpen: false,
        noteContent: initialNote,
        noteSaving: false,
        noteSaved: false,
        noteError: '',
        async saveNote() {
            this.noteSaving = true;
            this.noteSaved = false;
            this.noteError = '';

            try {
                const response = await fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ content: this.noteContent })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Помилка збереження');
                }

                this.noteSaved = true;
                setTimeout(() => this.noteSaved = false, 3000);
            } catch (err) {
                this.noteError = err.message;
                setTimeout(() => this.noteError = '', 5000);
            } finally {
                this.noteSaving = false;
            }
        }
    }))
})
</script>
@endpush
@endsection
