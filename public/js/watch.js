
let video = null;

 videojs('video', {
  html5: {
    hls: {
      overrideNative: !videojs.browser.IS_SAFARI
    }
  }
})

// //video.play();



// var v = document.getElementById('play-trailer')
// console.log(v)
// // $('.play-trailer').on('click',function(e){
// //   e.preventDefault()
// //   $('#video').css('display', 'block')
// //   video.play();
// // })