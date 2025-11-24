@props([
    'text' => '',
    'position' => 'top',
    'variant' => 'dark'
])

@php
    $positionClasses = [
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2'
    ];

    $arrowClasses = [
        'top' => 'top-full left-1/2 -translate-x-1/2 border-l-transparent border-r-transparent border-b-transparent',
        'bottom' => 'bottom-full left-1/2 -translate-x-1/2 border-l-transparent border-r-transparent border-t-transparent',
        'left' => 'left-full top-1/2 -translate-y-1/2 border-t-transparent border-b-transparent border-r-transparent',
        'right' => 'right-full top-1/2 -translate-y-1/2 border-t-transparent border-b-transparent border-l-transparent'
    ];

    $variantClasses = [
        'dark' => [
            'tooltip' => 'bg-gray-900 text-white',
            'arrow' => 'border-gray-900'
        ],
        'light' => [
            'tooltip' => 'bg-white text-gray-900 shadow-lg border border-gray-200',
            'arrow' => 'border-white'
        ]
    ];

    $tooltipClass = $variantClasses[$variant]['tooltip'] ?? $variantClasses['dark']['tooltip'];
    $arrowClass = $variantClasses[$variant]['arrow'] ?? $variantClasses['dark']['arrow'];
    $tooltipPosition = $positionClasses[$position] ?? $positionClasses['top'];
    $arrowPosition = $arrowClasses[$position] ?? $arrowClasses['top'];
@endphp

<div class="relative inline-block" x-data="{ show: false }">
    <div @mouseenter="show = true" @mouseleave="show = false">
        {{ $slot }}
    </div>

    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 {{ $tooltipPosition }}"
        style="display: none;"
    >
        <div class="{{ $tooltipClass }} whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium">
            {{ $text }}
            <div class="absolute h-0 w-0 border-4 {{ $arrowClass }} {{ $arrowPosition }}"></div>
        </div>
    </div>
</div>
