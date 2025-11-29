@extends('layouts.app')

@section('title', 'Профіль та налаштування')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Профіль та налаштування</h1>
        <p class="mt-1 text-sm text-gray-600">Керуйте своїм профілем, безпекою та налаштуваннями</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button type="button" data-tab="profile" class="tab-button flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 border-teal-500 text-teal-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Профіль
                </button>
                <button type="button" data-tab="profile-fields" class="tab-button flex items-center gap-2 px-6 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Анкетні дані
                </button>
            </nav>
        </div>

        <div id="profile-tab" class="tab-content">
            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PATCH')

                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Фото профілю</h2>
                    <div class="flex items-center gap-6">
                        <div class="relative group">
                            @if($student->hasProfilePhoto())
                                <img id="profile-photo-preview" src="{{ $student->profile_photo_url }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover">
                            @else
                                <div id="profile-photo-preview" class="w-24 h-24 rounded-full bg-teal-500 flex items-center justify-center">
                                    <span class="text-white text-2xl font-semibold">{{ $student->initials }}</span>
                                </div>
                            @endif
                            <label for="profile_photo" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png" class="hidden">
                        </div>
                        <div>
                            <button type="button" onclick="document.getElementById('profile_photo').click()" class="px-4 py-2 text-sm font-medium text-teal-600 hover:text-teal-700">
                                Завантажити нове фото
                            </button>
                            <p class="mt-1 text-xs text-gray-500">
                                Рекомендований розмір: 400x400px. Формат: JPG, PNG
                            </p>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Особиста інформація</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Ім'я</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">Прізвище</label>
                            <input type="text" name="surname" id="surname" value="{{ old('surname', $student->surname) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            @error('surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" placeholder="{{ $student->email }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Після зміни email вам буде відправлено код підтвердження</p>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $student->phone) }}" placeholder="+380XXXXXXXXX" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Після зміни телефону вам буде відправлено код підтвердження</p>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birthday" class="block text-sm font-medium text-gray-700 mb-2">Дата народження</label>
                            <input type="text" name="birthday" id="birthday" x-datepicker value="{{ old('birthday', $student->birthday?->format('d.m.Y')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Вам повинно бути не менше 18 років</p>
                            @error('birthday')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Місто</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $student->city) }}" placeholder="Введіть своє місто" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-teal-500 text-white font-medium rounded-lg hover:bg-teal-600 transition focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Зберегти зміни
                    </button>
                </div>
            </form>
        </div>

        <div id="profile-fields-tab" class="tab-content hidden">
            <form action="{{ route('student.profile.profile-fields.update') }}" method="POST" class="p-8">
                @csrf
                @method('PATCH')

                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Додаткова інформація</h2>

                    @if($profileFields->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Наразі немає доступних анкетних полів</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($profileFields as $field)
                                <div>
                                    <label for="field_{{ $field->key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span class="text-red-600">*</span>
                                        @endif
                                    </label>

                                    @switch($field->type->value)
                                        @case('select')
                                            <select name="profile_fields[{{ $field->key }}]"
                                                id="field_{{ $field->key }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                                {{ $field->is_required ? 'required' : '' }}>
                                                <option value="">Оберіть варіант</option>
                                                @if($field->options)
                                                    @foreach($field->options as $optionKey => $optionLabel)
                                                        <option value="{{ $optionKey }}"
                                                            {{ old('profile_fields.' . $field->key, $existingValues[$field->id] ?? '') == $optionKey ? 'selected' : '' }}>
                                                            {{ $optionLabel }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break

                                        @case('date')
                                            <input type="text"
                                                x-datepicker
                                                name="profile_fields[{{ $field->key }}]"
                                                id="field_{{ $field->key }}"
                                                value="{{ old('profile_fields.' . $field->key, $existingValues[$field->id] ?? '') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                                {{ $field->is_required ? 'required' : '' }}>
                                            @break

                                        @case('number')
                                            <input type="number"
                                                name="profile_fields[{{ $field->key }}]"
                                                id="field_{{ $field->key }}"
                                                placeholder="Введіть {{ mb_strtolower($field->label) }}"
                                                value="{{ old('profile_fields.' . $field->key, $existingValues[$field->id] ?? '') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                                {{ $field->is_required ? 'required' : '' }}>
                                            @break

                                        @default
                                            <input type="text"
                                                name="profile_fields[{{ $field->key }}]"
                                                id="field_{{ $field->key }}"
                                                placeholder="Введіть {{ mb_strtolower($field->label) }}"
                                                value="{{ old('profile_fields.' . $field->key, $existingValues[$field->id] ?? '') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                                {{ $field->is_required ? 'required' : '' }}>
                                    @endswitch

                                    @error('profile_fields.' . $field->key)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($profileFields->isNotEmpty())
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                        <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-teal-500 text-white font-medium rounded-lg hover:bg-teal-600 transition focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Зберегти зміни
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-photo-preview');
            preview.innerHTML = '';
            preview.className = 'w-24 h-24 rounded-full object-cover';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-24 h-24 rounded-full object-cover';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});

const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.getAttribute('data-tab');

        tabButtons.forEach(btn => {
            btn.classList.remove('border-teal-500', 'text-teal-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        button.classList.remove('border-transparent', 'text-gray-500');
        button.classList.add('border-teal-500', 'text-teal-600');

        tabContents.forEach(content => {
            content.classList.add('hidden');
        });

        document.getElementById(tabName + '-tab').classList.remove('hidden');
    });
});
</script>
@endpush
@endsection
