
@extends('layouts.watch')

@section('content')

<div id="video-page-title-pro" style="background-image:url({{ $video->poster }});">
    <img class="image" src="https://assets.nflxext.com/en_us/pages/wiplayer/site-spinner.png" alt="" width="120" height="120">
</div><!-- close #video-page-title-pro -->

<div  class="background_video hide">
    <div class="back"><a href="/"> <i class="fas fa-long-arrow-alt-left"></i>  Back</a></div>
    <video id="video"  poster="{{ $video->poster }}" class="video-js vjs-default-skin "  muted data-setup ='{}'
        controls 
        data-setup='{ "playbackRates": [1, 1.5, 2] }'>
        <source src="/videos/shayfeen.mp4" type="video/mp4">
        @if(optional($video)->track_file)
        <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>
</div><!-- close #content-pro -->

@endsection
