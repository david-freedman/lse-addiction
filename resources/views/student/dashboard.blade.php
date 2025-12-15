@extends('layouts.app')

@section('title', 'Дашборд')

@section('content')
<div class="space-y-6">
    <x-student.welcome-banner
        :greeting="$viewModel->greeting()"
        :message="$viewModel->welcomeMessage()"
    />

    @php $stats = $viewModel->stats(); @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-student.stat-card
            title="Курси в процесі"
            :value="$stats->coursesInProgress"
            :delta="$stats->coursesInProgressDelta"
            icon="book"
        />
        <x-student.stat-card
            title="Завершені курси"
            :value="$stats->completedCourses"
            :delta="$stats->completedCoursesDelta"
            icon="check"
        />
        <x-student.stat-card
            title="Годин навчання"
            :value="$stats->studyHours"
            :delta="$stats->studyHoursDelta"
            icon="clock"
        />
        <x-student.stat-card
            title="Сертифікатів"
            :value="$stats->certificates"
            :delta="$stats->certificatesDelta"
            :deltaText="$stats->certificatesDelta > 0 ? '+' . $stats->certificatesDelta . ' нових' : null"
            icon="certificate"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Мої курси</h2>
                        <p class="text-sm text-gray-500">{{ $viewModel->coursesCount() }} активних курсів</p>
                    </div>
                </div>

                @if($viewModel->coursesCount() > 0)
                    <div class="space-y-4">
                        @foreach($viewModel->courses() as $course)
                            <x-student.course-progress-card :course="$course" />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Ви ще не почали жодного курсу</h3>
                        <p class="text-gray-500 mb-4">Перегляньте каталог курсів та оберіть те, що вас цікавить</p>
                        <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-medium py-2.5 px-5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Переглянути каталог
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Найближчі вебінари</h2>
                            <p class="text-sm text-gray-500">{{ $viewModel->webinarsCount() }} майбутніх подій</p>
                        </div>
                    </div>
                    <button onclick="openCalendarModal()" class="text-sm font-medium text-gray-500 hover:text-teal-500 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-teal-200 transition">
                        Календар
                    </button>
                </div>

                @if($viewModel->hasWebinars())
                    <div class="space-y-4">
                        @foreach($viewModel->upcomingWebinars() as $webinar)
                            <x-student.webinar-card :webinar="$webinar" />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Немає запланованих вебінарів</h3>
                        <p class="text-gray-500">Слідкуйте за оновленнями - нові вебінари з'являться тут</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('student.dashboard.partials.calendar-modal', ['calendarData' => $viewModel->calendarData()])
@include('student.webinar.partials.register-modal')
@endsection
