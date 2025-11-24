@props(['title', 'chartId', 'height' => '350px'])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-6']) }}>
    <h3 class="mb-6 text-lg font-semibold text-gray-900">{{ $title }}</h3>
    <div id="{{ $chartId }}" style="height: {{ $height }};"></div>
</div>
