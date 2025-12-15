@extends('admin.layouts.admin')

@section('title', 'Редагувати модуль')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.modules.index', $course) }}" class="text-sm text-gray-500 hover:text-gray-700">
        ← Назад до модулів
    </a>
    <h1 class="text-title-xl font-bold text-gray-900 mt-1">Редагувати модуль</h1>
    <p class="text-gray-500">{{ $module->name }}</p>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.modules.update', [$course, $module]) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="space-y-5">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Назва модуля <span class="text-error-500">*</span></label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $module->name) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('name') border-error-500 @enderror"
                >
                @error('name')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Опис</label>
                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('description') border-error-500 @enderror"
                >{{ old('description', $module->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Статус</label>
                    <select
                        name="status"
                        id="status"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('status') border-error-500 @enderror"
                    >
                        @foreach($moduleStatuses as $status)
                            <option value="{{ $status->value }}" {{ old('status', $module->status->value) === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unlock_rule" class="mb-2 block text-sm font-medium text-gray-700">Правило розблокування</label>
                    <select
                        name="unlock_rule"
                        id="unlock_rule"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white @error('unlock_rule') border-error-500 @enderror"
                    >
                        @foreach($moduleUnlockRules as $rule)
                            <option value="{{ $rule->value }}" {{ old('unlock_rule', $module->unlock_rule->value) === $rule->value ? 'selected' : '' }}>
                                {{ $rule->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('unlock_rule')
                        <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="has_final_test"
                        value="1"
                        {{ old('has_final_test', $module->has_final_test) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                    >
                    <span class="text-sm text-gray-700">Має підсумковий тест</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-gray-200 pt-5 mt-6">
            <a href="{{ route('admin.modules.index', $course) }}" class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                Скасувати
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.lessons.index', [$course, $module]) }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Уроки ({{ $module->lessons_count }})
                </a>
                <button
                    type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20"
                >
                    Зберегти
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
