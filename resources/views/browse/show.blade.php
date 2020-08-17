@extends('layouts.access')

@section('content')



<div id="video-page-title-pro" style="background-image:url({{ optional($video)->poster }});">
    <a class="video-page-title-play-button "  data-fancybox  href="{{ optional($video)->preview_link }}"><i class="fas fa-play"></i></a>
    <div id="video-page-title-gradient-base"></div>
</div><!-- close #video-page-title-pro -->

<div id="content-pro">
			
    <div class="container custom-gutters-pro">
        <div id="video-post-container">
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
                <a href="#" class="buy-video"   data-prop="{{ $video }}" data-user="{{ $user }}" data-type="buy" id="video-post-play-text-btn"><i class="fas fa-shopping-cart"></i>Buy  {{ $video->currency }}{{ number_format($video->buy_price) }} </a>
                <a href="#" class="rent-video"  data-prop="{{ $video }}" data-user="{{ $user }}" data-type="rent"id="video-post-play-text-btn"><i class="fas fa-shopping-cart"></i>Rent  {{ $video->currency }}{{ number_format($video->rent_price) }}</a>
                <div class="clearfix"></div>
            </div><!-- close #video-post-buttons-container -->

    
            <div id="video-more-like-this-details-section">
                
                @if($video->related_videos->count())
                <h3 id="more-videos-heading">More Like This</h3>
            
                <div class="progression-studios-elementor-carousel-container progression-studios-always-arrows-on">
                    <div id="progression-video-carousel" class="owl-carousel progression-carousel-theme">
                    @foreach( $video->related_videos as $video)

                        <div class="item">
                            <div class="progression-studios-video-index-container">
                                <a href="/browse/{{ $video->slug }}">
                                    <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                                    <div class="progression-video-index-content no-background">
                                        <div class="progression-video-index-table">
                                            <div class="progression-video-index-vertical-align">
                                                <h2 class="progression-video-title"></h2>
                                                <div class="clearfix"></div>                                    
                                            </div><!-- close .progression-video-index-vertical-align -->
                                        </div><!-- close .progression-video-index-table -->
                                    </div><!-- close .progression-video-index-content -->
                                    <div class="video-index-border-hover"></div>
                                </a>
                            </div><!-- close .progression-studios-video-index-container  -->
                        </div><!-- close .item -->
                        
                        @endforeach

                    </div><!-- close #progression-video-carousel -->
                </div><!-- close .progression-studios-elementor-carousel-container  -->

            @else
            @endif 
                
                <div style="height:10px;"></div>

            </div><!-- close #video-more-like-this-details-section -->

        </div><!-- close #video-post-container -->
        
        
        
        <div id="video-post-sidebar">
          
            
            <div class="content-sidebar-section video-sidebar-section-length">
                <h4 class="content-sidebar-sub-header">Duration</h4>
                <div class="content-sidebar-short-description">{{ $video->duration }}</div>
            </div><!-- close .content-sidebar-section -->


            <div class="content-sidebar-section video-sidebar-section-length">
                <h4 class="content-sidebar-sub-header">Starring</h4>
               
            </div><!-- close .content-sidebar-section -->
            
         
    
            <div class="clearfix"></div>
        </div>
        <!-- close #video-post-sidebar -->
        
        
        <div class="clearfix"></div>
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->
@endsection
