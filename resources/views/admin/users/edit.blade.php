@extends('admin.layouts.admin')

@section('title', 'Редагувати користувача')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Редагувати користувача</h1>
    <div class="flex gap-3">
        <a href="{{ route('admin.users.show', $viewModel->user()) }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
            Переглянути
        </a>
        <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
            До списку
        </a>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.users.update', $viewModel->user()) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Ім'я <span class="text-error-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $viewModel->user()->name) }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('name') border-error-500 @enderror">
                @error('name')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email <span class="text-error-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $viewModel->user()->email) }}" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('email') border-error-500 @enderror">
                @error('email')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="role" class="mb-2 block text-sm font-medium text-gray-700">Роль <span class="text-error-500">*</span></label>
                <select name="role" id="role" required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('role') border-error-500 @enderror">
                    <option value="">Оберіть роль</option>
                    @foreach($viewModel->roles() as $role)
                        <option value="{{ $role->value }}" {{ old('role', $viewModel->user()->role?->value) === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Поточне фото</label>
            @if($viewModel->user()->photo)
                <img src="{{ str_starts_with($viewModel->user()->photo, 'http') ? $viewModel->user()->photo : (str_starts_with($viewModel->user()->photo, 'img/') ? asset($viewModel->user()->photo) : Storage::url($viewModel->user()->photo)) }}" alt="{{ $viewModel->user()->name }}" class="mb-3 h-20 w-20 rounded-full object-cover">
            @else
                <p class="mb-3 text-sm text-gray-500">Фото не завантажено</p>
            @endif
            <label for="photo" class="mb-2 block text-sm font-medium text-gray-700">Нове фото</label>
            <input type="file" name="photo" id="photo" accept="image/*"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('photo') border-error-500 @enderror">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP (макс. 5MB)</p>
            @error('photo')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $viewModel->user()->is_active) ? 'checked' : '' }}
                class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Активний</label>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-3 font-medium text-white transition hover:bg-brand-600">
                Зберегти зміни
            </button>
            <a href="{{ route('admin.users.show', $viewModel->user()) }}" class="rounded-lg border border-gray-300 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                Скасувати
            </a>
        </div>
    </form>
</div>
@endsection
