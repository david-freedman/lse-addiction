@props([
    'items' => [],
    'type' => 'unordered',
    'variant' => 'default',
    'divided' => false
])

@php
    $listClass = match($type) {
        'ordered' => 'list-decimal',
        'description' => '',
        default => 'list-disc'
    };

    $variantClass = match($variant) {
        'icon' => 'space-y-2',
        'avatar' => 'space-y-3',
        'interactive' => 'space-y-1',
        default => 'space-y-1.5'
    };

    $dividerClass = $divided ? 'divide-y divide-gray-200' : '';
@endphp

@if($type === 'description')
    <dl class="space-y-3">
        @foreach($items as $item)
            <div class="{{ $divided ? 'border-b border-gray-200 pb-3' : '' }}">
                <dt class="text-sm font-medium text-gray-900">{{ $item['term'] ?? '' }}</dt>
                <dd class="mt-1 text-sm text-gray-600">{{ $item['description'] ?? '' }}</dd>
            </div>
        @endforeach
    </dl>
@elseif($variant === 'icon')
    <ul class="{{ $variantClass }} {{ $dividerClass }}">
        @foreach($items as $item)
            <li class="flex items-start gap-3 {{ $divided ? 'py-2' : '' }}">
                @if(isset($item['icon']))
                    <span class="mt-0.5 flex-shrink-0 text-brand-500">
                        {!! $item['icon'] !!}
                    </span>
                @endif
                <span class="text-sm text-gray-700">{{ $item['text'] ?? $item }}</span>
            </li>
        @endforeach
    </ul>
@elseif($variant === 'avatar')
    <ul class="{{ $variantClass }} {{ $dividerClass }}">
        @foreach($items as $item)
            <li class="flex items-center gap-3 {{ $divided ? 'py-3' : '' }}">
                @if(isset($item['avatar']))
                    <img src="{{ $item['avatar'] }}" alt="{{ $item['name'] ?? '' }}" class="h-10 w-10 rounded-full object-cover">
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-500 text-sm font-medium text-white">
                        {{ strtoupper(substr($item['name'] ?? 'U', 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $item['name'] ?? '' }}</p>
                    @if(isset($item['subtitle']))
                        <p class="text-xs text-gray-500">{{ $item['subtitle'] }}</p>
                    @endif
                </div>
                @if(isset($item['action']))
                    <div class="flex-shrink-0">
                        {!! $item['action'] !!}
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@elseif($variant === 'interactive')
    <ul class="{{ $variantClass }} {{ $dividerClass }}">
        @foreach($items as $item)
            <li>
                <button type="button" class="w-full rounded-lg px-4 py-2.5 text-left text-sm text-gray-700 transition hover:bg-gray-100">
                    <div class="flex items-center justify-between">
                        <span>{{ $item['text'] ?? $item }}</span>
                        @if(isset($item['badge']))
                            <span class="ml-2 inline-flex items-center rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-800">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </div>
                </button>
            </li>
        @endforeach
    </ul>
@else
    <ul class="{{ $listClass }} {{ $variantClass }} ml-5">
        @foreach($items as $item)
            <li class="text-sm text-gray-700 {{ $divided ? 'border-b border-gray-200 pb-2' : '' }}">
                {{ is_string($item) ? $item : ($item['text'] ?? '') }}
            </li>
        @endforeach
    </ul>
@endif
