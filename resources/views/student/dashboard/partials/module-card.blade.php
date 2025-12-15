@php
    /** @var \App\Domains\Progress\Data\ModuleProgressData $module */

    $colorScheme = match($module->iconType) {
        'video' => [
            'icon_bg' => 'bg-teal-100',
            'icon_color' => 'text-teal-600',
            'progress_color' => 'teal',
            'button_bg' => 'bg-teal-500 hover:bg-teal-600',
            'border_color' => 'border-teal-500',
        ],
        'quiz' => [
            'icon_bg' => 'bg-purple-100',
            'icon_color' => 'text-purple-600',
            'progress_color' => 'purple',
            'button_bg' => 'bg-purple-500 hover:bg-purple-600',
            'border_color' => 'border-purple-500',
        ],
        'text' => [
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-600',
            'progress_color' => 'blue',
            'button_bg' => 'bg-blue-500 hover:bg-blue-600',
            'border_color' => 'border-blue-500',
        ],
        'dicom' => [
            'icon_bg' => 'bg-rose-100',
            'icon_color' => 'text-rose-600',
            'progress_color' => 'rose',
            'button_bg' => 'bg-rose-500 hover:bg-rose-600',
            'border_color' => 'border-rose-500',
        ],
        'survey' => [
            'icon_bg' => 'bg-orange-100',
            'icon_color' => 'text-orange-600',
            'progress_color' => 'orange',
            'button_bg' => 'bg-orange-500 hover:bg-orange-600',
            'border_color' => 'border-orange-500',
        ],
        'qa_session' => [
            'icon_bg' => 'bg-cyan-100',
            'icon_color' => 'text-cyan-600',
            'progress_color' => 'cyan',
            'button_bg' => 'bg-cyan-500 hover:bg-cyan-600',
            'border_color' => 'border-cyan-500',
        ],
        'lock' => [
            'icon_bg' => 'bg-amber-50',
            'icon_color' => 'text-amber-400',
            'progress_color' => 'gray',
            'button_bg' => 'bg-gray-200',
            'border_color' => 'border-gray-300',
        ],
        default => [
            'icon_bg' => 'bg-gray-100',
            'icon_color' => 'text-gray-600',
            'progress_color' => 'gray',
            'button_bg' => 'bg-gray-500 hover:bg-gray-600',
            'border_color' => 'border-gray-500',
        ],
    };
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow h-full flex flex-col">
    <div class="flex flex-col items-center text-center flex-1">
        <div class="mb-4">
            @switch($module->iconType)
                @case('lock')
                    <div class="w-12 h-12 rounded-full {{ $colorScheme['icon_bg'] }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $colorScheme['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    @break
                @case('quiz')
                    <div class="w-12 h-12 rounded-full {{ $colorScheme['icon_bg'] }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $colorScheme['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @break
                @case('text')
                    <div class="w-12 h-12 rounded-full {{ $colorScheme['icon_bg'] }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $colorScheme['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    @break
                @case('dicom')
                    <div class="w-12 h-12 rounded-full {{ $colorScheme['icon_bg'] }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $colorScheme['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    @break
                @case('survey')
                    <div class="w-12 h-12 rounded-full {{ $colorScheme['icon_bg'] }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $colorScheme['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    @break
                @case('qa_session')
                    <div class="w-12 h-12 rounded-full {{ $colorScheme['icon_bg'] }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $colorScheme['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    @break
                @default
                    <div class="w-12 h-12 rounded-full {{ $colorScheme['icon_bg'] }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $colorScheme['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
            @endswitch
        </div>

        <h3 class="text-lg font-bold text-gray-900 mb-1">
            Модуль {{ $module->order + 1 }}: {{ $module->name }}
        </h3>

        @if($module->description)
            <p class="text-sm text-gray-500 mb-4">{{ Str::limit($module->description, 50) }}</p>
        @endif

        <div class="mb-4">
            <x-circular-progress
                :percentage="$module->progressPercentage"
                :size="100"
                :stroke-width="6"
                :color="$colorScheme['progress_color']"
                :locked="!$module->isUnlocked"
            />
        </div>

        <div class="flex items-center justify-center gap-6 text-sm text-gray-600 mb-4 w-full">
            <div class="flex flex-col items-center">
                <span class="font-bold text-xl text-gray-900">{{ $module->lessonsCompleted }}</span>
                <span class="text-xs text-gray-500">Завершено</span>
            </div>
            <div class="w-px h-10 bg-gray-200"></div>
            <div class="flex flex-col items-center">
                <span class="font-bold text-xl text-gray-900">{{ $module->totalLessons }}</span>
                <span class="text-xs text-gray-500">Всього</span>
            </div>
        </div>

        @if($module->isUnlocked && count($module->recentLessons) > 0)
            <div class="w-full mb-4">
                <p class="text-xs text-gray-500 mb-2 font-medium">Останні уроки</p>
                <div class="space-y-2">
                    @foreach($module->recentLessons as $lesson)
                        <a href="{{ route('student.lessons.show', ['course' => $module->courseId, 'lesson' => $lesson['id']]) }}" class="flex items-center gap-3 px-4 py-2.5 border border-gray-200 rounded-full text-sm hover:bg-gray-50 transition">
                            <span class="w-3 h-3 rounded-full border-2 {{ $colorScheme['border_color'] }} bg-transparent"></span>
                            <span class="text-gray-700 truncate text-left flex-1">{{ $lesson['name'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <div class="mt-auto pt-4 text-center">
        @if(!$module->isUnlocked)
            <div class="mb-4">
                <div class="flex items-center justify-center gap-2 text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="text-sm font-medium">Модуль заблоковано</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ $module->unlockMessage }}</p>
            </div>
        @endif

        @if($module->isUnlocked)
            <a href="{{ route('student.modules.show', ['course' => $module->courseId, 'module' => $module->id]) }}" class="w-full {{ $colorScheme['button_bg'] }} text-white font-medium py-3 px-4 rounded-full transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Відкрити модуль
            </a>
        @else
            <span class="w-full bg-gray-200 text-gray-400 font-medium py-3 px-4 rounded-full cursor-not-allowed flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Заблоковано
            </span>
        @endif
    </div>
</div>
