
let video = null;

var  v = document.getElementById('video')
if (v){
  video = videojs('video', {
  html5: {
    hls: {
      overrideNative: !videojs.browser.IS_SAFARI
    }
  }
})

video.ready(function() {
 // document.getElementsByClassName('vjs-big-play-button')[0].click()
});

}

// //video.play();



var play = document.getElementById('play-trailer')

if (play){

 video = videojs('show-video', {
    html5: {
      hls: {
        overrideNative: !videojs.browser.IS_SAFARI
      }
    }
  })
  $('.play-trailer').on('click',function(e){
    e.preventDefault()
    $('#show-video').css('display', 'block')
    video.play();
  })
}