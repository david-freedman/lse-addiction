@props(['variant' => 'primary', 'icon' => null, 'href' => null])

@php
$classes = match($variant) {
    'primary' => 'bg-brand-500 text-white hover:bg-brand-600',
    'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
    'danger' => 'bg-error-500 text-white hover:bg-error-600',
    'success' => 'bg-success-500 text-white hover:bg-success-600',
    default => 'bg-brand-500 text-white hover:bg-brand-600',
};
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors {$classes}"]) }}>
        @if($icon)
            {!! $icon !!}
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors {$classes}"]) }}>
        @if($icon)
            {!! $icon !!}
        @endif
        {{ $slot }}
    </button>
@endif
