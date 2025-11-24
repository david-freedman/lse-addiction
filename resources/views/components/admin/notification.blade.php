@props([
    'type' => 'info',
    'title' => '',
    'message' => '',
    'dismissible' => true,
    'autoDismiss' => false,
    'duration' => 5000
])

@php
    $typeConfig = [
        'success' => [
            'bg' => 'bg-success-50',
            'border' => 'border-success-200',
            'icon' => 'text-success-500',
            'title' => 'text-success-800',
            'text' => 'text-success-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
        ],
        'error' => [
            'bg' => 'bg-error-50',
            'border' => 'border-error-200',
            'icon' => 'text-error-500',
            'title' => 'text-error-800',
            'text' => 'text-error-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
        ],
        'warning' => [
            'bg' => 'bg-warning-50',
            'border' => 'border-warning-200',
            'icon' => 'text-warning-500',
            'title' => 'text-warning-800',
            'text' => 'text-warning-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
        ],
        'info' => [
            'bg' => 'bg-info-50',
            'border' => 'border-info-200',
            'icon' => 'text-info-500',
            'title' => 'text-info-800',
            'text' => 'text-info-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        ]
    ];

    $config = $typeConfig[$type] ?? $typeConfig['info'];
@endphp

<div
    x-data="{
        show: true,
        init() {
            @if($autoDismiss)
                setTimeout(() => { this.show = false }, {{ $duration }})
            @endif
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-8"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-8"
    class="{{ $config['bg'] }} {{ $config['border'] }} rounded-lg border p-4 shadow-md"
    style="display: none;"
>
    <div class="flex items-start gap-3">
        <div class="{{ $config['icon'] }} flex-shrink-0">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $config['iconPath'] !!}
            </svg>
        </div>

        <div class="flex-1">
            @if($title)
                <h4 class="{{ $config['title'] }} mb-1 text-sm font-semibold">{{ $title }}</h4>
            @endif
            @if($message)
                <p class="{{ $config['text'] }} text-sm">{{ $message }}</p>
            @endif
            {{ $slot }}
        </div>

        @if($dismissible)
            <button
                @click="show = false"
                class="{{ $config['icon'] }} flex-shrink-0 rounded-lg p-1 hover:bg-black/5"
                type="button"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif
    </div>
</div>
