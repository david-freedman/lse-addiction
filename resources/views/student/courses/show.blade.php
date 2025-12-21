@extends('layouts.app')

@section('title', $viewModel->name())

@section('content')
<div class="space-y-6">
    <x-breadcrumbs :items="[
        ['title' => 'Каталог', 'url' => route('student.catalog.index')],
        ['title' => $viewModel->name()],
    ]" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                @if($viewModel->bannerUrl())
                    <div class="w-full h-48 lg:h-64 overflow-hidden bg-gradient-to-br from-teal-400 to-teal-600">
                        <img src="{{ $viewModel->bannerUrl() }}" alt="{{ $viewModel->name() }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full h-48 lg:h-64 bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @if($course->status->value === 'active')
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Активний</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">{{ $viewModel->statusLabel() }}</span>
                    @endif
                    @if($viewModel->isFree())
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Безкоштовно</span>
                    @endif
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $viewModel->name() }}</h1>
            </div>

            @if($viewModel->description())
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Про курс</h2>
                    <div class="text-gray-600 leading-relaxed prose max-w-none">
                        {!! nl2br(e($viewModel->description())) !!}
                    </div>
                </div>
            @endif

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
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Викладач</h3>
                    <div class="flex items-center gap-3">
                        @if($viewModel->teacher()?->avatar_url)
                            <img src="{{ $viewModel->teacher()->avatar_url }}" alt="{{ $viewModel->teacherName() }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-lg font-medium">
                                {{ mb_substr($viewModel->teacherName(), 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $viewModel->teacherName() }}</p>
                            @if($viewModel->teacher()?->position)
                                <p class="text-sm text-gray-500">{{ $viewModel->teacher()->position }}</p>
                            @endif
                        </div>
                    </div>
                    @if($viewModel->teacher()?->description)
                        <p class="mt-4 text-sm text-gray-600 leading-relaxed">{{ $viewModel->teacher()->description }}</p>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Деталі</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Модулів</p>
                                <p class="font-semibold text-gray-900">{{ $viewModel->modulesCount() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Уроків</p>
                                <p class="font-semibold text-gray-900">{{ $viewModel->lessonsCount() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Учасників</p>
                                <p class="font-semibold text-gray-900">{{ $viewModel->enrolledCount() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Запис</h3>
                    @if($viewModel->isEnrolled())
                        <div class="flex items-center gap-2 text-teal-600 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">
                                @if($viewModel->wasPurchased())
                                    Ви придбали цей курс
                                @else
                                    Ви записані на курс
                                @endif
                            </span>
                        </div>
                        <a href="{{ route('student.courses.progress', $course) }}" class="w-full inline-flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Перейти до курсу
                        </a>
                        @if(!$viewModel->wasPurchased())
                            <form action="{{ route('student.courses.unenroll', $course) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit"
                                        class="w-full text-center text-sm text-gray-500 hover:text-red-600 transition"
                                        onclick="return confirm('Ви впевнені, що хочете скасувати запис на цей курс?')">
                                    Скасувати запис
                                </button>
                            </form>
                        @endif
                    @else
                        @if(!$viewModel->isFree())
                            <div class="text-center mb-4">
                                <p class="text-3xl font-bold text-teal-600">{{ $viewModel->price() }} грн</p>
                            </div>
                        @endif
                        @if($viewModel->canEnroll())
                            <button type="button"
                                    @click="$dispatch('open-purchase-modal', {
                                        courseId: {{ $course->id }},
                                        courseName: '{{ addslashes($viewModel->name()) }}',
                                        coursePrice: {{ $viewModel->priceRaw() }}
                                    })"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                                @if($viewModel->isFree())
                                    Записатися безкоштовно
                                @else
                                    Купити курс
                                @endif
                            </button>
                        @elseif($viewModel->isActive() && !$viewModel->isAvailableByDate())
                            <div class="w-full inline-flex items-center justify-center gap-2 bg-amber-100 text-amber-700 font-medium py-3 px-6 rounded-lg text-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm">Доступно з {{ $course->formatted_date }}</span>
                            </div>
                        @elseif(!$viewModel->isActive())
                            <div class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-500 font-medium py-3 px-6 rounded-lg text-center">
                                Курс недоступний
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('student.catalog.partials.purchase-modal')
@endsection
