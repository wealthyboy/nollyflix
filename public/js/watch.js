document.addEventListener('contextmenu', event => event.preventDefault());

let = video = videojs('video', {
      html5: {
        hls: {
          overrideNative: !videojs.browser.IS_SAFARI
        }
      }
})






// var video = document.querySelector('video');


// var assetURL = 'https://vod-progressive.akamaized.net/exp=1597986478~acl=%2A%2F542258040.mp4%2A~hmac=cdf71489a2fc427ad74981ab5cd041011158b2525ff19d1dd9f7a0335d7142b6/vimeo-prod-skyfire-std-us/01/3821/6/169107226/542258040.mp4?filename=STALKER+SCREENER.mp4';
// // Need to be specific for Blink regarding codecs
// // ./mp4info frag_bunny.mp4 | grep Codec
// var mimeCodec = 'video/mp4; codecs="avc1.42E01E, mp4a.40.2"';

// if ('MediaSource' in window && MediaSource.isTypeSupported(mimeCodec)) {
//   var mediaSource = new //;
//   //console.log(mediaSource.readyState); // closed
//   video.src = URL.createObjectURL(mediaSource);
//   mediaSource.addEventListener('sourceopen', sourceOpen);
// } else {
//   console.error('Unsupported MIME type or codec: ', mimeCodec);
// }

// function sourceOpen (_) {
//   //console.log(this.readyState); // open
//   var mediaSource = this;
//   var sourceBuffer = mediaSource.addSourceBuffer(mimeCodec);
//   fetchAB(assetURL, function (buf) {
//     sourceBuffer.addEventListener('updateend', function (_) {
//       mediaSource.endOfStream();
//       video.play();
//       //console.log(mediaSource.readyState); // ended
//     });
//     sourceBuffer.appendBuffer(buf);
//   });
// };

// function fetchAB (url, cb) {
//   console.log(url);
//   var xhr = new XMLHttpRequest;
//   xhr.open('get', url);
//   xhr.responseType = 'arraybuffer';
//   xhr.onload = function () {
//     cb(xhr.response);
//   };
//   xhr.send();
// };






