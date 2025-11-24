@props([
    'value' => 0,
    'max' => 100,
    'size' => 'md',
    'color' => 'brand',
    'label' => null,
    'labelPosition' => 'outside',
    'showPercentage' => true
])

@php
    $percentage = $max > 0 ? ($value / $max) * 100 : 0;
    $heightClasses = [
        'xs' => 'h-1',
        'sm' => 'h-2',
        'md' => 'h-3',
        'lg' => 'h-4',
        'xl' => 'h-6'
    ];
    $height = $heightClasses[$size] ?? $heightClasses['md'];

    $colorClasses = [
        'brand' => 'bg-brand-500',
        'success' => 'bg-success-500',
        'error' => 'bg-error-500',
        'warning' => 'bg-warning-500',
        'info' => 'bg-info-500',
        'purple' => 'bg-purple-500',
        'pink' => 'bg-pink-500'
    ];
    $progressColor = $colorClasses[$color] ?? $colorClasses['brand'];
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label && $labelPosition === 'outside')
        <div class="mb-1.5 flex items-center justify-between text-sm">
            <span class="font-medium text-gray-700">{{ $label }}</span>
            @if($showPercentage)
                <span class="font-semibold text-gray-900">{{ number_format($percentage, 0) }}%</span>
            @endif
        </div>
    @endif

    <div class="overflow-hidden rounded-full bg-gray-200 {{ $height }}">
        <div
            class="{{ $progressColor }} {{ $height }} flex items-center justify-center rounded-full transition-all duration-300"
            style="width: {{ $percentage }}%"
            role="progressbar"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
        >
            @if($label && $labelPosition === 'inside' && $size === 'xl')
                <span class="text-xs font-medium text-white">{{ $label }}</span>
            @elseif($showPercentage && $labelPosition === 'inside' && $size === 'xl')
                <span class="text-xs font-medium text-white">{{ number_format($percentage, 0) }}%</span>
            @endif
        </div>
    </div>
</div>
