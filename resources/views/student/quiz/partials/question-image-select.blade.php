@props(['question', 'questionIndex'])

<div x-data="{ selected: [] }" class="space-y-4">
    @if($question->question_image)
        <div class="relative inline-block">
            <img src="{{ asset('storage/' . $question->question_image) }}"
                 alt="{{ $question->question_text }}"
                 class="max-w-full rounded-lg shadow-md">

            @foreach($question->answers as $index => $answer)
                @php
                    $positions = json_decode($answer->answer_text, true);
                    $x = $positions['x'] ?? (20 + ($index * 15));
                    $y = $positions['y'] ?? (20 + ($index * 10));
                @endphp
                <button type="button"
                        @click="selected.includes({{ $answer->id }}) ? selected = selected.filter(id => id !== {{ $answer->id }}) : selected.push({{ $answer->id }})"
                        :class="selected.includes({{ $answer->id }}) ? 'bg-teal-500 text-white ring-4 ring-teal-200' : 'bg-white text-gray-700 hover:bg-teal-50'"
                        class="absolute w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shadow-lg transition-all transform hover:scale-110"
                        style="left: {{ $x }}%; top: {{ $y }}%; transform: translate(-50%, -50%);">
                    {{ $index + 1 }}
                </button>
            @endforeach
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach($question->answers as $index => $answer)
                <button type="button"
                        @click="selected.includes({{ $answer->id }}) ? selected = selected.filter(id => id !== {{ $answer->id }}) : selected.push({{ $answer->id }})"
                        :class="selected.includes({{ $answer->id }}) ? 'ring-4 ring-teal-500 border-teal-500' : 'border-gray-200 hover:border-teal-300'"
                        class="relative aspect-square rounded-lg border-2 overflow-hidden transition-all">
                    @if($answer->answer_image)
                        <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-400">{{ $index + 1 }}</span>
                        </div>
                    @endif
                    <div x-show="selected.includes({{ $answer->id }})"
                         class="absolute top-2 right-2 w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </button>
            @endforeach
        </div>
    @endif

    <template x-for="id in selected" :key="id">
        <input type="hidden" name="answers[{{ $question->id }}][selected][]" :value="id">
    </template>
</div>
