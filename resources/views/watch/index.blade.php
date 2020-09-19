
@extends('layouts.watch')

@section('content')


<div class="background_video">
  jdjdjdjj
    <video id="video" class="video-js vjs-default-skin"  data-setup ='{}'
        controls poster='{{ optional($video)->poster }}'
        data-setup='{ "playbackRates": [1, 1.5, 2] }'>
        <source src="{{ optional($video)->link }}" type="application/x-mpegURL">
        @if(optional($video->video)->track_file)
        <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
        @endif
    </video>
</div><!-- close #content-pro -->

@endsection
