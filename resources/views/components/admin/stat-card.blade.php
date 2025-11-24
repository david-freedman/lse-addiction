@props(['title', 'value', 'change' => null, 'iconColor' => 'brand'])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-6']) }}>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-2">{{ $title }}</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>

            @if($change !== null && $change != 0)
                <div class="mt-2 flex items-center gap-1">
                    @if($change > 0)
                        <svg class="fill-success-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3.33333V12.6667M8 3.33333L12 7.33333M8 3.33333L4 7.33333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-medium text-success-600">+{{ $change }}%</span>
                    @else
                        <svg class="fill-error-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 12.6667V3.33333M8 12.6667L4 8.66667M8 12.6667L12 8.66667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-medium text-error-600">{{ $change }}%</span>
                    @endif
                    <span class="text-xs text-gray-400 ml-1">за місяць</span>
                </div>
            @endif
        </div>

        @if(isset($icon))
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-{{ $iconColor }}-50">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
