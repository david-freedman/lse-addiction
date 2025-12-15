@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div>
    <div class="mb-6">
        <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Назад до каталогу
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        @if($course->banner)
            <div class="w-full h-80 overflow-hidden bg-gray-100">
                <img src="{{ $course->banner_url }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $course->name }}</h1>

                    @if($course->label)
                        <span class="inline-block bg-yellow-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full uppercase mb-4">
                            {{ $course->label }}
                        </span>
                    @endif

                    <div class="flex items-center gap-6 text-gray-600 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Викладач: {{ $course->teacher?->full_name ?? 'Не вказано' }}</span>
                        </div>

                        @if($course->starts_at)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $course->formatted_date }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ml-8 bg-gray-50 p-6 rounded-lg">
                    @if($course->has_discount || isset($individualDiscount))
                        <div class="text-right mb-2">
                            @if(isset($individualDiscount))
                                <div class="text-4xl font-bold text-teal-600">{{ number_format($finalPrice, 2) }} ₴</div>
                                <div class="text-lg text-gray-500 line-through mt-1">{{ $course->formatted_price }}</div>
                            @else
                                <div class="text-4xl font-bold text-teal-600">{{ $course->formatted_price }}</div>
                                @if($course->has_discount)
                                    <div class="text-lg text-gray-500 line-through mt-1">{{ $course->formatted_old_price }}</div>
                                @endif
                            @endif
                        </div>
                        @if($course->has_discount)
                            <div class="text-right text-green-600 font-semibold">
                                Знижка на курс: {{ $course->formatted_discount_amount }}
                            </div>
                        @endif
                        @if(isset($individualDiscount))
                            <div class="text-right text-brand-600 font-semibold mb-4">
                                Персональна знижка: {{ $individualDiscount->formattedValue() }}
                            </div>
                        @else
                            <div class="mb-4"></div>
                        @endif
                    @else
                        <div class="text-4xl font-bold text-teal-600 mb-4">{{ $course->formatted_price }}</div>
                    @endif

                    <button
                        onclick="openPurchaseModal({{ $course->id }}, '{{ addslashes($course->name) }}', '{{ $course->teacher?->full_name ?? '' }}', '{{ $course->formatted_date ?? '' }}', '{{ $course->formatted_price }}', '{{ $course->has_discount ? $course->formatted_discount_amount : '' }}', '{{ $course->banner_url ?? '' }}', '{{ isset($individualDiscount) ? $individualDiscount->formattedValue() : '' }}', '{{ isset($finalPrice) ? number_format($finalPrice, 2) . " ₴" : "" }}')"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200"
                    >
                        Купити курс
                    </button>
                </div>
            </div>

            <div class="prose max-w-none">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Про курс</h2>
                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $course->description }}
                </div>
            </div>

            @if($course->tags->isNotEmpty())
                <div class="mt-8 pt-8 border-t">
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

@include('student.catalog.partials.purchase-modal')
@endsection
