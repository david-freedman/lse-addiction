<script src="https://unpkg.com/dwv@0.33.0/dist/dwv.min.js"></script>
<div x-data="dicomViewerCDN({
        url: '{{ $viewModel->dicomUrl() }}',
        isCine: {{ $viewModel->isCineLoop() ? 'true' : 'false' }},
        frameCount: {{ $viewModel->dicomMetadata()['frame_count'] ?? 1 }}
     })"
     x-init="init()"
     @keydown.space.prevent="togglePlay()"
     @keydown.arrow-left="previousFrame()"
     @keydown.arrow-right="nextFrame()"
     class="relative bg-black rounded-lg overflow-hidden">

    <template x-if="loading">
        <div class="absolute inset-0 flex items-center justify-center bg-gray-900 z-10">
            <div class="text-center">
                <svg class="animate-spin h-10 w-10 text-teal-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span class="text-white text-sm" x-text="loadingProgress + '%'"></span>
                <p class="text-gray-400 text-xs mt-1">Завантаження DICOM...</p>
            </div>
        </div>
    </template>

    <template x-if="error">
        <div class="absolute inset-0 flex items-center justify-center bg-gray-900 z-10">
            <div class="text-center">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-red-400 text-sm" x-text="error"></p>
            </div>
        </div>
    </template>

    <div x-ref="layerContainer"
         class="w-full aspect-video bg-black"
         @wheel="handleScroll($event)">
    </div>

    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <button @click="setTool('WindowLevel')"
                        :class="activeTool === 'WindowLevel' ? 'bg-teal-500 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'"
                        class="p-2 rounded-lg transition"
                        title="Яскравість/Контраст">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                    </svg>
                </button>
                <button @click="setTool('ZoomAndPan')"
                        :class="activeTool === 'ZoomAndPan' ? 'bg-teal-500 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'"
                        class="p-2 rounded-lg transition"
                        title="Масштаб">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                    </svg>
                </button>
                <template x-if="isCine">
                    <button @click="setTool('Scroll')"
                            :class="activeTool === 'Scroll' ? 'bg-teal-500 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'"
                            class="p-2 rounded-lg transition"
                            title="Прокрутка кадрів">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </button>
                </template>
                <button @click="reset()"
                        class="p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition"
                        title="Скинути">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>

            <template x-if="isCine && frameCount > 1">
                <div class="flex items-center gap-3">
                    <button @click="previousFrame()"
                            class="p-2 rounded-lg bg-gray-700 text-white hover:bg-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button @click="togglePlay()"
                            class="p-3 rounded-full bg-teal-500 text-white hover:bg-teal-600 transition">
                        <template x-if="!playing">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </template>
                        <template x-if="playing">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                            </svg>
                        </template>
                    </button>
                    <button @click="nextFrame()"
                            class="p-2 rounded-lg bg-gray-700 text-white hover:bg-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <span class="text-white text-sm ml-2 min-w-[80px] text-center">
                        <span x-text="currentFrame"></span> / <span x-text="frameCount"></span>
                    </span>
                    <select x-model="frameRate" @change="updateFrameRate()"
                            class="ml-2 bg-gray-700 text-white text-sm rounded-lg px-2 py-1.5 border-0 focus:ring-2 focus:ring-teal-500">
                        <option value="5">5 fps</option>
                        <option value="10">10 fps</option>
                        <option value="15">15 fps</option>
                        <option value="25">25 fps</option>
                        <option value="30">30 fps</option>
                    </select>
                </div>
            </template>

            <div class="flex items-center gap-2">
                @if($viewModel->dicomMetadata())
                    <span class="text-white/70 text-xs hidden sm:inline px-2 py-1 bg-gray-700/50 rounded">
                        {{ $viewModel->dicomMetadata()['modality'] ?? 'DICOM' }}
                    </span>
                @endif
                <button @click="toggleFullscreen()"
                        class="p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition"
                        title="На весь екран">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                </button>
            </div>
        </div>

        <template x-if="isCine && frameCount > 1">
            <div class="mt-3">
                <input type="range"
                       x-model="currentFrame"
                       @input="seekToFrame($event.target.value)"
                       min="1"
                       :max="frameCount"
                       class="w-full h-1.5 bg-gray-600 rounded-lg appearance-none cursor-pointer accent-teal-500">
            </div>
        </template>
    </div>
</div>
