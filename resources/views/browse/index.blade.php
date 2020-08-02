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
                                        <li class="slider-video-post-meta-cat"><ul><li><a href="#">{{ 'Drama'}}</a></li></ul></li>																				
                                       
                                        <li class="slider-video-post-meta-year">{{ $featured->created_at->format('Y') }}</li>
                                        <li class="slider-video-post-meta-rating"><span>PG-{{ optional($featured->video)->film_rating }}</span></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                    <div class="progression-studios-slider-excerpt"><?php echo html_entity_decode($featured->video->description) ?></div>
                                    <a class="btn btn-slider-pro afterglow" href="#"><i class="fas fa-play-circle"></i>Buy Now</a>
                                    <a class="btn btn-slider-pro afterglow" href="#"><i class="fas fa-play-circle"></i>Rent</a>

                                    
                                    <video id=""  class="d-none" poster="{{ optional($featured->video)->poster }}" width="960" height="540">
                                        <source src="/images/video/sample.mp4" type="video/mp4">
                                    </video>
                                    
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
    
    <div class="container custom-gutters-pro">
        
        <div style="height:15px;"></div>
        @if($sections->count())
            @foreach($sections as $section)
            <h2 class="post-list-heading">{{ $section->name }}<span></span></h2>
            

            <div class="progression-studios-elementor-carousel-container progression-studios-always-arrows-on">
                <div id="progression-video-carousel" class="owl-carousel progression-carousel-theme">
                    @foreach($section->videos as $video)

                    <div class="item">
                        <div class="progression-studios-video-index-container">
                            <a href="/browse/video/">
                                <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="Featured Image"></div>
                        
                                <div class="progression-video-index-content">
                                    <div class="progression-video-index-table">
                                        <div class="progression-video-index-vertical-align">
                                    
                                            <h2 class="progression-video-title"></h2>
                                    
                                            <div class="average-rating-video-post">
                                                <div class="average-rating-video-empty">
                                                    <span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>
                                                </div>
                                                <div class="average-rating-overflow-width" style="width:100%;">
                                                    <div class="average-rating-video-filled">
                                                        <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
                                                    <div class="clearfix"></div>
                                                    </div><!-- close .average-rating-video-filled -->
                                                </div><!-- close .average-rating-overflow-width -->
                                            </div><!-- close .average-rating-video-post -->
                                            <div class="clearfix"></div>
                                        
                                            <div class="clearfix"></div>
                                    
                                        </div><!-- close .progression-video-index-vertical-align -->
                                    </div><!-- close .progression-video-index-table -->
                                </div><!-- close .progression-video-index-content -->
                                <div class="video-index-border-hover"></div>
                            
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
        

        <div style="height:85px;"></div>
        
    
        <div class="clearfix"></div>
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->
@endsection