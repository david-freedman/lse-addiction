@props(['question', 'questionIndex'])

<div class="space-y-3">
    @foreach($question->answers as $answer)
        <label class="flex items-center gap-3 p-4 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-teal-300 transition group">
            <input type="radio"
                   name="answers[{{ $question->id }}][selected][]"
                   value="{{ $answer->id }}"
                   class="w-5 h-5 text-teal-500 border-gray-300 focus:ring-teal-500">
            <span class="text-gray-700 group-hover:text-gray-900">{{ $answer->answer_text }}</span>
            @if($answer->answer_image)
                <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="" class="w-16 h-16 object-cover rounded-lg ml-auto">
            @endif
        </label>
    @endforeach
</div>
