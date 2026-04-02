@extends('admin.layouts.admin')

@section('title', 'Додати спеціальність')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Додати спеціальність</h1>
    <a href="{{ route('admin.specialties.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
        Назад до списку
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.specialties.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Назва <span class="text-error-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('name') border-error-500 @enderror">
            @error('name')<p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-3 font-medium text-white transition hover:bg-brand-600">
                Зберегти
            </button>
            <a href="{{ route('admin.specialties.index') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                Скасувати
            </a>
        </div>
    </form>
</div>
@endsection
