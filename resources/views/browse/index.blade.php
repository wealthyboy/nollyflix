@extends('layouts.access')

@section('content')
<div class="flexslider progression-studios-slider">
    <ul class="slides">
        <li class="progression_studios_animate_left">
            <div class="progression-studios-slider-image-background" style="background-image:url({{ optional($featured->video)->poster }});">
                <div class="progression-studios-slider-display-table">
                    <div class="progression-studios-slider-vertical-align">
                        
                        <div class="container">
                            
                            <div class="progression-studios-slider-caption-width">
                                <div class="progression-studios-slider-caption-align">
                                    <h2><a href="/">{{ optional($featured->video)->title }}</a></h2>
                                    <ul class="slider-video-post-meta-list">
                                        <li class="slider-video-post-meta-cat"><ul><li><a href="#">{{ 'Drama' }}</a></li></ul></li>																				
                                       
                                        <li class="slider-video-post-meta-year">{{ $featured->created_at->format('Y') }}</li>
                                        <li class="slider-video-post-meta-rating"><span>PG-{{ optional($featured->video)->film_rating }}</span></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                    <div class="progression-studios-slider-excerpt"><?php echo html_entity_decode($featured->video->description) ?></div>
                                    <a class="btn btn-slider-pro" data-fancybox  href="{{ optional($featured->video)->preview_link }}"><i class="far fa-play-circle"></i>Play Trailer</a>
                                    <a class="btn btn-slider-pro buy-video"  data-prop="{{ $featured->video }}" data-type="buy" href="#"><i class="fas fa-shopping-cart"></i>Buy</a>
                                    <a class="btn btn-slider-pro rent-video"  data-prop="{{ $featured->video }}" data-type="rent"  href="#"><i class="fas fa-shopping-cart"></i>Rent </a>
                                </div><!-- close .progression-studios-slider-caption-align -->
                            </div><!-- close .progression-studios-slider-caption-width -->
                            
                        </div><!-- close .container -->
                        
                    </div><!-- close .progression-studios-slider-vertical-align -->
                </div><!-- close .progression-studios-slider-display-table -->
                
                <div class="progression-studios-slider-overlay-gradient"></div>
                
                <div class="progression-studios-skrn-slider-upside-down" style="background-image:url('images/demo/shutterstock_117660574.jpg');"></div>
                
                
            </div><!-- close .progression-studios-slider-image-background -->
        </li>
      
    </ul>
</div><!-- close .progression-studios-slider - See /js/script.js file for options -->

<div id="content-pro">
    
    <div class="container-fluid custom-gutters-pro">
        
        <div style="height:15px;"></div>
        @if($sections->count())
            @foreach($sections as $section)
            <h2 class="post-list-heading">{{ $section->name }}<span></span></h2>
        
            <div class="progression-studios-elementor-carousel-container progression-studios-always-arrows-on">
                <div id="progression-video-carousel" class="owl-carousel progression-carousel-theme">
                    @foreach($section->videos as $video)

                    <div class="item">
                        <div class="progression-studios-video-index-container">
                            <a href="/browse/{{ $video->id }}">
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
                                <div class="mt-3">
                                    <h2 class="progression-video-title">Buy - {{ $video->currency }}{{ number_format($video->buy_price) }}</h2>
                                    <h2 class="progression-video-title">Rent - {{ $video->currency }}{{ number_format($video->rent_price) }}</h2>
                                </div>
                            </a>
                        </div><!-- close .progression-studios-video-index-container  -->
                    </div><!-- close .item -->
                    
                    @endforeach

                </div><!-- close #progression-video-carousel - See /js/script.js file for options -->
            </div><!-- close .progression-studios-elementor-carousel-container  -->
            @endforeach

        @else
        @endif            
    
        <div class="clearfix"></div>
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->
@endsection
