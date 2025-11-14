@props(['course', 'showPurchaseButton' => false])

<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="relative h-48 overflow-hidden">
        @if($course->banner)
            <img src="{{ $course->banner_url }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif

        @if($course->label)
            <div class="absolute top-3 left-3 bg-yellow-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full uppercase">
                {{ $course->label }}
            </div>
        @endif

        @if(isset($course->is_purchased) && $course->is_purchased)
            <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Куплено
            </div>
        @endif
    </div>

    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 min-h-[56px]">{{ $course->name }}</h3>

        <div class="flex items-center text-sm text-gray-600 mb-3">
            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="truncate">Викладач: {{ $course->coach->name ?? 'Не вказано' }}</span>
        </div>

        @if($course->starts_at)
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $course->formatted_date }}</span>
            </div>
        @endif

        <div class="border-t pt-4 flex items-center justify-between">
            <div class="flex-1">
                @if($course->has_discount && !(isset($course->is_purchased) && $course->is_purchased))
                    <div class="flex items-baseline gap-2 flex-wrap">
                        <span class="text-2xl font-bold text-teal-600">{{ $course->formatted_price }}</span>
                        <span class="text-sm text-gray-500 line-through">{{ $course->formatted_old_price }}</span>
                    </div>
                    <div class="text-xs text-green-600 font-semibold mt-1">
                        Економія: {{ $course->formatted_discount_amount }}
                    </div>
                @else
                    <span class="text-2xl font-bold text-teal-600">{{ $course->formatted_price }}</span>
                @endif
            </div>

            @if(isset($course->is_purchased) && $course->is_purchased)
                <a
                    href="{{ route('customer.courses.show', $course) }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 inline-flex items-center gap-2 ml-2"
                >
                    Переглянути
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            @elseif($showPurchaseButton)
                <button
                    onclick="openPurchaseModal({{ $course->id }}, '{{ addslashes($course->name) }}', '{{ $course->coach->name ?? '' }}', '{{ $course->formatted_date ?? '' }}', '{{ $course->formatted_price }}', '{{ $course->has_discount ? $course->formatted_discount_amount : '' }}', '{{ $course->banner_url ?? '' }}')"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 ml-2"
                >
                    Купити
                </button>
            @else
                <a
                    href="{{ route('customer.catalog.show', $course) }}"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 inline-block ml-2"
                >
                    Детальніше
                </a>
            @endif
        </div>
    </div>
</div>
