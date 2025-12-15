@extends('admin.layouts.admin')

@section('title', 'Додати питання')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.quiz.questions.index', [$course, $module, $lesson]) }}" class="text-sm text-gray-500 hover:text-gray-700">
        ← Назад до питань
    </a>
    <h1 class="text-title-xl font-bold text-gray-900 mt-1">Додати питання</h1>
    <p class="text-gray-500">{{ $lesson->name }}</p>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.quiz.questions.store', [$course, $module, $lesson]) }}"
          method="POST"
          enctype="multipart/form-data"
          x-data="questionForm()">
        @csrf

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Тип питання *</label>
                    <select name="type" x-model="type" required
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                        @foreach($questionTypes as $questionType)
                            <option value="{{ $questionType->value }}">{{ $questionType->label() }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500" x-text="typeDescriptions[type]"></p>
                    @error('type')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Балів за питання</label>
                    <input type="number" name="points" value="{{ old('points', 1) }}" min="1"
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    @error('points')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Текст питання *</label>
                <textarea name="question_text" rows="3" required
                          class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">{{ old('question_text') }}</textarea>
                @error('question_text')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Зображення до питання</label>
                <input type="file" name="question_image" accept="image/*"
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white">
                @error('question_image')<p class="mt-1 text-sm text-error-600">{{ $message }}</p>@enderror
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Відповіді</h3>
                @include('admin.quiz.questions.partials.answers-choice')
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
                Створити
            </button>
            <a href="{{ route('admin.quiz.questions.index', [$course, $module, $lesson]) }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Скасувати
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function questionForm() {
    return {
        type: '{{ old('type', 'single_choice') }}',
        typeDescriptions: {
            'single_choice': 'Студент обирає одну відповідь зі списку',
            'multiple_choice': 'Студент може обрати кілька відповідей',
            'image_select': 'Студент обирає правильне зображення',
            'drag_drop': 'Студент перетягує елементи до категорій',
            'ordering': 'Студент розміщує елементи в правильному порядку'
        },
        answers: {!! json_encode(old('answers', [['answer_text' => '', 'is_correct' => false], ['answer_text' => '', 'is_correct' => false]])) !!},

        addAnswer() {
            this.answers.push({ answer_text: '', is_correct: false, category: '' });
        },

        removeAnswer(index) {
            if (this.answers.length > 2) {
                this.answers.splice(index, 1);
            }
        }
    }
}
</script>
@endpush
@endsection
