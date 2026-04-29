@extends('layouts.app')

@section('title', 'Результат: ' . $quiz->title . ' - ' . $webinar->title)

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6 max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('student.webinar.show', $webinar) }}" class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-700 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Повернутися до вебінару
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            <div class="text-center mb-10">
                @if($quiz->isSurvey())
                    <div class="w-20 h-20 mx-auto mb-4 bg-teal-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Дякуємо за відповіді!</h2>
                    <p class="text-gray-600">Ваші відповіді успішно збережено</p>
                @elseif($result->passed)
                    <div class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Вітаємо! Тестування пройдено</h2>
                    <p class="text-gray-600">Ви успішно пройшли цей тест до вебінару</p>
                @else
                    <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Тестування не пройдено</h2>
                    <p class="text-gray-600">Спробуйте ще раз, щоб покращити результат</p>
                @endif
            </div>

            @if(!$quiz->isSurvey())
                <div class="bg-gray-50 rounded-2xl p-8 mb-8 border border-gray-100">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
                        <div>
                            <p class="text-4xl font-bold mb-1 {{ $result->passed ? 'text-green-600' : 'text-red-600' }}">
                                {{ $result->scorePercentage }}%
                            </p>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Ваш результат</p>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-gray-900 mb-1">{{ $result->score }}/{{ $result->maxScore }}</p>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Балів</p>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-gray-900 mb-1">{{ $quiz->passing_score ?? 70 }}%</p>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Прохідний бал</p>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-gray-900 mb-1">
                                @if($attempt->time_spent_seconds)
                                    {{ gmdate('i:s', $attempt->time_spent_seconds) }}
                                @else
                                    --:--
                                @endif
                            </p>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Час</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($quiz->show_correct_answers)
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Детальні результати</h3>
                    <div class="space-y-4">
                        @foreach($quiz->questions as $question)
                            @php
                                $questionResult = $result->questionResults[$question->id] ?? null;
                                $isCorrect = $questionResult['correct'] ?? false;
                            @endphp
                            <div class="p-5 rounded-xl border {{ $isCorrect ? 'bg-green-50/50 border-green-200' : 'bg-red-50/50 border-red-200' }}">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 mt-1">
                                        @if($isCorrect)
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-lg mb-2 {{ $isCorrect ? 'text-green-900' : 'text-red-900' }}">
                                            {{ $question->question_text }}
                                        </p>
                                        @if(!$isCorrect && isset($questionResult['correctAnswers']))
                                            <div class="mt-3 bg-white/60 p-3 rounded-lg border {{ $isCorrect ? 'border-green-100' : 'border-red-100' }}">
                                                <p class="text-sm font-medium {{ $isCorrect ? 'text-green-800' : 'text-red-800' }} mb-1">Правильні відповіді:</p>
                                                <ul class="list-disc list-inside text-sm {{ $isCorrect ? 'text-green-700' : 'text-red-700' }}">
                                                    @foreach($question->answers->whereIn('id', $questionResult['correctAnswers']) as $answer)
                                                        <li>{{ $answer->answer_text }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-6 border-t border-gray-100">
                @php
                    $attemptsUsed = \App\Domains\Progress\Models\StudentQuizAttempt::where('student_id', auth()->id())->where('quiz_id', $quiz->id)->count();
                    $canAttempt = !$quiz->max_attempts || $attemptsUsed < $quiz->max_attempts;
                @endphp

                @if(!$quiz->isSurvey() && $canAttempt)
                    <a href="{{ route('student.webinar.quiz.show', $webinar) }}" class="inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-white bg-teal-500 rounded-xl hover:bg-teal-600 transition shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Спробувати ще раз
                    </a>
                @endif

                <a href="{{ route('student.webinar.show', $webinar) }}" class="inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm hover:shadow-md">
                    Повернутися до вебінару
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
