@extends('admin.layouts.admin')

@section('title', 'Створити урок')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.lessons.index', [$course, $module]) }}" class="text-sm text-gray-500 hover:text-gray-700">
        ← Назад до уроків
    </a>
    <h1 class="text-title-xl font-bold text-gray-900 mt-1">Створити урок</h1>
    <p class="text-gray-500">Модуль: {{ $module->name }}</p>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.lessons.store', [$course, $module]) }}" method="POST" enctype="multipart/form-data" x-data="{ type: 'text' }">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Назва *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                @error('name')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Тип уроку</label>
                <select name="type" x-model="type"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    @foreach($lessonTypes as $lessonType)
                        <option value="{{ $lessonType->value }}">{{ $lessonType->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Опис</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">{{ old('description') }}</textarea>
            </div>

            <template x-if="type === 'video'">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL відео</label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}"
                           placeholder="https://youtube.com/... або https://vimeo.com/..."
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                </div>
            </template>

            <template x-if="type === 'text'">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Контент</label>
                    <textarea name="content" rows="10"
                              class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">{{ old('content') }}</textarea>
                </div>
            </template>

            <template x-if="type === 'qa_session'">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Q&A сесії *</label>
                    <input type="url" name="qa_session_url" value="{{ old('qa_session_url') }}"
                           placeholder="https://zoom.us/... або https://meet.google.com/..."
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <p class="mt-1 text-xs text-gray-500">Посилання на Zoom, Google Meet або іншу платформу для відеоконференцій</p>
                    @error('qa_session_url')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
                </div>
            </template>

            @php($lesson = null)
            @include('admin.lessons.partials.dicom-settings')
            @include('admin.lessons.partials.quiz-settings')
            @include('admin.lessons.partials.homework-settings')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Тривалість (хвилин)</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" min="0"
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select name="status"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    @foreach($lessonStatuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Створити
            </button>
            <a href="{{ route('admin.lessons.index', [$course, $module]) }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Скасувати
            </a>
        </div>
    </form>
</div>
@endsection
