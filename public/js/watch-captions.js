(function () {
    'use strict';

    function setupNollyflixCaptions() {
        var video = document.getElementById('video');
        var button = document.querySelector('[data-watch-captions]');
        var overlay = document.querySelector('[data-watch-caption-overlay]');

        if (!video || !overlay || overlay.getAttribute('data-caption-ready') === '1') {
            return;
        }

        overlay.setAttribute('data-caption-ready', '1');

        // Captions are ON by default for every movie/episode that has a track.
        var captionsEnabled = true;
        var boundTracks = [];
        var boundTrackElements = [];

        function cleanCueText(value) {
            return String(value || '')
                .replace(/<[^>]*>/g, '')
                .replace(/&nbsp;/gi, ' ')
                .replace(/&amp;/gi, '&')
                .replace(/&lt;/gi, '<')
                .replace(/&gt;/gi, '>')
                .replace(/&quot;/gi, '"')
                .replace(/&#39;/gi, "'")
                .trim();
        }

        function getSubtitleTracks() {
            var tracks = [];
            var list = video.textTracks || [];

            for (var i = 0; i < list.length; i += 1) {
                if ((list[i].kind === 'subtitles' || list[i].kind === 'captions') && tracks.indexOf(list[i]) === -1) {
                    tracks.push(list[i]);
                }
            }

            return tracks;
        }

        function clearOverlay() {
            while (overlay.firstChild) {
                overlay.removeChild(overlay.firstChild);
            }
            overlay.hidden = true;
            overlay.setAttribute('aria-hidden', 'true');
        }

        function renderOverlay() {
            if (!captionsEnabled) {
                clearOverlay();
                return;
            }

            var tracks = getSubtitleTracks();
            var lines = [];

            tracks.forEach(function (track) {
                if (track.mode === 'disabled' || !track.activeCues) {
                    return;
                }

                for (var i = 0; i < track.activeCues.length; i += 1) {
                    var cueText = cleanCueText(track.activeCues[i].text);
                    if (!cueText) {
                        continue;
                    }

                    cueText.split(/\r?\n/).forEach(function (line) {
                        line = line.trim();
                        if (line) {
                            lines.push(line);
                        }
                    });
                }
            });

            while (overlay.firstChild) {
                overlay.removeChild(overlay.firstChild);
            }

            if (!lines.length) {
                overlay.hidden = true;
                overlay.setAttribute('aria-hidden', 'true');
                return;
            }

            lines.forEach(function (line) {
                var row = document.createElement('div');
                var text = document.createElement('span');
                row.className = 'watch-caption-line';
                text.textContent = line;
                row.appendChild(text);
                overlay.appendChild(row);
            });

            overlay.hidden = false;
            overlay.setAttribute('aria-hidden', 'false');
        }

        function updateButton() {
            if (!button) {
                return;
            }

            var hasTrack = getSubtitleTracks().length > 0 || !!video.querySelector('track[data-watch-subtitle-track]');
            button.classList.toggle('is-active', captionsEnabled && hasTrack);
            button.setAttribute('aria-pressed', captionsEnabled && hasTrack ? 'true' : 'false');
            button.setAttribute('aria-label', captionsEnabled ? 'Turn captions off' : 'Turn captions on');
        }

        function bindTrack(track) {
            if (!track || boundTracks.indexOf(track) !== -1) {
                return;
            }

            boundTracks.push(track);

            if (track.addEventListener) {
                track.addEventListener('cuechange', renderOverlay);
            }
        }

        function applyTrackModes() {
            var tracks = getSubtitleTracks();

            tracks.forEach(function (track, index) {
                bindTrack(track);
                // hidden = load cues and expose activeCues, but suppress the browser's
                // native caption renderer so we can position captions safely ourselves.
                track.mode = captionsEnabled && index === 0 ? 'hidden' : 'disabled';
            });

            updateButton();
            renderOverlay();
        }

        function bindTrackElement(trackElement) {
            if (!trackElement || boundTrackElements.indexOf(trackElement) !== -1) {
                return;
            }

            boundTrackElements.push(trackElement);
            trackElement.removeAttribute('default');

            trackElement.addEventListener('load', function () {
                if (trackElement.track) {
                    bindTrack(trackElement.track);
                    trackElement.track.mode = captionsEnabled ? 'hidden' : 'disabled';
                }
                applyTrackModes();
            });

            trackElement.addEventListener('error', function () {
                clearOverlay();
                updateButton();
            });
        }

        function configureTracks() {
            var elements = video.querySelectorAll('track[data-watch-subtitle-track], track[kind="subtitles"], track[kind="captions"]');

            for (var i = 0; i < elements.length; i += 1) {
                bindTrackElement(elements[i]);
                if (elements[i].track) {
                    bindTrack(elements[i].track);
                }
            }

            applyTrackModes();
        }

        function setCaptionsEnabled(enabled) {
            captionsEnabled = !!enabled;
            window.nollyflixCaptionsEnabled = captionsEnabled;
            applyTrackModes();
        }

        // Make the state available to the episode switcher and future player code.
        window.nollyflixCaptionsEnabled = true;
        window.nollyflixWatchSetCaptionsEnabled = setCaptionsEnabled;
        window.nollyflixWatchRefreshCaptions = configureTracks;

        if (button) {
            // Capture phase prevents the older native-caption click handler from
            // also toggling the same tracks after this handler runs.
            button.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                if (event.stopImmediatePropagation) {
                    event.stopImmediatePropagation();
                }
                setCaptionsEnabled(!captionsEnabled);
            }, true);
        }

        video.addEventListener('timeupdate', renderOverlay);
        video.addEventListener('seeked', renderOverlay);
        video.addEventListener('playing', renderOverlay);
        video.addEventListener('loadedmetadata', configureTracks);
        video.addEventListener('canplay', configureTracks);

        if (window.MutationObserver) {
            new MutationObserver(function (mutations) {
                var trackChanged = mutations.some(function (mutation) {
                    if (mutation.type !== 'childList') {
                        return false;
                    }

                    var nodes = Array.prototype.slice.call(mutation.addedNodes || [])
                        .concat(Array.prototype.slice.call(mutation.removedNodes || []));

                    return nodes.some(function (node) {
                        return node && node.nodeType === 1 && String(node.tagName).toLowerCase() === 'track';
                    });
                });

                if (trackChanged) {
                    window.setTimeout(configureTracks, 0);
                }
            }).observe(video, { childList: true });
        }

        configureTracks();

        // The legacy player performs some setup on window.load/setTimeout(0).
        // Re-apply our state after those steps so captions remain on by default.
        [0, 50, 250, 1000].forEach(function (delay) {
            window.setTimeout(configureTracks, delay);
        });
    }

    if (document.readyState === 'complete') {
        setupNollyflixCaptions();
    } else {
        window.addEventListener('load', setupNollyflixCaptions);
    }
})();
