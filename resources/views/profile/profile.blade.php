@extends('layouts.access')

@section('content')

<div id="page-title-pro">
    <div id="progression-studios-page-title-container">
                <div class="container">
                <h1 class="page-title">{{ $user->fullname() }}</h1>
                <h4 class="progression-sub-title"></h4>				
            </div><!-- close .container -->
    </div><!-- close #progression-studios-page-title-container -->
    <div class="clearfix"></div>
    <div id="page-title-overlay-image" style="background-image:url('images/banners/Bgnollyn01.jpg');"></div>
</div>
<div id="content-pro">	
    <div class="container custom-gutters-pro">
        <h2 class="post-list-heading"><span></span></h2>
        <div class="row">
            @if($user->profile_videos->count())
            @foreach($user->profile_videos as $video)

            <div class="col col-12 col-md-6 col-lg-3">
                <div class="progression-studios-video-index-container">
                    <a href="/browse/{{ $video->slug }}/user/{{ $user->slug }}">
                        <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                    
                        <div class="progression-video-index-content no-background">
                            <div class="progression-video-index-table">
                                <div class="progression-video-index-vertical-align">
                                    <div class="clearfix"></div>
                                    <div class="clearfix"></div>
                                </div><!-- close .progression-video-index-vertical-align -->
                            </div><!-- close .progression-video-index-table -->
                        </div><!-- close .progression-video-index-content -->
                        <div class="video-index-border-hover"></div>
                        <div class="mt-3">
                            <h2 class="progression-video-title">Buy - {{ $video->currency }}{{ number_format($video->buy_price) }}</h2>
                            <h2 class="progression-video-title">Rent - {{ $video->currency }}{{ number_format($video->rent_price) }}</h2>
                        </div>
                        
                    </a>
                </div><!-- close .progression-studios-video-index-container -->
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
