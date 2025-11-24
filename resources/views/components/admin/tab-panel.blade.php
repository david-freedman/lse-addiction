@props(['id'])

<div
    x-show="activeTab === '{{ $id }}'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform translate-y-1"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    {{ $attributes->merge(['class' => '']) }}
>
    {{ $slot }}
</div>
