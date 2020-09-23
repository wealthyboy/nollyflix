

// let video = null;





// if (v){
//   //Destroy the old instance

//   video = videojs('video', {
//   html5: {
//     hls: {
//       overrideNative: !videojs.browser.IS_SAFARI
//     }
//   },
//   autoplay:true
// })


// video.ready(function() {

//   this.muted(false)
//   bV.classList.remove('hide')
//   let isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
//   if (isSafari){
//    // this.play();
//   }


//   v.style.display ="block"

//   this.on('ended', function() {
//     videojs.log('Awww...over so soon?!');
//   });
// });

// }


// var  bV = document.querySelector('.background_video')

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
      overrideNative: !videojs.browser.IS_SAFARI
    }
  },
  autoplay:true

})


vidjs.ready(function() {
  document.getElementById("video-page-title-pro").classList.add('hide')
  this.muted(false)

 /// let isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
});













