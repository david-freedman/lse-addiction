@extends('admin.layouts.admin')

@section('title', 'Редагувати курс')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">Редагувати курс</h1>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Назва курсу <span class="text-error-500">*</span></label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $course->name) }}"
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
            >{{ old('description', $course->description) }}</textarea>
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
                    value="{{ old('price', $course->price) }}"
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
                    value="{{ old('old_price', $course->old_price) }}"
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
                    value="{{ old('discount_percentage', $course->discount_percentage) }}"
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
            selectedId: {{ old('teacher_id', $course->teacher_id) }},
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
            @if($course->banner)
                <div class="mb-3 overflow-hidden rounded-lg">
                    <img src="{{ $course->banner_url }}" alt="Current banner" class="max-w-md rounded-lg border border-gray-200">
                </div>
            @endif
            <input
                type="file"
                name="banner"
                id="banner"
                accept="image/*"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('banner') border-error-500 @enderror"
            >
            <p class="mt-1 text-xs text-gray-500">Залиште порожнім, щоб зберегти поточний банер</p>
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
                    <option value="draft" {{ old('status', $course->status->value) === 'draft' ? 'selected' : '' }}>Чернетка</option>
                    <option value="published" {{ old('status', $course->status->value) === 'published' ? 'selected' : '' }}>Опубліковано</option>
                    <option value="in_progress" {{ old('status', $course->status->value) === 'in_progress' ? 'selected' : '' }}>В процесі</option>
                    <option value="finished" {{ old('status', $course->status->value) === 'finished' ? 'selected' : '' }}>Завершений</option>
                    <option value="archived" {{ old('status', $course->status->value) === 'archived' ? 'selected' : '' }}>Архівовано</option>
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
                    <option value="upcoming" {{ old('type', $course->type?->value) === 'upcoming' ? 'selected' : '' }}>Майбутні події</option>
                    <option value="recorded" {{ old('type', $course->type?->value) === 'recorded' ? 'selected' : '' }}>Курси у запісі</option>
                    <option value="free" {{ old('type', $course->type?->value) === 'free' ? 'selected' : '' }}>Безкоштовні</option>
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
                value="{{ old('tags_input', $course->tags->pluck('name')->implode(', ')) }}"
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
                    value="{{ old('starts_at', $course->starts_at?->format('d.m.Y H:i')) }}"
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
                    value="{{ old('label', $course->label) }}"
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
            <a href="{{ route('admin.courses.show', $course) }}" class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                Скасувати
            </a>
            <button
                type="submit"
                class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20"
            >
                Оновити курс
            </button>
        </div>
    </form>
</div>

{{-- Modules Management Section --}}
<div class="mt-6 rounded-2xl border border-gray-200 bg-white" x-data="modulesManager()">
    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Модулі курсу</h2>
        <button type="button" @click="openAddModal()" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Додати модуль
        </button>
    </div>

    <div class="p-6">
        @if($course->modules->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="mt-4 text-gray-600">Модулів ще немає. Додайте перший модуль.</p>
            </div>
        @else
            <div id="modules-list" class="space-y-2">
                @foreach($course->modules as $module)
                    <div class="module-card flex items-center gap-4 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition" data-id="{{ $module->id }}">
                        <span class="handle cursor-move text-gray-400 hover:text-gray-600">⋮⋮</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $module->order + 1 }}. {{ $module->name }}</p>
                            <p class="text-sm text-gray-500">{{ $module->lessons_count }} уроків</p>
                        </div>
                        @php
                            $statusClass = match($module->status->color()) {
                                'green' => 'bg-success-100 text-success-700',
                                'purple' => 'bg-purple-100 text-purple-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                            {{ $module->status->label() }}
                        </span>
                        @if($module->has_final_test)
                            <span class="inline-flex rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700">Тест</span>
                        @endif
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.lessons.index', [$course, $module]) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50">
                                Уроки
                            </a>
                            <button type="button" @click="openEditModal({{ $module->id }}, '{{ addslashes($module->name) }}', '{{ $module->description }}', '{{ $module->status->value }}', {{ $module->has_final_test ? 'true' : 'false' }}, '{{ $module->unlock_rule->value }}')" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-100">
                                Редагувати
                            </button>
                            <button type="button" @click="deleteModule({{ $module->id }})" class="rounded-lg px-3 py-1.5 text-xs font-medium text-error-600 transition hover:bg-error-50">
                                Видалити
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Module Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal()"></div>
            <div class="relative w-full max-w-md transform rounded-2xl bg-white p-6 shadow-xl transition-all">
                <h3 class="mb-4 text-lg font-semibold text-gray-900" x-text="editingId ? 'Редагувати модуль' : 'Додати модуль'"></h3>

                <form @submit.prevent="saveModule()">
                    <div class="space-y-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Назва модуля *</label>
                            <input type="text" x-model="form.name" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Опис</label>
                            <textarea x-model="form.description" rows="3" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Статус</label>
                                <select x-model="form.status" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                                    @foreach($moduleStatuses as $status)
                                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Розблокування</label>
                                <select x-model="form.unlock_rule" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                                    @foreach($moduleUnlockRules as $rule)
                                        <option value="{{ $rule->value }}">{{ $rule->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" x-model="form.has_final_test" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                                <span class="text-sm text-gray-700">Має підсумковий тест</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" @click="closeModal()" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Скасувати
                        </button>
                        <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600" :disabled="saving">
                            <span x-show="!saving" x-text="editingId ? 'Зберегти' : 'Додати'"></span>
                            <span x-show="saving">Збереження...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
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

function modulesManager() {
    return {
        showModal: false,
        editingId: null,
        saving: false,
        form: {
            name: '',
            description: '',
            status: 'active',
            has_final_test: false,
            unlock_rule: 'none'
        },

        init() {
            const list = document.getElementById('modules-list');
            if (list) {
                new Sortable(list, {
                    handle: '.handle',
                    animation: 150,
                    onEnd: async () => {
                        const ids = [...document.querySelectorAll('.module-card')].map(el => el.dataset.id);
                        await fetch('{{ route('admin.modules.reorder', $course) }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order: ids })
                        });
                    }
                });
            }
        },

        openAddModal() {
            this.editingId = null;
            this.form = { name: '', description: '', status: 'active', has_final_test: false, unlock_rule: 'none' };
            this.showModal = true;
        },

        openEditModal(id, name, description, status, hasFinalTest, unlockRule) {
            this.editingId = id;
            this.form = { name, description: description || '', status, has_final_test: hasFinalTest, unlock_rule: unlockRule };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editingId = null;
        },

        async saveModule() {
            this.saving = true;
            const url = this.editingId
                ? '{{ route('admin.modules.update', [$course, ':id']) }}'.replace(':id', this.editingId)
                : '{{ route('admin.modules.store', $course) }}';

            try {
                const response = await fetch(url, {
                    method: this.editingId ? 'PATCH' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.form)
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    alert(data.message || 'Помилка збереження');
                }
            } catch (error) {
                alert('Помилка збереження');
            } finally {
                this.saving = false;
            }
        },

        async deleteModule(id) {
            if (!confirm('Видалити модуль? Всі уроки модуля також будуть видалені.')) return;

            try {
                const response = await fetch('{{ route('admin.modules.destroy', [$course, ':id']) }}'.replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Помилка видалення');
                }
            } catch (error) {
                alert('Помилка видалення');
            }
        }
    }
}
</script>
@endpush
@endsection
