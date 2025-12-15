@extends('layouts.app')

@section('title', 'Мої курси')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Мої курси</h1>
        <p class="text-gray-600">Керуйте своїми курсами та відстежуйте прогрес навчання</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Всього курсів</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->totalCount() }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Курси в процесі</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->activeCount() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Завершені</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewModel->completedCount() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex space-x-2 mb-6 border-b border-gray-200">
            <a href="{{ route('student.my-courses') }}"
               class="px-4 py-2 -mb-px {{ $viewModel->isShowingAll() ? 'border-b-2 border-teal-500 text-teal-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                Всі курси ({{ $viewModel->totalCount() }})
            </a>
            <a href="{{ route('student.my-courses', ['status' => 'active']) }}"
               class="px-4 py-2 -mb-px {{ $viewModel->isFilteredBy('active') ? 'border-b-2 border-teal-500 text-teal-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                В процесі ({{ $viewModel->activeCount() }})
            </a>
            <a href="{{ route('student.my-courses', ['status' => 'completed']) }}"
               class="px-4 py-2 -mb-px {{ $viewModel->isFilteredBy('completed') ? 'border-b-2 border-teal-500 text-teal-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                Завершені ({{ $viewModel->completedCount() }})
            </a>
        </div>

        @if($viewModel->hasNoCourses())
            <div class="text-center py-12">
                <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="text-gray-600">Немає курсів для відображення</p>
            </div>
        @else
            <div class="grid gap-6" style="grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));">
                @foreach($viewModel->courses() as $course)
                    @php
                        $isCompleted = $course->pivot->status === 'completed';
                        $progress = $viewModel->getCourseProgress($course->id);
                    @endphp
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition group">
                        @if($course->banner)
                            <div class="w-full aspect-[16/9] overflow-hidden bg-gray-100">
                                <img src="{{ $course->banner_url }}"
                                     alt="{{ $course->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @else
                            <div class="w-full aspect-[16/9] bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif

                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $course->name }}</h3>

                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Викладач: {{ $course->teacher?->full_name ?? 'Не вказано' }}
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-600">Прогрес</span>
                                    <span class="text-sm font-semibold text-teal-600">{{ $progress->progressPercentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-{{ $isCompleted ? 'green' : 'teal' }}-500 h-2 rounded-full transition-all duration-300"
                                         style="width: {{ $progress->progressPercentage }}%"></div>
                                </div>
                            </div>

                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ $progress->lessonsCompleted }}/{{ $progress->totalLessons }} уроків
                            </div>

                            <a href="{{ route('student.courses.show', $course) }}"
                               class="block w-full text-center px-4 py-2.5 rounded-lg font-medium transition
                                      {{ $isCompleted
                                         ? 'bg-green-500 hover:bg-green-600 text-white'
                                         : 'bg-teal-500 hover:bg-teal-600 text-white' }}">
                                <span class="flex items-center justify-center">
                                    @if($isCompleted)
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Переглянути курс
                                    @else
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                        Продовжити
                                    @endif
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
