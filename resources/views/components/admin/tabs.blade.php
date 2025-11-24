@props([
    'id' => 'tabs-' . uniqid(),
    'variant' => 'default',
    'items' => []
])

<div x-data="{ activeTab: '{{ $items[0]['id'] ?? 'tab1' }}' }" {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($variant === 'default')
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-6">
                @foreach($items as $item)
                    <button
                        @click="activeTab = '{{ $item['id'] }}'"
                        :class="activeTab === '{{ $item['id'] }}' ? 'border-brand-500 text-brand-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition"
                        type="button"
                    >
                        @if(isset($item['icon']))
                            <span class="inline-flex items-center gap-2">
                                {!! $item['icon'] !!}
                                {{ $item['label'] }}
                            </span>
                        @else
                            {{ $item['label'] }}
                        @endif
                        @if(isset($item['badge']))
                            <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>
    @elseif($variant === 'pills')
        <div class="border-b border-gray-200">
            <nav class="flex space-x-2">
                @foreach($items as $item)
                    <button
                        @click="activeTab = '{{ $item['id'] }}'"
                        :class="activeTab === '{{ $item['id'] }}' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-medium transition"
                        type="button"
                    >
                        @if(isset($item['icon']))
                            <span class="inline-flex items-center gap-2">
                                {!! $item['icon'] !!}
                                {{ $item['label'] }}
                            </span>
                        @else
                            {{ $item['label'] }}
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>
    @elseif($variant === 'vertical')
        <div class="flex gap-6">
            <nav class="flex flex-col space-y-1 border-r border-gray-200 pr-6">
                @foreach($items as $item)
                    <button
                        @click="activeTab = '{{ $item['id'] }}'"
                        :class="activeTab === '{{ $item['id'] }}' ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                        class="whitespace-nowrap border-l-2 px-4 py-2 text-left text-sm font-medium transition"
                        type="button"
                    >
                        {{ $item['label'] }}
                    </button>
                @endforeach
            </nav>
            <div class="flex-1">
                {{ $slot }}
            </div>
        </div>
        @php return; @endphp
    @endif

    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
