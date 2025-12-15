const dwvApps = new Map();

function dicomViewerCDN(config) {
    const instanceId = 'dwv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    const layerContainerId = 'layerGroup0';

    return {
        url: config.url,
        isCine: config.isCine || false,
        frameCount: config.frameCount || 1,
        instanceId: instanceId,
        layerContainerId: layerContainerId,

        loading: true,
        loadingProgress: 0,
        activeTool: 'WindowLevel',
        error: null,

        playing: false,
        currentFrame: 1,
        frameRate: 15,
        playInterval: null,

        getApp() {
            return dwvApps.get(this.instanceId);
        },

        init() {
            if (typeof dwv === 'undefined') {
                this.error = 'DWV library not loaded';
                this.loading = false;
                return;
            }

            const containerEl = this.$refs.layerContainer;
            if (containerEl) {
                containerEl.id = this.layerContainerId;
            }

            try {
                const app = new dwv.App();
                dwvApps.set(this.instanceId, app);

                app.init({
                    dataViewConfigs: { '*': [{ divId: this.layerContainerId }] },
                    tools: {
                        WindowLevel: {},
                        ZoomAndPan: {},
                        Scroll: {},
                    }
                });

                const self = this;

                app.addEventListener('loadprogress', function(event) {
                    if (event.total > 0) {
                        self.loadingProgress = Math.round((event.loaded / event.total) * 100);
                    }
                });

                app.addEventListener('load', function() {
                    self.loading = false;
                    app.setTool('WindowLevel');

                    try {
                        const layerGroup = app.getActiveLayerGroup();
                        if (layerGroup) {
                            const viewLayer = layerGroup.getActiveViewLayer();
                            if (viewLayer) {
                                const vc = viewLayer.getViewController();
                                if (vc && vc.getNumberOfFrames) {
                                    const frames = vc.getNumberOfFrames();
                                    if (frames && frames > 1) {
                                        self.frameCount = frames;
                                        self.isCine = true;
                                    }
                                }
                            }
                        }
                    } catch (e) {
                        console.warn('Could not get frame info:', e);
                    }
                });

                app.addEventListener('error', function(event) {
                    console.error('DICOM load error:', event);
                    self.loading = false;
                    self.error = event.error?.message || 'Помилка завантаження DICOM файлу';
                });

                this.loadDicom();
            } catch (e) {
                console.error('Failed to initialize DWV:', e);
                this.loading = false;
                this.error = 'Помилка ініціалізації: ' + e.message;
            }
        },

        async loadDicom() {
            const app = this.getApp();
            if (!app) return;

            try {
                let loadUrl = this.url;

                if (this.url.includes('/dicom')) {
                    const response = await fetch(this.url);
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                        if (data.type === 'url') {
                            loadUrl = data.url;
                        }
                    }
                }

                app.loadURLs([loadUrl]);
            } catch (e) {
                console.error('Failed to load DICOM:', e);
                this.loading = false;
                this.error = 'Не вдалося завантажити DICOM файл';
            }
        },

        setTool(tool) {
            const app = this.getApp();
            if (!app || this.loading) return;
            this.activeTool = tool;
            try {
                app.setTool(tool);
            } catch (e) {
                console.warn('Could not set tool:', e);
            }
        },

        reset() {
            const app = this.getApp();
            if (!app || this.loading) return;
            try {
                app.resetDisplay();
            } catch (e) {
                console.warn('Could not reset display:', e);
            }
        },

        handleScroll(event) {
            if (this.isCine && this.frameCount > 1) {
                event.preventDefault();
                const delta = event.deltaY > 0 ? 1 : -1;
                this.goToFrame(this.currentFrame + delta);
            }
        },

        togglePlay() {
            if (this.playing) {
                this.pause();
            } else {
                this.play();
            }
        },

        play() {
            this.playing = true;
            const self = this;
            this.playInterval = setInterval(function() {
                self.currentFrame++;
                if (self.currentFrame > self.frameCount) {
                    self.currentFrame = 1;
                }
                self.setFrame(self.currentFrame - 1);
            }, 1000 / this.frameRate);
        },

        pause() {
            this.playing = false;
            if (this.playInterval) {
                clearInterval(this.playInterval);
                this.playInterval = null;
            }
        },

        nextFrame() {
            this.pause();
            this.goToFrame(this.currentFrame + 1);
        },

        previousFrame() {
            this.pause();
            this.goToFrame(this.currentFrame - 1);
        },

        goToFrame(frame) {
            this.currentFrame = Math.max(1, Math.min(this.frameCount, frame));
            this.setFrame(this.currentFrame - 1);
        },

        setFrame(index) {
            const app = this.getApp();
            if (!app || this.loading) return;
            try {
                const layerGroup = app.getActiveLayerGroup();
                if (layerGroup) {
                    const viewLayer = layerGroup.getActiveViewLayer();
                    if (viewLayer) {
                        const vc = viewLayer.getViewController();
                        if (vc) {
                            vc.setCurrentFrame(index);
                        }
                    }
                }
            } catch (e) {
                console.warn('Failed to set frame:', e);
            }
        },

        seekToFrame(frame) {
            this.pause();
            this.goToFrame(parseInt(frame));
        },

        updateFrameRate() {
            if (this.playing) {
                this.pause();
                this.play();
            }
        },

        toggleFullscreen() {
            const container = this.$el;
            if (!document.fullscreenElement) {
                container.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        },

        destroy() {
            this.pause();
            const app = this.getApp();
            if (app) {
                app.reset();
                dwvApps.delete(this.instanceId);
            }
        }
    };
}

window.dicomViewerCDN = dicomViewerCDN;
