@props(['question', 'questionIndex'])

@php
    $categories = $question->answers->pluck('category')->filter()->unique()->values()->toArray();
    $items = $question->answers->map(fn($a) => ['id' => $a->id, 'text' => $a->answer_text, 'image' => $a->answer_image])->toArray();
@endphp

<div x-data="dragDropQuestion({{ json_encode($items) }}, {{ json_encode($categories) }}, {{ $question->id }})"
     x-init="syncToParent()"
     class="space-y-6">
    <div class="mb-4">
        <p class="text-sm text-gray-600 mb-3">Елементи:</p>
        <div class="flex flex-wrap gap-2" x-ref="itemsContainer">
            <template x-for="item in availableItems" :key="item.id">
                <div draggable="true"
                     @dragstart="dragStart($event, item)"
                     @dragend="dragEnd($event)"
                     class="px-4 py-2 bg-teal-500 text-white rounded-lg cursor-move hover:bg-teal-600 transition select-none"
                     x-text="item.text">
                </div>
            </template>
        </div>
    </div>

    <div class="space-y-4">
        @foreach($categories as $category)
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                    <span class="font-medium text-gray-700">{{ $category }}</span>
                </div>
                <div @dragover.prevent
                     @dragenter.prevent="dragEnter($event)"
                     @dragleave="dragLeave($event)"
                     @drop="drop($event, '{{ $category }}')"
                     :class="{ 'bg-teal-50 border-teal-300': isDragOver === '{{ $category }}' }"
                     class="min-h-[60px] p-3 flex flex-wrap gap-2 transition-colors">
                    <template x-for="item in getCategoryItems('{{ $category }}')" :key="item.id">
                        <div class="px-3 py-1.5 bg-teal-100 text-teal-800 rounded-lg flex items-center gap-2">
                            <span x-text="item.text"></span>
                            <button type="button"
                                    @click="removeFromCategory(item, '{{ $category }}')"
                                    class="text-teal-600 hover:text-teal-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <span x-show="getCategoryItems('{{ $category }}').length === 0" class="text-gray-400 text-sm">
                        Перетягніть елементи сюди
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>

@once
@push('scripts')
<script>
function dragDropQuestion(items, categories, questionId) {
    return {
        allItems: items,
        categories: categories,
        availableItems: [...items],
        categoryItems: Object.fromEntries(categories.map(c => [c, []])),
        draggedItem: null,
        isDragOver: null,
        questionId: questionId,

        syncToParent() {
            const categoriesData = {};
            Object.entries(this.categoryItems).forEach(([category, items]) => {
                categoriesData[category] = items.map(item => item.id);
            });

            if (typeof this.setDragDropAnswer === 'function') {
                this.setDragDropAnswer(this.questionId, categoriesData);
            } else if (this.$parent && typeof this.$parent.setDragDropAnswer === 'function') {
                this.$parent.setDragDropAnswer(this.questionId, categoriesData);
            }
        },

        dragStart(event, item) {
            this.draggedItem = item;
            event.dataTransfer.effectAllowed = 'move';
            event.target.classList.add('opacity-50');
        },

        dragEnd(event) {
            event.target.classList.remove('opacity-50');
            this.draggedItem = null;
            this.isDragOver = null;
        },

        dragEnter(event) {
            const category = event.currentTarget.dataset?.category ||
                            event.currentTarget.closest('[\\@drop]')?.outerHTML.match(/'([^']+)'/)?.[1];
        },

        dragLeave(event) {
            if (!event.currentTarget.contains(event.relatedTarget)) {
                this.isDragOver = null;
            }
        },

        drop(event, category) {
            this.isDragOver = null;
            if (!this.draggedItem) return;

            this.availableItems = this.availableItems.filter(i => i.id !== this.draggedItem.id);

            Object.keys(this.categoryItems).forEach(cat => {
                this.categoryItems[cat] = this.categoryItems[cat].filter(i => i.id !== this.draggedItem.id);
            });

            this.categoryItems[category].push(this.draggedItem);
            this.draggedItem = null;

            this.syncToParent();
        },

        getCategoryItems(category) {
            return this.categoryItems[category] || [];
        },

        removeFromCategory(item, category) {
            this.categoryItems[category] = this.categoryItems[category].filter(i => i.id !== item.id);
            this.availableItems.push(item);
            this.syncToParent();
        }
    }
}
</script>
@endpush
@endonce
