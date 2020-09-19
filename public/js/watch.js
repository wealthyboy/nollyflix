
let video = null;

var  v = document.getElementById('video')
if (v){
  videojs('video', {
  html5: {
    hls: {
      overrideNative: !videojs.browser.IS_SAFARI
    }
  }
})



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