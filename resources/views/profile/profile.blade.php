@extends('layouts.access')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center mt-9">
        <div class="col-6 col-lg-2 mb-5">
            <div class="card card-profile text-center card-plain">
                <div class="card-avatar">
                    <a href="#">
                        <img class="img" src="{{ $user->m_path() }}">
                    </a>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $user->fullname() }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="content-pro">	
    <div class="container custom-gutters-pro">
        <div class="row">
            @if($user->profile_videos->count())
            @foreach($user->profile_videos as $video)

            <div class="col col-6 col-md-4 mb-2 col-lg-2">
                    <div class="progression-studios-video-index-container">
                        <a href="/browse/{{ $video->slug }}/user/{{ $user->slug }}">
                            <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                            <div class="progression-video-index-content no-background">
                                <div class="progression-video-index-table">
                                    <div class="progression-video-index-vertical-align">
                                        <div class="clearfix"></div>
                                    </div><!-- close .progression-video-index-vertical-align -->
                                </div><!-- close .progression-video-index-table -->
                            </div><!-- close .progression-video-index-content -->
                            <div class="video-index-border-hover"></div>
                        </a>
                    </div><!-- close ohram.progression-studios-video-index-container -->
                    <div class="d-flex position-absolute links-section flex-column  justify-content-center ">
                        <div class="mx-auto buy-rent-links">
                            <a href="{{ optional($video)->preview_link }}" class="btn anchor-btn rounded-0"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                            <a href="#" class="buy-video btn anchor-btn rounded-0"   data-prop="{{ $video }}"  data-type="buy" id=""><i class="fas fa-shopping-cart"></i>Buy  {{ $video->currency }}{{ number_format($video->converted_buy_price) }} </a>
                            <a href="#" class="rent-video btn anchor-btn rounded-0"  data-prop="{{ $video }}"  data-type="rent"id=""><i class="fas fa-shopping-cart"></i>Rent  {{ $video->currency }}{{ number_format($video->converted_rent_price) }}</a>
                        </div>
                    </div><!-- close #video-post-buttons-container -->
                </div><!-- close .col -->
            @endforeach
             
            @else
               <div class="col col-12 col-md-6 col-lg-4">
                     No videos for  {{ $user->fullname() }}
               </div>
            @endif
        </div><!-- close .row -->
    </div><!-- close .container -->
</div><!-- close #content-pro -->
@endsection
