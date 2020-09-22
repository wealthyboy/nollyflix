@extends('layouts.watch')

@section('content')


<div  class="background_video">
    <div class="back"><a href="/"> <i class="fas fa-long-arrow-alt-left"></i>  Back</a></div>
    <video id="video"  poster="{{ $video->poster }}" class="video-js vjs-default-skin "  muted data-setup ='{}'
        controls 
        data-setup='{ "playbackRates": [1, 1.5, 2] }'>
        <source src="{{ $video->link }}" type="application/x-mpegURL">
        @if(optional($video)->track_file)
        <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>
</div><!-- close #content-pro -->

@endsection
