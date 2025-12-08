@extends('admin.layouts.admin')

@section('title', 'Редагувати групу')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Редагувати групу</h1>
    <a href="{{ route('admin.student-groups.show', $studentGroup) }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
        Назад
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.student-groups.update', $studentGroup) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Назва групи <span class="text-error-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $studentGroup->name) }}" required
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('name') border-error-500 @enderror">
            @error('name')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Опис</label>
            <textarea name="description" id="description" rows="3"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('description') border-error-500 @enderror">{{ old('description', $studentGroup->description) }}</textarea>
            @error('description')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="course_id" class="mb-2 block text-sm font-medium text-gray-700">Курс</label>
            <select name="course_id" id="course_id"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('course_id') border-error-500 @enderror">
                <option value="">Без курсу</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $studentGroup->course_id) == $course->id ? 'selected' : '' }}>
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>
            @error('course_id')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-3 font-medium text-white transition hover:bg-brand-600">
                Зберегти зміни
            </button>
            <a href="{{ route('admin.student-groups.show', $studentGroup) }}" class="rounded-lg border border-gray-300 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                Скасувати
            </a>
        </div>
    </form>
</div>
@endsection
