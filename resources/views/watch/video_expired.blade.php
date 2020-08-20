@extends('layouts.access')

@section('content')
<div id="content-pro">
    <div class="container-fluid custom-gutters-pro">
        <div class="row">
           
            <div class="col col-12 col-md-7 col-lg-7">
                <div class="progression-studios-video-index-container">
                
                    <div class="progression-studios-video-feaured-image"><img src="{{ $video->poster }}" alt="{{ $video->title }}"></div>

                    <div class="progression-video-index-content no-background">
                        <div class="progression-video-index-table">
                            <div class="progression-video-index-vertical-align">
                    
                                <div class="clearfix"></div>
                                <ul class="video-index-meta-taxonomy">
                                    <li></li>
                                </ul>												
                                <div class="clearfix"></div>
                    
                            </div><!-- close .progression-video-index-vertical-align -->
                        </div><!-- close .progression-video-index-table -->
                    </div><!-- close .progression-video-index-content -->
                    <div class="video-index-border-hover"></div>
                    
                    <div class="mr-2">

                        
                    </div>
                        
                
                
                </div><!-- close .progression-studios-video-index-container -->
            </div><!-- close .col -->

            <div class="col col-12 col-md-5 col-lg-5">
                <div class="video-title-container">
                    <h1 class="video-post-heading-title">{{ optional($video)->title }}</h1>
                    <div class="clearfix"></div>
                
                    <ul id="video-post-meta-list">
                        <li><a href="/">{{ $video->genres[0]->name }}</a></li>
                        <li id="video-post-meta-year">{{ $video->created_at->format('Y') }}</li>
                        <li id="video-post-meta-rating"><span>{{ $video->film_rating }}</span></li>
                    </ul>
                    <div class="clearfix"></div>

                    <div id="vayvo-video-post-content">
                        <p>{!! optional($video)->description !!}</p>
                    </div><!-- #vayvo-video-post-content -->

                    <div id="video-post-buttons-container">
                        <a href="{{ optional($video)->preview_link }}" class=""  data-fancybox id="video-post-play-text-btn"><i class="far fa-play-circle"></i>Play Trailer </a>
                        <a href="#" class="buy-video"   data-prop="{{ $video }}"  data-type="buy" id="video-post-play-text-btn"><i class="fas fa-shopping-cart"></i>Buy  {{ $video->currency }}{{ number_format($video->buy_price) }} </a>
                        <a href="#" class="rent-video"  data-prop="{{ $video }}"  data-type="rent"id="video-post-play-text-btn"><i class="fas fa-shopping-cart"></i>Rent  {{ $video->currency }}{{ number_format($video->rent_price) }}</a>
                        <div class="clearfix"></div>
                    </div><!-- close #video-post-buttons-container -->
                </div>
            </div>
        </div><!-- close .row --> 
        
    </div><!-- close .container -->
</div><!-- close #content-pro -->
@endsection
