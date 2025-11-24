@props([
    'href' => '#',
    'variant' => 'default',
    'underline' => 'hover',
    'external' => false,
    'disabled' => false
])

@php
    $variantClasses = match($variant) {
        'brand' => 'text-brand-600 hover:text-brand-700',
        'success' => 'text-success-600 hover:text-success-700',
        'error' => 'text-error-600 hover:text-error-700',
        'warning' => 'text-warning-600 hover:text-warning-700',
        'muted' => 'text-gray-500 hover:text-gray-700',
        default => 'text-gray-700 hover:text-gray-900'
    };

    $underlineClasses = match($underline) {
        'always' => 'underline',
        'none' => 'no-underline',
        default => 'hover:underline'
    };

    $disabledClasses = $disabled ? 'pointer-events-none opacity-50' : '';
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 text-sm font-medium transition {$variantClasses} {$underlineClasses} {$disabledClasses}"]) }}
    @if($external)
        target="_blank"
        rel="noopener noreferrer"
    @endif
>
    {{ $slot }}
    @if($external)
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
    @endif
</a>
