@extends('admin.layouts.admin')

@section('title', 'Викладач: ' . $viewModel->teacher()->full_name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-title-xl font-bold text-gray-900">Профіль викладача</h1>
    <a href="{{ route('admin.teachers.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
        Назад до списку
    </a>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg bg-success-100 p-4 text-sm text-success-800">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded-lg bg-error-100 p-4 text-sm text-error-800">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex flex-col items-center text-center">
            @if($viewModel->teacher()->user->photo)
                <img src="{{ str_starts_with($viewModel->teacher()->user->photo, 'http') ? $viewModel->teacher()->user->photo : (str_starts_with($viewModel->teacher()->user->photo, 'img/') ? asset($viewModel->teacher()->user->photo) : Storage::url($viewModel->teacher()->user->photo)) }}" alt="{{ $viewModel->teacher()->full_name }}" class="mb-4 h-32 w-32 rounded-full object-cover">
            @else
                <div class="mb-4 flex h-32 w-32 items-center justify-center rounded-full bg-gray-200 text-4xl font-bold text-gray-600">
                    {{ mb_substr($viewModel->teacher()->last_name, 0, 1) }}
                </div>
            @endif
            <h2 class="text-xl font-bold text-gray-900">{{ $viewModel->teacher()->full_name }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $viewModel->teacher()->user->email }}</p>
            <p class="mt-2 inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                {{ $viewModel->teacher()->specialization }}
            </p>
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <a href="{{ route('admin.teachers.edit', $viewModel->teacher()) }}" class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-brand-600">
                Редагувати
            </a>
            @if($viewModel->canDelete())
                <form action="{{ route('admin.teachers.delete', $viewModel->teacher()) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цього викладача?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg border border-error-300 bg-white px-4 py-2.5 text-sm font-medium text-error-600 transition hover:bg-error-50">
                        Видалити
                    </button>
                </form>
            @else
                <div class="rounded-lg bg-warning-50 p-3 text-sm text-warning-700">
                    {{ $viewModel->deleteBlockedReason() }}
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Інформація про викладача</h3>

            <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">Прізвище</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->last_name }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">Ім'я</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->first_name }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">По-батькові</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->middle_name ?: '—' }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">Спеціалізація</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->specialization }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">Посада</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->position ?: '—' }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">Місце роботи</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->workplace ?: '—' }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Опис</dt>
                    <dd class="mt-1 whitespace-pre-wrap text-base text-gray-900">{{ $viewModel->teacher()->description ?: '—' }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">Дата створення</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->created_at->format('d.m.Y H:i') }}</dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-sm font-medium text-gray-500">Останнє оновлення</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $viewModel->teacher()->updated_at->format('d.m.Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
