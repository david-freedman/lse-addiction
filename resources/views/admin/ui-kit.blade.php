@extends('admin.layouts.admin')

@section('title', 'UI Kit - Компоненти')

@section('content')
<div class="mb-6">
    <h1 class="text-title-xl font-bold text-gray-900">UI Kit</h1>
    <p class="mt-2 text-sm text-gray-600">Демонстрація всіх доступних компонентів TailAdmin дизайн-системи</p>
</div>

{{-- СЕКЦІЯ 1: КНОПКИ --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Кнопки</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Базові кнопки</h3>
            <div class="flex flex-wrap gap-3">
                <button class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20">
                    Primary
                </button>
                <button class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Secondary
                </button>
                <button class="rounded-lg bg-success-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-success-600">
                    Success
                </button>
                <button class="rounded-lg bg-error-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-error-600">
                    Error
                </button>
                <button class="rounded-lg bg-warning-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-warning-600">
                    Warning
                </button>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Розміри</h3>
            <div class="flex flex-wrap items-center gap-3">
                <button class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-brand-600">
                    Small
                </button>
                <button class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                    Medium
                </button>
                <button class="rounded-lg bg-brand-500 px-6 py-3 text-base font-medium text-white transition hover:bg-brand-600">
                    Large
                </button>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Кнопки з іконками</h3>
            <div class="flex flex-wrap gap-3">
                <button class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Додати
                </button>
                <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Завантажити
                </button>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Outline кнопки</h3>
            <div class="flex flex-wrap gap-3">
                <button class="rounded-lg border-2 border-brand-500 bg-transparent px-5 py-2.5 text-sm font-medium text-brand-600 transition hover:bg-brand-50">
                    Primary Outline
                </button>
                <button class="rounded-lg border-2 border-success-500 bg-transparent px-5 py-2.5 text-sm font-medium text-success-600 transition hover:bg-success-50">
                    Success Outline
                </button>
                <button class="rounded-lg border-2 border-error-500 bg-transparent px-5 py-2.5 text-sm font-medium text-error-600 transition hover:bg-error-50">
                    Error Outline
                </button>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Icon-only кнопки</h3>
            <div class="flex flex-wrap items-center gap-3">
                <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-brand-500 text-white transition hover:bg-brand-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
                <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>
                <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-error-500 text-white transition hover:bg-error-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-success-500 text-white transition hover:bg-success-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Кнопки з іконками справа</h3>
            <div class="flex flex-wrap gap-3">
                <button class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                    Наступний крок
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
                <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Експорт
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Стани</h3>
            <div class="flex flex-wrap gap-3">
                <button disabled class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white opacity-50 cursor-not-allowed">
                    Disabled
                </button>
                <button class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading...
                </button>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 2: ФОРМИ --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Форми</h2>

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="demo-input" class="mb-2 block text-sm font-medium text-gray-700">Текстове поле</label>
                <input
                    type="text"
                    id="demo-input"
                    placeholder="Введіть текст..."
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>

            <div>
                <label for="demo-input-error" class="mb-2 block text-sm font-medium text-gray-700">Поле з помилкою</label>
                <input
                    type="text"
                    id="demo-input-error"
                    value="Невірне значення"
                    class="w-full rounded-lg border border-error-500 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-error-500 focus:bg-white"
                >
                <p class="mt-1.5 text-sm text-error-600">Це поле обов'язкове для заповнення</p>
            </div>

            <div>
                <label for="demo-select" class="mb-2 block text-sm font-medium text-gray-700">Випадаючий список</label>
                <select
                    id="demo-select"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
                    <option>Оберіть опцію...</option>
                    <option>Опція 1</option>
                    <option>Опція 2</option>
                    <option>Опція 3</option>
                </select>
            </div>

            <div>
                <label for="demo-file" class="mb-2 block text-sm font-medium text-gray-700">Файл</label>
                <input
                    type="file"
                    id="demo-file"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>
        </div>

        <div>
            <label for="demo-textarea" class="mb-2 block text-sm font-medium text-gray-700">Текстова область</label>
            <textarea
                id="demo-textarea"
                rows="4"
                placeholder="Введіть опис..."
                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            ></textarea>
        </div>

        <div>
            <label class="mb-3 block text-sm font-medium text-gray-700">Checkboxes</label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Опція 1 (вибрано)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Опція 2</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Опція 3</span>
                </label>
            </div>
        </div>

        <div>
            <label class="mb-3 block text-sm font-medium text-gray-700">Radio buttons</label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="demo-radio" checked class="h-4 w-4 border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Варіант 1 (вибрано)</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="demo-radio" class="h-4 w-4 border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Варіант 2</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="demo-radio" class="h-4 w-4 border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span class="ml-2 text-sm text-gray-700">Варіант 3</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="demo-date" class="mb-2 block text-sm font-medium text-gray-700">Дата</label>
                <input
                    type="date"
                    id="demo-date"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>

            <div>
                <label for="demo-time" class="mb-2 block text-sm font-medium text-gray-700">Час</label>
                <input
                    type="time"
                    id="demo-time"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
                >
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 3: КАРТКИ --}}
<div class="mb-8 space-y-6">
    <h2 class="text-xl font-semibold text-gray-900">Картки</h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Всього користувачів</h3>
                    <p class="text-2xl font-bold text-gray-900">1,234</p>
                    <div class="mt-2 flex items-center gap-1">
                        <svg class="fill-success-500 h-4 w-4" viewBox="0 0 16 16">
                            <path d="M8 3.33333V12.6667M8 3.33333L12 7.33333M8 3.33333L4 7.33333" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span class="text-sm font-medium text-success-600">+12%</span>
                        <span class="text-xs text-gray-400 ml-1">за місяць</span>
                    </div>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50">
                    <svg class="fill-brand-500 h-6 w-6" viewBox="0 0 24 24" fill="none">
                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Дохід</h3>
                    <p class="text-2xl font-bold text-gray-900">45,678 ₴</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-success-50">
                    <svg class="fill-success-500 h-6 w-6" viewBox="0 0 24 24" fill="none">
                        <path d="M12 1V23M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Активні курси</h3>
                    <p class="text-2xl font-bold text-gray-900">24</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-warning-50">
                    <svg class="fill-warning-500 h-6 w-6" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2"/>
                        <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Нові заявки</h3>
                    <p class="text-2xl font-bold text-gray-900">89</p>
                    <div class="mt-2 flex items-center gap-1">
                        <svg class="fill-error-500 h-4 w-4" viewBox="0 0 16 16">
                            <path d="M8 12.6667V3.33333M8 12.6667L4 8.66667M8 12.6667L12 8.66667" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span class="text-sm font-medium text-error-600">-5%</span>
                        <span class="text-xs text-gray-400 ml-1">за місяць</span>
                    </div>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-error-50">
                    <svg class="fill-error-500 h-6 w-6" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12h6m-6 0a6 6 0 11-6-6 6 6 0 016 6z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
            <div class="h-32 bg-gradient-to-br from-brand-500 to-brand-600"></div>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Картка з зображенням</h3>
                <p class="text-sm text-gray-600">Приклад картки з header-зображенням та текстовим контентом.</p>
                <button class="mt-4 text-sm font-medium text-brand-600 hover:text-brand-700">Детальніше →</button>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Info Card</h3>
            <p class="text-sm text-gray-600 mb-4">Проста інформаційна картка з текстовим контентом та кнопкою дії.</p>
            <div class="flex gap-2">
                <button class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Дія 1</button>
                <button class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Дія 2</button>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Card з Header/Footer</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600">Контент картки з окремим header та footer розділами.</p>
            </div>
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                <p class="text-xs text-gray-500">Footer інформація</p>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 4: ТАБЛИЦІ --}}
<div class="mb-8">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Таблиці</h2>

    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Користувачі системи</h3>
                <div class="flex items-center gap-3">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Фільтри
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 top-full z-50 mt-2 w-56 rounded-lg border border-gray-200 bg-white p-4 shadow-lg" style="display: none;">
                            <div class="space-y-3">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-700">Роль</label>
                                    <select class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                                        <option>Всі</option>
                                        <option>Адміністратор</option>
                                        <option>Коуч</option>
                                        <option>Студент</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-700">Статус</label>
                                    <select class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                                        <option>Всі</option>
                                        <option>Активний</option>
                                        <option>Неактивний</option>
                                    </select>
                                </div>
                                <button class="w-full rounded-lg bg-brand-500 px-3 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                    Застосувати
                                </button>
                            </div>
                        </div>
                    </div>
                    <button class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-3 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Додати
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ім'я</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Роль</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Дії</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($demoTableData as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $row['id'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $row['email'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $row['role'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($row['status'] === 'active')
                                    <span class="inline-flex rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-700">Активний</span>
                                @elseif($row['status'] === 'pending')
                                    <span class="inline-flex rounded-full bg-warning-100 px-2.5 py-0.5 text-xs font-medium text-warning-700">Очікує</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Неактивний</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50">Редагувати</button>
                                    <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-error-600 transition hover:bg-error-50">Видалити</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Показано <span class="font-medium">1</span> - <span class="font-medium">5</span> з <span class="font-medium">47</span> результатів
                </div>
                <nav class="flex items-center gap-2">
                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-brand-500 bg-brand-500 text-sm font-medium text-white">1</button>
                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</button>
                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</button>
                    <span class="text-gray-400">...</span>
                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">10</button>
                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <h3 class="mt-4 text-sm font-medium text-gray-900">Empty State</h3>
        <p class="mt-2 text-sm text-gray-500">Приклад порожнього стану, коли немає даних для відображення.</p>
        <button class="mt-4 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
            Додати перший запис
        </button>
    </div>
