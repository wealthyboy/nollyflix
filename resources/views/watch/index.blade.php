@extends('layouts.watch')

@section('content')
<style>
  
  /* Show the controls (hidden at the start by default) */
  .video-js .vjs-control-bar { 
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
  }

  /* Make the demo a little prettier */
  body {
    margin-top: 20px;
    background: #222;
    text-align: center; 
    color: #aaa;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    background: radial-gradient(#333, hsl(200,30%,6%) );
  }

  a, a:hover, a:visited { color: #76DAFF; }
</style>
<div>
    <video id="background_video" class="video-js vjs-default-skin"  data-setup ='{}'
        controls preload="none" poster='{{ $video->poster }}'
        data-setup='{ "playbackRates": [1, 1.5, 2] }'>
         <source src="https://player.vimeo.com/external/295694148.m3u8?s=18ac64a43a5451767174c13df7e0e54b3f471a0b" type="application/x-mpegURL">
        @if($video->track_file)
        <track src="{{ $video->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>
</div><!-- close #content-pro -->
@endsection
