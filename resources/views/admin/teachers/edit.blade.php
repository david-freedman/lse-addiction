@extends('admin.layouts.admin')

@section('title', 'Редагувати викладача')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Редагувати викладача</h1>
    <a href="{{ route('admin.teachers.show', $teacher) }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
        Назад до профілю
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
                <label for="last_name" class="mb-2 block text-sm font-medium text-gray-700">Прізвище <span class="text-error-500">*</span></label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $teacher->last_name) }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('last_name') border-error-500 @enderror">
                @error('last_name')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="first_name" class="mb-2 block text-sm font-medium text-gray-700">Ім'я <span class="text-error-500">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $teacher->first_name) }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('first_name') border-error-500 @enderror">
                @error('first_name')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="middle_name" class="mb-2 block text-sm font-medium text-gray-700">По-батькові</label>
                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $teacher->middle_name) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('middle_name') border-error-500 @enderror">
                @error('middle_name')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email <span class="text-error-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $teacher->user->email) }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('email') border-error-500 @enderror">
                @error('email')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="specialization" class="mb-2 block text-sm font-medium text-gray-700">Спеціалізація <span class="text-error-500">*</span></label>
                <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $teacher->specialization) }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('specialization') border-error-500 @enderror">
                @error('specialization')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="position" class="mb-2 block text-sm font-medium text-gray-700">Посада</label>
                <input type="text" name="position" id="position" value="{{ old('position', $teacher->position) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('position') border-error-500 @enderror">
                @error('position')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="workplace" class="mb-2 block text-sm font-medium text-gray-700">Місце роботи</label>
                <input type="text" name="workplace" id="workplace" value="{{ old('workplace', $teacher->workplace) }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('workplace') border-error-500 @enderror">
                @error('workplace')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Опис</label>
            <textarea name="description" id="description" rows="4"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('description') border-error-500 @enderror">{{ old('description', $teacher->description) }}</textarea>
            @error('description')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="photo" class="mb-2 block text-sm font-medium text-gray-700">Фото</label>
            @if($teacher->avatar_url)
                <div class="mb-3 flex items-center gap-4">
                    <img src="{{ $teacher->avatar_url }}" alt="{{ $teacher->full_name }}" class="h-20 w-20 rounded-lg object-cover">
                    <span class="text-sm text-gray-500">Поточне фото</span>
                </div>
            @endif
            <input type="file" name="photo" id="photo" accept="image/*"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('photo') border-error-500 @enderror">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP (макс. 5MB). Залиште порожнім, щоб зберегти поточне фото.</p>
            @error('photo')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-3 font-medium text-white transition hover:bg-brand-600">
                Зберегти зміни
            </button>
            <a href="{{ route('admin.teachers.show', $teacher) }}" class="rounded-lg border border-gray-300 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                Скасувати
            </a>
        </div>
    </form>
</div>
@endsection
