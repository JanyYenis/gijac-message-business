// Audio Player Manager
class AudioPlayerManager {
    constructor() {
        this.players = [];
        this.currentPlaying = null;
        this.init();
    }

    init() {
        // Initialize all audio players
        document.querySelectorAll('.audio-player').forEach(player => {
            this.setupPlayer(player);
        });
    }

    setupPlayer(playerElement) {
        const audio = playerElement.querySelector('audio');
        const playButton = playerElement.querySelector('.play-button');
        const playIcon = playButton.querySelector('i');
        const timeCurrent = playerElement.querySelector('.time-current');
        const timeTotal = playerElement.querySelector('.time-total');
        const waveformBars = playerElement.querySelectorAll('.waveform-bar');
        const audioIcon = playerElement.querySelector('.audio-icon');
        const waveformContainer = playerElement.querySelector('.waveform-container');

        // Store player data
        const playerData = {
            element: playerElement,
            audio: audio,
            playButton: playButton,
            playIcon: playIcon,
            timeCurrent: timeCurrent,
            timeTotal: timeTotal,
            waveformBars: waveformBars,
            audioIcon: audioIcon,
            isPlaying: false
        };

        this.players.push(playerData);

        // Load metadata
        audio.addEventListener('loadedmetadata', () => {
            // Esperar un pequeño tiempo para asegurar que la duración esté calculada
            setTimeout(() => {
                let duration = audio.duration;

                // Si sigue siendo Infinity, intentar cargar un poco más del audio
                if (!isFinite(duration) || duration === Infinity) {
                    audio.currentTime = 1e101; // “Salto” al final del archivo
                    audio.ontimeupdate = () => {
                        audio.ontimeupdate = null; // eliminar listener temporal
                        audio.currentTime = 0; // volver al inicio
                        duration = audio.duration;
                        if (isFinite(duration)) {
                            timeTotal.textContent = this.formatTime(duration);
                        } else {
                            timeTotal.textContent = '0:00';
                        }
                    };
                } else {
                    timeTotal.textContent = this.formatTime(duration);
                }
            }, 300);
        });

        // Play/Pause button click
        playButton.addEventListener('click', () => {
            this.togglePlay(playerData);
        });

        // Time update
        audio.addEventListener('timeupdate', () => {
            this.updateProgress(playerData);
        });

        // Audio ended
        audio.addEventListener('ended', () => {
            this.resetPlayer(playerData);
        });

        // Waveform click to seek
        waveformContainer.addEventListener('click', (e) => {
            this.seekAudio(playerData, e);
        });

        // Loading state
        audio.addEventListener('waiting', () => {
            playerElement.classList.add('loading');
        });

        audio.addEventListener('canplay', () => {
            playerElement.classList.remove('loading');
        });
    }

    togglePlay(playerData) {
        if (playerData.isPlaying) {
            this.pause(playerData);
        } else {
            // Stop other players
            if (this.currentPlaying && this.currentPlaying !== playerData) {
                this.pause(this.currentPlaying);
            }
            this.play(playerData);
        }
    }

    play(playerData) {
        playerData.audio.play();
        playerData.isPlaying = true;
        playerData.playIcon.classList.remove('fa-play');
        playerData.playIcon.classList.add('fa-pause');
        playerData.playButton.classList.add('playing');
        playerData.audioIcon.classList.add('playing');

        // Animate waveform bars
        playerData.waveformBars.forEach((bar, index) => {
            bar.style.animationDelay = `${index * 0.05}s`;
            bar.classList.add('playing');
        });

        this.currentPlaying = playerData;
    }

    pause(playerData) {
        playerData.audio.pause();
        playerData.isPlaying = false;
        playerData.playIcon.classList.remove('fa-pause');
        playerData.playIcon.classList.add('fa-play');
        playerData.playButton.classList.remove('playing');
        playerData.audioIcon.classList.remove('playing');

        // Stop waveform animation
        playerData.waveformBars.forEach(bar => {
            bar.classList.remove('playing');
        });

        if (this.currentPlaying === playerData) {
            this.currentPlaying = null;
        }
    }

    resetPlayer(playerData) {
        playerData.audio.currentTime = 0;
        this.pause(playerData);
        playerData.timeCurrent.textContent = '0:00';

        // Reset waveform
        playerData.waveformBars.forEach(bar => {
            bar.classList.remove('active');
        });
    }

    updateProgress(playerData) {
        const { audio, timeCurrent, waveformBars } = playerData;
        const progress = (audio.currentTime / audio.duration) * 100;

        // Update time display
        timeCurrent.textContent = this.formatTime(audio.currentTime);

        // Update waveform bars
        const activeBars = Math.floor((progress / 100) * waveformBars.length);
        waveformBars.forEach((bar, index) => {
            if (index < activeBars) {
                bar.classList.add('active');
            } else {
                bar.classList.remove('active');
            }
        });
    }

    seekAudio(playerData, event) {
        const { audio, waveformBars } = playerData;
        const waveformContainer = event.currentTarget;
        const rect = waveformContainer.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const percentage = x / rect.width;

        audio.currentTime = percentage * audio.duration;

        // Update waveform immediately
        const activeBars = Math.floor(percentage * waveformBars.length);
        waveformBars.forEach((bar, index) => {
            if (index < activeBars) {
                bar.classList.add('active');
            } else {
                bar.classList.remove('active');
            }
        });
    }

    formatTime(seconds) {
        if (isNaN(seconds) || !isFinite(seconds)) return '0:00';

        // console.log('seconds: '+seconds);

        const mins = Math.floor(seconds / 60);
        // console.log('Minutos: '+mins);

        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }
}

// Initialize audio player manager
window.iniciarAudio = () => {
    new AudioPlayerManager();
}

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    // Space to play/pause current audio
    if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        const playButtons = document.querySelectorAll('.play-button');
        if (playButtons.length > 0) {
            playButtons[0].click();
        }
    }
});
