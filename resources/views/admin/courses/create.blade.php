@extends('layouts.app')

@section('title', 'Створити курс')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Створити курс</h1>

    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Назва курсу</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Опис</label>
                <textarea name="description" id="description" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Ціна (грн)</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" required>
                @error('price')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="coach_id" class="block text-gray-700 text-sm font-bold mb-2">Коуч (User ID)</label>
                <input type="number" name="coach_id" id="coach_id" value="{{ old('coach_id', 1) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('coach_id') border-red-500 @enderror" required>
                @error('coach_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="author_id" value="{{ auth()->guard('web')->user()->id ?? 1 }}">

            <div class="mb-4">
                <label for="banner" class="block text-gray-700 text-sm font-bold mb-2">Банер</label>
                <input type="file" name="banner" id="banner" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('banner') border-red-500 @enderror">
                @error('banner')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Статус</label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Чернетка</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Опубліковано</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Архівовано</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">Теги (через кому)</label>
                <input type="text" name="tags_input" id="tags_input" value="{{ old('tags_input') }}" placeholder="наприклад: психологія, групова терапія, онлайн" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-gray-600 text-xs mt-1">Введіть теги через кому</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Створити курс
                </button>
                <a href="{{ route('admin.courses.index') }}" class="text-gray-600 hover:text-gray-800">Скасувати</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('tags_input').addEventListener('change', function(e) {
    const tagsInput = e.target.value;
    const tagsArray = tagsInput.split(',').map(tag => tag.trim()).filter(tag => tag);

    const form = e.target.closest('form');
    form.querySelectorAll('input[name="tags[]"]').forEach(input => input.remove());

    tagsArray.forEach(tag => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = tag;
        form.appendChild(input);
    });
});

document.querySelector('form').addEventListener('submit', function(e) {
    const tagsInput = document.getElementById('tags_input').value;
    const tagsArray = tagsInput.split(',').map(tag => tag.trim()).filter(tag => tag);

    this.querySelectorAll('input[name="tags[]"]').forEach(input => input.remove());

    tagsArray.forEach(tag => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = tag;
        this.appendChild(input);
    });
});
</script>
@endpush
@endsection
