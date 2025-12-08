@extends('admin.layouts.admin')

@section('title', 'Прогрес: ' . $student->name . ' ' . $student->surname)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-title-xl font-bold text-gray-900">{{ $student->name }} {{ $student->surname }}</h1>
        <p class="mt-1 text-sm text-gray-500">Детальний прогрес студента</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.students.show', $student) }}" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
            Профіль студента
        </a>
        <a href="{{ route('admin.progress.dashboard') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
            Назад до статистики
        </a>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Загальний прогрес</p>
        <p class="mt-2 text-3xl font-bold text-brand-600">{{ $viewModel->overallProgress() }}%</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Уроків пройдено</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $viewModel->totalLessonsCompleted() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Тестів пройдено</p>
        <p class="mt-2 text-3xl font-bold text-green-600">{{ $viewModel->totalQuizzesPassed() }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-sm font-medium text-gray-500">Середній бал</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $viewModel->averageQuizScore() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-4 text-lg font-bold text-gray-900">Прогрес по курсах</h3>

        @if($viewModel->hasCourseProgress())
            <div class="space-y-4">
                @foreach($viewModel->courseProgress() as $progress)
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="mb-2 flex items-start justify-between">
                            <div>
                                @if($progress->course)
                                    <a href="{{ route('admin.progress.course', $progress->course) }}" class="font-medium text-gray-900 hover:text-brand-600">
                                        {{ $progress->course->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Курс видалено</span>
                                @endif
                            </div>
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                {{ $progress->status->color() === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $progress->status->color() === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $progress->status->color() === 'gray' ? 'bg-gray-100 text-gray-700' : '' }}
                            ">
                                {{ $progress->status->label() }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-200">
                                <div class="h-full bg-brand-500" style="width: {{ $progress->progress_percentage }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ round($progress->progress_percentage, 1) }}%</span>
                        </div>
                        <div class="mt-2 flex gap-4 text-xs text-gray-500">
                            @if($progress->started_at)
                                <span>Розпочато: {{ $progress->started_at->format('d.m.Y') }}</span>
                            @endif
                            @if($progress->completed_at)
                                <span>Завершено: {{ $progress->completed_at->format('d.m.Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center">
                <p class="text-gray-500">Студент ще не розпочав жодного курсу</p>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-4 text-lg font-bold text-gray-900">Останні спроби тестів</h3>

            @if($viewModel->hasQuizAttempts())
                <div class="space-y-3">
                    @foreach($viewModel->quizAttempts()->take(10) as $attempt)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ $attempt->quiz?->title ?? 'Тест' }}</p>
                                <p class="text-xs text-gray-500">{{ $attempt->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->score }}/{{ $attempt->max_score }}
                                </p>
                                <p class="text-xs {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->passed ? 'Пройдено' : 'Не пройдено' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center">
                    <p class="text-gray-500">Немає спроб тестів</p>
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <h3 class="mb-4 text-lg font-bold text-gray-900">Остання активність</h3>

            @if($viewModel->hasRecentActivity())
                <div class="space-y-3">
                    @foreach($viewModel->recentActivity() as $activity)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $activity['type'] === 'quiz' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                    @if($activity['type'] === 'quiz')
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['status'] }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $activity['date']->format('d.m.Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center">
                    <p class="text-gray-500">Немає останньої активності</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
