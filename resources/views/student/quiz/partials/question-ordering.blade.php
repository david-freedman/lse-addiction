@props(['question', 'questionIndex'])

@php
    $items = $question->answers->shuffle()->map(fn($a) => ['id' => $a->id, 'text' => $a->answer_text])->values()->toArray();
@endphp

<div x-data="orderingQuestion({{ json_encode($items) }}, {{ $question->id }})"
     x-init="syncToParent()"
     class="space-y-4">
    <p class="text-sm text-gray-600 mb-3">Перетягніть елементи в правильному порядку:</p>

    <div class="space-y-2">
        <template x-for="(item, index) in orderedItems" :key="item.id">
            <div draggable="true"
                 @dragstart="dragStart($event, index)"
                 @dragend="dragEnd($event)"
                 @dragover.prevent="dragOver($event, index)"
                 @drop.prevent="drop($event, index)"
                 class="flex items-center gap-3 p-4 bg-white border border-gray-200 rounded-lg cursor-move hover:border-teal-300 hover:shadow-sm transition select-none"
                 :class="{ 'opacity-50 border-teal-400': draggedIndex === index, 'border-teal-300 bg-teal-50': dropTargetIndex === index }">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-700 font-semibold text-sm shrink-0">
                    <span x-text="index + 1"></span>
                </div>

                <div class="text-gray-400 shrink-0">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    </svg>
                </div>

                <span class="flex-1 text-gray-900" x-text="item.text"></span>
            </div>
        </template>
    </div>
</div>

@once
@push('scripts')
<script>
function orderingQuestion(items, questionId) {
    return {
        orderedItems: [...items],
        draggedIndex: null,
        dropTargetIndex: null,
        questionId: questionId,

        syncToParent() {
            const ids = this.orderedItems.map(item => item.id);
            if (typeof this.setOrderingAnswer === 'function') {
                this.setOrderingAnswer(this.questionId, ids);
            } else if (this.$parent && typeof this.$parent.setOrderingAnswer === 'function') {
                this.$parent.setOrderingAnswer(this.questionId, ids);
            }
        },

        dragStart(event, index) {
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', index);
        },

        dragEnd(event) {
            this.draggedIndex = null;
            this.dropTargetIndex = null;
        },

        dragOver(event, index) {
            if (this.draggedIndex !== null && this.draggedIndex !== index) {
                this.dropTargetIndex = index;
            }
        },

        drop(event, index) {
            if (this.draggedIndex === null || this.draggedIndex === index) {
                this.dropTargetIndex = null;
                return;
            }

            const item = this.orderedItems.splice(this.draggedIndex, 1)[0];
            this.orderedItems.splice(index, 0, item);

            this.draggedIndex = null;
            this.dropTargetIndex = null;

            this.syncToParent();
        }
    }
}
</script>
@endpush
@endonce
