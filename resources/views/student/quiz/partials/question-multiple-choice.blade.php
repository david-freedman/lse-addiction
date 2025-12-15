@props(['question', 'questionIndex'])

<div class="space-y-2">
    @foreach($question->answers as $answer)
        <label @click="toggleMultipleAnswer({{ $question->id }}, {{ $answer->id }})"
               :class="answers[{{ $question->id }}]?.selected?.includes({{ $answer->id }}) ? 'border-teal-500 bg-teal-50' : 'border-gray-200 bg-white hover:border-teal-300'"
               class="flex items-center gap-2.5 py-2.5 px-3 border rounded-lg cursor-pointer transition group">
            <span :class="answers[{{ $question->id }}]?.selected?.includes({{ $answer->id }}) ? 'border-teal-500 bg-teal-500' : 'border-gray-300 bg-white'"
                  class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0">
                <svg x-show="answers[{{ $question->id }}]?.selected?.includes({{ $answer->id }})"
                     class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
            <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $answer->answer_text }}</span>
            @if($answer->answer_image)
                <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="" class="w-16 h-16 object-cover rounded-lg ml-auto">
            @endif
        </label>
    @endforeach
</div>