</div>

{{-- СЕКЦІЯ 5: ГРАФІКИ --}}
<div class="mb-8">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Графіки (ApexCharts)</h2>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Лінійний графік</h3>
            <div id="lineChart" style="height: 300px;"></div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Стовпчиковий графік</h3>
            <div id="barChart" style="height: 300px;"></div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Area Chart</h3>
            <div id="areaChart" style="height: 300px;"></div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Donut Chart</h3>
            <div id="donutChart" style="height: 300px;"></div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 6: ALERTS & BADGES --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Alerts & Badges</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Alerts з іконками та закриттям</h3>
            <div class="space-y-3">
                <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 rounded-lg border border-success-200 bg-success-50 px-4 py-3">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="flex-1 text-sm text-success-700"><strong>Успіх!</strong> Операція виконана успішно.</p>
                    <button @click="show = false" class="flex-shrink-0 text-success-500 hover:text-success-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 rounded-lg border border-error-200 bg-error-50 px-4 py-3">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-error-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="flex-1 text-sm text-error-700"><strong>Помилка!</strong> Виникла помилка при виконанні операції.</p>
                    <button @click="show = false" class="flex-shrink-0 text-error-500 hover:text-error-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 rounded-lg border border-warning-200 bg-warning-50 px-4 py-3">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-warning-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="flex-1 text-sm text-warning-700"><strong>Увага!</strong> Будь ласка, перевірте введені дані.</p>
                    <button @click="show = false" class="flex-shrink-0 text-warning-500 hover:text-warning-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 rounded-lg border border-brand-200 bg-brand-50 px-4 py-3">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="flex-1 text-sm text-brand-700"><strong>Інформація:</strong> Ця функція знаходиться в розробці.</p>
                    <button @click="show = false" class="flex-shrink-0 text-brand-500 hover:text-brand-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Badges (Light background)</h3>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-700">Brand</span>
                <span class="inline-flex rounded-full bg-success-100 px-3 py-1 text-xs font-medium text-success-700">Success</span>
                <span class="inline-flex rounded-full bg-error-100 px-3 py-1 text-xs font-medium text-error-700">Error</span>
                <span class="inline-flex rounded-full bg-warning-100 px-3 py-1 text-xs font-medium text-warning-700">Warning</span>
                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Neutral</span>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Badges (Solid background)</h3>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex rounded-full bg-brand-500 px-3 py-1 text-xs font-medium text-white">Brand</span>
                <span class="inline-flex rounded-full bg-success-500 px-3 py-1 text-xs font-medium text-white">Success</span>
                <span class="inline-flex rounded-full bg-error-500 px-3 py-1 text-xs font-medium text-white">Error</span>
                <span class="inline-flex rounded-full bg-warning-500 px-3 py-1 text-xs font-medium text-white">Warning</span>
                <span class="inline-flex rounded-full bg-gray-500 px-3 py-1 text-xs font-medium text-white">Neutral</span>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Badges з іконками (зліва)</h3>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-success-100 px-2.5 py-1 text-xs font-medium text-success-700">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Активний
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-warning-100 px-2.5 py-1 text-xs font-medium text-warning-700">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Очікує
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-100 px-2.5 py-1 text-xs font-medium text-brand-700">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    Перегляд
                </span>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Badges з іконками (справа)</h3>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                    3
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-error-100 px-2.5 py-1 text-xs font-medium text-error-700">
                    12 нових
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </span>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Rounded Badge Tags</h3>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex rounded-full bg-brand-50 px-2 py-0.5 text-xs text-brand-700">Тег 1</span>
                <span class="inline-flex rounded-full bg-success-50 px-2 py-0.5 text-xs text-success-700">Тег 2</span>
                <span class="inline-flex rounded-full bg-error-50 px-2 py-0.5 text-xs text-error-700">Тег 3</span>
                <span class="inline-flex rounded-full bg-warning-50 px-2 py-0.5 text-xs text-warning-700">Тег 4</span>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 7: AVATARS --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Аватари</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Розміри аватарів</h3>
            <div class="flex flex-wrap items-center gap-5">
                <div class="relative h-6 w-6 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-brand-500 text-[10px] font-medium text-white">AB</div>
                </div>
                <div class="relative h-8 w-8 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-brand-500 text-xs font-medium text-white">AB</div>
                </div>
                <div class="relative h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-brand-500 text-sm font-medium text-white">AB</div>
                </div>
                <div class="relative h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-brand-500 text-base font-medium text-white">AB</div>
                </div>
                <div class="relative h-14 w-14 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-brand-500 text-lg font-medium text-white">AB</div>
                </div>
                <div class="relative h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-brand-500 text-xl font-medium text-white">AB</div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Аватар з індикатором Online</h3>
            <div class="flex flex-wrap items-center gap-5">
                <div class="relative h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-success-500 text-sm font-medium text-white">ІП</div>
                    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white bg-success-500"></span>
                </div>
                <div class="relative h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-success-500 text-base font-medium text-white">ІП</div>
                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-success-500"></span>
                </div>
                <div class="relative h-14 w-14 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-success-500 text-lg font-medium text-white">ІП</div>
                    <span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-success-500"></span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Аватар з індикатором Offline</h3>
            <div class="flex flex-wrap items-center gap-5">
                <div class="relative h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-gray-400 text-sm font-medium text-white">МК</div>
                    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white bg-gray-400"></span>
                </div>
                <div class="relative h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-gray-400 text-base font-medium text-white">МК</div>
                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-gray-400"></span>
                </div>
                <div class="relative h-14 w-14 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-gray-400 text-lg font-medium text-white">МК</div>
                    <span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-gray-400"></span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Аватар з індикатором Busy</h3>
            <div class="flex flex-wrap items-center gap-5">
                <div class="relative h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-warning-500 text-sm font-medium text-white">ОШ</div>
                    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white bg-warning-500"></span>
                </div>
                <div class="relative h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-warning-500 text-base font-medium text-white">ОШ</div>
                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-warning-500"></span>
                </div>
                <div class="relative h-14 w-14 rounded-full overflow-hidden bg-gray-100">
                    <div class="flex h-full w-full items-center justify-center bg-warning-500 text-lg font-medium text-white">ОШ</div>
                    <span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-warning-500"></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 8: BREADCRUMBS --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Breadcrumbs (Навігаційні крихти)</h2>

    <div class="space-y-4">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Базовий варіант</h3>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700" href="#">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Головна
                            <svg class="stroke-current h-4 w-4" viewBox="0 0 17 16" fill="none">
                                <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700" href="#">
                            Налаштування
                            <svg class="stroke-current h-4 w-4" viewBox="0 0 17 16" fill="none">
                                <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li class="text-sm text-gray-900">Профіль</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 9: MODALS --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6" x-data="{ showModal: false }">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Модальні вікна</h2>

    <div class="space-y-4">
        <button @click="showModal = true" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
            Відкрити модальне вікно
        </button>

        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="display: none;">
            <div @click="showModal = false" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>

            <div class="relative z-50 w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 shadow-xl transition-all">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Заголовок модального вікна</h3>
                    <button @click="showModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-600">Це приклад модального вікна. Тут може бути форма, повідомлення або будь-який інший контент.</p>

                    <div class="mt-4 space-y-3">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Ім'я</label>
                            <input type="text" placeholder="Введіть ваше ім'я..." class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" placeholder="email@example.com" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button @click="showModal = false" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Скасувати
                    </button>
                    <button @click="showModal = false" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600">
                        Зберегти
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 10: DROPDOWNS --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Випадаючі меню (Dropdowns)</h2>

    <div class="flex flex-wrap gap-4">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Дії
                <svg class="h-4 w-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak class="absolute left-0 top-full z-50 mt-2 w-48 rounded-lg border border-gray-200 bg-white shadow-lg" style="display: none;">
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">Редагувати</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">Дублювати</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">Архівувати</a>
                    <div class="my-1 border-t border-gray-100"></div>
                    <a href="#" class="block px-4 py-2 text-sm text-error-600 transition hover:bg-error-50">Видалити</a>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Створити
                <svg class="h-4 w-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak class="absolute left-0 top-full z-50 mt-2 w-56 rounded-lg border border-gray-200 bg-white shadow-lg" style="display: none;">
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                        <div class="font-medium">Новий курс</div>
                        <div class="text-xs text-gray-500">Створити новий навчальний курс</div>
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                        <div class="font-medium">Нова група</div>
                        <div class="text-xs text-gray-500">Створити групу студентів</div>
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                        <div class="font-medium">Новий матеріал</div>
                        <div class="text-xs text-gray-500">Додати навчальний матеріал</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 11: ENHANCED FORM ELEMENTS --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Додаткові елементи форм</h2>

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div x-data="{ showPassword: false }">
                <label class="mb-2 block text-sm font-medium text-gray-700">Пароль з показом/приховуванням</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" placeholder="Введіть пароль..." class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 pr-11 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <button @click="showPassword = !showPassword" type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-500">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"/>
                        </svg>
                        <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div x-data="{ searchValue: '' }">
                <label class="mb-2 block text-sm font-medium text-gray-700">Пошук з очищенням</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input x-model="searchValue" type="text" placeholder="Шукати..." class="w-full rounded-lg border border-gray-300 bg-gray-50 py-3 pl-11 pr-11 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    <button x-show="searchValue" @click="searchValue = ''" type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" x-cloak style="display: none;">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div>
            <label class="mb-3 block text-sm font-medium text-gray-700">Toggle Switches (Перемикачі)</label>
            <div class="space-y-3">
                <label class="flex items-center cursor-pointer">
                    <div class="relative" x-data="{ checked: true }">
                        <input type="checkbox" class="sr-only" x-model="checked">
                        <div @click="checked = !checked" :class="checked ? 'bg-brand-500' : 'bg-gray-300'" class="block h-6 w-11 rounded-full transition"></div>
                        <div @click="checked = !checked" :class="checked ? 'translate-x-6' : 'translate-x-1'" class="absolute left-0 top-1 h-4 w-4 rounded-full bg-white transition"></div>
                    </div>
                    <span class="ml-3 text-sm text-gray-700">Увімкнути сповіщення</span>
                </label>

                <label class="flex items-center cursor-pointer">
                    <div class="relative" x-data="{ checked: false }">
                        <input type="checkbox" class="sr-only" x-model="checked">
                        <div @click="checked = !checked" :class="checked ? 'bg-success-500' : 'bg-gray-300'" class="block h-6 w-11 rounded-full transition"></div>
                        <div @click="checked = !checked" :class="checked ? 'translate-x-6' : 'translate-x-1'" class="absolute left-0 top-1 h-4 w-4 rounded-full bg-white transition"></div>
                    </div>
                    <span class="ml-3 text-sm text-gray-700">Автоматичне збереження</span>
                </label>

                <label class="flex items-center cursor-pointer opacity-50">
                    <div class="relative">
                        <input type="checkbox" disabled class="sr-only">
                        <div class="block h-6 w-11 rounded-full bg-gray-200 cursor-not-allowed"></div>
                        <div class="absolute left-0 top-1 h-4 w-4 translate-x-1 rounded-full bg-white"></div>
                    </div>
                    <span class="ml-3 text-sm text-gray-400">Вимкнено (disabled)</span>
                </label>
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Loading Spinner (Індикатор завантаження)</label>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2 rounded-lg bg-brand-50 px-4 py-2">
                    <svg class="h-5 w-5 animate-spin text-brand-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-brand-700">Завантаження...</span>
                </div>

                <div class="flex items-center gap-2 rounded-lg bg-success-50 px-4 py-2">
                    <svg class="h-5 w-5 animate-spin text-success-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-success-700">Обробка...</span>
                </div>

                <div class="inline-flex h-8 w-8 animate-spin items-center justify-center rounded-full border-4 border-gray-200 border-t-brand-500"></div>
                <div class="inline-flex h-8 w-8 animate-spin items-center justify-center rounded-full border-4 border-gray-200 border-t-success-500"></div>
                <div class="inline-flex h-8 w-8 animate-spin items-center justify-center rounded-full border-4 border-gray-200 border-t-error-500"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div x-data="{ selectedCountry: 'UA', phoneNumber: '' }">
                <label class="mb-2 block text-sm font-medium text-gray-700">Телефон з кодом країни</label>
                <div class="relative">
                    <div class="absolute">
                        <select x-model="selectedCountry" class="h-11 appearance-none rounded-l-lg border-0 border-r border-gray-200 bg-transparent px-3.5 pr-8 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            <option value="UA">UA</option>
                            <option value="US">US</option>
                            <option value="GB">GB</option>
                            <option value="PL">PL</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <input x-model="phoneNumber" type="tel" placeholder="+380 (50) 123-45-67" class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 py-3 pl-[84px] pr-4 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                </div>
            </div>

            <div x-data="{ copyText: 'https://lse-addiction.loc/share/abc123', copied: false }">
                <label class="mb-2 block text-sm font-medium text-gray-700">URL з копіюванням</label>
                <div class="relative">
                    <input :value="copyText" type="text" readonly class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 py-3 pl-4 pr-20 text-sm text-gray-900">
                    <button @click="navigator.clipboard.writeText(copyText); copied = true; setTimeout(() => copied = false, 2000)" type="button" class="absolute right-0 top-0 inline-flex h-11 items-center gap-1.5 border-l border-gray-200 px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.58822 4.58398C6.58822 4.30784 6.81207 4.08398 7.08822 4.08398H15.4154C15.6915 4.08398 15.9154 4.30784 15.9154 4.58398L15.9154 12.9128C15.9154 13.189 15.6916 13.4128 15.4154 13.4128H7.08821C6.81207 13.4128 6.58822 13.189 6.58822 12.9128V4.58398ZM7.08822 2.58398C5.98365 2.58398 5.08822 3.47942 5.08822 4.58398V5.09416H4.58496C3.48039 5.09416 2.58496 5.98959 2.58496 7.09416V15.4161C2.58496 16.5207 3.48039 17.4161 4.58496 17.4161H12.9069C14.0115 17.4161 14.9069 16.5207 14.9069 15.4161L14.9069 14.9128H15.4154C16.52 14.9128 17.4154 14.0174 17.4154 12.9128L17.4154 4.58398C17.4154 3.47941 16.52 2.58398 15.4154 2.58398H7.08822ZM13.4069 14.9128H7.08821C5.98364 14.9128 5.08822 14.0174 5.08822 12.9128V6.59416H4.58496C4.30882 6.59416 4.08496 6.81801 4.08496 7.09416V15.4161C4.08496 15.6922 4.30882 15.9161 4.58496 15.9161H12.9069C13.183 15.9161 13.4069 15.6922 13.4069 15.4161L13.4069 14.9128Z"/>
                        </svg>
                        <span x-text="copied ? 'Скопійовано!' : 'Копіювати'"></span>
                    </button>
                </div>
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Email з іконкою</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 border-r border-gray-200 pr-3.5 text-gray-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 7.06206V14.375C3.04175 14.6511 3.26561 14.875 3.54175 14.875H16.4584C16.7346 14.875 16.9584 14.6511 16.9584 14.375V7.06245L11.1443 11.1168C10.457 11.5961 9.54373 11.5961 8.85638 11.1168L3.04175 7.06206ZM16.9584 5.19262C16.9584 5.19341 16.9584 5.1942 16.9584 5.19498V5.20026C16.9572 5.22216 16.946 5.24239 16.9279 5.25501L10.2864 9.88638C10.1145 10.0062 9.8862 10.0062 9.71437 9.88638L3.07255 5.25485C3.05342 5.24151 3.04202 5.21967 3.04202 5.19636C3.042 5.15695 3.07394 5.125 3.11335 5.125H16.8871C16.9253 5.125 16.9564 5.15494 16.9584 5.19262ZM18.4584 5.21428V14.375C18.4584 15.4796 17.563 16.375 16.4584 16.375H3.54175C2.43718 16.375 1.54175 15.4796 1.54175 14.375V5.19498C1.54175 5.1852 1.54194 5.17546 1.54231 5.16577C1.55858 4.31209 2.25571 3.625 3.11335 3.625H16.8871C17.7549 3.625 18.4584 4.32843 18.4585 5.19622C18.4585 5.20225 18.4585 5.20826 18.4584 5.21428Z" fill="#667085"/>
                    </svg>
                </span>
                <input type="email" placeholder="example@email.com" class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 py-3 pl-[62px] pr-4 text-sm text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Стан успіху (Success state)</label>
            <div class="relative">
                <input type="email" value="example@email.com" class="h-11 w-full rounded-lg border border-success-300 bg-gray-50 py-3 pl-4 pr-10 text-sm text-gray-900 outline-none transition focus:border-success-300 focus:bg-white focus:ring-3 focus:ring-success-500/10">
                <span class="absolute right-3.5 top-1/2 -translate-y-1/2">
                    <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.61792 8.00034C2.61792 5.02784 5.0276 2.61816 8.00009 2.61816C10.9726 2.61816 13.3823 5.02784 13.3823 8.00034C13.3823 10.9728 10.9726 13.3825 8.00009 13.3825C5.0276 13.3825 2.61792 10.9728 2.61792 8.00034ZM8.00009 1.11816C4.19917 1.11816 1.11792 4.19942 1.11792 8.00034C1.11792 11.8013 4.19917 14.8825 8.00009 14.8825C11.801 14.8825 14.8823 11.8013 14.8823 8.00034C14.8823 4.19942 11.801 1.11816 8.00009 1.11816ZM10.5192 7.266C10.8121 6.97311 10.8121 6.49823 10.5192 6.20534C10.2264 5.91245 9.75148 5.91245 9.45858 6.20534L7.45958 8.20434L6.54162 7.28638C6.24873 6.99349 5.77385 6.99349 5.48096 7.28638C5.18807 7.57927 5.18807 8.05415 5.48096 8.34704L6.92925 9.79533C7.0699 9.93599 7.26067 10.015 7.45958 10.015C7.6585 10.015 7.84926 9.93599 7.98991 9.79533L10.5192 7.266Z" fill="#12B76A"/>
                    </svg>
                </span>
            </div>
            <p class="mt-1.5 text-xs text-success-600">Email підтверджено успішно!</p>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 12: PRELOADER / FULL PAGE LOADING --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Preloader (Full Page Loading)</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Демонстрація Preloader</h3>
            <div x-data="{ showPreloader: false }" class="space-y-3">
                <button @click="showPreloader = true; setTimeout(() => showPreloader = false, 3000)" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Показати Preloader (3 сек)
                </button>

                <div x-show="showPreloader" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center bg-white" style="display: none;">
                    <div class="text-center">
                        <div class="inline-flex h-16 w-16 animate-spin items-center justify-center rounded-full border-4 border-gray-200 border-t-brand-500"></div>
                        <p class="mt-4 text-sm font-medium text-gray-700">Завантаження...</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Варіанти Preloader</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <div class="flex flex-col items-center">
                        <div class="inline-flex h-12 w-12 animate-spin items-center justify-center rounded-full border-4 border-gray-200 border-t-brand-500"></div>
                        <p class="mt-3 text-xs text-gray-600">Border Spinner</p>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 animate-spin text-brand-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-3 text-xs text-gray-600">SVG Spinner</p>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center gap-1">
                            <div class="h-3 w-3 animate-bounce rounded-full bg-brand-500" style="animation-delay: 0ms;"></div>
                            <div class="h-3 w-3 animate-bounce rounded-full bg-brand-500" style="animation-delay: 150ms;"></div>
                            <div class="h-3 w-3 animate-bounce rounded-full bg-brand-500" style="animation-delay: 300ms;"></div>
                        </div>
                        <p class="mt-3 text-xs text-gray-600">Dots Loader</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Preloader з брендом</h3>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-8">
                <div class="flex flex-col items-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-500">
                        <span class="text-2xl font-bold text-white">L</span>
                    </div>
                    <div class="inline-flex h-12 w-12 animate-spin items-center justify-center rounded-full border-4 border-gray-200 border-t-brand-500"></div>
                    <p class="mt-4 text-sm font-medium text-gray-700">Завантаження LSE...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 13: ТИПОГРАФІКА --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Типографіка</h2>

    <div class="space-y-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Heading 1 (4xl)</h1>
            <p class="text-xs text-gray-500 mt-1">text-4xl font-bold</p>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Heading 2 (3xl)</h2>
            <p class="text-xs text-gray-500 mt-1">text-3xl font-bold</p>
        </div>
        <div>
            <h3 class="text-2xl font-semibold text-gray-900">Heading 3 (2xl)</h3>
            <p class="text-xs text-gray-500 mt-1">text-2xl font-semibold</p>
        </div>
        <div>
            <h4 class="text-xl font-semibold text-gray-900">Heading 4 (xl)</h4>
            <p class="text-xs text-gray-500 mt-1">text-xl font-semibold</p>
        </div>
        <div>
            <h5 class="text-lg font-medium text-gray-900">Heading 5 (lg)</h5>
            <p class="text-xs text-gray-500 mt-1">text-lg font-medium</p>
        </div>
        <div>
            <h6 class="text-base font-medium text-gray-900">Heading 6 (base)</h6>
            <p class="text-xs text-gray-500 mt-1">text-base font-medium</p>
        </div>
        <div class="pt-4 border-t">
            <p class="text-base text-gray-700 mb-2">
                Звичайний параграф тексту. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
            <p class="text-sm text-gray-600 mb-2">
                Малий текст (text-sm). Використовується для додаткової інформації та підказок.
            </p>
            <p class="text-xs text-gray-500">
                Дуже малий текст (text-xs). Використовується для мета-інформації.
            </p>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 14: КОЛЬОРИ --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Кольорова палітра</h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-5">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Brand (Primary)</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-brand-50"></div>
                    <span class="text-xs text-gray-600">brand-50</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-brand-100"></div>
                    <span class="text-xs text-gray-600">brand-100</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-brand-500"></div>
                    <span class="text-xs text-white">brand-500</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-brand-600"></div>
                    <span class="text-xs text-white">brand-600</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Success</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-success-50"></div>
                    <span class="text-xs text-gray-600">success-50</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-success-100"></div>
                    <span class="text-xs text-gray-600">success-100</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-success-500"></div>
                    <span class="text-xs text-white">success-500</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-success-600"></div>
                    <span class="text-xs text-white">success-600</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Error</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-error-50"></div>
                    <span class="text-xs text-gray-600">error-50</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-error-100"></div>
                    <span class="text-xs text-gray-600">error-100</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-error-500"></div>
                    <span class="text-xs text-white">error-500</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-error-600"></div>
                    <span class="text-xs text-white">error-600</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Warning</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-warning-50"></div>
                    <span class="text-xs text-gray-600">warning-50</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-warning-100"></div>
                    <span class="text-xs text-gray-600">warning-100</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-warning-500"></div>
                    <span class="text-xs text-white">warning-500</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-warning-600"></div>
                    <span class="text-xs text-white">warning-600</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Gray</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-gray-50 border border-gray-200"></div>
                    <span class="text-xs text-gray-600">gray-50</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-gray-100"></div>
                    <span class="text-xs text-gray-600">gray-100</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-gray-500"></div>
                    <span class="text-xs text-white">gray-500</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-gray-900"></div>
                    <span class="text-xs text-white">gray-900</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <h3 class="mb-3 text-sm font-medium text-gray-700">Додаткові кольори</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <h4 class="mb-2 text-xs font-medium text-gray-600">Info (Blue)</h4>
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #EFF6FF;"></div>
                    <span class="text-xs text-gray-600">info-50</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #DBEAFE;"></div>
                    <span class="text-xs text-gray-600">info-100</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #3B82F6;"></div>
                    <span class="text-xs text-white">info-500</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #2563EB;"></div>
                    <span class="text-xs text-white">info-600</span>
                </div>
            </div>

            <div>
                <h4 class="mb-2 text-xs font-medium text-gray-600">Purple</h4>
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #FAF5FF;"></div>
                    <span class="text-xs text-gray-600">purple-50</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #F3E8FF;"></div>
                    <span class="text-xs text-gray-600">purple-100</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #A855F7;"></div>
                    <span class="text-xs text-white">purple-500</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #9333EA;"></div>
                    <span class="text-xs text-white">purple-600</span>
                </div>
            </div>

            <div>
                <h4 class="mb-2 text-xs font-medium text-gray-600">Pink</h4>
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #FDF2F8;"></div>
                    <span class="text-xs text-gray-600">pink-50</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #FCE7F3;"></div>
                    <span class="text-xs text-gray-600">pink-100</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #EC4899;"></div>
                    <span class="text-xs text-white">pink-500</span>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: #DB2777;"></div>
                    <span class="text-xs text-white">pink-600</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 15: ВКЛАДКИ (TABS) --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Вкладки (Tabs)</h2>

    <div class="space-y-8">
        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Стандартні вкладки</h3>
            <x-admin.tabs :items="[
                ['id' => 'profile', 'label' => 'Профіль'],
                ['id' => 'settings', 'label' => 'Налаштування'],
                ['id' => 'messages', 'label' => 'Повідомлення'],
                ['id' => 'notifications', 'label' => 'Сповіщення']
            ]">
                <x-admin.tab-panel id="profile">
                    <p class="text-sm text-gray-600">Вміст вкладки "Профіль". Тут відображається інформація про користувача.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="settings">
                    <p class="text-sm text-gray-600">Вміст вкладки "Налаштування". Тут можна змінювати параметри системи.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="messages">
                    <p class="text-sm text-gray-600">Вміст вкладки "Повідомлення". Список всіх повідомлень користувача.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="notifications">
                    <p class="text-sm text-gray-600">Вміст вкладки "Сповіщення". Системні сповіщення та оновлення.</p>
                </x-admin.tab-panel>
            </x-admin.tabs>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Вкладки з іконками</h3>
            <x-admin.tabs :items="[
                [
                    'id' => 'home',
                    'label' => 'Головна',
                    'icon' => '<svg class=\'h-4 w-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\'/></svg>'
                ],
                [
                    'id' => 'users',
                    'label' => 'Користувачі',
                    'icon' => '<svg class=\'h-4 w-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\'/></svg>'
                ],
                [
                    'id' => 'analytics',
                    'label' => 'Аналітика',
                    'icon' => '<svg class=\'h-4 w-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\'/></svg>'
                ]
            ]">
                <x-admin.tab-panel id="home">
                    <p class="text-sm text-gray-600">Панель управління та статистика.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="users">
                    <p class="text-sm text-gray-600">Управління користувачами системи.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="analytics">
                    <p class="text-sm text-gray-600">Детальна аналітика та звіти.</p>
                </x-admin.tab-panel>
            </x-admin.tabs>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Вкладки з бейджами</h3>
            <x-admin.tabs :items="[
                ['id' => 'all', 'label' => 'Всі', 'badge' => '128'],
                ['id' => 'active', 'label' => 'Активні', 'badge' => '24'],
                ['id' => 'pending', 'label' => 'Очікують', 'badge' => '5'],
                ['id' => 'archived', 'label' => 'Архів', 'badge' => '99']
            ]">
                <x-admin.tab-panel id="all">
                    <p class="text-sm text-gray-600">Всі записи в системі (128).</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="active">
                    <p class="text-sm text-gray-600">Активні записи (24).</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="pending">
                    <p class="text-sm text-gray-600">Записи, що очікують обробки (5).</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="archived">
                    <p class="text-sm text-gray-600">Архівовані записи (99).</p>
                </x-admin.tab-panel>
            </x-admin.tabs>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Вкладки-пілюлі (Pills)</h3>
            <x-admin.tabs variant="pills" :items="[
                ['id' => 'overview', 'label' => 'Огляд'],
                ['id' => 'details', 'label' => 'Деталі'],
                ['id' => 'history', 'label' => 'Історія']
            ]">
                <x-admin.tab-panel id="overview">
                    <p class="text-sm text-gray-600">Загальний огляд інформації.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="details">
                    <p class="text-sm text-gray-600">Детальна інформація про об'єкт.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="history">
                    <p class="text-sm text-gray-600">Історія змін та дій.</p>
                </x-admin.tab-panel>
            </x-admin.tabs>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Вертикальні вкладки</h3>
            <x-admin.tabs variant="vertical" :items="[
                ['id' => 'general', 'label' => 'Загальні'],
                ['id' => 'security', 'label' => 'Безпека'],
                ['id' => 'privacy', 'label' => 'Приватність'],
                ['id' => 'integrations', 'label' => 'Інтеграції']
            ]">
                <x-admin.tab-panel id="general">
                    <h4 class="mb-2 font-semibold text-gray-900">Загальні налаштування</h4>
                    <p class="text-sm text-gray-600">Основні параметри та конфігурація системи.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="security">
                    <h4 class="mb-2 font-semibold text-gray-900">Налаштування безпеки</h4>
                    <p class="text-sm text-gray-600">Параметри захисту та аутентифікації.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="privacy">
                    <h4 class="mb-2 font-semibold text-gray-900">Приватність</h4>
                    <p class="text-sm text-gray-600">Управління конфіденційністю даних.</p>
                </x-admin.tab-panel>
                <x-admin.tab-panel id="integrations">
                    <h4 class="mb-2 font-semibold text-gray-900">Інтеграції</h4>
                    <p class="text-sm text-gray-600">Підключення зовнішніх сервісів.</p>
                </x-admin.tab-panel>
            </x-admin.tabs>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 16: ПРОГРЕС-БАРИ --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Прогрес-бари</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Базові прогрес-бари</h3>
            <div class="space-y-4">
                <x-admin.progress :value="25" :max="100" color="brand" />
                <x-admin.progress :value="50" :max="100" color="success" />
                <x-admin.progress :value="75" :max="100" color="warning" />
                <x-admin.progress :value="100" :max="100" color="error" />
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Розміри</h3>
            <div class="space-y-4">
                <x-admin.progress :value="60" :max="100" size="xs" label="Extra Small" />
                <x-admin.progress :value="60" :max="100" size="sm" label="Small" />
                <x-admin.progress :value="60" :max="100" size="md" label="Medium" />
                <x-admin.progress :value="60" :max="100" size="lg" label="Large" />
                <x-admin.progress :value="60" :max="100" size="xl" label="Extra Large" />
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">З підписами зовні</h3>
            <div class="space-y-4">
                <x-admin.progress :value="35" :max="100" label="Завантаження файлів" color="brand" />
                <x-admin.progress :value="65" :max="100" label="Обробка даних" color="info" />
                <x-admin.progress :value="90" :max="100" label="Майже готово" color="success" />
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">З відсотками всередині</h3>
            <div class="space-y-4">
                <x-admin.progress :value="45" :max="100" size="xl" labelPosition="inside" color="brand" />
                <x-admin.progress :value="78" :max="100" size="xl" labelPosition="inside" color="purple" />
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Різні кольори</h3>
            <div class="space-y-4">
                <x-admin.progress :value="40" :max="100" color="brand" label="Brand" />
                <x-admin.progress :value="50" :max="100" color="success" label="Success" />
                <x-admin.progress :value="60" :max="100" color="error" label="Error" />
                <x-admin.progress :value="70" :max="100" color="warning" label="Warning" />
                <x-admin.progress :value="80" :max="100" color="info" label="Info" />
                <x-admin.progress :value="90" :max="100" color="purple" label="Purple" />
                <x-admin.progress :value="95" :max="100" color="pink" label="Pink" />
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 17: ПІДКАЗКИ (TOOLTIPS) --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Підказки (Tooltips)</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Позиціонування</h3>
            <div class="flex flex-wrap items-center gap-4">
                <x-admin.tooltip text="Підказка зверху" position="top">
                    <button class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Зверху
                    </button>
                </x-admin.tooltip>

                <x-admin.tooltip text="Підказка справа" position="right">
                    <button class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Справа
                    </button>
                </x-admin.tooltip>

                <x-admin.tooltip text="Підказка знизу" position="bottom">
                    <button class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Знизу
                    </button>
                </x-admin.tooltip>

                <x-admin.tooltip text="Підказка зліва" position="left">
                    <button class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Зліва
                    </button>
                </x-admin.tooltip>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Темна та світла теми</h3>
            <div class="flex flex-wrap items-center gap-4">
                <x-admin.tooltip text="Темна підказка" variant="dark">
                    <button class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600">
                        Темна тема
                    </button>
                </x-admin.tooltip>

                <x-admin.tooltip text="Світла підказка" variant="light">
                    <button class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600">
                        Світла тема
                    </button>
                </x-admin.tooltip>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">На іконках</h3>
            <div class="flex flex-wrap items-center gap-4">
                <x-admin.tooltip text="Редагувати" position="top">
                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                </x-admin.tooltip>

                <x-admin.tooltip text="Видалити" position="top">
                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-error-500 text-white transition hover:bg-error-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </x-admin.tooltip>

                <x-admin.tooltip text="Завантажити" position="top">
                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-success-500 text-white transition hover:bg-success-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                </x-admin.tooltip>

                <x-admin.tooltip text="Налаштування" position="top">
                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gray-700 text-white transition hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </x-admin.tooltip>
            </div>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 18: СПОВІЩЕННЯ (NOTIFICATIONS) --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Сповіщення (Notifications)</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Типи сповіщень</h3>
            <div class="space-y-3">
                <x-admin.notification
                    type="success"
                    title="Успішно!"
                    message="Дані збережено успішно."
                />

                <x-admin.notification
                    type="error"
                    title="Помилка!"
                    message="Не вдалося виконати операцію. Спробуйте пізніше."
                />

                <x-admin.notification
                    type="warning"
                    title="Увага!"
                    message="Це сповіщення вимагає вашої уваги."
                />

                <x-admin.notification
                    type="info"
                    title="Інформація"
                    message="Нова версія доступна для оновлення."
                />
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Без заголовка</h3>
            <div class="space-y-3">
                <x-admin.notification
                    type="success"
                    message="Файл успішно завантажено."
                />

                <x-admin.notification
                    type="info"
                    message="У вас 3 нових повідомлення."
                />
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Без кнопки закриття</h3>
            <x-admin.notification
                type="warning"
                title="Системне повідомлення"
                message="Це повідомлення не можна закрити вручну."
                :dismissible="false"
            />
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">З автоматичним закриттям</h3>
            <x-admin.notification
                type="success"
                title="Автоматичне закриття"
                message="Це повідомлення зникне через 5 секунд."
                :autoDismiss="true"
                :duration="5000"
            />
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 19: ГРУПИ КНОПОК --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Групи кнопок</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Горизонтальна група</h3>
            <x-admin.button-group>
                <button class="button-group-item border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Лівий
                </button>
                <button class="button-group-item border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Центр
                </button>
                <button class="button-group-item border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Правий
                </button>
            </x-admin.button-group>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">З іконками</h3>
            <x-admin.button-group>
                <button class="button-group-item inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Список
                </button>
                <button class="button-group-item inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Сітка
                </button>
            </x-admin.button-group>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Кольорові групи</h3>
            <x-admin.button-group>
                <button class="button-group-item border border-brand-500 bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    Зберегти
                </button>
                <button class="button-group-item border border-brand-500 bg-white px-4 py-2 text-sm font-medium text-brand-600 hover:bg-brand-50">
                    Відмінити
                </button>
            </x-admin.button-group>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Тільки іконки</h3>
            <x-admin.button-group>
                <button class="button-group-item inline-flex h-10 w-10 items-center justify-center border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>
                <button class="button-group-item inline-flex h-10 w-10 items-center justify-center border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                </button>
                <button class="button-group-item inline-flex h-10 w-10 items-center justify-center border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </x-admin.button-group>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Вертикальна група</h3>
            <x-admin.button-group orientation="vertical" class="button-group-vertical">
                <button class="button-group-item border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Перший
                </button>
                <button class="button-group-item border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Другий
                </button>
                <button class="button-group-item border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Третій
                </button>
            </x-admin.button-group>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 20: СПИСКИ --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Списки</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Маркований список</h3>
            <x-admin.list :items="['Перший елемент списку', 'Другий елемент списку', 'Третій елемент списку', 'Четвертий елемент списку']" />
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Нумерований список</h3>
            <x-admin.list type="ordered" :items="['Перший крок', 'Другий крок', 'Третій крок', 'Четвертий крок']" />
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Список з іконками</h3>
            <x-admin.list variant="icon" :items="[
                [
                    'text' => 'Доступ до всіх функцій',
                    'icon' => '<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>'
                ],
                [
                    'text' => 'Необмежені користувачі',
                    'icon' => '<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>'
                ],
                [
                    'text' => 'Пріоритетна підтримка',
                    'icon' => '<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>'
                ],
                [
                    'text' => 'Щомісячні звіти',
                    'icon' => '<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>'
                ]
            ]" />
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Список з аватарами</h3>
            <x-admin.list variant="avatar" :items="[
                ['name' => 'Іван Петренко', 'subtitle' => 'ivan@example.com'],
                ['name' => 'Марія Коваленко', 'subtitle' => 'maria@example.com'],
                ['name' => 'Олег Шевченко', 'subtitle' => 'oleg@example.com']
            ]" />
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Інтерактивний список</h3>
            <x-admin.list variant="interactive" :items="[
                ['text' => 'Налаштування профілю', 'badge' => '3'],
                ['text' => 'Сповіщення'],
                ['text' => 'Безпека та конфіденційність', 'badge' => '1'],
                ['text' => 'Мова та регіон']
            ]" />
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Список з роздільниками</h3>
            <x-admin.list variant="avatar" :divided="true" :items="[
                ['name' => 'Адміністратор', 'subtitle' => 'Повний доступ до системи'],
                ['name' => 'Менеджер', 'subtitle' => 'Управління користувачами'],
                ['name' => 'Користувач', 'subtitle' => 'Базовий доступ']
            ]" />
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Список описів</h3>
            <x-admin.list type="description" :items="[
                ['term' => 'Назва продукту', 'description' => 'LifeScanEducation'],
                ['term' => 'Версія', 'description' => '1.0.0'],
                ['term' => 'Розробник', 'description' => 'LSE Team'],
                ['term' => 'Ліцензія', 'description' => 'MIT License']
            ]" />
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 21: ПОСИЛАННЯ --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Посилання</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Базові посилання</h3>
            <div class="flex flex-wrap gap-4">
                <x-admin.link href="#">Стандартне посилання</x-admin.link>
                <x-admin.link href="#" variant="brand">Brand посилання</x-admin.link>
                <x-admin.link href="#" variant="success">Success посилання</x-admin.link>
                <x-admin.link href="#" variant="error">Error посилання</x-admin.link>
                <x-admin.link href="#" variant="muted">Muted посилання</x-admin.link>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Стилі підкреслення</h3>
            <div class="flex flex-wrap gap-4">
                <x-admin.link href="#" underline="always">Завжди підкреслене</x-admin.link>
                <x-admin.link href="#" underline="hover">При наведенні</x-admin.link>
                <x-admin.link href="#" underline="none">Без підкреслення</x-admin.link>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">З іконками</h3>
            <div class="flex flex-col gap-3">
                <x-admin.link href="#" variant="brand">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Головна сторінка
                </x-admin.link>

                <x-admin.link href="#" variant="brand">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Завантажити файл
                </x-admin.link>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Зовнішні посилання</h3>
            <div class="flex flex-col gap-3">
                <x-admin.link href="https://example.com" :external="true" variant="brand">
                    Відкрити в новій вкладці
                </x-admin.link>

                <x-admin.link href="https://github.com" :external="true" variant="brand">
                    GitHub
                </x-admin.link>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Вимкнені посилання</h3>
            <x-admin.link href="#" :disabled="true" variant="muted">
                Недоступне посилання
            </x-admin.link>
        </div>
    </div>
