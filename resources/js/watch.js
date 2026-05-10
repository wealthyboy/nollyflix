import Hls from "hls.js";

window.Hls = Hls;

function formatTime(seconds) {
    if (!Number.isFinite(seconds) || seconds < 0) {
        return "0:00";
    }

    let total = Math.floor(seconds);
    let hours = Math.floor(total / 3600);
    let minutes = Math.floor((total % 3600) / 60);
    let secs = total % 60;

    if (hours > 0) {
        return (
            hours +
            ":" +
            String(minutes).padStart(2, "0") +
            ":" +
            String(secs).padStart(2, "0")
        );
    }

    return minutes + ":" + String(secs).padStart(2, "0");
}

window.onload = () => {
    let container = document.querySelector(".background_video");
    let video = document.getElementById("video");
    let back = document.querySelector(".back");
    let loader = document.querySelector("[data-watch-player-loader]");
    let centerPlay = document.querySelector("[data-watch-center-play]");
    let controls = document.querySelector("[data-watch-controls]");
    let playButton = document.querySelector("[data-watch-play]");
    let muteButton = document.querySelector("[data-watch-mute]");
    let seek = document.querySelector("[data-watch-seek]");
    let currentTime = document.querySelector("[data-watch-current-time]");
    let durationTime = document.querySelector("[data-watch-duration]");
    let captionsButton = document.querySelector("[data-watch-captions]");
    let fullscreenButton = document.querySelector("[data-watch-fullscreen]");
    let skipButtons = Array.prototype.slice.call(document.querySelectorAll("[data-watch-skip]"));
    let speedButton = document.querySelector("[data-watch-speed]");
    let playbackRates = [1, 1.25, 1.5, 0.75];

    if (container) {
        container.classList.remove("hide");
        container.addEventListener("mouseenter", function() {
            if (back) {
                back.style.display = "block";
                back.style.opacity = "1";
            }
        });
        container.addEventListener("mouseleave", function() {
            if (back) {
                back.style.display = "none";
                back.style.opacity = "0";
            }
        });
        container.addEventListener("click", function(event) {
            if (
                event.target.closest("[data-watch-controls]") ||
                event.target.closest("[data-watch-center-play]") ||
                event.target.closest(".back") ||
                event.target.closest(".watch-series-panel")
            ) {
                return;
            }

            togglePlay();
        });
    }

    if (!video) {
        return;
    }

    let hls = null;

    function getStoredEpisodeSource() {
        let videoId = video.getAttribute("data-watch-video-id");

        if (!videoId || !window.localStorage) {
            return "";
        }

        let episodeId = window.localStorage.getItem("nollyflix:watch:episode:" + videoId);
        if (!episodeId) {
            return "";
        }

        let episodeButton = document.querySelector('[data-episode-id="' + episodeId + '"][data-episode-source]');
        if (!episodeButton) {
            return "";
        }

        Array.prototype.slice.call(document.querySelectorAll("[data-episode-source]")).forEach(function(button) {
            button.classList.toggle("active", button === episodeButton);
        });

        let episodeTitle = episodeButton.getAttribute("data-episode-title");
        if (episodeTitle) {
            Array.prototype.slice.call(
                document.querySelectorAll("[data-watch-player-title], [data-watch-control-title]")
            ).forEach(function(title) {
                title.textContent = episodeTitle;
            });
        }

        return episodeButton.getAttribute("data-episode-source") || "";
    }

    function loadHlsSource(source) {
        if (!source) {
            return;
        }

        if (hls) {
            hls.destroy();
            hls = null;
        }

        if (Hls.isSupported()) {
            hls = new Hls({
                enableWorker: true,
                lowLatencyMode: false
            });
            hls.attachMedia(video);
            hls.on(Hls.Events.MEDIA_ATTACHED, function() {
                hls.loadSource(source);
            });
            hls.on(Hls.Events.ERROR, function(event, data) {
                if (data && data.fatal) {
                    if (data.type === Hls.ErrorTypes.NETWORK_ERROR) {
                        hls.startLoad();
                        return;
                    }
                    if (data.type === Hls.ErrorTypes.MEDIA_ERROR) {
                        hls.recoverMediaError();
                        return;
                    }
                    hls.destroy();
                    hls = null;
                    video.dispatchEvent(new Event("error"));
                }
            });
            return;
        }

        if (video.canPlayType("application/vnd.apple.mpegurl")) {
            video.src = source;
        }
    }

    const player = {
        on(eventName, callback) {
            video.addEventListener(eventName, function(event) {
                callback.call(player, event);
            });
        },
        ready(callback) {
            window.setTimeout(function() {
                callback.call(player);
            }, 0);
        },
        src(source) {
            let nextSource = typeof source === "string" ? source : source && source.src;
            loadHlsSource(nextSource);
        },
        load() {
            if (!hls) {
                video.load();
            }
        },
        play() {
            return video.play();
        },
        pause() {
            return video.pause();
        },
        paused() {
            return video.paused;
        },
        muted(value) {
            if (typeof value !== "undefined") {
                video.muted = value;
            }
            return video.muted;
        },
        volume(value) {
            if (typeof value !== "undefined") {
                video.volume = value;
            }
            return video.volume;
        },
        duration() {
            return video.duration;
        },
        currentTime(value) {
            if (typeof value !== "undefined") {
                video.currentTime = value;
            }
            return video.currentTime;
        },
        readyState() {
            return video.readyState;
        },
        textTracks() {
            return video.textTracks;
        },
        playbackRate(value) {
            if (typeof value !== "undefined") {
                video.playbackRate = value;
            }
            return video.playbackRate;
        },
        isFullscreen() {
            return document.fullscreenElement === video || document.fullscreenElement === container;
        },
        requestFullscreen() {
            let target = container || video;
            if (target.requestFullscreen) {
                return target.requestFullscreen();
            }
        },
        exitFullscreen() {
            if (document.exitFullscreen) {
                return document.exitFullscreen();
            }
        },
        playsinline(value) {
            if (value) {
                video.setAttribute("playsinline", "");
                video.setAttribute("webkit-playsinline", "");
            }
        },
        el() {
            return video;
        },
        currentSrc() {
            return video.currentSrc;
        }
    };

    window.nollyflixWatchPlayer = player;
    window.dispatchEvent(
        new CustomEvent("nollyflix:watch-player-ready", { detail: { player } })
    );

    function showLoader() {
        if (loader) {
            loader.classList.add("is-active");
        }
    }

    function hideLoader() {
        if (loader) {
            loader.classList.remove("is-active");
        }
    }

    function setPlayingState(isPlaying) {
        if (controls) {
            controls.classList.toggle("is-playing", isPlaying);
        }
        if (centerPlay) {
            centerPlay.classList.toggle("d-none", isPlaying);
        }
        if (playButton) {
            playButton.setAttribute("aria-label", isPlaying ? "Pause" : "Play");
        }
    }

    function setMutedState() {
        if (controls) {
            controls.classList.toggle("is-muted", player.muted() || player.volume() === 0);
        }
        if (muteButton) {
            muteButton.setAttribute(
                "aria-label",
                player.muted() || player.volume() === 0 ? "Unmute" : "Mute"
            );
        }
    }

    function setCaptionsState() {
        if (!captionsButton) {
            return;
        }

        let tracks = player.textTracks ? Array.prototype.slice.call(player.textTracks()) : [];
        let active = tracks.some(function(track) {
            return track.kind === "subtitles" && track.mode === "showing";
        });
        captionsButton.classList.toggle("is-active", active);
    }

    function updateTime() {
        let duration = player.duration();
        let current = player.currentTime();

        if (currentTime) {
            currentTime.textContent = formatTime(current);
        }
        if (durationTime) {
            durationTime.textContent = formatTime(duration);
        }
        if (seek && Number.isFinite(duration) && duration > 0 && !seek.dragging) {
            seek.value = Math.round((current / duration) * 1000);
        }
    }

    function playFromUserGesture() {
        showLoader();
        player.muted(false);
        setMutedState();

        if (player.readyState() === 0) {
            player.load();
        }

        let promise = player.play();
        if (promise && typeof promise.catch === "function") {
            promise.catch(function() {
                hideLoader();
                setPlayingState(false);
            });
        }
    }

    function autoplayWithSoundFallback() {
        player.muted(false);
        setMutedState();

        if (player.readyState() === 0) {
            player.load();
        }

        let promise = player.play();
        if (promise && typeof promise.catch === "function") {
            promise.catch(function() {
                player.muted(true);
                setMutedState();

                let mutedPromise = player.play();
                if (mutedPromise && typeof mutedPromise.catch === "function") {
                    mutedPromise.catch(function() {
                        hideLoader();
                        setPlayingState(false);
                    });
                }
            });
        }
    }

    function togglePlay() {
        if (player.paused()) {
            playFromUserGesture();
        } else {
            player.pause();
        }
    }

    player.on("error", function() {
        hideLoader();
        setPlayingState(false);
    });

    player.on("loadstart", showLoader);
    player.on("waiting", showLoader);
    player.on("seeking", showLoader);
    player.on("canplay", hideLoader);
    player.on("loadeddata", hideLoader);
    player.on("playing", function() {
        hideLoader();
        setPlayingState(true);
        setMutedState();
    });
    player.on("pause", function() {
        setPlayingState(false);
    });
    player.on("timeupdate", updateTime);
    player.on("durationchange", updateTime);
    player.on("volumechange", setMutedState);

    if (centerPlay) {
        centerPlay.addEventListener("click", togglePlay);
    }
    if (playButton) {
        playButton.addEventListener("click", togglePlay);
    }
    if (muteButton) {
        muteButton.addEventListener("click", function() {
            player.muted(!player.muted());
            if (!player.muted() && player.volume() === 0) {
                player.volume(0.8);
            }
            setMutedState();
        });
    }
    if (seek) {
        seek.addEventListener("input", function() {
            seek.dragging = true;
            let duration = player.duration();
            if (Number.isFinite(duration) && duration > 0) {
                player.currentTime((Number(seek.value) / 1000) * duration);
            }
        });
        seek.addEventListener("change", function() {
            seek.dragging = false;
            updateTime();
        });
    }
    if (captionsButton) {
        captionsButton.addEventListener("click", function() {
            let tracks = player.textTracks ? Array.prototype.slice.call(player.textTracks()) : [];
            let subtitles = tracks.filter(function(track) {
                return track.kind === "subtitles";
            });
            let hasShowing = subtitles.some(function(track) {
                return track.mode === "showing";
            });

            subtitles.forEach(function(track, index) {
                track.mode = !hasShowing && index === 0 ? "showing" : "disabled";
            });
            setCaptionsState();
        });
    }
    skipButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            let delta = Number(button.getAttribute("data-watch-skip")) || 0;
            let duration = player.duration();
            let nextTime = player.currentTime() + delta;

            if (Number.isFinite(duration)) {
                nextTime = Math.min(duration, Math.max(0, nextTime));
            } else {
                nextTime = Math.max(0, nextTime);
            }

            player.currentTime(nextTime);
            updateTime();
        });
    });
    if (speedButton) {
        speedButton.addEventListener("click", function() {
            let currentRate = player.playbackRate();
            let currentIndex = playbackRates.indexOf(currentRate);
            let nextRate = playbackRates[(currentIndex + 1) % playbackRates.length];

            player.playbackRate(nextRate);
            speedButton.setAttribute("aria-label", "Playback speed " + nextRate + "x");
        });
    }
    if (fullscreenButton) {
        fullscreenButton.addEventListener("click", function() {
            if (player.isFullscreen()) {
                player.exitFullscreen();
            } else {
                player.requestFullscreen();
            }
        });
    }

    player.ready(function() {
        let poster = document.getElementById("video-page-title-pro");
        if (poster) {
            poster.classList.add("hide");
        }
        this.playsinline(true);
        hideLoader();
        setPlayingState(!this.paused());
        setMutedState();
        setCaptionsState();
        updateTime();
        let firstSource = video.querySelector("source");
        loadHlsSource(getStoredEpisodeSource() || (firstSource ? firstSource.src : video.currentSrc));
        autoplayWithSoundFallback();
    });
};
