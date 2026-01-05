@props(['items' => []])

<nav class="flex items-center gap-2 text-sm text-gray-600">
    @foreach($items as $index => $item)
        @if($index > 0)
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif

        @if(isset($item['url']))
            <a href="{{ $item['url'] }}" class="hover:text-teal-600 transition-colors {{ $item['class'] ?? '' }}">
                {{ $item['title'] }}
            </a>
        @else
            <span class="text-gray-900 font-medium {{ $item['class'] ?? '' }}">
                {{ $item['title'] }}
            </span>
        @endif
    @endforeach
</nav>
