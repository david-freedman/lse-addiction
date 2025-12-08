@props([
    'percentage' => 0,
    'size' => 100,
    'strokeWidth' => 6,
    'showLabel' => true,
    'color' => 'teal',
    'locked' => false,
])

@php
    $radius = ($size - $strokeWidth) / 2;
    $circumference = 2 * pi() * $radius;
    $offset = $circumference - ($percentage / 100) * $circumference;
    $center = $size / 2;

    $strokeColor = match($color) {
        'teal' => 'text-teal-500',
        'purple' => 'text-purple-500',
        'blue' => 'text-blue-500',
        'rose' => 'text-rose-500',
        'orange' => 'text-orange-500',
        'cyan' => 'text-cyan-500',
        'gray' => 'text-gray-300',
        default => 'text-teal-500',
    };

    $textColor = match($color) {
        'teal' => 'text-teal-600',
        'purple' => 'text-purple-600',
        'blue' => 'text-blue-600',
        'rose' => 'text-rose-600',
        'orange' => 'text-orange-600',
        'cyan' => 'text-cyan-600',
        'gray' => 'text-gray-400',
        default => 'text-teal-600',
    };
@endphp

<div class="relative inline-flex items-center justify-center" style="width: {{ $size }}px; height: {{ $size }}px;">
    <svg class="transform -rotate-90" width="{{ $size }}" height="{{ $size }}">
        <circle
            class="text-gray-200"
            stroke="currentColor"
            stroke-width="{{ $strokeWidth }}"
            fill="transparent"
            r="{{ $radius }}"
            cx="{{ $center }}"
            cy="{{ $center }}"
        />
        @if(!$locked)
            <circle
                class="{{ $strokeColor }} transition-all duration-500 ease-out"
                stroke="currentColor"
                stroke-width="{{ $strokeWidth }}"
                stroke-linecap="round"
                fill="transparent"
                r="{{ $radius }}"
                cx="{{ $center }}"
                cy="{{ $center }}"
                style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $offset }};"
            />
        @endif
    </svg>
    @if($showLabel)
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            @if($locked)
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            @else
                <span class="text-lg font-semibold {{ $textColor }}">{{ $percentage }}%</span>
                <span class="text-xs text-gray-500">Прогрес</span>
            @endif
        </div>
    @endif
</div>
