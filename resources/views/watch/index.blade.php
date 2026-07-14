@extends('layouts.watch')

@php
    $seriesEpisodes = $video->episodes->filter(function ($episode) {
        return !empty($episode->link);
    })->values();
    $firstEpisode = $seriesEpisodes->first();
    $initialSource = $firstEpisode
        ? route('watch.hls.episode', ['episode' => $firstEpisode->id, 'playback_token' => $playbackToken])
        : route('watch.hls.video', ['video' => $video->slug, 'playback_token' => $playbackToken]);
    $initialTitle = $firstEpisode
        ? ($firstEpisode->title ?: 'Episode ' . ($firstEpisode->episode_number ?: 1))
        : $video->title;
    $fallbackDescription = trim(strip_tags($video->description));
@endphp

@section('page-css')
@if($seriesEpisodes->count() || $nextVideoUrl)
<style>
    .watch-series-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        font-family: "Fira Sans Condensed", sans-serif;
    }

    .watch-series-menu {
        position: relative;
    }

    .watch-series-next,
    .watch-series-toggle {
        width: 54px;
        height: 54px;
        border: 0;
        border-radius: 0;
        background: transparent;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        transition: transform .18s ease, opacity .18s ease;
    }

    .watch-series-next:hover,
    .watch-series-toggle:hover,
    .watch-series-menu.is-open .watch-series-toggle {
        background: transparent;
        transform: scale(1.08);
    }

    .watch-series-next:disabled {
        cursor: default;
        opacity: .35;
        transform: none;
    }

    .watch-series-next svg,
    .watch-series-toggle svg {
        width: 38px;
        height: 38px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2.2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .watch-series-panel {
        position: absolute;
        right: 0;
        bottom: 58px;
        width: min(560px, calc(100vw - 32px));
        max-height: min(66vh, 560px);
        overflow-y: auto;
        background: rgba(28, 28, 28, .98);
        border-radius: 4px;
        box-shadow: 0 24px 64px rgba(0, 0, 0, .55);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: translateY(8px);
        transition: opacity .18s ease, visibility .18s ease, transform .18s ease;
    }

    .watch-series-menu.is-open .watch-series-panel,
    .watch-series-menu:hover .watch-series-panel {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateY(0);
    }

    .watch-series-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        background: #252525;
        border-bottom: 1px solid rgba(255, 255, 255, .14);
    }

    .watch-series-close {
        border: 0;
        background: transparent;
        color: #fff;
        padding: 0;
        display: inline-flex;
        cursor: pointer;
    }

    .watch-series-close svg {
        width: 26px;
        height: 26px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2.4;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .watch-series-header h2 {
        margin: 0;
        color: #fff;
        font-size: 25px;
        font-weight: 700;
        line-height: 1;
    }

    .watch-season-title {
        padding: 14px 20px 4px;
        color: rgba(255, 255, 255, .62);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .watch-episode-item {
        width: 100%;
        display: grid;
        grid-template-columns: 34px minmax(0, 1fr) 92px;
        gap: 14px;
        border: 0;
        border-bottom: 1px solid rgba(255, 255, 255, .1);
        background: transparent;
        color: #fff;
        text-align: left;
        padding: 14px 20px;
        cursor: pointer;
        transition: background .16s ease, box-shadow .16s ease;
    }

    .watch-episode-item:hover,
    .watch-episode-item.active {
        background: rgba(255, 255, 255, .08);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .65);
    }

    .watch-episode-number {
        font-size: 19px;
        font-weight: 700;
        color: rgba(255, 255, 255, .88);
        line-height: 1.2;
    }

    .watch-episode-copy {
        min-width: 0;
    }

    .watch-episode-title {
        display: block;
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.2;
    }

    .watch-episode-duration {
        display: block;
        margin-top: 4px;
        color: rgba(255, 255, 255, .58);
        font-size: 13px;
    }

    .watch-episode-bar {
        width: 100%;
        height: 2px;
        margin-top: 10px;
        background: rgba(255, 255, 255, .45);
        align-self: start;
    }

    .watch-episode-details {
        display: none;
        margin-top: 10px;
        color: rgba(255, 255, 255, .74);
        font-family: Lato, sans-serif;
        font-size: 13px;
        line-height: 1.4;
    }

    .watch-episode-item:hover .watch-episode-details,
    .watch-episode-item.active .watch-episode-details {
        display: block;
    }

    @media (max-width: 767px) {
        .watch-series-controls {
            gap: 8px;
        }

        .watch-series-next,
        .watch-series-toggle {
            width: 44px;
            height: 44px;
        }

        .watch-series-next svg,
        .watch-series-toggle svg {
            width: 30px;
            height: 30px;
        }

        .watch-series-panel {
            right: -8px;
            width: calc(100vw - 20px);
            max-height: 70vh;
        }

        .watch-episode-item {
            grid-template-columns: 30px minmax(0, 1fr);
            padding: 14px 16px;
        }

        .watch-episode-bar {
            display: none;
        }
    }
</style>
@endif
@endsection

@section('content')
<div id="video-page-title-pro" style="background-image:url({{ optional($video)->poster }});">
    <img class="spinner-image" src="/images/loaders/spinner.png" alt="" width="30" height="30">
</div><!-- close #video-page-title-pro -->

<div  class="background_video hide">
    <div class="back">
        <a href="/"> <i class="fas fa-long-arrow-alt-left"></i></a>
    </div>
    <div class="watch-player-title" data-watch-player-title>{{ $initialTitle }}</div>
    <div class="watch-player-loader" data-watch-player-loader>
        <img src="/images/loaders/spinner.png" alt="" width="72" height="72">
    </div>

    <video id="video"  poster="{{ optional($video)->poster }}"  muted  disablePictureInPicture controlsList="nodownload noplaybackrate noremoteplayback" nodownload  class="video-js vjs-default-skin" data-auth-required="{{ $video->access_type === 'is_free' ? '0' : '1' }}" data-watch-video-id="{{ $video->id }}"
        playsinline webkit-playsinline oncontextmenu="return false">
        <source src="{{ $initialSource }}" type="application/x-mpegURL">
         @if($video->track_file)
        <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>


    <button type="button" class="watch-center-play" data-watch-center-play aria-label="Play">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M8 5v14l11-7z"></path>
        </svg>
    </button>

    <div class="watch-custom-controls" data-watch-controls>
        <div class="watch-progress-row">
            <input class="watch-seek" type="range" min="0" max="1000" value="0" step="1" data-watch-seek aria-label="Seek">
        </div>
        <div class="watch-control-row">
            <div class="watch-control-group watch-control-group-left">
                <button type="button" class="watch-control-button" data-watch-play aria-label="Play">
                    <svg class="watch-icon-play" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8 5v14l11-7z"></path>
                    </svg>
                    <svg class="watch-icon-pause" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 5h4v14H7z"></path>
                        <path d="M13 5h4v14h-4z"></path>
                    </svg>
                </button>
                <button type="button" class="watch-control-button watch-skip-button" data-watch-skip="-10" aria-label="Rewind 10 seconds">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 7H5V3"></path>
                        <path d="M5.5 8.5a7 7 0 1 1-1.2 5.8"></path>
                        <text x="8" y="16">10</text>
                    </svg>
                </button>
                <button type="button" class="watch-control-button watch-skip-button" data-watch-skip="10" aria-label="Forward 10 seconds">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 7h4V3"></path>
                        <path d="M18.5 8.5a7 7 0 1 0 1.2 5.8"></path>
                        <text x="7" y="16">10</text>
                    </svg>
                </button>
                <button type="button" class="watch-control-button" data-watch-mute aria-label="Mute">
                    <svg class="watch-icon-volume" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 9v6h4l5 4V5L8 9H4z"></path>
                        <path d="M16 8a5 5 0 0 1 0 8"></path>
                        <path d="M18.5 5.5a9 9 0 0 1 0 13"></path>
                    </svg>
                    <svg class="watch-icon-muted" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 9v6h4l5 4V5L8 9H4z"></path>
                        <path d="M17 9l5 5"></path>
                        <path d="M22 9l-5 5"></path>
                    </svg>
                </button>
                <span class="watch-time">
                    <span data-watch-current-time>0:00</span>
                    <span>/</span>
                    <span data-watch-duration>0:00</span>
                </span>
            </div>

            <div class="watch-control-title" data-watch-control-title>
                <strong>{{ $initialTitle }}</strong>
            </div>

            <div class="watch-control-group watch-control-group-right">
                @if($seriesEpisodes->count() || $nextVideoUrl)
                <div class="watch-series-controls" data-watch-series-controls>
                    <button type="button" class="watch-series-next" data-watch-next-episode data-next-video-url="{{ $nextVideoUrl }}" aria-label="{{ $seriesEpisodes->count() ? 'Next episode' : 'Next movie' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M5 5l9 7-9 7V5z"></path>
                            <path d="M17 5v14"></path>
                        </svg>
                    </button>

                    @if($seriesEpisodes->count())
                    <div class="watch-series-menu" data-watch-series-menu>
                        <button type="button" class="watch-series-toggle" aria-label="Series episodes">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="4" y="5" width="12" height="9" rx="1"></rect>
                                <path d="M8 9h12v10H8z"></path>
                                <path d="M12 13h5"></path>
                            </svg>
                        </button>

                        <div class="watch-series-panel" role="dialog" aria-label="Series episodes">
                            <div class="watch-series-header">
                                <button type="button" class="watch-series-close" aria-label="Close episodes">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M19 12H5"></path>
                                        <path d="M12 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <h2>Episodes</h2>
                            </div>

                            @foreach($seriesEpisodes->groupBy('season_number') as $seasonNumber => $seasonEpisodes)
                                <div class="watch-season-title">Season {{ $seasonNumber ?: 1 }}</div>
                                @foreach($seasonEpisodes as $episode)
                                    @php
                                        $episodeNumber = $episode->episode_number ?: $loop->iteration;
                                        $episodeTitle = $episode->title ?: 'Episode ' . $episodeNumber;
                                        $episodeSummary = $fallbackDescription ?: 'Select this episode to continue watching.';
                                    @endphp
                                    <button type="button"
                                        class="watch-episode-item {{ $loop->parent->first && $loop->first ? 'active' : '' }}"
                                        data-episode-id="{{ $episode->id }}"
                                        data-episode-source="{{ route('watch.hls.episode', ['episode' => $episode->id, 'playback_token' => $playbackToken]) }}"
                                        data-episode-title="{{ $episodeTitle }}">
                                        <span class="watch-episode-number">{{ $episodeNumber }}</span>
                                        <span class="watch-episode-copy">
                                            <span class="watch-episode-title">{{ $episodeTitle }}</span>
                                            @if($episode->duration)
                                                <span class="watch-episode-duration">{{ $episode->duration }}</span>
                                            @endif
                                            <span class="watch-episode-details">{{ \Illuminate\Support\Str::limit($episodeSummary, 150) }}</span>
                                        </span>
                                        <span class="watch-episode-bar"></span>
                                    </button>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                @if($video->track_file)
                <button type="button" class="watch-control-button" data-watch-captions aria-label="Captions">
                    <span class="watch-cc-label">CC</span>
                </button>
                @endif
                <button type="button" class="watch-control-button watch-speed-button" data-watch-speed aria-label="Playback speed">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5 17a8 8 0 1 1 14 0"></path>
                        <path d="M12 17l4-6"></path>
                    </svg>
                </button>
                <button type="button" class="watch-control-button" data-watch-fullscreen aria-label="Fullscreen">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 9V4h5"></path>
                        <path d="M20 9V4h-5"></path>
                        <path d="M4 15v5h5"></path>
                        <path d="M20 15v5h-5"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

</div><!-- close #content-pro -->
<!-- type="application/x-mpegURL" -->
@endsection

@section('page-scripts')
@if($seriesEpisodes->count() || $nextVideoUrl)
<script>
(function () {
    function setupSeriesPanel(player) {
        var controls = document.querySelector('[data-watch-series-controls]');
        if (!controls) {
            return;
        }
        if (controls.getAttribute('data-watch-series-ready') === '1') {
            return;
        }
        controls.setAttribute('data-watch-series-ready', '1');

        var menu = document.querySelector('[data-watch-series-menu]');
        var toggle = menu ? menu.querySelector('.watch-series-toggle') : null;
        var closeButton = menu ? menu.querySelector('.watch-series-close') : null;
        var nextButton = controls.querySelector('[data-watch-next-episode]');
        var titles = Array.prototype.slice.call(document.querySelectorAll('[data-watch-player-title], [data-watch-control-title]'));
        var loader = document.querySelector('[data-watch-player-loader]');
        var nextVideoUrl = nextButton ? nextButton.getAttribute('data-next-video-url') : '';
        var episodeButtons = menu ? Array.prototype.slice.call(menu.querySelectorAll('[data-episode-source]')) : [];
        var isSeries = episodeButtons.length > 0;
        var video = document.getElementById('video');
        var videoId = video ? video.getAttribute('data-watch-video-id') : '';
        var storageKey = videoId ? 'nollyflix:watch:episode:' + videoId : '';
        var currentIndex = Math.max(episodeButtons.findIndex(function (button) {
            return button.classList.contains('active');
        }), 0);

        function getStoredEpisodeId() {
            if (!storageKey || !window.localStorage) {
                return '';
            }

            return window.localStorage.getItem(storageKey) || '';
        }

        function rememberEpisode(button) {
            var episodeId = button.getAttribute('data-episode-id');

            if (!storageKey || !episodeId || !window.localStorage) {
                return;
            }

            window.localStorage.setItem(storageKey, episodeId);
        }

        function closeMenu() {
            if (!menu) {
                return;
            }

            menu.classList.remove('is-open');
        }

        function updateNextButton() {
            if (!nextButton) {
                return;
            }

            nextButton.disabled = isSeries
                ? episodeButtons.length === 0
                : !nextVideoUrl;
        }

        function showLoader() {
            if (loader) {
                loader.classList.add('is-active');
            }
        }

        function hideLoader() {
            if (loader) {
                loader.classList.remove('is-active');
            }
        }

        function selectEpisode(button) {
            var source = button.getAttribute('data-episode-source');
            var episodeTitle = button.getAttribute('data-episode-title');

            if (!source) {
                return;
            }

            showLoader();
            currentIndex = episodeButtons.indexOf(button);
            rememberEpisode(button);
            episodeButtons.forEach(function (item) {
                item.classList.remove('active');
            });
            button.classList.add('active');
            if (episodeTitle) {
                titles.forEach(function (title) {
                    title.textContent = episodeTitle;
                });
            }
            updateNextButton();

            player.src({
                src: source,
                type: 'application/x-mpegURL'
            });
            player.load();

            var playPromise = player.play();
            if (playPromise && typeof playPromise.catch === 'function') {
                playPromise.catch(function () {});
            }
        }

        player.on('canplay', hideLoader);
        player.on('playing', hideLoader);
        player.on('error', hideLoader);
        player.on('ended', function () {
            if (isSeries && currentIndex < episodeButtons.length - 1) {
                selectEpisode(episodeButtons[currentIndex + 1]);
                closeMenu();
            }
        });

        if (toggle && menu) {
            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                menu.classList.toggle('is-open');
            });
        }

        if (closeButton) {
            closeButton.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                closeMenu();
            });
        }

        controls.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        document.addEventListener('click', function () {
            closeMenu();
        });

        episodeButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                selectEpisode(button);
                closeMenu();
            });
        });

        if (nextButton) {
            nextButton.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                if (event.isTrusted === false) {
                    return;
                }

                if (isSeries && episodeButtons.length > 1) {
                    selectEpisode(episodeButtons[currentIndex + 1] || episodeButtons[0]);
                    closeMenu();
                    return;
                }

                if (!isSeries && nextVideoUrl) {
                    showLoader();
                    window.location.href = nextVideoUrl;
                }
            });
        }

        if (isSeries) {
            var storedEpisodeId = getStoredEpisodeId();
            var storedEpisodeButton = storedEpisodeId
                ? episodeButtons.find(function (button) {
                    return button.getAttribute('data-episode-id') === storedEpisodeId;
                })
                : null;

            if (storedEpisodeButton && !storedEpisodeButton.classList.contains('active')) {
                selectEpisode(storedEpisodeButton);
            }
        }

        updateNextButton();
    }

    function waitForPlayer(attempt) {
        attempt = attempt || 0;

        if (window.nollyflixWatchPlayer) {
            window.nollyflixWatchPlayer.ready(function () {
                setupSeriesPanel(window.nollyflixWatchPlayer);
            });
            return;
        }

        if (!window.videojs || !window.videojs.getPlayer) {
            if (attempt < 60) {
                window.setTimeout(function () {
                    waitForPlayer(attempt + 1);
                }, 100);
            }
            return;
        }

        var player = window.videojs.getPlayer('video');
        if (!player) {
            if (attempt < 60) {
                window.setTimeout(function () {
                    waitForPlayer(attempt + 1);
                }, 100);
            }
            return;
        }

        player.ready(function () {
            setupSeriesPanel(player);
        });
    }

    if (document.readyState === 'complete') {
        waitForPlayer();
    } else {
        window.addEventListener('load', function () {
            waitForPlayer();
        });
    }

    window.addEventListener('nollyflix:watch-player-ready', function (event) {
        if (event.detail && event.detail.player) {
            event.detail.player.ready(function () {
                setupSeriesPanel(event.detail.player);
            });
        }
    });
})();
</script>
@endif
@endsection
