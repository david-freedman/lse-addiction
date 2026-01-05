<div class="md:col-span-2 rounded-xl border border-gray-200 bg-white overflow-hidden"
     x-data="{ hasHomework: {{ old('has_homework', isset($lesson) && $lesson?->homework ? 'true' : 'false') }} }">

    <div class="flex items-center justify-between px-5 py-4" :class="hasHomework ? 'bg-brand-50 border-b border-brand-100' : 'bg-gray-50'">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg" :class="hasHomework ? 'bg-brand-100' : 'bg-gray-200'">
                <svg class="w-5 h-5" :class="hasHomework ? 'text-brand-600' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Домашнє завдання</h3>
                <p class="text-xs text-gray-500">Практичне завдання для закріплення матеріалу</p>
            </div>
        </div>

        <div class="relative inline-flex items-center cursor-pointer">
            <input type="hidden" name="has_homework" value="0">
            <input type="checkbox" name="has_homework" value="1" x-model="hasHomework" class="sr-only">
            <div @click="hasHomework = !hasHomework"
                 :class="hasHomework ? 'bg-brand-500' : 'bg-gray-200'"
                 class="block h-6 w-11 rounded-full transition"></div>
            <div @click="hasHomework = !hasHomework"
                 :class="hasHomework ? 'translate-x-6' : 'translate-x-1'"
                 class="absolute left-0 top-1 h-4 w-4 rounded-full bg-white shadow transition"></div>
        </div>
    </div>

    <div x-show="hasHomework" x-collapse>
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Опис завдання</label>
                <textarea name="homework_description" rows="3"
                          class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">{{ old('homework_description', $lesson?->homework?->description ?? '') }}</textarea>
                @error('homework_description')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Тип відповіді</label>
                <select name="homework_response_type"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                    @foreach(\App\Domains\Homework\Enums\HomeworkResponseType::cases() as $responseType)
                        <option value="{{ $responseType->value }}"
                            {{ old('homework_response_type', $lesson?->homework?->response_type?->value ?? 'both') === $responseType->value ? 'selected' : '' }}>
                            {{ $responseType->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Максимум балів</label>
                <input type="number" name="homework_max_points"
                       value="{{ old('homework_max_points', $lesson?->homework?->max_points ?? 10) }}"
                       min="1" max="100"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                @error('homework_max_points')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Прохідний бал (%)</label>
                <input type="number" name="homework_passing_score"
                       value="{{ old('homework_passing_score', $lesson?->homework?->passing_score ?? '') }}"
                       min="0" max="100" placeholder="Без мінімуму"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                @error('homework_passing_score')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Макс. спроб</label>
                <input type="number" name="homework_max_attempts"
                       value="{{ old('homework_max_attempts', $lesson?->homework?->max_attempts ?? '') }}"
                       min="1" placeholder="Без обмежень"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                @error('homework_max_attempts')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Дедлайн</label>
                <input type="datetime-local" name="homework_deadline_at"
                       value="{{ old('homework_deadline_at', $lesson?->homework?->deadline_at?->format('Y-m-d\TH:i') ?? '') }}"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                <p class="mt-1 text-xs text-gray-500">М'який дедлайн - здача після нього буде позначена як пізня</p>
                @error('homework_deadline_at')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Макс. файлів</label>
                <input type="number" name="homework_max_files"
                       value="{{ old('homework_max_files', $lesson?->homework?->max_files ?? 5) }}"
                       min="1" max="20"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                @error('homework_max_files')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Макс. розмір файлу (МБ)</label>
                <input type="number" name="homework_max_file_size_mb"
                       value="{{ old('homework_max_file_size_mb', $lesson?->homework?->max_file_size_mb ?? 10) }}"
                       min="1" max="50"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-500">
                @error('homework_max_file_size_mb')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="homework_is_required" value="0">
                    <input type="checkbox" name="homework_is_required" value="1"
                           {{ old('homework_is_required', $lesson?->homework?->is_required ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-sm text-gray-700">Обов'язкове для завершення уроку</span>
                </label>
                <p class="mt-1 text-xs text-gray-500 ml-6">Якщо увімкнено, студент не зможе завершити урок без зарахованого ДЗ</p>
            </div>

            @if(isset($lesson) && $lesson->homework)
                <div class="md:col-span-2 pt-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        @php
                            $pendingCount = $lesson->homework->submissions()->pending()->count();
                            $totalCount = $lesson->homework->submissions()->count();
                        @endphp
                        Здано: <span class="font-medium">{{ $totalCount }}</span>
                        @if($pendingCount > 0)
                            <span class="mx-2">•</span>
                            На перевірці: <span class="font-medium text-warning-600">{{ $pendingCount }}</span>
                        @endif
                    </div>
                    @if($totalCount > 0)
                        <a href="{{ route('admin.homework.index', ['lesson_id' => $lesson->id]) }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Переглянути роботи
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
