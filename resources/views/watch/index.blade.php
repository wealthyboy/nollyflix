
@extends('layouts.watch')

@section('content')
<div id="video-page-title-pro" style="background-image:url({{ optional($video)->poster }});">
    <img class="spinner-image" src="/images/loaders/spinner.png" alt="" width="120" height="120">
</div><!-- close #video-page-title-pro -->
<div  class="background_video hide">
    <div class="back">
        <a href="/"> <i class="fas fa-long-arrow-alt-left"></i></a>
    </div>
    <video id="video"    muted  disablePictureInPicture nodownload  class="video-js vjs-default-skin"   
        controls 
        >
        <source src="{{ optional($video)->link }}" type="application/x-mpegURL">       
         @if($video->track_file)
        <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>
</div><!-- close #content-pro -->
<!-- type="application/x-mpegURL" -->
@endsection
