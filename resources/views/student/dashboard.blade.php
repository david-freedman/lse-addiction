@extends('layouts.app')

@section('title', 'Дашборд')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-2xl shadow-lg p-8 text-white">
        <h1 class="text-3xl font-bold mb-2">
            Вітаю, {{ $student->name }}! 👋
        </h1>
        <p class="text-teal-50 text-lg">
            Ласкаво просимо на освітню платформу LSE. Продовжуйте навчання і розвивайтесь разом з нами.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-teal-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-sm text-gray-500">Всього курсів</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalCount }}</div>
            <a href="{{ route('student.my-courses') }}" class="text-sm text-teal-600 hover:text-teal-700">Переглянути всі →</a>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm text-gray-500">Курси в процесі</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $activeCount }}</div>
            <a href="{{ route('student.my-courses', ['status' => 'active']) }}" class="text-sm text-teal-600 hover:text-teal-700">Активні курси →</a>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm text-gray-500">Завершені курси</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $completedCount }}</div>
            <a href="{{ route('student.my-courses', ['status' => 'completed']) }}" class="text-sm text-teal-600 hover:text-teal-700">Завершені →</a>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-yellow-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <span class="text-sm text-gray-500">Сертифікати</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $completedCount }}</div>
            <p class="text-sm text-gray-400">Доступно після завершення</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-teal-100 rounded-lg p-2">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Мої курси</h2>
                            <p class="text-sm text-gray-500">{{ $activeCourses->count() }} активних курсів</p>
                        </div>
                    </div>
                    <a href="{{ route('student.my-courses') }}" class="text-teal-600 hover:text-teal-700 font-medium text-sm flex items-center gap-1">
                        Календар
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                @if($activeCourses->isEmpty())
                    <div class="text-center py-12">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <p class="text-gray-600 mb-2">Ви ще не записані на жоден курс</p>
                        <a href="{{ route('student.my-courses') }}" class="text-teal-600 hover:text-teal-700 font-medium">
                            Переглянути доступні курси →
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($activeCourses as $course)
                            @php
                                $progressPercentages = [50, 75, 100];
                                $randomProgress = $progressPercentages[array_rand($progressPercentages)];
                                $randomHoursCompleted = rand(12, 22);
                                $randomHoursTotal = 24;
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-5 hover:border-teal-300 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 text-lg mb-1">
                                            {{ $course->name }}
                                        </h3>
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $course->teacher?->full_name ?? 'Не вказано' }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.courses.show', $course) }}" class="text-teal-600 hover:bg-teal-50 rounded-lg px-4 py-2 font-medium text-sm transition">
                                        Продовжити
                                    </a>
                                </div>
                                <p class="text-gray-600 text-sm mb-3">Прогрес: {{ $randomHoursCompleted }}/{{ $randomHoursTotal }} годин</p>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-teal-500 h-2 rounded-full transition-all duration-300" style="width: {{ $randomProgress }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-purple-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Найближчі курси</h2>
                        <p class="text-sm text-gray-500">{{ $upcomingCourses->count() }} майбутніх {{ Str::plural('подія', $upcomingCourses->count(), ['подія', 'події', 'подій']) }}</p>
                    </div>
                </div>

                @if($upcomingCourses->isEmpty())
                    <div class="text-center py-8">
                        <div class="bg-gray-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 text-sm">Немає запланованих курсів</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingCourses as $index => $course)
                            <div class="{{ $index === 0 ? 'border-l-4 border-teal-500 bg-teal-50 rounded-r-lg' : 'border border-gray-200 rounded-lg hover:border-teal-300 transition' }} p-4">
                                @if($index === 0)
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="text-xs text-teal-700 font-medium bg-white px-2 py-1 rounded">Скоро почнеться</div>
                                    </div>
                                @endif
                                <h3 class="font-bold text-gray-900 mb-2">{{ $course->name }}</h3>
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $course->teacher?->full_name ?? 'Не вказано' }}</span>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-3 flex-wrap">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $course->starts_at->locale('uk')->isoFormat('D MMMM') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $course->starts_at->format('H:i') }}
                                    </span>
                                </div>
                                @if($course->label)
                                    <div class="mb-3">
                                        <span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">{{ $course->label }}</span>
                                    </div>
                                @endif
                                <a href="{{ route('student.courses.show', $course) }}" class="block w-full text-center {{ $index === 0 ? 'bg-teal-500 hover:bg-teal-600 text-white' : 'border border-teal-500 text-teal-600 hover:bg-teal-50' }} font-medium py-2 px-4 rounded-lg transition">
                                    {{ $index === 0 ? 'Переглянути деталі' : 'Детальніше' }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
