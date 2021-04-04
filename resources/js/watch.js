import videojs from 'video.js'


  let  bV = document.querySelector('.background_video')
  let  bK = document.querySelector('.back')
  
  bV.addEventListener('mouseenter',function(e) {
     bK.style.display ="block"
     bK.style.opacity ="1"
  })
  
  bV.addEventListener('mouseleave',function(e) {
    bK.style.display ="none"
    bK.style.opacity ="0"
  })

  window.onload = (event) => {

  
  const vidjs = videojs('video', {
    html5: {
      hls: {
        overrideNative: !videojs.browser.IS_SAFAR}
    },
    autoplay:true
  })
  
  
  
  
  vidjs.ready(function() {
    document.getElementById("video-page-title-pro").classList.add('hide')
    this.muted(false)
    console.log("Video loaded")
  });


};















