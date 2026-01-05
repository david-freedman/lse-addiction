@props(['question', 'questionIndex'])

<div class="space-y-2">
    @foreach($question->answers as $answer)
        <label @click="setSingleAnswer({{ $question->id }}, {{ $answer->id }})"
               :class="answers[{{ $question->id }}]?.selected === {{ $answer->id }} ? 'border-teal-500 bg-teal-50' : 'border-gray-200 bg-white hover:border-teal-300'"
               class="flex items-center gap-2.5 py-2.5 px-3 border rounded-lg cursor-pointer transition group">
            <span :class="answers[{{ $question->id }}]?.selected === {{ $answer->id }} ? 'border-teal-500' : 'border-gray-300'"
                  class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0">
                <span x-show="answers[{{ $question->id }}]?.selected === {{ $answer->id }}"
                      class="w-2 h-2 rounded-full bg-teal-500"></span>
            </span>
            <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $answer->answer_text }}</span>
            @if($answer->answer_image)
                <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="" class="w-16 h-16 object-cover rounded-lg ml-auto">
            @endif
        </label>
    @endforeach
</div>
