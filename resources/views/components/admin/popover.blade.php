@props([
    'title' => '',
    'position' => 'bottom',
    'trigger' => 'click'
])

@php
    $positionClasses = [
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2'
    ];

    $arrowClasses = [
        'top' => 'top-full left-1/2 -translate-x-1/2',
        'bottom' => 'bottom-full left-1/2 -translate-x-1/2 rotate-180',
        'left' => 'left-full top-1/2 -translate-y-1/2 -rotate-90',
        'right' => 'right-full top-1/2 -translate-y-1/2 rotate-90'
    ];

    $popoverPosition = $positionClasses[$position] ?? $positionClasses['bottom'];
    $arrowPosition = $arrowClasses[$position] ?? $arrowClasses['bottom'];
@endphp

<div class="relative inline-block" x-data="{ show: false }" @click.away="show = false">
    <div
        @if($trigger === 'click')
            @click="show = !show"
        @else
            @mouseenter="show = true"
            @mouseleave="show = false"
        @endif
    >
        {{ $trigger }}
    </div>

    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 {{ $popoverPosition }} w-64"
        style="display: none;"
    >
        <div class="rounded-lg border border-gray-200 bg-white shadow-lg">
            @if($title)
                <div class="border-b border-gray-200 px-4 py-3">
                    <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
                </div>
            @endif
            <div class="px-4 py-3">
                {{ $slot }}
            </div>

            <div class="absolute {{ $arrowPosition }}">
                <svg class="h-3 w-3 text-white drop-shadow-sm" viewBox="0 0 12 12">
                    <path d="M6 0L12 12H0z" fill="currentColor"/>
                </svg>
            </div>
        </div>
    </div>
</div>
