@extends('layouts.app')

@section('title', $viewModel->name())

@section('content')
<div>
    <div class="mb-6">
        <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Назад до каталогу
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Hero Banner -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                @if($viewModel->bannerUrl())
                    <div class="w-full h-64 lg:h-80 overflow-hidden bg-gradient-to-br from-teal-400 to-teal-600">
                        <img src="{{ $viewModel->bannerUrl() }}" alt="{{ $viewModel->name() }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full h-64 lg:h-80 bg-gradient-to-br from-teal-400 to-teal-600"></div>
                @endif
            </div>

            <!-- Course Title -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ $viewModel->name() }}</h1>
            </div>

            <!-- About Course -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Про курс</h2>
                <div class="text-gray-700 leading-relaxed prose max-w-none">
                    {!! nl2br(e($viewModel->description())) !!}
                </div>
            </div>

            <!-- Tags -->
            @if(!empty($viewModel->tags()))
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Теги курсу</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($viewModel->tags() as $tag)
                            <span class="px-4 py-2 bg-teal-50 text-teal-700 text-sm font-medium rounded-lg">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Enrollment Info (Only if enrolled) -->
            @if($viewModel->isEnrolled())
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-green-900 mb-2">
                                @if($viewModel->wasPurchased())
                                    Ви придбали цей курс
                                @else
                                    Ви записані на цей курс
                                @endif
                            </h3>
                            <p class="text-green-800 mb-4">
                                @if($viewModel->wasPurchased())
                                    Дякуємо за покупку! Ви маєте повний доступ до всіх матеріалів курсу.
                                @else
                                    Ви успішно записані на цей курс. Доступ до матеріалів відкриється відповідно до розкладу.
                                @endif
                            </p>
                            @if(!$viewModel->wasPurchased())
                                <form action="{{ route('student.courses.unenroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-700 font-medium transition-colors duration-200"
                                            onclick="return confirm('Ви впевнені, що хочете скасувати запис на цей курс?')">
                                        Скасувати запис
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <!-- Price & Purchase Card (Only show if not enrolled) -->
                @if(!$viewModel->isEnrolled())
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <!-- Price -->
                        <div class="mb-6">
                            <p class="text-4xl font-bold text-teal-600">{{ $viewModel->price() }} грн</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            @if($viewModel->canEnroll())
                                <button type="button"
                                        @click="$dispatch('open-purchase-modal', {
                                            courseId: {{ $course->id }},
                                            courseName: '{{ addslashes($viewModel->name()) }}',
                                            coursePrice: {{ $viewModel->price() }}
                                        })"
                                        class="block w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                                    Купити курс
                                </button>
                            @elseif(!$viewModel->isActive())
                                <div class="w-full bg-gray-100 text-gray-600 font-semibold py-3 px-6 rounded-lg text-center">
                                    Курс недоступний
                                </div>
                            @endif
                        </div>

                        <!-- Course Includes -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-900 mb-3">Цей курс включає:</p>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Повний доступ до матеріалів
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Підтримка викладача
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Сертифікат після завершення
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Teacher Info Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ваш викладач</h3>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $viewModel->teacherName() }}</p>
                            <p class="text-sm text-gray-600">Викладач курсу</p>
                        </div>
                    </div>
                </div>

                <!-- Course Stats Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Статистика</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Учасників:</span>
                            <span class="font-semibold text-gray-900">{{ $viewModel->enrolledCount() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Статус:</span>
                            @if($course->status->value === 'active')
                                <span class="text-green-600 font-semibold text-sm">{{ $viewModel->statusLabel() }}</span>
                            @else
                                <span class="text-gray-600 font-semibold text-sm">{{ $viewModel->statusLabel() }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Modal -->
@include('student.catalog.partials.purchase-modal')
@endsection
