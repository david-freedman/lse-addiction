@props(['question', 'questionIndex'])

<div class="space-y-4">
    @if($question->question_image)
        <img src="{{ asset('storage/' . $question->question_image) }}"
             alt="{{ $question->question_text }}"
             class="max-w-full rounded-lg shadow-md">
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        @foreach($question->answers as $index => $answer)
            <button type="button"
                    @click="toggleMultipleAnswer({{ $question->id }}, {{ $answer->id }})"
                    :class="answers[{{ $question->id }}]?.selected?.includes({{ $answer->id }}) ? 'ring-4 ring-teal-500 border-teal-500' : 'border-gray-200 hover:border-teal-300'"
                    class="relative rounded-lg border-2 overflow-hidden transition-all">
                <div class="aspect-square">
                    @if($answer->answer_image)
                        <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-400">{{ $index + 1 }}</span>
                        </div>
                    @endif
                </div>
                @if($answer->answer_text)
                    <div class="p-2 text-sm text-gray-700 text-center bg-white">{{ $answer->answer_text }}</div>
                @endif
                <div x-show="answers[{{ $question->id }}]?.selected?.includes({{ $answer->id }})"
                     class="absolute top-2 right-2 w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </button>
        @endforeach
    </div>
</div>
