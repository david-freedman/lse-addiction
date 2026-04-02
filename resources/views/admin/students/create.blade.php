@extends('admin.layouts.admin')

@section('title', 'Створити студента')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Створити студента</h1>
    <a href="{{ route('admin.students.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
        Назад до списку
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email <span class="text-error-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('email') border-error-500 @enderror">
                @error('email')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">Телефон <span class="text-error-500">*</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="+380XXXXXXXXX"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('phone') border-error-500 @enderror">
                @error('phone')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Ім'я <span class="text-error-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('name') border-error-500 @enderror">
                @error('name')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="surname" class="mb-2 block text-sm font-medium text-gray-700">Прізвище <span class="text-error-500">*</span></label>
                <input type="text" name="surname" id="surname" value="{{ old('surname') }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('surname') border-error-500 @enderror">
                @error('surname')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="patronymic" class="mb-2 block text-sm font-medium text-gray-700">По-батькові</label>
                <input type="text" name="patronymic" id="patronymic" value="{{ old('patronymic') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('patronymic') border-error-500 @enderror">
                @error('patronymic')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="birthday" class="mb-2 block text-sm font-medium text-gray-700">Дата народження</label>
                <input type="text" name="birthday" id="birthday" x-datepicker value="{{ old('birthday') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('birthday') border-error-500 @enderror">
                @error('birthday')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="city" class="mb-2 block text-sm font-medium text-gray-700">Місто</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('city') border-error-500 @enderror">
                @error('city')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

        </div>

        @if($fields->isNotEmpty())
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach($fields as $field)
            <div @if($field->type->value === 'tags') class="md:col-span-2" @endif>
                <label for="field_{{ $field->key }}" class="mb-2 block text-sm font-medium text-gray-700">
                    {{ $field->label }}
                    @if($field->is_required)<span class="text-error-500">*</span>@endif
                </label>

                @switch($field->type->value)
                    @case('select')
                        @php
                            $selectedValue = old('profile_fields.' . $field->key, '');
                            $selectedLabel = $field->options[$selectedValue] ?? '';
                        @endphp
                        <div x-data="{ open: false, selected: '{{ $selectedValue }}', label: '{{ $selectedLabel }}' }"
                             @click.away="open = false" class="relative">
                            <input type="hidden" name="profile_fields[{{ $field->key }}]" :value="selected">
                            <button type="button" @click="open = !open"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('profile_fields.'.$field->key) border-error-500 @enderror">
                                <span x-text="label || 'Оберіть варіант'" :class="{ 'text-gray-400': !selected }"></span>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute z-10 mt-1 max-h-48 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                                @if($field->options)
                                    @foreach($field->options as $optionKey => $optionLabel)
                                        <div class="cursor-pointer px-4 py-2 text-sm text-gray-900 hover:bg-gray-50"
                                             @click="selected = '{{ $optionKey }}'; label = '{{ $optionLabel }}'; open = false">
                                            {{ $optionLabel }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @break

                    @case('date')
                        <input type="text" x-datepicker
                            name="profile_fields[{{ $field->key }}]"
                            id="field_{{ $field->key }}"
                            value="{{ old('profile_fields.' . $field->key) }}"
                            {{ $field->is_required ? 'required' : '' }}
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('profile_fields.'.$field->key) border-error-500 @enderror">
                        @break

                    @case('number')
                        <input type="number"
                            name="profile_fields[{{ $field->key }}]"
                            id="field_{{ $field->key }}"
                            placeholder="Введіть {{ mb_strtolower($field->label) }}"
                            value="{{ old('profile_fields.' . $field->key) }}"
                            {{ $field->is_required ? 'required' : '' }}
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('profile_fields.'.$field->key) border-error-500 @enderror">
                        @break

                    @case('tags')
                        @php
                            $maxItems = $field->options['max_items'] ?? 5;
                            $existingTags = old("profile_fields.{$field->key}", []);
                            if (!is_array($existingTags)) { $existingTags = []; }
                        @endphp
                        <div x-data="adminTagInput(@js($existingTags), {{ $maxItems }}, '{{ $field->key }}')">
                            <div class="flex flex-wrap gap-1.5 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 @error('profile_fields.'.$field->key) border-error-500 @enderror"
                                 @click="$refs.tagField.focus()">
                                <template x-for="(tag, index) in tags" :key="index">
                                    <span class="inline-flex items-center gap-1 rounded bg-brand-50 px-2 py-0.5 text-sm text-brand-700">
                                        <span x-text="tag"></span>
                                        <button type="button" @click.stop="removeTag(index)" class="text-brand-400 hover:text-red-500">&times;</button>
                                    </span>
                                </template>
                                <input x-show="tags.length < maxItems"
                                    x-ref="tagField"
                                    x-model="input"
                                    @keydown.enter.prevent="addTag()"
                                    @keydown.,.prevent="addTag()"
                                    @keydown.backspace="handleBackspace()"
                                    type="text"
                                    placeholder="Введіть і натисніть Enter"
                                    class="min-w-[120px] flex-1 border-none bg-transparent text-sm outline-none">
                            </div>
                            <p class="mt-1 text-xs text-gray-500" x-text="`${tags.length}/${maxItems}`"></p>
                            <template x-for="(tag, i) in tags" :key="'h-'+i">
                                <input type="hidden" :name="`profile_fields[${fieldKey}][]`" :value="tag">
                            </template>
                        </div>
                        @break

                    @default
                        <input type="text"
                            name="profile_fields[{{ $field->key }}]"
                            id="field_{{ $field->key }}"
                            placeholder="Введіть {{ mb_strtolower($field->label) }}"
                            value="{{ old('profile_fields.' . $field->key) }}"
                            {{ $field->is_required ? 'required' : '' }}
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('profile_fields.'.$field->key) border-error-500 @enderror">
                @endswitch

                @error('profile_fields.' . $field->key)
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>
            @endforeach
        </div>
        @endif

        <div>
            <label for="profile_photo" class="mb-2 block text-sm font-medium text-gray-700">Фото профілю</label>
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('profile_photo') border-error-500 @enderror">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP (макс. 5MB)</p>
            @error('profile_photo')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="flex items-center">
                <input type="checkbox" name="email_verified" id="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <label for="email_verified" class="ml-2 text-sm font-medium text-gray-700">Email підтверджено</label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="phone_verified" id="phone_verified" value="1" {{ old('phone_verified') ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <label for="phone_verified" class="ml-2 text-sm font-medium text-gray-700">Телефон підтверджено</label>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-3 font-medium text-white transition hover:bg-brand-600">
                Створити студента
            </button>
            <a href="{{ route('admin.students.index') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                Скасувати
            </a>
        </div>
    </form>
</div>
@push('scripts')
<script>
function adminTagInput(initialTags, maxItems, fieldKey) {
    return {
        tags: initialTags || [],
        input: '',
        maxItems: maxItems,
        fieldKey: fieldKey,
        addTag() {
            const tag = this.input.trim();
            if (tag.length < 2 || tag.length > 50) { this.input = ''; return; }
            if (this.tags.length >= this.maxItems) return;
            if (this.tags.some(t => t.toLowerCase() === tag.toLowerCase())) { this.input = ''; return; }
            this.tags.push(tag);
            this.input = '';
        },
        removeTag(index) { this.tags.splice(index, 1); },
        handleBackspace() { if (this.input === '' && this.tags.length > 0) this.tags.pop(); }
    };
}
</script>
@endpush
@endsection
