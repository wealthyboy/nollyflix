
const videoTag = document.getElementById("background_video");

// creating the MediaSource, just with the "new" keyword, and the URL for it
const myMediaSource = new MediaSource();
const url = URL.createObjectURL(myMediaSource);
console.log(url)

// attaching the MediaSource to the video tag
videoTag.src = url;


const videoSourceBuffer = myMediaSource
  .addSourceBuffer('video/mp4; codecs="avc1.64001e"');


// the same for the video SourceBuffer
fetch("https://player.vimeo.com/external/169107226.hd.mp4?s=4bb07bfbaf9212c9f3162b4d08ce146a3c9e8dc8&profile_id=119").then(function(response) {
  // The data has to be a JavaScript ArrayBuffer
  return response.arrayBuffer();
}).then(function(videoData) {
  videoSourceBuffer.appendBuffer(videoData);
});


