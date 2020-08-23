@extends('layouts.watch')

@section('content')
<div >
    <div class="" id=""><<< Back</div>
    <video 
    class="video-js"
    data-setup='{ "controls": true, "autoplay": true, "preload": "auto" }'
    id="background_video" controls>
        <source src="{{ $video->link }}" type="video/mp4">
    </video>
</div><!-- close #content-pro -->
@endsection
