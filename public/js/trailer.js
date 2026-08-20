(function () {
    'use strict';

    function initialiseTrailerPlayer() {
        var root = document.querySelector('[data-nollyflix-player]');
        var trigger = document.querySelector('[data-trailer-trigger]');
        var hero = document.getElementById('video-page-title-pro');

        if (!root || !trigger || !hero) {
            return;
        }

        var video = root.querySelector('[data-player-video]');

        if (!video || typeof video.play !== 'function') {
            return;
        }

        var controls = root.querySelector('[data-player-controls]');
        var loader = root.querySelector('[data-player-loader]');
        var errorMessage = root.querySelector('[data-player-error]');
        var centerPlay = root.querySelector('[data-center-play]');
        var playButton = root.querySelector('[data-play-button]');
        var volumeButton = root.querySelector('[data-volume-button]');
        var fullscreenButton = root.querySelector('[data-fullscreen-button]');
        var progress = root.querySelector('[data-player-progress]');
        var volume = root.querySelector('[data-player-volume]');
        var currentTime = root.querySelector('[data-player-current-time]');
        var duration = root.querySelector('[data-player-duration]');
        var actionButtons = root.querySelectorAll('[data-player-action]');
        var hideControlsTimer = null;
        var lastAudibleVolume = video.volume || 1;
        var isSeeking = false;
        var isOpen = false;

        function setHidden(element, hidden) {
            if (!element) {
                return;
            }

            if (hidden) {
                element.setAttribute('hidden', 'hidden');
            } else {
                element.removeAttribute('hidden');
            }
        }

        function setIcon(element, name, hidden) {
            if (!element) {
                return;
            }

            setHidden(element.querySelector('[data-icon="' + name + '"]'), hidden);
        }

        function formatTime(value) {
            if (!isFinite(value) || value < 0) {
                return '0:00';
            }

            var seconds = Math.floor(value % 60);
            var minutes = Math.floor((value / 60) % 60);
            var hours = Math.floor(value / 3600);
            var paddedSeconds = seconds < 10 ? '0' + seconds : String(seconds);

            if (hours > 0) {
                return hours + ':' + (minutes < 10 ? '0' + minutes : minutes) + ':' + paddedSeconds;
            }

            return minutes + ':' + paddedSeconds;
        }

        function clearControlsTimer() {
            if (hideControlsTimer) {
                window.clearTimeout(hideControlsTimer);
                hideControlsTimer = null;
            }
        }

        function scheduleControlsHide() {
            clearControlsTimer();

            if (video.paused || video.ended || isSeeking || !isOpen) {
                return;
            }

            hideControlsTimer = window.setTimeout(function () {
                if (!root.contains(document.activeElement) || document.activeElement === root) {
                    root.classList.remove('is-controls-visible');
                }
            }, 3000);
        }

        function showControls(keepVisible) {
            root.classList.add('is-controls-visible');
            clearControlsTimer();

            if (!keepVisible) {
                scheduleControlsHide();
            }
        }

        function setLoading(loading) {
            setHidden(loader, !loading || !isOpen);
            root.classList.toggle('is-loading', Boolean(loading && isOpen));
        }

        function clearError() {
            setHidden(errorMessage, true);
            root.classList.remove('has-error');
        }

        function showError() {
            setLoading(false);
            setHidden(errorMessage, false);
            root.classList.add('has-error');
            showControls(true);
        }

        function updateTimeline() {
            var total = video.duration;
            var elapsed = video.currentTime;
            var percentage = isFinite(total) && total > 0 ? (elapsed / total) * 100 : 0;

            if (!isSeeking && progress) {
                progress.value = Math.max(0, Math.min(100, percentage));
                progress.style.setProperty('--progress-position', progress.value + '%');
                progress.setAttribute('aria-valuetext', formatTime(elapsed) + ' of ' + formatTime(total));
            }

            if (currentTime) {
                currentTime.textContent = formatTime(elapsed);
            }

            if (duration) {
                duration.textContent = formatTime(total);
            }
        }

        function updatePlaybackState() {
            var isPlaying = !video.paused && !video.ended;
            var label = video.ended ? 'Replay trailer' : (isPlaying ? 'Pause trailer' : 'Play trailer');

            root.classList.toggle('is-playing', isPlaying);
            setHidden(centerPlay, isPlaying);
            setIcon(playButton, 'play', isPlaying);
            setIcon(playButton, 'pause', !isPlaying);

            if (playButton) {
                playButton.setAttribute('aria-label', label);
            }

            if (centerPlay) {
                centerPlay.setAttribute('aria-label', label);
            }

            showControls(!isPlaying);
        }

        function updateVolumeState() {
            var isMuted = video.muted || video.volume === 0;

            if (!isMuted) {
                lastAudibleVolume = video.volume;
            }

            setIcon(volumeButton, 'volume', isMuted);
            setIcon(volumeButton, 'muted', !isMuted);

            if (volumeButton) {
                volumeButton.setAttribute('aria-label', isMuted ? 'Unmute trailer' : 'Mute trailer');
                volumeButton.setAttribute('aria-pressed', isMuted ? 'true' : 'false');
            }

            if (volume) {
                volume.value = isMuted ? 0 : video.volume;
                volume.style.setProperty('--volume-position', (parseFloat(volume.value) * 100) + '%');
            }
        }

        function playVideo() {
            clearError();

            if (video.error) {
                showError();
                return;
            }

            if (video.ended) {
                video.currentTime = 0;
            }

            var playPromise = video.play();

            if (playPromise && typeof playPromise.catch === 'function') {
                playPromise.catch(function () {
                    if (video.error) {
                        showError();
                    } else {
                        setLoading(false);
                        updatePlaybackState();
                        showControls(true);
                    }
                });
            }
        }

        function togglePlayback() {
            if (video.paused || video.ended) {
                playVideo();
            } else {
                video.pause();
            }
        }

        function seekBy(seconds) {
            if (!isFinite(video.duration)) {
                return;
            }

            video.currentTime = Math.max(0, Math.min(video.duration, video.currentTime + seconds));
            updateTimeline();
        }

        function toggleMute() {
            if (video.muted || video.volume === 0) {
                video.muted = false;
                video.volume = lastAudibleVolume || 1;
            } else {
                lastAudibleVolume = video.volume;
                video.muted = true;
            }
        }

        function fullscreenElement() {
            return document.fullscreenElement || document.webkitFullscreenElement || null;
        }

        function toggleFullscreen() {
            var fullscreenPromise;

            if (fullscreenElement()) {
                if (document.exitFullscreen) {
                    fullscreenPromise = document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    fullscreenPromise = document.webkitExitFullscreen();
                }
            } else if (root.requestFullscreen) {
                fullscreenPromise = root.requestFullscreen();
            } else if (root.webkitRequestFullscreen) {
                fullscreenPromise = root.webkitRequestFullscreen();
            } else if (video.webkitEnterFullscreen) {
                video.webkitEnterFullscreen();
            }

            if (fullscreenPromise && typeof fullscreenPromise.catch === 'function') {
                fullscreenPromise.catch(function () {});
            }
        }

        function updateFullscreenState() {
            var isFullscreen = Boolean(fullscreenElement());

            root.classList.toggle('is-fullscreen', isFullscreen);
            setIcon(fullscreenButton, 'maximize', isFullscreen);
            setIcon(fullscreenButton, 'minimize', !isFullscreen);

            if (fullscreenButton) {
                fullscreenButton.setAttribute('aria-label', isFullscreen ? 'Exit fullscreen' : 'Enter fullscreen');
            }

            showControls(false);
        }

        function toggleCaptions(button) {
            if (!video.textTracks || !video.textTracks.length) {
                return;
            }

            var track = video.textTracks[0];
            var enable = track.mode !== 'showing';
            track.mode = enable ? 'showing' : 'disabled';
            button.setAttribute('aria-pressed', enable ? 'true' : 'false');
            button.setAttribute('aria-label', enable ? 'Turn captions off' : 'Turn captions on');
            button.classList.toggle('is-active', enable);
        }

        function openPlayer(event) {
            event.preventDefault();
            isOpen = true;
            hero.classList.add('d-none');
            root.hidden = false;
            root.focus();
            clearError();
            showControls(true);
            setLoading(video.readyState < 3);
            playVideo();
        }

        function closePlayer() {
            isOpen = false;
            video.pause();

            if (fullscreenElement()) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }

            setLoading(false);
            clearError();
            root.hidden = true;
            hero.classList.remove('d-none');
            trigger.focus();
        }

        function handleAction(event) {
            var action = event.currentTarget.getAttribute('data-player-action');

            event.preventDefault();
            event.stopPropagation();

            if (event.detail > 0 && typeof event.currentTarget.blur === 'function') {
                event.currentTarget.blur();
            }

            if (action === 'toggle-play') {
                togglePlayback();
            } else if (action === 'rewind') {
                seekBy(-10);
            } else if (action === 'forward') {
                seekBy(10);
            } else if (action === 'toggle-mute') {
                toggleMute();
            } else if (action === 'fullscreen') {
                toggleFullscreen();
            } else if (action === 'captions') {
                toggleCaptions(event.currentTarget);
            } else if (action === 'close') {
                closePlayer();
            }

            showControls(false);
        }

        function handleKeyboard(event) {
            var targetName = event.target && event.target.tagName ? event.target.tagName.toLowerCase() : '';
            var key = String(event.key || '').toLowerCase();

            if (targetName === 'input' || targetName === 'button' || targetName === 'a' || targetName === 'textarea' || targetName === 'select') {
                return;
            }

            if (key === ' ' || key === 'k') {
                event.preventDefault();
                togglePlayback();
            } else if (key === 'arrowleft') {
                event.preventDefault();
                seekBy(-10);
            } else if (key === 'arrowright') {
                event.preventDefault();
                seekBy(10);
            } else if (key === 'm') {
                event.preventDefault();
                toggleMute();
            } else if (key === 'f') {
                event.preventDefault();
                toggleFullscreen();
            } else if (key === 'escape' && !fullscreenElement()) {
                closePlayer();
            }

            showControls(false);
        }

        for (var i = 0; i < actionButtons.length; i += 1) {
            actionButtons[i].addEventListener('click', handleAction);
        }

        trigger.addEventListener('click', openPlayer);
        root.addEventListener('keydown', handleKeyboard);
        root.addEventListener('mousemove', function () { showControls(false); });
        root.addEventListener('touchstart', function () { showControls(false); }, { passive: true });
        root.addEventListener('mouseleave', scheduleControlsHide);
        root.addEventListener('focusin', function () { showControls(true); });
        root.addEventListener('focusout', function () { showControls(false); });
        video.addEventListener('click', togglePlayback);
        video.addEventListener('play', updatePlaybackState);
        video.addEventListener('pause', function () {
            if (video.readyState >= 2) {
                setLoading(false);
            }
            updatePlaybackState();
        });
        video.addEventListener('ended', updatePlaybackState);
        video.addEventListener('timeupdate', updateTimeline);
        video.addEventListener('durationchange', updateTimeline);
        video.addEventListener('loadedmetadata', updateTimeline);
        video.addEventListener('loadeddata', function () { setLoading(false); });
        video.addEventListener('loadstart', function () { setLoading(true); });
        video.addEventListener('waiting', function () { setLoading(true); });
        video.addEventListener('stalled', function () { setLoading(true); });
        video.addEventListener('seeking', function () { setLoading(true); });
        video.addEventListener('canplay', function () { setLoading(false); });
        video.addEventListener('playing', function () { setLoading(false); });
        video.addEventListener('seeked', function () { setLoading(false); });
        video.addEventListener('error', showError);
        video.addEventListener('volumechange', updateVolumeState);

        if (progress) {
            progress.addEventListener('input', function () {
                if (!isFinite(video.duration) || video.duration <= 0) {
                    return;
                }

                isSeeking = true;
                video.currentTime = (parseFloat(progress.value) / 100) * video.duration;
                progress.style.setProperty('--progress-position', progress.value + '%');
                updateTimeline();
            });
            progress.addEventListener('change', function () {
                isSeeking = false;
                updateTimeline();
                scheduleControlsHide();
            });
            progress.addEventListener('mouseup', function () { progress.blur(); });
            progress.addEventListener('touchend', function () { progress.blur(); });
        }

        if (volume) {
            volume.addEventListener('input', function () {
                var nextVolume = Math.max(0, Math.min(1, parseFloat(volume.value)));
                video.volume = nextVolume;
                video.muted = nextVolume === 0;
            });
            volume.addEventListener('mouseup', function () { volume.blur(); });
            volume.addEventListener('touchend', function () { volume.blur(); });
        }

        document.addEventListener('fullscreenchange', updateFullscreenState);
        document.addEventListener('webkitfullscreenchange', updateFullscreenState);

        root.classList.add('is-enhanced');
        video.controls = false;
        updateTimeline();
        updateVolumeState();
        updatePlaybackState();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialiseTrailerPlayer);
    } else {
        initialiseTrailerPlayer();
    }
})();
