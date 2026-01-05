@extends('admin.layouts.admin')

@section('title', 'Коментарі до уроків')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Коментарі до уроків</h1>
    <p class="text-gray-500">Коментарі та питання студентів</p>
</div>

<div class="rounded-2xl border border-gray-200 bg-white mb-6"
     x-data="{
        courseId: {{ $viewModel->filters->course_id ?? 'null' }},
        lessonId: {{ $viewModel->filters->lesson_id ?? 'null' }},
        lessons: {{ json_encode($viewModel->lessons()) }},
        loading: false,
        replyingTo: null,
        replyContent: '',

        async loadLessons() {
            if (!this.courseId) {
                this.lessons = [];
                this.lessonId = null;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/admin/comments/lessons/${this.courseId}`);
                this.lessons = await response.json();
                this.lessonId = null;
            } finally {
                this.loading = false;
            }
        }
     }">
    <form method="GET" class="p-4 border-b border-gray-200">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px] max-w-[300px]">
                <input type="text" name="search" value="{{ $viewModel->filters->search }}"
                       placeholder="Пошук..."
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
            <div class="min-w-[200px]">
                <select name="course_id" x-model="courseId" @change="loadLessons()"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <option value="">Всі курси</option>
                    @foreach($viewModel->courses() as $course)
                        <option value="{{ $course->id }}" @selected($viewModel->filters->course_id == $course->id)>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[200px]">
                <select name="lesson_id" x-model="lessonId" :disabled="!courseId || loading"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white disabled:opacity-50">
                    <option value="">Всі уроки</option>
                    <template x-for="lesson in lessons" :key="lesson.id">
                        <option :value="lesson.id" x-text="lesson.name"></option>
                    </template>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.comments.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
        </div>
    </form>

    @if($viewModel->hasNoComments())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Немає коментарів</h3>
            <p class="mt-2 text-gray-500">Студенти поки не залишали коментарів</p>
        </div>
    @elseif($viewModel->comments()->isEmpty())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Нічого не знайдено</h3>
            <p class="mt-2 text-gray-500">Спробуйте змінити фільтри</p>
        </div>
    @else
        <div class="divide-y divide-gray-200">
            @foreach($viewModel->comments() as $comment)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-start gap-4">
                        <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-medium flex-shrink-0">
                            {{ $comment->authorInitials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-gray-900">{{ $comment->authorName() }}</span>
                                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">
                                {{ $comment->lesson->module->course->name }} / {{ $comment->lesson->module->name }} / {{ $comment->lesson->name }}
                            </p>
                            <p class="text-gray-700 whitespace-pre-line">{{ $comment->content }}</p>

                            @if($comment->replies->isNotEmpty())
                                <div class="mt-4 space-y-3 border-l-2 border-gray-100 pl-4">
                                    @foreach($comment->replies as $reply)
                                        <div class="flex items-start gap-3">
                                            <div class="h-8 w-8 rounded-full {{ $reply->isFromUser() ? 'bg-blue-100 text-blue-700' : 'bg-teal-100 text-teal-700' }} flex items-center justify-center font-medium text-xs flex-shrink-0">
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
                                                    <span class="text-xs text-gray-500">{{ $reply->created_at->format('d.m.Y H:i') }}</span>
                                                </div>
                                                <p class="text-gray-700 text-sm whitespace-pre-line">{{ $reply->content }}</p>
                                            </div>
                                            <form action="{{ route('admin.comments.destroy', $reply) }}" method="POST" class="flex-shrink-0"
                                                  onsubmit="return confirm('Видалити цю відповідь?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-4 flex items-center gap-3">
                                <button type="button"
                                        @click="replyingTo = replyingTo === {{ $comment->id }} ? null : {{ $comment->id }}; replyContent = ''"
                                        class="inline-flex items-center gap-1 text-sm text-brand-600 hover:text-brand-700 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    Відповісти
                                </button>
                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                      onsubmit="return confirm('Видалити цей коментар та всі відповіді?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Видалити
                                    </button>
                                </form>
                            </div>

                            <div x-show="replyingTo === {{ $comment->id }}" x-cloak class="mt-4">
                                <form action="{{ route('admin.comments.reply', $comment) }}" method="POST">
                                    @csrf
                                    <textarea name="content" x-model="replyContent" rows="3" required
                                              placeholder="Напишіть відповідь..."
                                              class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none"></textarea>
                                    <div class="mt-2 flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-brand-500 rounded-lg hover:bg-brand-600 transition">
                                            Надіслати
                                        </button>
                                        <button type="button" @click="replyingTo = null"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                            Скасувати
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="flex-shrink-0 hidden sm:block"
                              onsubmit="return confirm('Видалити цей коментар та всі відповіді?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $viewModel->comments()->links() }}
        </div>
    @endif
</div>
@endsection