</div>

{{-- СЕКЦІЯ 22: СТРІЧКИ (RIBBONS) --}}
<div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6">
    <h2 class="mb-6 text-xl font-semibold text-gray-900">Стрічки (Ribbons)</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Позиції стрічок</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Зверху справа" position="top-right" />
                    <p class="text-center text-sm text-gray-600">Картка з стрічкою</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Зверху зліва" position="top-left" />
                    <p class="text-center text-sm text-gray-600">Картка з стрічкою</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Знизу справа" position="bottom-right" />
                    <p class="text-center text-sm text-gray-600">Картка з стрічкою</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Знизу зліва" position="bottom-left" />
                    <p class="text-center text-sm text-gray-600">Картка з стрічкою</p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Кольори стрічок</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Новинка" position="top-right" color="brand" />
                    <p class="text-center text-sm text-gray-600">Brand</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Успіх" position="top-right" color="success" />
                    <p class="text-center text-sm text-gray-600">Success</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Розпродаж" position="top-right" color="error" />
                    <p class="text-center text-sm text-gray-600">Error</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Увага" position="top-right" color="warning" />
                    <p class="text-center text-sm text-gray-600">Warning</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Інфо" position="top-right" color="info" />
                    <p class="text-center text-sm text-gray-600">Info</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Преміум" position="top-right" color="purple" />
                    <p class="text-center text-sm text-gray-600">Purple</p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-3 text-sm font-medium text-gray-700">Розміри</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Малий" position="top-right" size="sm" />
                    <p class="text-center text-sm text-gray-600">Small</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Середній" position="top-right" size="md" />
                    <p class="text-center text-sm text-gray-600">Medium</p>
                </div>

                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 bg-gray-50 p-8">
                    <x-admin.ribbon text="Великий" position="top-right" size="lg" />
                    <p class="text-center text-sm text-gray-600">Large</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyData = @json($monthlyData);
    const categoryData = @json($categoryData);

    // Line Chart
    const lineOptions = {
        series: [{
            name: 'Значення',
            data: monthlyData.map(item => item.value)
        }],
        chart: {
            type: 'line',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#465fff'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: monthlyData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('uk-UA', { month: 'short' });
            })
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toLocaleString('uk-UA');
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#lineChart"), lineOptions).render();

    // Bar Chart
    const barOptions = {
        series: [{
            name: 'Кількість',
            data: categoryData.map(item => item.count)
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#12b76a'],
        plotOptions: {
            bar: {
                borderRadius: 8,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        xaxis: {
            categories: categoryData.map(item => item.category)
        }
    };
    new ApexCharts(document.querySelector("#barChart"), barOptions).render();

    // Area Chart
    const areaOptions = {
        series: [{
            name: 'Дохід',
            data: monthlyData.map(item => item.value)
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#465fff'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: monthlyData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('uk-UA', { month: 'short' });
            })
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toLocaleString('uk-UA') + ' ₴';
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#areaChart"), areaOptions).render();

    // Donut Chart
    const donutOptions = {
        series: categoryData.map(item => item.count),
        chart: {
            type: 'donut',
            height: 300
        },
        labels: categoryData.map(item => item.category),
        colors: ['#465fff', '#12b76a', '#f79009', '#f04438', '#9333ea'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%'
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#donutChart"), donutOptions).render();
});
</script>
@endpush

<!-- Section 15: Media Cards -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <h2 class="mb-6 text-xl font-bold text-gray-900">15. Media Cards</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Картки типів медіа</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 xl:grid-cols-3">
                <!-- Images -->
                <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white py-4 pl-4 pr-4 xl:pr-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-[52px] w-[52px] items-center justify-center rounded-xl bg-success-500/[0.08] text-success-500">
                            <svg class="fill-current" width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.05 3.9L8.45 4.35L9.05 3.9ZM2.25 2.25H6.5V0.75H2.25V2.25ZM1.5 15V3H0V15H1.5ZM17.75 15.75H2.25V17.25H17.75V15.75ZM18.5 6V15H20V6H18.5ZM17.75 3.75H10.25V5.25H17.75V3.75ZM9.65 3.45L8.3 1.65L7.1 2.55L8.45 4.35L9.65 3.45ZM10.25 3.75C10.0139 3.75 9.79164 3.63885 9.65 3.45L8.45 4.35C8.87492 4.91656 9.5418 5.25 10.25 5.25V3.75ZM20 6C20 4.75736 18.9926 3.75 17.75 3.75V5.25C18.1642 5.25 18.5 5.58579 18.5 6H20ZM17.75 17.25C18.9926 17.25 20 16.2426 20 15H18.5C18.5 15.4142 18.1642 15.75 17.75 15.75V17.25ZM0 15C0 16.2426 1.00736 17.25 2.25 17.25V15.75C1.83579 15.75 1.5 15.4142 1.5 15H0ZM6.5 2.25C6.73607 2.25 6.95836 2.36115 7.1 2.55L8.3 1.65C7.87508 1.08344 7.2082 0.75 6.5 0.75V2.25ZM2.25 0.75C1.00736 0.75 0 1.75736 0 3H1.5C1.5 2.58579 1.83579 2.25 2.25 2.25V0.75Z" fill=""/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-800">Зображення</h4>
                            <span class="block text-sm text-gray-500">17% Використано</span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1 block text-right text-sm text-gray-500">245 файлів</span>
                        <span class="block text-right text-sm text-gray-500">26.40 GB</span>
                    </div>
                </div>

                <!-- Videos -->
                <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white py-4 pl-4 pr-4 xl:pr-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-[52px] w-[52px] items-center justify-center rounded-xl bg-pink-500/[0.08] text-pink-500">
                            <svg class="stroke-current" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.70825 5.93126L6.70825 18.0687C6.70825 19.2416 7.9937 19.9607 8.99315 19.347L18.8765 13.2783C19.83 12.6928 19.83 11.3072 18.8765 10.7217L8.99315 4.65301C7.9937 4.03931 6.70825 4.75844 6.70825 5.93126Z" stroke="" stroke-width="1.5" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-800">Відео</h4>
                            <span class="block text-sm text-gray-500">22% Використано</span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1 block text-right text-sm text-gray-500">128 файлів</span>
                        <span class="block text-right text-sm text-gray-500">32.10 GB</span>
                    </div>
                </div>

                <!-- Audio -->
                <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white py-4 pl-4 pr-4 xl:pr-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-[52px] w-[52px] items-center justify-center rounded-xl bg-info-500/[0.08] text-info-500">
                            <svg class="fill-current" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.2502 4.99951C9.2502 4.5853 9.58599 4.24951 10.0002 4.24951C10.4144 4.24951 10.7502 4.5853 10.7502 4.99951V9.24971H15.0006C15.4148 9.24971 15.7506 9.5855 15.7506 9.99971C15.7506 10.4139 15.4148 10.7497 15.0006 10.7497H10.7502V15.0001C10.7502 15.4143 10.4144 15.7501 10.0002 15.7501C9.58599 15.7501 9.2502 15.4143 9.2502 15.0001V10.7497H5C4.58579 10.7497 4.25 10.4139 4.25 9.99971C4.25 9.5855 4.58579 9.24971 5 9.24971H9.2502V4.99951Z" fill=""/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-800">Аудіо</h4>
                            <span class="block text-sm text-gray-500">24% Використано</span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1 block text-right text-sm text-gray-500">89 файлів</span>
                        <span class="block text-right text-sm text-gray-500">12.80 GB</span>
                    </div>
                </div>

                <!-- Apps -->
                <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white py-4 pl-4 pr-4 xl:pr-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-[52px] w-[52px] items-center justify-center rounded-xl bg-warning-500/[0.08] text-warning-500">
                            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill=""/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-800">Додатки</h4>
                            <span class="block text-sm text-gray-500">46% Використано</span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1 block text-right text-sm text-gray-500">67 файлів</span>
                        <span class="block text-right text-sm text-gray-500">8.90 GB</span>
                    </div>
                </div>

                <!-- Docs -->
                <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white py-4 pl-4 pr-4 xl:pr-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-[52px] w-[52px] items-center justify-center rounded-xl bg-error-500/[0.08] text-error-500">
                            <svg class="fill-current" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.8335 19.75C19.8335 20.9926 18.8261 22 17.5835 22H7.0835C5.84086 22 4.8335 20.9926 4.8335 19.75V9.62105C4.8335 9.02455 5.07036 8.45247 5.49201 8.03055L10.8597 2.65951C11.2817 2.23725 11.8542 2 12.4512 2H17.5835C18.8261 2 19.8335 3.00736 19.8335 4.25V19.75ZM17.5835 20.5C17.9977 20.5 18.3335 20.1642 18.3335 19.75V4.25C18.3335 3.83579 17.9977 3.5 17.5835 3.5H12.5815L12.5844 7.49913C12.5853 8.7424 11.5776 9.75073 10.3344 9.75073H6.3335V19.75C6.3335 20.1642 6.66928 20.5 7.0835 20.5H17.5835ZM7.39262 8.25073L11.0823 4.55876L11.0844 7.5002C11.0847 7.91462 10.7488 8.25073 10.3344 8.25073H7.39262ZM8.5835 14.5C8.5835 14.0858 8.91928 13.75 9.3335 13.75H15.3335C15.7477 13.75 16.0835 14.0858 16.0835 14.5C16.0835 14.9142 15.7477 15.25 15.3335 15.25H9.3335C8.91928 15.25 8.5835 14.9142 8.5835 14.5ZM8.5835 17.5C8.5835 17.0858 8.91928 16.75 9.3335 16.75H12.3335C12.7477 16.75 13.0835 17.0858 13.0835 17.5C13.0835 17.9142 12.7477 18.25 12.3335 18.25H9.3335C8.91928 18.25 8.5835 17.9142 8.5835 17.5Z" fill=""/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-800">Документи</h4>
                            <span class="block text-sm text-gray-500">18% Використано</span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1 block text-right text-sm text-gray-500">312 файлів</span>
                        <span class="block text-right text-sm text-gray-500">4.60 GB</span>
                    </div>
                </div>

                <!-- Downloads -->
                <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white py-4 pl-4 pr-4 xl:pr-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-[52px] w-[52px] items-center justify-center rounded-xl bg-purple-500/[0.08] text-purple-500">
                            <svg class="fill-current" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6686 16.75C12.4526 16.75 12.2579 16.6587 12.1211 16.5126L7.5115 11.9059C7.21851 11.6131 7.21836 11.1382 7.51116 10.8452C7.80396 10.5523 8.27883 10.5521 8.57182 10.8449L11.9186 14.1896V4C11.9186 3.58579 12.2544 3.25 12.6686 3.25C13.0828 3.25 13.4186 3.58579 13.4186 4V14.1854L16.7615 10.8449C17.0545 10.5521 17.5294 10.5523 17.8222 10.8453C18.115 11.1383 18.1148 11.6131 17.8218 11.9059L13.2469 16.4776C13.1093 16.644 12.9013 16.75 12.6686 16.75ZM5.41663 16C5.41663 15.5858 5.08084 15.25 4.66663 15.25C4.25241 15.25 3.91663 15.5858 3.91663 16V18.5C3.91663 19.7426 4.92399 20.75 6.16663 20.75H19.1675C20.4101 20.75 21.4175 19.7426 21.4175 18.5V16C21.4175 15.5858 21.0817 15.25 20.6675 15.25C20.2533 15.25 19.9175 15.5858 19.9175 16V18.5C19.9175 18.9142 19.5817 19.25 19.1675 19.25H6.16663C5.75241 19.25 5.41663 18.9142 5.41663 18.5V16Z" fill=""/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-medium text-gray-800">Завантаження</h4>
                            <span class="block text-sm text-gray-500">16% Використано</span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1 block text-right text-sm text-gray-500">156 файлів</span>
                        <span class="block text-right text-sm text-gray-500">7.20 GB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 16: Social Links -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <h2 class="mb-6 text-xl font-bold text-gray-900">16. Social Links</h2>

    <div class="space-y-6">
        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Іконки соціальних мереж</h3>
            <div class="flex items-center justify-center gap-5">
                <a href="#" class="text-gray-500 transition hover:text-gray-800">
                    <svg class="fill-current" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.6663 12.1366H13.7497L14.583 8.80322H11.6663V7.13656C11.6663 6.27874 11.6663 5.46989 13.333 5.46989H14.583V2.66997C14.3116 2.63393 13.2855 2.55322 12.2021 2.55322C9.94001 2.55322 8.33301 3.93394 8.33301 6.46965V8.80322H5.83301V12.1366H8.33301V19.2199H11.6663V12.1366Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="text-gray-500 transition hover:text-gray-800">
                    <svg class="fill-current" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.171 2.76172H17.9277L11.9052 9.64505L18.9902 19.0117H13.4427L9.09766 13.3309L4.12603 19.0117H1.3677L7.80936 11.6492L1.0127 2.76172H6.70103L10.6285 7.95422L15.171 2.76172ZM14.2035 17.3617H15.731L5.87103 4.32505H4.23186L14.2035 17.3617Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="text-gray-500 transition hover:text-gray-800">
                    <svg class="fill-current" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.78357 5.05316C5.78326 5.73176 5.37157 6.34241 4.74262 6.59716C4.11367 6.85193 3.39306 6.69993 2.92059 6.21285C2.44811 5.72576 2.31813 5.00085 2.59192 4.37995C2.86572 3.75905 3.48862 3.36614 4.1669 3.3865C5.0678 3.41354 5.78398 4.15186 5.78357 5.05316ZM5.83357 7.95316H2.50024V18.3865H5.83357V7.95316ZM11.1003 7.95316H7.78357V18.3865H11.0669V12.9115C11.0669 9.86147 15.0419 9.57814 15.0419 12.9115V18.3865H18.3336V11.7781C18.3336 6.6365 12.4503 6.82816 11.0669 9.35314L11.1003 7.95316Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="text-gray-500 transition hover:text-gray-800">
                    <svg class="fill-current" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.8572 2.5542C11.7951 2.55575 12.2703 2.56072 12.681 2.57294L12.8427 2.57822C13.0296 2.58487 13.2139 2.5932 13.4362 2.60362C14.3229 2.64459 14.9278 2.78487 15.4591 2.99112C16.0083 3.20292 16.4722 3.48903 16.9354 3.95223C17.3979 4.41542 17.6841 4.8807 17.8966 5.42862C18.1021 5.95917 18.2424 6.56473 18.2841 7.45153C18.294 7.67376 18.302 7.85809 18.3086 8.04496L18.3138 8.2067C18.326 8.61732 18.3316 9.09264 18.3333 10.0305L18.334 10.6518C18.3341 10.7278 18.3341 10.8061 18.3341 10.8869L18.334 11.122L18.3335 11.7434C18.3319 12.6813 18.327 13.1566 18.3147 13.5672L18.3094 13.7289C18.3028 13.9158 18.2945 14.1002 18.2841 14.3223C18.2431 15.2092 18.1021 15.814 17.8966 16.3453C17.6847 16.8946 17.3979 17.3585 16.9354 17.8217C16.4722 18.2842 16.0062 18.5703 15.4591 18.7828C14.9278 18.9883 14.3229 19.1286 13.4362 19.1703C13.2139 19.1802 13.0296 19.1883 12.8427 19.1948L12.681 19.2C12.2703 19.2123 11.7951 19.2178 10.8572 19.2196L10.2358 19.2203C10.1599 19.2203 10.0816 19.2203 10.0007 19.2203H9.76565L9.14424 19.2197C8.2064 19.2182 7.73109 19.2132 7.32046 19.2009L7.15873 19.1957C6.97185 19.189 6.78752 19.1807 6.5653 19.1703C5.67849 19.1293 5.07433 18.9883 4.54239 18.7828C3.99377 18.571 3.52919 18.2842 3.06599 17.8217C2.6028 17.3585 2.31739 16.8925 2.10489 16.3453C1.89864 15.814 1.75905 15.2092 1.71739 14.3223C1.70749 14.1002 1.69941 13.9158 1.69287 13.7289L1.68763 13.5672C1.67544 13.1566 1.66988 12.6813 1.66808 11.7434L1.66797 10.0305C1.66952 9.09264 1.67448 8.61732 1.6867 8.2067L1.69199 8.04496C1.69864 7.85809 1.70697 7.67376 1.71739 7.45153C1.75835 6.56403 1.89864 5.95987 2.10489 5.42862C2.31669 4.88001 2.6028 4.41542 3.06599 3.95223C3.52919 3.48903 3.99447 3.20362 4.54239 2.99112C5.07364 2.78487 5.6778 2.64528 6.5653 2.60362C6.78752 2.59372 6.97185 2.58565 7.15873 2.57911L7.32046 2.57387C7.73109 2.56167 8.2064 2.55611 9.14424 2.55431L10.8572 2.5542ZM10.0007 6.72028C7.6983 6.72028 5.83405 8.58656 5.83405 10.8869C5.83405 13.1893 7.70033 15.0536 10.0007 15.0536C12.3032 15.0536 14.1674 13.1873 14.1674 10.8869C14.1674 8.58453 12.3011 6.72028 10.0007 6.72028ZM10.0007 8.38695C11.3815 8.38695 12.5007 9.50584 12.5007 10.8869C12.5007 12.2677 11.3818 13.3869 10.0007 13.3869C8.61999 13.3869 7.50072 12.2681 7.50072 10.8869C7.50072 9.50617 8.61957 8.38695 10.0007 8.38695ZM14.3757 5.47028C13.8013 5.47028 13.3341 5.93687 13.3341 6.51124C13.3341 7.08562 13.8007 7.55292 14.3757 7.55292C14.9501 7.55292 15.4174 7.08634 15.4174 6.51124C15.4174 5.93687 14.9493 5.46957 14.3757 5.47028Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="text-gray-500 transition hover:text-gray-800">
                    <svg class="fill-current" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.7495 10.8869C18.7495 6.05353 14.8329 2.13672 9.99958 2.13672C5.16625 2.13672 1.24958 6.05353 1.24958 10.8869C1.24958 15.2701 4.43833 18.9034 8.59375 19.5367V13.2617H6.37458V10.8869H8.59375V9.05353C8.59375 6.86172 9.93708 5.61172 11.9329 5.61172C12.8912 5.61172 13.8912 5.78672 13.8912 5.78672V7.92839H12.7912C11.7079 7.92839 11.4079 8.59506 11.4079 9.27839V10.8869H13.7912L13.4412 13.2617H11.4079V19.5367C15.5621 18.9034 18.7495 15.2701 18.7495 10.8869Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="text-gray-500 transition hover:text-gray-800">
                    <svg class="fill-current" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.1669 7.01172C18.9167 6.07093 18.1936 5.33554 17.2669 5.08047C15.6002 4.63672 10.0002 4.63672 10.0002 4.63672C10.0002 4.63672 4.40021 4.63672 2.73354 5.08047C1.80687 5.33559 1.08374 6.07093 0.833542 7.01172C0.397583 8.69714 0.397583 12.2119 0.397583 12.2119C0.397583 12.2119 0.397583 15.7266 0.833542 17.412C1.08374 18.3528 1.80687 19.0617 2.73354 19.3168C4.40021 19.7605 10.0002 19.7605 10.0002 19.7605C10.0002 19.7605 15.6002 19.7605 17.2669 19.3168C18.1936 19.0617 18.9167 18.3528 19.1669 17.412C19.6029 15.7266 19.6029 12.2119 19.6029 12.2119C19.6029 12.2119 19.6029 8.69714 19.1669 7.01172ZM8.13354 15.0367V9.38714L13.0669 12.2119L8.13354 15.0367Z" fill=""/>
                    </svg>
                </a>
            </div>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-medium text-gray-700">Social Links з кольоровими іконками</h3>
            <div class="flex items-center justify-center gap-5">
                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1877F2] text-white transition hover:opacity-80">
                    <svg class="fill-current" width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.00007 1H6.53341C5.49275 1 4.49493 1.42143 3.75759 2.17157C3.02024 2.92172 2.60425 3.93913 2.60425 5V7.5H0.137573V11H2.60425V17H6.02091V11H8.48758L9.00007 7.5H6.02091V5C6.02091 4.86739 6.07168 4.74021 6.16169 4.64645C6.25171 4.55268 6.37419 4.5 6.50258 4.5H9.00007V1Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-white transition hover:opacity-80">
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.1369 0.5H14.3425L9.52392 6.01562L15.1994 15.5H10.5144L6.85267 10.2594L2.66767 15.5H0.460172L5.61017 9.59687L0.199219 0.5H4.99329L8.32392 5.34375L12.1369 0.5ZM11.3369 14H12.6619L4.11954 1.84375H2.69954L11.3369 14Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0A66C2] text-white transition hover:opacity-80">
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.62857 3.5C3.62834 4.03438 3.38885 4.54688 2.96571 4.92188C2.54257 5.29687 1.9717 5.5 1.38 5.5C0.788285 5.5 0.217428 5.29687 -0.205714 4.92188C-0.628857 4.54688 -0.868357 4.03438 -0.868572 3.5C-0.868357 2.96562 -0.628857 2.45312 -0.205714 2.07812C0.217428 1.70312 0.788285 1.5 1.38 1.5C1.9717 1.5 2.54257 1.70312 2.96571 2.07812C3.38885 2.45312 3.62834 2.96562 3.62857 3.5ZM3.66857 6.5H0.200001V16H3.66857V6.5ZM8.74286 6.5H5.30571V16H8.74286V10.9C8.74286 7.975 12.6686 7.7125 12.6686 10.9V16H16.1371V9.7375C16.1371 4.45 10.3429 4.6375 8.74286 7.1125V6.5Z" fill=""/>
                    </svg>
                </a>

                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#833AB4] via-[#FD1D1D] to-[#F77737] text-white transition hover:opacity-80">
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 0C5.827 0 5.555.01 4.702.048 3.85.088 3.27.222 2.76.42c-.526.204-.973.478-1.417.923-.445.444-.72.89-.923 1.417-.198.51-.333 1.09-.372 1.942C.008 5.555 0 5.827 0 8s.01 2.445.048 3.298c.04.852.174 1.433.372 1.942.204.526.478.973.923 1.417.444.445.89.72 1.417.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.445-.01 3.298-.048c.852-.04 1.433-.174 1.942-.372.526-.204.973-.478 1.417-.923.445-.444.72-.89.923-1.417.198-.51.333-1.09.372-1.942.04-.853.048-1.125.048-3.298s-.01-2.445-.048-3.298c-.04-.852-.174-1.433-.372-1.942-.204-.526-.478-.973-.923-1.417-.444-.445-.89-.72-1.417-.923-.51-.198-1.09-.333-1.942-.372C10.445.008 10.173 0 8 0zm0 1.44c2.136 0 2.39.01 3.233.048.78.036 1.203.166 1.485.276.374.145.64.318.92.598.28.28.453.546.598.92.11.282.24.705.276 1.485.038.844.047 1.097.047 3.233s-.01 2.39-.048 3.233c-.036.78-.166 1.203-.276 1.485-.145.374-.318.64-.598.92-.28.28-.546.453-.92.598-.282.11-.705.24-1.485.276-.844.038-1.097.047-3.233.047s-2.39-.01-3.233-.048c-.78-.036-1.203-.166-1.485-.276-.374-.145-.64-.318-.92-.598-.28-.28-.453-.546-.598-.92-.11-.282-.24-.705-.276-1.485C1.45 10.39 1.44 10.136 1.44 8s.01-2.39.048-3.233c.036-.78.166-1.203.276-1.485.145-.374.318-.64.598-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276C5.61 1.45 5.864 1.44 8 1.44z" fill=""/>
                        <path d="M8 3.892c-2.27 0-4.108 1.84-4.108 4.108 0 2.27 1.84 4.108 4.108 4.108 2.27 0 4.108-1.84 4.108-4.108 0-2.27-1.84-4.108-4.108-4.108zM8 10.667c-1.473 0-2.667-1.194-2.667-2.667S6.527 5.333 8 5.333 10.667 6.527 10.667 8 9.473 10.667 8 10.667zm4.27-6.912c0 .53-.43.96-.96.96s-.96-.43-.96-.96.43-.96.96-.96.96.43.96.96z" fill=""/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
