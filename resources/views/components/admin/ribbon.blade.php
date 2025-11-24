@props([
    'text' => '',
    'position' => 'top-right',
    'color' => 'brand',
    'size' => 'md'
])

@php
    $colorClasses = match($color) {
        'success' => 'bg-success-500 text-white',
        'error' => 'bg-error-500 text-white',
        'warning' => 'bg-warning-500 text-white',
        'info' => 'bg-info-500 text-white',
        'purple' => 'bg-purple-500 text-white',
        'pink' => 'bg-pink-500 text-white',
        default => 'bg-brand-500 text-white'
    };

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1 text-xs',
        'lg' => 'px-6 py-2 text-base',
        default => 'px-4 py-1.5 text-sm'
    };

    [$vertical, $horizontal] = explode('-', $position);

    $positionClasses = match($position) {
        'top-left' => '-left-10 top-5 -rotate-45',
        'top-right' => '-right-10 top-5 rotate-45',
        'bottom-left' => '-left-10 bottom-5 rotate-45',
        'bottom-right' => '-right-10 bottom-5 -rotate-45',
        default => '-right-10 top-5 rotate-45'
    };

    $cornerPosition = match($position) {
        'top-left' => 'left-0 top-0',
        'top-right' => 'right-0 top-0',
        'bottom-left' => 'bottom-0 left-0',
        'bottom-right' => 'bottom-0 right-0',
        default => 'right-0 top-0'
    };
@endphp

@if(str_contains($position, 'corner'))
    <div class="absolute {{ str_replace('corner-', '', $position) === 'top-left' ? 'left-0 top-0' : (str_replace('corner-', '', $position) === 'top-right' ? 'right-0 top-0' : (str_replace('corner-', '', $position) === 'bottom-left' ? 'bottom-0 left-0' : 'bottom-0 right-0')) }} z-10">
        <div class="{{ $colorClasses }} {{ $sizeClasses }} shadow-md">
            <div class="rotate-45 transform whitespace-nowrap font-semibold">
                {{ $text }}
            </div>
        </div>
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="{{ str_replace('text-white', '', $colorClasses) }} h-20 w-20 {{ str_contains($position, 'right') ? '-right-10' : '-left-10' }} {{ str_contains($position, 'bottom') ? '-bottom-10' : '-top-10' }} absolute rotate-45 shadow-lg"></div>
        </div>
    </div>
@else
    <div class="absolute {{ $positionClasses }} z-10 w-40 overflow-hidden shadow-lg">
        <div class="{{ $colorClasses }} {{ $sizeClasses }} text-center font-semibold">
            {{ $text }}
        </div>
    </div>
@endif
