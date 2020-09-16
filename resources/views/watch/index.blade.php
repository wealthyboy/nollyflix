


  
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title> Video</title>
  <link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet">
</head>
<style>

  
body {
  margin: 0;
  padding: 0;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background: black;
  font-family: 'Rubik', sans-serif;
}

.video-container {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.video-container video {
  width: 100%;
  height: 100%;
}

.video-container .controls-container {
  position: fixed;
  bottom: 0px;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  min-height: 40vh;
  /* Thanks to theArtifacts */
  background: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.9)); 
  transition: opacity 0.5s linear;
}

.video-container .progress-controls {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
}

.video-container .progress-controls .time-remaining {
  margin: 1vw;
  width: 4vw;
}

.video-container .progress-controls .progress-bar {
  width: 90vw;
  height: 1vw;
  max-height: 7px;
  background: #5B5B5B;
  display: flex;
  align-items: center;
  cursor: pointer;
}

.video-container .progress-controls .progress-bar .watched-bar,
.video-container .progress-controls .progress-bar .playhead {
  background: #E31221;
  display: inline-block;
  transition: all 0.2s;
}

.video-container .progress-controls .progress-bar .watched-bar {
  height: 110%;
  width: 20%;
}

.video-container .progress-controls .progress-bar .playhead {
  height: 3vw;
  width: 3vw;
  max-height: 25px;
  max-width: 25px;
  border-radius: 50%;
  transform: translateX(-50%);
}

