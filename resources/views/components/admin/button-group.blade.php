@props([
    'orientation' => 'horizontal',
    'size' => 'md',
    'variant' => 'default'
])

@php
    $orientationClass = $orientation === 'vertical' ? 'flex-col' : 'flex-row';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base'
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="inline-flex {{ $orientationClass }}" role="group">
    {{ $slot }}
</div>

@once
@push('styles')
<style>
.button-group-item:not(:last-child) {
    @apply border-r-0;
}
.button-group-vertical .button-group-item:not(:last-child) {
    @apply border-b-0 border-r;
}
.button-group-item:first-child {
    @apply rounded-l-lg;
}
.button-group-vertical .button-group-item:first-child {
    @apply rounded-l-none rounded-t-lg;
}
.button-group-item:last-child {
    @apply rounded-r-lg;
}
.button-group-vertical .button-group-item:last-child {
    @apply rounded-b-lg rounded-r-none;
}
.button-group-item:not(:first-child):not(:last-child) {
    @apply rounded-none;
}
</style>
@endpush
@endonce
