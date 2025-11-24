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
                <label for="birthday" class="mb-2 block text-sm font-medium text-gray-700">Дата народження</label>
                <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}"
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
@endsection