.video-container .controls {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.video-container .controls button {
  background: none;
  outline: none;
  box-shadow: none;
  border: none;
  width: 5vw;
  height: 5vw;
  min-width: 50px;
  min-height: 50px;
  margin: 0px 1vw;
  opacity: 0.4;
  transform: scale(0.9);
  transition: all 0.2s ease-in-out;
  cursor: pointer;
}

.video-container .controls button:hover {
  opacity: 1;
  transform: scale(1.2);
}

.video-container .controls button svg {
  fill: white;
  stroke: white;
  stroke-width: 2;
  stroke-linecap: round;
  stroke-linejoin: round;
  width: 100%;
  height: 100%;
}

.video-container .controls button.volume svg path,
.video-container .controls button.help svg,
.video-container .controls button.episodes svg,
.video-container .controls button.full-screen svg,
.video-container .controls button.volume svg path,
.video-container .controls button.cast svg {
  fill: none;
}

.video-container .controls button.rewind svg,
.video-container .controls button.fast-forward svg {
  stroke: none;
}

.video-container .controls button.captions svg {
  stroke: none;
}

.video-container .controls .title {
  font-size: 2vw;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

@media only screen and (max-width: 768px) {
  .video-container .controls .title {
    display: none;
  }
}

.video-container .controls .title .series {
  color: #FEFEFE;
  font-weight: bold;
  font-size: 1em;
}

.video-container .controls .title .episode {
  color: #A1A1A1;
  font-size: 0.75em;
  padding-left: 1vw;
}


</style>

<body>
  <div class="video-container">
    <!-- <video src="bbb.mp4"></video> -->
    <video src="{{ optional($video->video)->link }}"></video>
    <div class="controls-container">
      <div class="progress-controls">
        <div class="progress-bar">
          <div class="watched-bar"></div>
          <div class="playhead"></div>
        </div>
        <div class="time-remaining">
          00:00
        </div>
      </div>
      <div class="controls">
        <button class="play-pause">
          <svg class="playing" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <polygon points="5 3 19 12 5 21 5 3"></polygon>
          </svg>
          <svg class="paused" viewBox="0 0 24 24">
            <rect x="6" y="4" width="4" height="16"></rect>
            <rect x="14" y="4" width="4" height="16"></rect>
          </svg>
        </button>
        <button class="rewind">
          <svg viewBox="0 0 24 24">
            <path fill="#ffffff"
              d="M12.5,3C17.15,3 21.08,6.03 22.47,10.22L20.1,11C19.05,7.81 16.04,5.5 12.5,5.5C10.54,5.5 8.77,6.22 7.38,7.38L10,10H3V3L5.6,5.6C7.45,4 9.85,3 12.5,3M10,12V22H8V14H6V12H10M18,14V20C18,21.11 17.11,22 16,22H14A2,2 0 0,1 12,20V14A2,2 0 0,1 14,12H16C17.11,12 18,12.9 18,14M14,14V20H16V14H14Z" />
          </svg>
        </button>
        <button class="fast-forward">
          <svg viewBox="0 0 24 24">
            <path fill="#ffffff"
              d="M10,12V22H8V14H6V12H10M18,14V20C18,21.11 17.11,22 16,22H14A2,2 0 0,1 12,20V14A2,2 0 0,1 14,12H16C17.11,12 18,12.9 18,14M14,14V20H16V14H14M11.5,3C14.15,3 16.55,4 18.4,5.6L21,3V10H14L16.62,7.38C15.23,6.22 13.46,5.5 11.5,5.5C7.96,5.5 4.95,7.81 3.9,11L1.53,10.22C2.92,6.03 6.85,3 11.5,3Z" />
          </svg>
        </button>
        <button class="volume">
          <svg class="full-volume" viewBox="0 0 24 24">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
          </svg>
          <svg class="muted" viewBox="0 0 24 24">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
            <line x1="23" y1="9" x2="17" y2="15"></line>
            <line x1="17" y1="9" x2="23" y2="15"></line>
          </svg>
        </button>
        <p class="title">
          <span class="series">Crazy People</span> <span class="episode">Big Buck Bunny</span>
        </p>
        <button class="help">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
          </svg>
        </button>
        <button class="next">
          <svg viewBox="0 0 24 24">
            <polygon points="5 4 15 12 5 20 5 4"></polygon>
            <line x1="19" y1="5" x2="19" y2="19"></line>
          </svg>
        </button>
        <button class="episodes">
          <svg viewBox="0 0 24 24">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
          </svg>
        </button>
        <button class="captions">
          <svg viewBox="0 0 20 20">
            <path
              d="M17 0H3a3 3 0 00-3 3v10a3 3 0 003 3h11.59l3.7 3.71A1 1 0 0019 20a.84.84 0 00.38-.08A1 1 0 0020 19V3a3 3 0 00-3-3zM3.05 9.13h2a.75.75 0 010 1.5h-2a.75.75 0 110-1.5zm3.89 4.44H3.05a.75.75 0 010-1.5h3.89a.75.75 0 110 1.5zm5 0H10a.75.75 0 010-1.5h2a.75.75 0 010 1.5zm0-2.94H8.08a.75.75 0 010-1.5H12a.75.75 0 010 1.5zm5 0H15a.75.75 0 010-1.5h2a.75.75 0 010 1.5z" />
          </svg>
        </button>
        <button class="cast">
          <svg viewBox="0 0 24 24">
            <path
              d="M2 16.1A5 5 0 0 1 5.9 20M2 12.05A9 9 0 0 1 9.95 20M2 8V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6">
            </path>
            <line x1="2" y1="20" x2="2.01" y2="20"></line>
          </svg>
        </button>
        <button class="full-screen">
          <svg class="maximize" viewBox="0 0 24 24">
            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
            </path>
          </svg>
          <svg class="minimize" viewBox="0 0 24 24">
            <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3">
            </path>
          </svg>
        </button>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

  <script>
    const videoContainer = document.querySelector('.video-container');
    const video = document.querySelector('.video-container video');

    const controlsContainer = document.querySelector('.video-container .controls-container');

    const playPauseButton = document.querySelector('.video-container .controls button.play-pause');
    const rewindButton = document.querySelector('.video-container .controls button.rewind');
    const fastForwardButton = document.querySelector('.video-container .controls button.fast-forward');
    const volumeButton = document.querySelector('.video-container .controls button.volume');
    const fullScreenButton = document.querySelector('.video-container .controls button.full-screen');
    const playButton = playPauseButton.querySelector('.playing');
    const pauseButton = playPauseButton.querySelector('.paused');
    const fullVolumeButton = volumeButton.querySelector('.full-volume');
    const mutedButton = volumeButton.querySelector('.muted');
    const maximizeButton = fullScreenButton.querySelector('.maximize');
    const minimizeButton = fullScreenButton.querySelector('.minimize');


    const progressBar = document.querySelector('.video-container .progress-controls .progress-bar');
    const watchedBar = document.querySelector('.video-container .progress-controls .progress-bar .watched-bar');
    const timeLeft = document.querySelector('.video-container .progress-controls .time-remaining');

    let controlsTimeout;
    controlsContainer.style.opacity = '0';
    watchedBar.style.width = '0px';
    pauseButton.style.display = 'none';
    minimizeButton.style.display = 'none';

    const displayControls = () => {
      controlsContainer.style.opacity = '1';
      document.body.style.cursor = 'initial';
      if (controlsTimeout) {
        clearTimeout(controlsTimeout);
      }
      controlsTimeout = setTimeout(() => {
        controlsContainer.style.opacity = '0';
        document.body.style.cursor = 'none';
      }, 5000);
    };

    const playPause = () => {
      var videoSrc = 'https://player.vimeo.com/external/295694148.m3u8?s=18ac64a43a5451767174c13df7e0e54b3f471a0b';

     // https://player.vimeo.com/external/295694148.m3u8?s=18ac64a43a5451767174c13df7e0e54b3f471a0b
      if (video.paused) {
        if (Hls.isSupported()) {
          var hls = new Hls();
        hls.loadSource(videoSrc);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function() {
          video.play();
        });
      }
      // hls.js is not supported on platforms that do not have Media Source
      // Extensions (MSE) enabled.
      //
      // When the browser has built-in HLS support (check using `canPlayType`),
      // we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video
      // element through the `src` property. This is using the built-in support
      // of the plain video element, without using hls.js.
      //
      // Note: it would be more normal to wait on the 'canplay' event below however
      // on Safari (where you are most likely to find built-in HLS support) the
      // video.src URL must be on the user-driven white-list before a 'canplay'
      // event will be emitted; the last video event that can be reliably
      // listened-for when the URL is not on the white-list is 'loadedmetadata'.
      else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = videoSrc;
        video.addEventListener('loadedmetadata', function() {
          video.play();
        });
      }
        playButton.style.display = 'none';
        pauseButton.style.display = '';
      } else {
        video.pause();
        playButton.style.display = '';
        pauseButton.style.display = 'none';
      }
    };

    const toggleMute = () => {
      video.muted = !video.muted;
      if (video.muted) {
        fullVolumeButton.style.display = 'none';
        mutedButton.style.display = '';
      } else {
        fullVolumeButton.style.display = '';
        mutedButton.style.display = 'none';
      }
    };

    const toggleFullScreen = () => {
      if (!document.fullscreenElement) {
        videoContainer.requestFullscreen();
      } else {
        document.exitFullscreen();
      }
    };

    document.addEventListener('fullscreenchange', () => {
      if (!document.fullscreenElement) {
        maximizeButton.style.display = '';
        minimizeButton.style.display = 'none';
      } else {
        maximizeButton.style.display = 'none';
        minimizeButton.style.display = '';
      }
    });

    document.addEventListener('keyup', (event) => {
      if (event.code === 'Space') {
        playPause(); 
      }

      if (event.code === 'KeyM') {
        toggleMute();
      }

      if (event.code === 'KeyF') {
        toggleFullScreen();
      }

      displayControls();
    });

    document.addEventListener('mousemove', () => {
      displayControls();
    });

    video.addEventListener('timeupdate', () => {
      watchedBar.style.width = ((video.currentTime / video.duration) * 100) + '%';
      // TODO: calculate hours as well...
      const totalSecondsRemaining = video.duration - video.currentTime;
      // THANK YOU: BEGANOVICH
      const time = new Date(null);
      time.setSeconds(totalSecondsRemaining);
      let hours = null;

      if(totalSecondsRemaining >= 3600) {
        hours = (time.getHours().toString()).padStart('2', '0');
      }

      let minutes = (time.getMinutes().toString()).padStart('2', '0');
      let seconds = (time.getSeconds().toString()).padStart('2', '0');

      timeLeft.textContent = `${hours ? hours : '00'}:${minutes}:${seconds}`;
    });

    progressBar.addEventListener('click', (event) => {
      const pos = (event.pageX  - (progressBar.offsetLeft + progressBar.offsetParent.offsetLeft)) / progressBar.offsetWidth;
      video.currentTime = pos * video.duration;
    });

    playPauseButton.addEventListener('click', playPause);

    rewindButton.addEventListener('click', () => {
      video.currentTime -= 10;
    });

    fastForwardButton.addEventListener('click', () => {
      video.currentTime += 10;
    });

    volumeButton.addEventListener('click', toggleMute);

    fullScreenButton.addEventListener('click', toggleFullScreen);



  </script>
</body>

</html>


