<div class="space-y-3">
    <template x-if="type !== 'ordering'">
        <template x-for="(answer, index) in answers" :key="index">
            <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 bg-gray-50">
                <input type="hidden" :name="'answers[' + index + '][id]'" :value="answer.id || ''">

                <div class="pt-2">
                    <template x-if="type === 'single_choice'">
                        <input type="radio"
                               :name="'correct_answer'"
                               :value="index"
                               :checked="answer.is_correct"
                               x-on:change="answers.forEach((a, i) => a.is_correct = i === index)"
                               class="h-4 w-4 border-gray-300 text-brand-600 focus:ring-brand-500">
                    </template>
                    <template x-if="type !== 'single_choice'">
                        <input type="checkbox"
                               :name="'answers[' + index + '][is_correct]'"
                               value="1"
                               :checked="answer.is_correct"
                               x-on:change="answer.is_correct = $event.target.checked"
                               class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    </template>
                </div>

                <div class="flex-1 space-y-2">
                    <template x-if="type === 'single_choice'">
                        <input type="hidden" :name="'answers[' + index + '][is_correct]'" :value="answer.is_correct ? '1' : '0'">
                    </template>

                    <template x-if="type === 'single_choice' || type === 'multiple_choice'">
                        <input type="text"
                               :name="'answers[' + index + '][answer_text]'"
                               x-model="answer.answer_text"
                               placeholder="Текст відповіді"
                               class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 outline-none transition focus:border-brand-500">
                    </template>

                    <template x-if="type === 'image_select'">
                        <div class="space-y-2">
                            <template x-if="answer.existing_image">
                                <div class="flex items-center gap-2">
                                    <img :src="'/storage/' + answer.existing_image" class="h-16 w-16 rounded object-cover">
                                    <input type="hidden" :name="'answers[' + index + '][existing_image]'" :value="answer.existing_image">
                                </div>
                            </template>
                            <input type="file"
                                   :name="'answers[' + index + '][answer_image]'"
                                   accept="image/*"
                                   class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 text-sm">
                            <input type="text"
                                   :name="'answers[' + index + '][answer_text]'"
                                   x-model="answer.answer_text"
                                   placeholder="Підпис до зображення (опціонально)"
                                   class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 outline-none transition focus:border-brand-500">
                        </div>
                    </template>

                    <template x-if="type === 'drag_drop'">
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text"
                                   :name="'answers[' + index + '][answer_text]'"
                                   x-model="answer.answer_text"
                                   placeholder="Елемент для перетягування"
                                   class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 outline-none transition focus:border-brand-500">
                            <input type="text"
                                   :name="'answers[' + index + '][category]'"
                                   x-model="answer.category"
                                   placeholder="Категорія (куди перетягувати)"
                                   class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 outline-none transition focus:border-brand-500">
                        </div>
                    </template>
                </div>

                <button type="button"
                        x-on:click="removeAnswer(index)"
                        :disabled="answers.length <= 2"
                        class="mt-2 text-gray-400 hover:text-error-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </template>
    </template>

    <template x-if="type === 'ordering'">
        <div class="space-y-2" x-data="{ draggedIndex: null }">
            <template x-for="(answer, index) in answers" :key="answer.id || index">
                <div draggable="true"
                     x-on:dragstart="draggedIndex = index; $event.dataTransfer.effectAllowed = 'move'"
                     x-on:dragend="draggedIndex = null"
                     x-on:dragover.prevent
                     x-on:drop.prevent="if (draggedIndex !== null && draggedIndex !== index) { const item = answers.splice(draggedIndex, 1)[0]; answers.splice(index, 0, item); draggedIndex = null; }"
                     class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-gray-50 cursor-move hover:border-brand-300 transition"
                     :class="{ 'opacity-50': draggedIndex === index }">
                    <input type="hidden" :name="'answers[' + index + '][id]'" :value="answer.id || ''">
                    <input type="hidden" :name="'answers[' + index + '][correct_order]'" :value="index + 1">

                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-semibold text-sm">
                        <span x-text="index + 1"></span>
                    </div>

                    <div class="text-gray-400 cursor-grab">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                    </div>

                    <input type="text"
                           :name="'answers[' + index + '][answer_text]'"
                           x-model="answer.answer_text"
                           placeholder="Текст елемента"
                           class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 outline-none transition focus:border-brand-500">

                    <button type="button"
                            x-on:click="removeAnswer(index)"
                            :disabled="answers.length <= 2"
                            class="text-gray-400 hover:text-error-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </template>

    <button type="button"
            x-on:click="addAnswer()"
            class="flex items-center gap-2 text-sm font-medium text-brand-600 hover:text-brand-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Додати відповідь
    </button>

    <template x-if="type === 'ordering'">
        <div class="mt-4 p-3 rounded-lg bg-blue-50 text-sm text-blue-700">
            <strong>Підказка:</strong> Перетягніть елементи для встановлення правильного порядку. Номер зліва показує поточну позицію.
        </div>
    </template>

    <template x-if="type === 'drag_drop'">
        <div class="mt-4 p-3 rounded-lg bg-blue-50 text-sm text-blue-700">
            <strong>Підказка:</strong> Для drag & drop введіть елементи та їх правильні категорії. Студент має перетягнути кожен елемент до відповідної категорії. Позначка "правильно" означає, що елемент знаходиться у своїй категорії.
        </div>
    </template>

    <template x-if="type === 'single_choice'">
        <div class="mt-2 text-sm text-gray-500">
            Оберіть одну правильну відповідь за допомогою радіо-кнопки зліва.
        </div>
    </template>

    <template x-if="type === 'multiple_choice'">
        <div class="mt-2 text-sm text-gray-500">
            Позначте всі правильні відповіді за допомогою чекбоксів зліва.
        </div>
    </template>
</div>
