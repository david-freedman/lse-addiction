@extends('admin.layouts.admin')

@section('title', 'Викладачі')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">Викладачі</h1>
        <p class="mt-1 text-sm text-gray-500">Всього: {{ $viewModel->totalCount() }}</p>
    </div>
    <a href="{{ route('admin.teachers.create') }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
        + Додати викладача
    </a>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form method="GET" class="mb-6" x-data="{ showFilters: {{ $viewModel->isFiltered() ? 'true' : 'false' }} }">
        <div class="mb-4 flex items-center gap-3">
            <input
                type="text"
                name="search"
                value="{{ $viewModel->filters()->search }}"
                placeholder="Пошук по імені або прізвищу..."
                class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            >
            <button type="button" @click="showFilters = !showFilters" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <span x-show="!showFilters">Показати фільтри</span>
                <span x-show="showFilters">Сховати фільтри</span>
            </button>
            @if($viewModel->isFiltered())
                <a href="{{ route('admin.teachers.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>
        </div>

        <div x-show="showFilters" x-transition class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Спеціалізація</label>
                <select name="specialization" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі спеціалізації</option>
                    @foreach($viewModel->specializations() as $specialization)
                        <option value="{{ $specialization }}" {{ $viewModel->filters()->specialization === $specialization ? 'selected' : '' }}>
                            {{ $specialization }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Місце роботи</label>
                <select name="workplace" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі місця роботи</option>
                    @foreach($viewModel->workplaces() as $workplace)
                        <option value="{{ $workplace }}" {{ $viewModel->filters()->workplace === $workplace ? 'selected' : '' }}>
                            {{ $workplace }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

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

    @if($viewModel->hasNoTeachers())
        <div class="py-12 text-center">
            <p class="text-gray-500">Викладачів не знайдено</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Викладач</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Посада</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Спеціалізація</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Дата реєстрації</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Дії</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewModel->teachers() as $teacher)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="flex items-center gap-3 hover:opacity-80">
                                    @if($teacher->avatar_url)
                                        <img src="{{ $teacher->avatar_url }}" alt="{{ $teacher->full_name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-bold text-gray-600">
                                            {{ mb_substr($teacher->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $teacher->full_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $teacher->user->email }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $teacher->position }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $teacher->specialization }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $teacher->created_at->format('d.m.Y') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                        Редагувати
                                    </a>
                                    <form action="{{ route('admin.teachers.delete', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цього викладача?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-error-600 hover:text-error-700">
                                            Видалити
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
            {{ $viewModel->teachers()->links() }}
        </div>
    @endif
</div>
@endsection
