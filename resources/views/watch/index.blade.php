@extends('layouts.watch')

@section('content')
<div>
    <video id="background_video" class="video-js vjs-default-skin"  data-setup ='{}'
        controls preload="none" poster='http://content.bitsontherun.com/thumbs/3XnJSIm4-480.jpg'
        data-setup='{ "playbackRates": [1, 1.5, 2] }'>
    <source src="https://player.vimeo.com/external/295694148.m3u8?s=18ac64a43a5451767174c13df7e0e54b3f471a0b" type="video/mpegURL">
    </video>
</div>



</div><!-- close #content-pro -->
@endsection
