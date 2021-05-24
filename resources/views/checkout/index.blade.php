@extends('layouts.checkout')

@section('content')

<div id="video-page-title-pro" class="d-none" style="background-image:url({{ optional($video)->poster }});">
    <img class="spinner-image" src="/images/loaders/spinner.png" alt="" width="30" height="30">
</div><!-- close #video-page-title-pro -->

<div id="content-pro">
    <section class="pb-4 mt-1">
       <mobile-payment :params={{ $params }} />
    </div>
</section>

</div><!-- close #content-pro -->
@endsection
