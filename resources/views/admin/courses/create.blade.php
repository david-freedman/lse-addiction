@extends('admin.layouts.admin')

@section('title', 'Створити курс')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Створити курс</h1>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Назва курсу <span class="text-error-500">*</span></label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                required
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('name') border-error-500 @enderror"
            >
            @error('name')
                <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Опис <span class="text-error-500">*</span></label>
            <textarea
                name="description"
                id="description"
                rows="6"
                required
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('description') border-error-500 @enderror"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label for="price" class="mb-2 block text-sm font-medium text-gray-700">Ціна (₴) <span class="text-error-500">*</span></label>
                <input
                    type="number"
                    name="price"
                    id="price"
                    value="{{ old('price') }}"
                    step="0.01"
                    min="0"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('price') border-error-500 @enderror"
                >
                @error('price')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="old_price" class="mb-2 block text-sm font-medium text-gray-700">Стара ціна (₴)</label>
                <input
                    type="number"
                    name="old_price"
                    id="old_price"
                    value="{{ old('old_price') }}"
                    step="0.01"
                    min="0"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('old_price') border-error-500 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">Для показу знижки</p>
                @error('old_price')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="discount_percentage" class="mb-2 block text-sm font-medium text-gray-700">Знижка (%)</label>
                <input
                    type="number"
                    name="discount_percentage"
                    id="discount_percentage"
                    value="{{ old('discount_percentage') }}"
                    min="0"
                    max="100"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('discount_percentage') border-error-500 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">Для відображення</p>
                @error('discount_percentage')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="coach_id" class="mb-2 block text-sm font-medium text-gray-700">Коуч (User ID) <span class="text-error-500">*</span></label>
            <input
                type="number"
                name="coach_id"
                id="coach_id"
                value="{{ old('coach_id', 1) }}"
                required
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('coach_id') border-error-500 @enderror"
            >
            @error('coach_id')
                <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="author_id" value="{{ auth()->guard('web')->user()->id ?? 1 }}">

        <div>
            <label for="banner" class="mb-2 block text-sm font-medium text-gray-700">Банер</label>
            <input
                type="file"
                name="banner"
                id="banner"
                accept="image/*"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('banner') border-error-500 @enderror"
            >
            @error('banner')
                <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Статус <span class="text-error-500">*</span></label>
                <select
                    name="status"
                    id="status"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('status') border-error-500 @enderror"
                >
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Чернетка</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Опубліковано</option>
                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>В процесі</option>
                    <option value="finished" {{ old('status') === 'finished' ? 'selected' : '' }}>Завершений</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Архівовано</option>
                </select>
                @error('status')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="mb-2 block text-sm font-medium text-gray-700">Тип курсу</label>
                <select
                    name="type"
                    id="type"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('type') border-error-500 @enderror"
                >
                    <option value="">Не вказано</option>
                    <option value="upcoming" {{ old('type') === 'upcoming' ? 'selected' : '' }}>Майбутні події</option>
                    <option value="recorded" {{ old('type') === 'recorded' ? 'selected' : '' }}>Курси у запісі</option>
                    <option value="free" {{ old('type') === 'free' ? 'selected' : '' }}>Безкоштовні</option>
                </select>
                @error('type')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="tags_input" class="mb-2 block text-sm font-medium text-gray-700">Теги</label>
            <input
                type="text"
                name="tags_input"
                id="tags_input"
                value="{{ old('tags_input') }}"
                placeholder="наприклад: психологія, групова терапія, онлайн"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            >
            <p class="mt-1 text-xs text-gray-500">Введіть теги через кому</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="starts_at" class="mb-2 block text-sm font-medium text-gray-700">Дата початку</label>
                <input
                    type="datetime-local"
                    name="starts_at"
                    id="starts_at"
                    value="{{ old('starts_at') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('starts_at') border-error-500 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">Залиште порожнім, якщо дата не вказана</p>
                @error('starts_at')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="label" class="mb-2 block text-sm font-medium text-gray-700">Мітка (Label)</label>
                <input
                    type="text"
                    name="label"
                    id="label"
                    value="{{ old('label') }}"
                    placeholder="наприклад: BESTSELLER, NEW"
                    maxlength="50"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('label') border-error-500 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">Промо-мітка (макс. 50 символів)</p>
                @error('label')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-gray-200 pt-5">
            <a href="{{ route('admin.courses.index') }}" class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                Скасувати
            </a>
            <button
                type="submit"
                class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20"
            >
                Створити курс
            </button>
        </div>
    </form>
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
