

let video = null;

var  v = document.getElementById('video')
var  bV = document.querySelector('.background_video')
var  bK = document.querySelector('.back')

bV.addEventListener('mouseenter',function(e) {
   bK.style.display ="block"
   bK.style.opacity ="1"
})

bV.addEventListener('mouseleave',function(e) {
  bK.style.display ="none"
  bK.style.opacity ="0"
})



if (v){
  video = videojs('video', {
  html5: {
    hls: {
      overrideNative: !videojs.browser.IS_SAFARI
    }
  },
  autoplay:true
})


video.ready(function() {
  this.muted(false)
  document.getElementById("video-page-title-pro").classList.add('hide')
  bV.classList.remove('hide')


  v.style.display ="block"
  console.log(v)
  this.on('ended', function() {
    videojs.log('Awww...over so soon?!');
  });
});

}





