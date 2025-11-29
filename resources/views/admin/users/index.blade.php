@extends('admin.layouts.admin')

@section('title', 'Користувачі')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">Користувачі</h1>
        <p class="mt-1 text-sm text-gray-500">Всього: {{ $viewModel->totalCount() }}</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        + Додати користувача
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form method="GET" class="mb-6" x-data="{ showFilters: {{ $viewModel->isFiltered() ? 'true' : 'false' }} }">
        <div class="mb-4 flex items-center gap-3">
            <input
                type="text"
                name="search"
                value="{{ $viewModel->filters()->search }}"
                placeholder="Пошук по імені, email..."
                class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            >
            <button type="button" @click="showFilters = !showFilters" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <span x-show="!showFilters">Показати фільтри</span>
                <span x-show="showFilters">Сховати фільтри</span>
            </button>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
        </div>

        <div x-show="showFilters" x-transition class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Роль</label>
                <select name="role" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі ролі</option>
                    @foreach($viewModel->roles() as $role)
                        <option value="{{ $role->value }}" {{ $viewModel->filters()->role === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Статус</label>
                <select name="is_active" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі статуси</option>
                    <option value="1" {{ $viewModel->filters()->is_active === true ? 'selected' : '' }}>Активний</option>
                    <option value="0" {{ $viewModel->filters()->is_active === false ? 'selected' : '' }}>Неактивний</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Дата реєстрації (від)</label>
                <input
                    type="text"
                    name="created_from"
                    x-datepicker
                    value="{{ $viewModel->filters()->created_from?->format('d.m.Y') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Дата реєстрації (до)</label>
                <input
                    type="text"
                    name="created_to"
                    x-datepicker
                    value="{{ $viewModel->filters()->created_to?->format('d.m.Y') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>
        </div>
    </form>

    @if($viewModel->hasNoUsers())
        <div class="py-12 text-center">
            <p class="text-gray-500">Користувачів не знайдено</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Користувач</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Роль</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Статус</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Реєстрація</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Дії</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewModel->users() as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3 hover:opacity-80">
                                    @if($user->photo)
                                        <img src="{{ str_starts_with($user->photo, 'http') ? $user->photo : (str_starts_with($user->photo, 'img/') ? asset($user->photo) : Storage::url($user->photo)) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-bold text-gray-600">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->role)
                                    <span class="inline-flex items-center rounded-full {{ $user->role === \App\Models\Enums\UserRole::Admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} px-2.5 py-0.5 text-xs font-medium">
                                        {{ $user->role->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($user->is_active)
                                    <span class="inline-flex items-center rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-800">
                                        Активний
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-error-100 px-2.5 py-0.5 text-xs font-medium text-error-800">
                                        Неактивний
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $user->created_at->format('d.m.Y') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                        Редагувати
                                    </a>
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm font-medium {{ $user->is_active ? 'text-warning-600 hover:text-warning-700' : 'text-success-600 hover:text-success-700' }}">
                                            {{ $user->is_active ? 'Деактивувати' : 'Активувати' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $viewModel->users()->links() }}
        </div>
    @endif
</div>
@endsection
