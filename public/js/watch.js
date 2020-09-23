
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

vidjs = videojs('video', {
  html5: {
    hls: {
      overrideNative: !videojs.browser.IS_SAFAR}
  },
  autoplay:true
})

let loader = document.querySelector(".vjs-loading-spinner")

if (loader){
  loader.style.visibility = 'visible'
}

vidjs.on('timeupdate', function () {
  loader.style.visibility = 'hidden'
})

vidjs.ready(function() {
  document.getElementById("video-page-title-pro").classList.add('hide')
  let isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
  if (isSafari){return}
  this.muted(false)
});













