
@extends('layouts.watch')

@section('content')
<div id="video-page-title-pro" style="background-image:url({{ optional($video)->poster }});">
    <img class="spinner-image" src="/images/loaders/spinner.png" alt="" width="120" height="120">
</div><!-- close #video-page-title-pro -->

<!-- type="application/x-mpegURL" -->
@endsection
