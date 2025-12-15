@php
    $homework = $viewModel->homework();
    $latestSubmission = $viewModel->latestSubmission();
    $canSubmit = $viewModel->canSubmitHomework();
    $attemptsRemaining = $viewModel->homeworkAttemptsRemaining();
@endphp

<div class="space-y-6">
    @if($homework->description)
        <p class="text-gray-600 leading-relaxed">
            {!! nl2br(e($homework->description)) !!}
        </p>
    @endif

    <div class="flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-sm text-gray-600">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            {{ $homework->response_type->label() }}
        </span>
        @if($homework->passing_score)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-sm text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Прохідний: {{ $homework->getPassingScorePoints() }}
            </span>
        @endif
        @if($attemptsRemaining !== null)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm {{ $attemptsRemaining === 0 ? 'bg-red-50 border border-red-200 text-red-600' : 'bg-white border border-gray-200 text-gray-600' }}">
                <svg class="w-4 h-4 {{ $attemptsRemaining === 0 ? 'text-red-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Спроб: {{ $attemptsRemaining }}
            </span>
        @endif
    </div>

    @if($latestSubmission)
        <div class="rounded-xl bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100">
                <h4 class="font-semibold text-gray-900">Спроба #{{ $latestSubmission->attempt_number }}</h4>
                <span class="inline-flex items-center gap-1.5 text-sm font-medium
                    @switch($latestSubmission->status->value)
                        @case('pending') text-amber-600 @break
                        @case('revision_requested') text-blue-600 @break
                        @case('approved') text-green-600 @break
                        @case('rejected') text-red-600 @break
                    @endswitch
                ">
                    @if($latestSubmission->status->value === 'pending')
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    @elseif($latestSubmission->status->value === 'approved')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($latestSubmission->status->value === 'rejected')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ $latestSubmission->status->label() }}
                </span>
            </div>

            <div class="px-5 py-4">
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $latestSubmission->submitted_at->format('d.m.Y H:i') }}
                    </div>
                    @if($deadlineDiff = $latestSubmission->getDeadlineDifferenceText())
                        <div class="flex items-center gap-2 {{ $latestSubmission->is_late ? 'text-red-500' : 'text-green-500' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $deadlineDiff }}
                        </div>
                    @endif
                    @if($latestSubmission->score !== null)
                        <div class="flex items-center gap-2 {{ $latestSubmission->isPassed() ? 'text-green-600 font-medium' : 'text-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            {{ $latestSubmission->score }}/{{ $homework->max_points }} ({{ $latestSubmission->scorePercentage() }}%)
                        </div>
                    @endif
                </div>

                @if($latestSubmission->hasFiles())
                    <div class="mt-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Завантажені файли</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($latestSubmission->files as $index => $filePath)
                                <a href="{{ route('student.homework.download', [$course, $lesson, $latestSubmission, $index]) }}"
                                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ basename($filePath) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($latestSubmission->feedback)
                    <div class="mt-4 p-4 rounded-lg bg-gray-50">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Коментар викладача</p>
                        <p class="text-sm text-gray-700">{!! nl2br(e($latestSubmission->feedback)) !!}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($canSubmit)
        <div class="rounded-xl bg-white border border-gray-200 p-5">
            <h4 class="font-semibold text-gray-900 mb-4">
                @if($latestSubmission)
                    Нова спроба
                @else
                    Здати роботу
                @endif
            </h4>

            <form action="{{ route('student.homework.submit', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($homework->response_type->allowsText())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Текстова відповідь</label>
                        <textarea name="text_response" rows="5"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none"
                                  placeholder="Введіть вашу відповідь...">{{ old('text_response') }}</textarea>
                        @error('text_response')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                @endif

                @if($homework->response_type->allowsFiles())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Файли (макс. {{ $homework->max_files }} файлів по {{ $homework->max_file_size_mb }} МБ)
                        </label>
                        <input type="file" name="files[]" multiple
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        @if($homework->allowed_extensions)
                            <p class="mt-1 text-xs text-gray-500">Дозволені формати: {{ implode(', ', $homework->allowed_extensions) }}</p>
                        @endif
                        @error('files')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        @error('files.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                @endif

                @error('submission')<p class="mb-4 text-sm text-red-600">{{ $message }}</p>@enderror

                <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Відправити
                </button>
            </form>
        </div>
    @elseif(!$viewModel->isHomeworkPassed() && $latestSubmission)
        <div class="rounded-xl p-4 flex items-center justify-center gap-2
            @if($latestSubmission->status->value === 'pending')
                bg-amber-50 border border-amber-200 text-amber-700
            @elseif($attemptsRemaining === 0)
                bg-red-50 border border-red-200 text-red-700
            @else
                bg-gray-50 border border-gray-200 text-gray-600
            @endif
        ">
            @if($latestSubmission->status->value === 'pending')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">Ваша робота на перевірці</span>
            @elseif($attemptsRemaining === 0)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="text-sm font-medium">Ви використали всі спроби</span>
            @endif
        </div>
    @endif

    @if($viewModel->homeworkSubmissions()->count() > 1)
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Попередні спроби</h4>
            <div class="space-y-2">
                @foreach($viewModel->homeworkSubmissions()->skip(1) as $submission)
                    <div class="flex items-center justify-between text-sm px-4 py-3 rounded-xl bg-white border border-gray-200">
                        <div class="flex items-center gap-4">
                            <span class="font-medium text-gray-700">Спроба #{{ $submission->attempt_number }}</span>
                            <span class="text-gray-400">{{ $submission->submitted_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <span class="font-medium
                            @switch($submission->status->value)
                                @case('pending') text-amber-600 @break
                                @case('revision_requested') text-blue-600 @break
                                @case('approved') text-green-600 @break
                                @case('rejected') text-red-600 @break
                            @endswitch
                        ">
                            {{ $submission->status->label() }}
                            @if($submission->score !== null)
                                — {{ $submission->score }}/{{ $homework->max_points }}
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
