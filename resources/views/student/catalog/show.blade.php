@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div class="space-y-6">
    <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-teal-500 font-medium transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Назад до каталогу
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                @if($course->banner_url)
                    <div class="w-full h-48 lg:h-64 overflow-hidden bg-gradient-to-br from-teal-400 to-teal-600">
                        <img src="{{ $course->banner_url }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
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
                    @if($course->label_text)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                            {{ $course->label_text }}
                        </span>
                    @endif
                    @if($course->price == 0)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Безкоштовно</span>
                    @endif
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $course->name }}</h1>
            </div>

            @if($course->description)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Про курс</h2>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $course->description }}</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Викладач</h3>
                    <div class="flex items-center gap-3">
                        @if($course->teacher?->avatar_url)
                            <img src="{{ $course->teacher->avatar_url }}" alt="{{ $course->teacher->full_name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-lg font-medium">
                                {{ mb_substr($course->teacher?->full_name ?? 'N', 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $course->teacher?->full_name ?? 'Не вказано' }}</p>
                            @if($course->teacher?->position)
                                <p class="text-sm text-gray-500">{{ $course->teacher->position }}</p>
                            @endif
                        </div>
                    </div>
                    @if($course->teacher?->description)
                        <p class="mt-4 text-sm text-gray-600 leading-relaxed">{{ $course->teacher->description }}</p>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Деталі</h3>
                    <div class="space-y-4">
                        @if($course->starts_at)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Дата старту</p>
                                    <p class="font-semibold text-gray-900">{{ $course->formatted_date }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Модулів</p>
                                <p class="font-semibold text-gray-900">{{ $course->modules_count }}</p>
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
                                <p class="font-semibold text-gray-900">{{ $course->lessons_count }}</p>
                            </div>
                        </div>
                        @if($course->formatted_duration)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Тривалість</p>
                                    <p class="font-semibold text-gray-900">{{ $course->formatted_duration }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Придбання</h3>
                    @if($course->has_discount || isset($individualDiscount))
                        <div class="text-center mb-4">
                            @if(isset($individualDiscount))
                                <div class="text-3xl font-bold text-teal-600">{{ number_format($finalPrice, 0, ',', ' ') }} ₴</div>
                                <div class="text-lg text-gray-400 line-through mt-1">{{ $course->formatted_price }}</div>
                            @else
                                <div class="text-3xl font-bold text-teal-600">{{ $course->formatted_price }}</div>
                                @if($course->has_discount)
                                    <div class="text-lg text-gray-400 line-through mt-1">{{ $course->formatted_old_price }}</div>
                                @endif
                            @endif
                        </div>
                        @if($course->has_discount)
                            <div class="text-center text-green-600 font-semibold text-sm mb-2">
                                Знижка на курс: {{ $course->formatted_discount_amount }}
                            </div>
                        @endif
                        @if(isset($individualDiscount))
                            <div class="text-center text-brand-600 font-semibold text-sm mb-4">
                                Персональна знижка: {{ $individualDiscount->formattedValue() }}
                            </div>
                        @else
                            <div class="mb-4"></div>
                        @endif
                    @else
                        <div class="text-center mb-4">
                            <div class="text-3xl font-bold text-teal-600">{{ $course->formatted_price }}</div>
                        </div>
                    @endif

                    @if($course->isAvailableByDate())
                        <button
                            type="button"
                            onclick="openPurchaseModal('{{ $course->slug }}', '{{ addslashes($course->name) }}', '{{ $course->teacher?->full_name ?? '' }}', '{{ $course->formatted_date ?? '' }}', '{{ $course->formatted_price }}', '{{ $course->has_discount ? $course->formatted_discount_amount : '' }}', '{{ $course->banner_url ?? '' }}', '{{ isset($individualDiscount) ? $individualDiscount->formattedValue() : '' }}', '{{ isset($finalPrice) ? number_format($finalPrice, 0, ',', ' ') . ' ₴' : '' }}')"
                            class="w-full inline-flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-6 rounded-lg transition"
                        >
                            Купити курс
                        </button>
                    @else
                        <div class="w-full inline-flex items-center justify-center gap-2 bg-amber-100 text-amber-800 font-semibold py-3 px-6 rounded-lg text-center">
                            Доступно з {{ $course->formatted_date }}
                        </div>
                    @endif
                </div>

                @if($course->tags->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Теги</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($course->tags as $tag)
                                <span class="px-3 py-1 bg-teal-50 text-teal-700 text-sm rounded-full">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('student.catalog.partials.purchase-modal')
@endsection
