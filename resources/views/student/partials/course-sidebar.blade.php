<button @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden fixed bottom-4 right-4 z-40 flex items-center justify-center w-14 h-14 bg-teal-500 text-white rounded-full shadow-lg hover:bg-teal-600 transition">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
    </svg>
</button>

<div x-show="sidebarOpen"
     x-cloak
     @click="sidebarOpen = false"
     class="lg:hidden fixed inset-0 z-40 bg-black/50"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
       class="fixed lg:static right-0 top-0 z-50 lg:z-auto w-80 h-full lg:h-auto bg-gray-50 border-l border-gray-200 overflow-y-auto transition-transform duration-300 lg:flex-shrink-0">
    <div class="p-4 lg:sticky lg:top-0">
        <div class="flex items-center justify-between mb-4 lg:hidden">
            <h3 class="font-semibold text-gray-900">Зміст курсу</h3>
            <button @click="sidebarOpen = false" class="p-2 text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @foreach($modules as $module)
            @php $isUnlocked = $module['isUnlocked'] ?? true; @endphp
            <div class="mb-4 {{ !$isUnlocked ? 'opacity-60' : '' }}" x-data="{ open: {{ ($module['isCurrent'] ?? false) || ($module['id'] ?? null) === ($currentModuleId ?? null) ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 {{ ($module['isCurrent'] ?? false) ? 'bg-teal-50 border-teal-200' : 'bg-white border-gray-200' }} rounded-lg border hover:border-gray-300 transition">
                    <span class="text-sm font-medium {{ ($module['isCurrent'] ?? false) ? 'text-teal-700' : 'text-gray-900' }} text-left flex items-center gap-2">
                        @if(!$isUnlocked)
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        @endif
                        Модуль {{ $module['order'] + 1 }}: {{ $module['name'] }}
                    </span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-500 transition-transform flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak class="mt-2 space-y-1">
                    @foreach($module['lessons'] as $lessonItem)
                        @if($isUnlocked)
                        <a href="{{ $lessonItem['url'] }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $lessonItem['isCurrent'] ?? false ? 'bg-teal-50 text-teal-700' : 'hover:bg-white text-gray-700' }}">
                        @else
                        <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-400 cursor-not-allowed">
                        @endif
                            <div class="flex-shrink-0">
                                @if($lessonItem['isCompleted'] ?? false)
                                    <div class="w-5 h-5 flex items-center justify-center rounded-full bg-green-500 text-white">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                @elseif($lessonItem['isCurrent'] ?? false)
                                    <div class="w-5 h-5 flex items-center justify-center rounded-full bg-teal-500 text-white">
                                        @if(($lessonItem['typeIcon'] ?? '') === 'question')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        @endif
                                    </div>
                                @else
                                    <div class="w-5 h-5 flex items-center justify-center rounded-full border-2 border-gray-300 text-gray-400">
                                        @switch($lessonItem['typeIcon'] ?? 'document')
                                            @case('play')
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                                @break
                                            @case('question')
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                @break
                                            @case('document')
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                @break
                                            @case('medical')
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                                </svg>
                                                @break
                                            @case('chart')
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                                @break
                                            @case('chat')
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                @break
                                        @endswitch
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm truncate {{ ($lessonItem['isCurrent'] ?? false) ? 'font-medium' : '' }}">{{ $lessonItem['name'] }}</p>
                            </div>
                            @if($lessonItem['hasHomework'] ?? false)
                                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Домашнє завдання">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            @if($lessonItem['duration'] ?? null)
                                <span class="text-xs text-gray-500 flex-shrink-0">{{ $lessonItem['duration'] }}</span>
                            @endif
                        @if($isUnlocked)
                        </a>
                        @else
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</aside>
