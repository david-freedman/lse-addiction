@extends('admin.layouts.admin')

@section('title', $viewModel->user()->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">{{ $viewModel->user()->name }}</h1>
        @if($viewModel->user()->is_active)
            <span class="mt-2 inline-block rounded-full bg-success-100 px-3 py-1 text-xs font-medium text-success-700">Активний</span>
        @else
            <span class="mt-2 inline-block rounded-full bg-error-100 px-3 py-1 text-xs font-medium text-error-700">Неактивний</span>
        @endif
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.users.edit', $viewModel->user()) }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
            Редагувати
        </a>
        <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
            До списку
        </a>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-1">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="text-center">
                @if($viewModel->user()->photo)
                    <img src="{{ str_starts_with($viewModel->user()->photo, 'http') ? $viewModel->user()->photo : (str_starts_with($viewModel->user()->photo, 'img/') ? asset($viewModel->user()->photo) : Storage::url($viewModel->user()->photo)) }}" alt="{{ $viewModel->user()->name }}" class="mx-auto mb-4 h-32 w-32 rounded-full object-cover">
                @else
                    <div class="mx-auto mb-4 flex h-32 w-32 items-center justify-center rounded-full bg-gray-200 text-4xl font-bold text-gray-600">
                        {{ mb_substr($viewModel->user()->name, 0, 1) }}
                    </div>
                @endif
                <h3 class="text-lg font-bold text-gray-900">{{ $viewModel->user()->name }}</h3>
                <p class="text-sm text-gray-500">ID: {{ $viewModel->user()->id }}</p>
            </div>

            <div class="mt-6 space-y-3">
                <div>
                    <p class="text-xs font-medium text-gray-500">Email</p>
                    <p class="text-sm text-gray-900">{{ $viewModel->user()->email }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500">Роль</p>
                    @if($viewModel->user()->role)
                        <span class="inline-flex items-center rounded-full {{ $viewModel->user()->role === \App\Models\Enums\UserRole::Admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} px-2.5 py-0.5 text-xs font-medium">
                            {{ $viewModel->user()->role->label() }}
                        </span>
                    @else
                        <p class="text-sm text-gray-400">Не призначено</p>
                    @endif
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500">Зареєстровано</p>
                    <p class="text-sm text-gray-900">{{ $viewModel->user()->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <form action="{{ route('admin.users.toggle-status', $viewModel->user()) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full rounded-lg {{ $viewModel->user()->is_active ? 'bg-warning-500 hover:bg-warning-600' : 'bg-success-500 hover:bg-success-600' }} px-4 py-2.5 text-sm font-medium text-white transition">
                        {{ $viewModel->user()->is_active ? 'Деактивувати' : 'Активувати' }}
                    </button>
                </form>

                <form action="{{ route('admin.users.delete', $viewModel->user()) }}" method="POST" onsubmit="return confirm('Видалити користувача?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-error-600">
                        Видалити користувача
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-lg font-bold text-gray-900">Статистика</h3>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $viewModel->authoredCoursesCount() }}</p>
                    <p class="text-sm text-gray-500">Авторських курсів</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
