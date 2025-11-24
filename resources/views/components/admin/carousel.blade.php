@props([
    'items' => [],
    'autoplay' => false,
    'interval' => 5000,
    'showIndicators' => true,
    'showControls' => true
])

<div
    x-data="{
        currentIndex: 0,
        items: {{ count($items) }},
        autoplay: {{ $autoplay ? 'true' : 'false' }},
        interval: {{ $interval }},
        timer: null,
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.items;
        },
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.items) % this.items;
        },
        goto(index) {
            this.currentIndex = index;
        },
        startAutoplay() {
            if (this.autoplay) {
                this.timer = setInterval(() => this.next(), this.interval);
            }
        },
        stopAutoplay() {
            if (this.timer) {
                clearInterval(this.timer);
            }
        },
        init() {
            this.startAutoplay();
        }
    }"
    @mouseenter="stopAutoplay()"
    @mouseleave="startAutoplay()"
    class="relative overflow-hidden rounded-lg"
    {{ $attributes }}
>
    <div class="relative">
        @foreach($items as $index => $item)
            <div
                x-show="currentIndex === {{ $index }}"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="w-full"
                style="display: none;"
            >
                @if(isset($item['image']))
                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] ?? '' }}" class="h-full w-full object-cover">
                @endif
                @if(isset($item['title']) || isset($item['description']))
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6 text-white">
                        @if(isset($item['title']))
                            <h3 class="text-lg font-semibold">{{ $item['title'] }}</h3>
                        @endif
                        @if(isset($item['description']))
                            <p class="mt-1 text-sm">{{ $item['description'] }}</p>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if($showControls && count($items) > 1)
        <button
            @click="prev()"
            class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow-lg transition hover:bg-white"
            type="button"
        >
            <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button
            @click="next()"
            class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow-lg transition hover:bg-white"
            type="button"
        >
            <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    @endif

    @if($showIndicators && count($items) > 1)
        <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2">
            @foreach($items as $index => $item)
                <button
                    @click="goto({{ $index }})"
                    :class="currentIndex === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-2'"
                    class="h-2 rounded-full transition-all duration-300"
                    type="button"
                ></button>
            @endforeach
        </div>
    @endif
</div>
