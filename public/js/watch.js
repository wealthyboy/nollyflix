

let video = null;

var  v = document.getElementById('video')
if (v){
  video = videojs('video', {
  html5: {
    hls: {
      overrideNative: !videojs.browser.IS_SAFARI
    }
  },
  autoplay: true
})

video.ready(function() {

  // In this context, `this` is the player that was created by Video.js.
  //this.play();
  this.muted(false)


   // How about an event listener?
   this.on('ended', function() {
     videojs.log('Awww...over so soon?!');
   });
});

}


var play = document.getElementById('show-video')

if (play){

 video = videojs('show-video', {
    html5: {
      hls: {
        overrideNative: !videojs.browser.IS_SAFARI
      }
    }
  })

    video.play();

}