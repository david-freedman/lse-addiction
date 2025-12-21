@props(['course', 'showPurchaseButton' => false, 'individualDiscount' => null])

@php
    $cardUrl = (isset($course->is_purchased) && $course->is_purchased)
        ? route('student.courses.show', $course)
        : route('student.catalog.show', $course);

    $finalPrice = (float) $course->price;
    if ($individualDiscount) {
        $discountAmount = $individualDiscount->calculateDiscountAmount($finalPrice);
        $finalPrice = max(0, $finalPrice - $discountAmount);
    }
@endphp

<a href="{{ $cardUrl }}" class="group block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer">
    <div class="relative h-48 overflow-hidden">
        @if($course->banner)
            <img src="{{ $course->banner_url }}" alt="{{ $course->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center group-hover:from-teal-500 group-hover:to-teal-700 transition-all duration-300">
                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif

        @if($course->label_text)
            <div class="absolute top-3 left-3 bg-yellow-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full uppercase">
                {{ $course->label_text }}
            </div>
        @endif

    </div>

    <div class="p-6">
        @if($course->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1 mb-2">
                @foreach($course->tags->take(2) as $tag)
                    <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-xs rounded-full">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 min-h-[56px] group-hover:text-teal-600 transition-colors duration-200">{{ $course->name }}</h3>

        <div class="flex items-center text-sm text-gray-600 mb-3">
            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="truncate">Викладач: {{ $course->teacher?->full_name ?? 'Не вказано' }}</span>
        </div>

        @if($course->starts_at)
            <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $course->formatted_date }}</span>
            </div>
        @endif

        <div class="border-t border-gray-300 pt-4 flex items-center justify-between">
            <div class="flex-1">
                @if(isset($course->is_purchased) && $course->is_purchased)
                @elseif($course->has_discount || $individualDiscount)
                    <div class="flex items-baseline gap-2 flex-wrap">
                        <span class="text-2xl font-bold text-teal-600">{{ number_format($finalPrice, 0, ',', ' ') }} ₴</span>
                        <span class="text-sm text-gray-500 line-through">{{ $course->has_discount ? $course->formatted_old_price : $course->formatted_price }}</span>
                    </div>
                    @if($individualDiscount)
                        <div class="text-xs text-brand-600 font-semibold mt-1">
                            Персональна знижка: {{ $individualDiscount->formattedValue() }}
                        </div>
                    @endif
                @else
                    <span class="text-2xl font-bold text-teal-600">{{ $course->formatted_price }}</span>
                @endif
            </div>

            @if($showPurchaseButton && !(isset($course->is_purchased) && $course->is_purchased) && $course->isAvailableByDate())
                <button
                    onclick="event.preventDefault(); event.stopPropagation(); openPurchaseModal({{ $course->id }}, '{{ addslashes($course->name) }}', '{{ $course->teacher?->full_name ?? '' }}', '{{ $course->formatted_date ?? '' }}', '{{ $course->formatted_price }}', '{{ $course->has_discount ? $course->formatted_discount_amount : '' }}', '{{ $course->banner_url ?? '' }}', '{{ $individualDiscount?->formattedValue() ?? '' }}', '{{ $individualDiscount ? number_format($finalPrice, 0, ',', ' ') . " ₴" : "" }}')"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 ml-2 relative z-10"
                >
                    Купити
                </button>
            @elseif($showPurchaseButton && !(isset($course->is_purchased) && $course->is_purchased) && !$course->isAvailableByDate())
                <span class="bg-amber-100 text-amber-800 font-semibold py-2 px-4 rounded-lg ml-2 text-sm whitespace-nowrap">
                    з {{ $course->formatted_date }}
                </span>
            @elseif(isset($course->is_purchased) && $course->is_purchased)
                <span class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition ml-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Переглянути
                </span>
            @else
                <span class="inline-flex items-center gap-2 text-teal-600 font-semibold ml-2">
                    Детальніше
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            @endif
        </div>
    </div>
</a>
