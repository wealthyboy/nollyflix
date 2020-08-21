



const NUM_CHUNKS = 5;

var video = document.querySelector('video');
video.src = video.webkitMediaSourceURL;
console.log(video.webkitMediaSourceURL)

video.addEventListener('webkitsourceopen', function(e) {
  var chunkSize = Math.ceil(file.size / NUM_CHUNKS);

  // Slice the video into NUM_CHUNKS and append each to the media element.
  for (var i = 0; i < NUM_CHUNKS; ++i) {
    var startByte = chunkSize * i;

    // file is a video file.
    var chunk = file.slice(startByte, startByte + chunkSize);

    var reader = new FileReader();
    reader.onload = (function(idx) {
      return function(e) {
        video.webkitSourceAppend(new Uint8Array(e.target.result));
        logger.log('appending chunk:' + idx);
        if (idx == NUM_CHUNKS - 1) {
          video.webkitSourceEndOfStream(HTMLMediaElement.EOS_NO_ERROR);
        }
      };
    })(i);

    reader.readAsArrayBuffer(chunk);
  }
}, false);


