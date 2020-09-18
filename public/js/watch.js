
let video = null;

video = videojs('video', {
  html5: {
    hls: {
      overrideNative: !videojs.browser.IS_SAFARI
    }
  }
})


$('.play-trailer').on('click',function(e){
  e.preventDefault()
  $('.video-container').css('display', 'block')


  video.play();
})
