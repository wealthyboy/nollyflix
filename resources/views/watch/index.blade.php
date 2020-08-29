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
</style>

<div class="background_video">
    <video id="video" class="video-js vjs-default-skin"  data-setup ='{}'
        controls preload="none" poster='{{ $video->poster }}'
        data-setup='{ "playbackRates": [1, 1.5, 2] }'>
         <source src="{{ optional($video->video)->link }}" type="application/x-mpegURL">
        @if($optional($video->video)->track_file)
        <track src="{{ optional($video->video)->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>
</div><!-- close #content-pro -->
@endsection
