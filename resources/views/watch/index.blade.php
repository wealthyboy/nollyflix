@extends('layouts.watch')

@section('content')
<div >
    <div class="position-absolute" id=""><<< Back</div>
    <video  id="background_video" controls>
        <source src="{{ $video->link }}" type="video/mp4">
    </video>
</div><!-- close #content-pro -->
@endsection
