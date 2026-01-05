<template x-if="type === 'dicom'">
    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-gray-50 p-4 mt-2"
         x-data="{
             sourceType: '{{ old('dicom_source_type', $lesson->dicom_source_type?->value ?? 'file') }}',
             hasExistingFile: {{ isset($lesson) && $lesson->dicom_file_path ? 'true' : 'false' }},
             showReplaceFile: false
         }">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">DICOM налаштування</h3>

        <div class="flex gap-4 mb-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="dicom_source_type" value="file"
                       x-model="sourceType"
                       class="text-brand-600 focus:ring-brand-500">
                <span class="text-sm text-gray-700">Завантажити файл</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="dicom_source_type" value="url"
                       x-model="sourceType"
                       class="text-brand-600 focus:ring-brand-500">
                <span class="text-sm text-gray-700">Зовнішнє посилання</span>
            </label>
        </div>

        <div x-show="sourceType === 'file'" x-cloak>
            <template x-if="hasExistingFile && !showReplaceFile">
                <div class="mb-3 p-3 bg-white rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm text-gray-600">
                                Файл завантажено: {{ isset($lesson) ? basename($lesson->dicom_file_path ?? '') : '' }}
                            </span>
                        </div>
                        <button type="button"
                                @click="showReplaceFile = true"
                                class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                            Замінити
                        </button>
                    </div>
                    @if(isset($lesson) && $lesson->dicom_metadata)
                        <div class="mt-2 pt-2 border-t border-gray-100 grid grid-cols-3 gap-2 text-xs text-gray-500">
                            <div>
                                <span class="font-medium">Модальність:</span>
                                {{ $lesson->dicom_metadata['modality'] ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Кадрів:</span>
                                {{ $lesson->dicom_metadata['frame_count'] ?? 1 }}
                            </div>
                            <div>
                                <span class="font-medium">Тип:</span>
                                {{ ($lesson->dicom_metadata['is_multiframe'] ?? false) ? 'Cine/Video' : 'Статичний' }}
                            </div>
                        </div>
                    @endif
                </div>
            </template>

            <template x-if="!hasExistingFile || showReplaceFile">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        DICOM файл (.dcm)
                    </label>
                    <input type="file"
                           name="dicom_file_upload"
                           accept=".dcm,.DCM,.dicom"
                           class="w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-medium
                                  file:bg-brand-50 file:text-brand-700
                                  hover:file:bg-brand-100
                                  cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500">
                        Максимальний розмір: 100 MB. Підтримуються статичні та мультикадрові DICOM файли.
                    </p>
                    @error('dicom_file_upload')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <template x-if="hasExistingFile && showReplaceFile">
                        <button type="button"
                                @click="showReplaceFile = false"
                                class="mt-2 text-sm text-gray-500 hover:text-gray-700">
                            Скасувати заміну
                        </button>
                    </template>
                </div>
            </template>
        </div>

        <div x-show="sourceType === 'url'" x-cloak>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                URL DICOM файлу
            </label>
            <input type="url"
                   name="dicom_url"
                   value="{{ old('dicom_url', $lesson->dicom_url ?? '') }}"
                   placeholder="https://example.com/dicom/study.dcm"
                   class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5
                          text-gray-900 outline-none transition
                          focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
            <p class="mt-1 text-xs text-gray-500">
                Тільки HTTPS. URL повинен повертати файл з Content-Type: application/dicom
            </p>
            @error('dicom_url')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</template>
