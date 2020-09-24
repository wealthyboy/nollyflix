
@extends('layouts.watch')

@section('content')
<div id="video-page-title-pro" style="background-image:url({{ $video->poster }});">
    <img class="spinner-image" src="https://assets.nflxext.com/en_us/pages/wiplayer/site-spinner.png" alt="" width="120" height="120">
</div><!-- close #video-page-title-pro -->

<div  class="background_video">
    <div class="back"><a href="/"> <i class="fas fa-long-arrow-alt-left"></i></a></div>
    <video id="video"  poster="{{ $video->poster }}"  muted  disablePictureInPicture nodownload  class="video-js vjs-default-skin"   
        controls 
        >
        <source src="{{ $video->link }}" type="application/x-mpegURL">       
         @if(optional($video)->track_file)
        <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>
</div><!-- close #content-pro -->
<!-- type="application/x-mpegURL" -->
@endsection
