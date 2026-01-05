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

        <div x-data="{
            open: false,
            search: '',
            selectedId: {{ old('teacher_id', 'null') }},
            teachers: {{ Js::from($teachers->map(fn($t) => ['id' => $t->id, 'full_name' => $t->full_name, 'position' => $t->position])) }},
            get selectedTeacher() {
                return this.teachers.find(t => t.id === this.selectedId);
            },
            get filteredTeachers() {
                if (!this.search) return this.teachers;
                const s = this.search.toLowerCase();
                return this.teachers.filter(t => t.full_name.toLowerCase().includes(s));
            }
        }">
            <label class="mb-2 block text-sm font-medium text-gray-700">Викладач <span class="text-error-500">*</span></label>
            <input type="hidden" name="teacher_id" :value="selectedId">

            <div class="relative">
                <button
                    type="button"
                    @click="open = !open"
                    class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('teacher_id') border-error-500 @enderror"
                >
                    <span x-text="selectedTeacher ? selectedTeacher.full_name : 'Оберіть викладача'"></span>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                >
                    <div class="sticky top-0 bg-white p-2">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Пошук викладача..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-brand-500"
                            @click.stop
                        >
                    </div>
                    <ul class="py-1">
                        <template x-for="teacher in filteredTeachers" :key="teacher.id">
                            <li
                                @click="selectedId = teacher.id; open = false; search = ''"
                                class="cursor-pointer px-4 py-2 hover:bg-gray-100"
                                :class="{ 'bg-brand-50': selectedId === teacher.id }"
                            >
                                <div class="font-medium" x-text="teacher.full_name"></div>
                                <div class="text-xs text-gray-500" x-text="teacher.position || ''"></div>
                            </li>
                        </template>
                        <li x-show="filteredTeachers.length === 0" class="px-4 py-2 text-sm text-gray-500">
                            Викладачів не знайдено
                        </li>
                    </ul>
                </div>
            </div>
            @error('teacher_id')
                <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
            @enderror
        </div>

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
                    @foreach(\App\Domains\Course\Enums\CourseStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
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
                    <option value="recorded" {{ old('type') === 'recorded' ? 'selected' : '' }}>Курси у записі</option>
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
                    type="text"
                    name="starts_at"
                    id="starts_at"
                    x-datepicker.datetime
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
                <select
                    name="label"
                    id="label"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('label') border-error-500 @enderror"
                >
                    <option value="">Без мітки</option>
                    @foreach(\App\Domains\Course\Enums\CourseLabel::cases() as $labelOption)
                        <option value="{{ $labelOption->value }}" {{ old('label') === $labelOption->value ? 'selected' : '' }}>
                            {{ $labelOption->label() }}
                        </option>
                    @endforeach
                </select>
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
