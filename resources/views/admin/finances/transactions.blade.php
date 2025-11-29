@extends('admin.layouts.admin')

@section('title', 'Транзакції')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">Транзакції</h1>
        <p class="mt-1 text-sm text-gray-500">Перегляд та фільтрація всіх платежів</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.finances.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            ← Назад до фінансів
        </a>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Експорт
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-2 w-40 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                <a href="{{ route('admin.finances.transactions.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Експорт CSV
                </a>
                <a href="{{ route('admin.finances.transactions.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Експорт Excel
                </a>
            </div>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form method="GET" class="mb-6" x-data="{ showFilters: {{ $listViewModel->isFiltered() ? 'true' : 'false' }} }">
        <div class="mb-4 flex items-center gap-3">
            <input
                type="text"
                name="search"
                value="{{ $listViewModel->filters()->search }}"
                placeholder="Пошук по номеру, імені, email..."
                class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            >
            <button type="button" @click="showFilters = !showFilters" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <span x-show="!showFilters">Показати фільтри</span>
                <span x-show="showFilters">Сховати фільтри</span>
            </button>
            @if($listViewModel->isFiltered())
                <a href="{{ route('admin.finances.transactions') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Скинути
                </a>
            @endif
            <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Фільтрувати
            </button>

        </div>

        <div x-show="showFilters" x-transition class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Статус</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі статуси</option>
                    @foreach($listViewModel->statuses() as $status)
                        <option value="{{ $status->value }}" {{ $listViewModel->filters()->status?->value === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Студент</label>
                <select name="student_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі студенти</option>
                    @foreach($listViewModel->students() as $student)
                        <option value="{{ $student->id }}" {{ $listViewModel->filters()->student_id == $student->id ? 'selected' : '' }}>
                            {{ $student->surname }} {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Курс</label>
                <select name="course_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі курси</option>
                    @foreach($listViewModel->courses() as $course)
                        <option value="{{ $course->id }}" {{ $listViewModel->filters()->course_id == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Метод оплати</label>
                <select name="payment_method" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5">
                    <option value="">Всі методи</option>
                    @foreach($listViewModel->paymentMethods() as $method)
                        <option value="{{ $method->value }}" {{ $listViewModel->filters()->payment_method?->value === $method->value ? 'selected' : '' }}>
                            {{ $method->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Сума від</label>
                <input
                    type="number"
                    name="amount_from"
                    value="{{ $listViewModel->filters()->amount_from }}"
                    placeholder="0"
                    step="0.01"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Сума до</label>
                <input
                    type="number"
                    name="amount_to"
                    value="{{ $listViewModel->filters()->amount_to }}"
                    placeholder="0"
                    step="0.01"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Дата (від)</label>
                <input
                    type="text"
                    name="created_from"
                    x-datepicker
                    value="{{ $listViewModel->filters()->created_from?->format('d.m.Y') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Дата (до)</label>
                <input
                    type="text"
                    name="created_to"
                    x-datepicker
                    value="{{ $listViewModel->filters()->created_to?->format('d.m.Y') }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Номер транзакції</label>
                <input
                    type="text"
                    name="transaction_number"
                    value="{{ $listViewModel->filters()->transaction_number }}"
                    placeholder="TXN-..."
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5"
                >
            </div>
        </div>
    </form>

    <div class="mb-4 flex items-center justify-between border-b border-gray-200 pb-4">
        <p class="text-sm text-gray-600">Всього: <span class="font-semibold">{{ $listViewModel->totalCount() }}</span> транзакцій</p>
    </div>

    @if($listViewModel->hasNoTransactions())
        <div class="py-12 text-center">
            <p class="text-gray-500">Транзакцій не знайдено</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Номер</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Студент</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Курс</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600">Сума</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-600">Статус</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Метод</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">Дата</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($listViewModel->transactions() as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm text-gray-900">{{ $transaction->transaction_number }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($transaction->student)
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $transaction->student->name }} {{ $transaction->student->surname }}</p>
                                        <p class="text-sm text-gray-500">{{ $transaction->student->email->value }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-900">{{ $transaction->purchasable?->name ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-medium text-gray-900">{{ $transaction->formatted_amount }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span @class([
                                    'inline-flex rounded-full px-2 py-1 text-xs font-medium',
                                    'bg-gray-100 text-gray-700' => $transaction->status->color() === 'gray',
                                    'bg-orange-100 text-orange-700' => $transaction->status->color() === 'orange',
                                    'bg-green-100 text-green-700' => $transaction->status->color() === 'green',
                                    'bg-red-100 text-red-700' => $transaction->status->color() === 'red',
                                ])>
                                    {{ $transaction->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $transaction->payment_method?->label() ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $transaction->created_at->format('d.m.Y H:i') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $listViewModel->transactions()->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
