@extends('layouts.access')

@section('content')
<div class="flexslider progression-studios-slider">
    <ul class="slides">
        <li class="progression_studios_animate_left">
            <div class="progression-studios-slider-image-background" style="background-image:url({{ optional($video)->poster }});">
                <div class="progression-studios-slider-display-table">
                    <div class="progression-studios-slider-vertical-align">
                        
                        <div class="container">
                            
                            <div class="progression-studios-slider-caption-width">
                                <div class="progression-studios-slider-caption-align">
                                    <h2><a href="/">{{ optional($video)->title }}</a></h2>
                                    <ul class="slider-video-post-meta-list">
                                        <li class="slider-video-post-meta-cat"><ul><li><a href="#">{{ 'Drama'}}</a></li></ul></li>																				
                                       
                                        <li class="slider-video-post-meta-year">{{ $video->created_at->format('Y') }}</li>
                                        <li class="slider-video-post-meta-rating"><span>PG-{{ $video->film_rating }}</span></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                    <div class="progression-studios-slider-excerpt"><?php echo html_entity_decode($video->description) ?></div>
                                    <a class="btn btn-slider-pro afterglow" href="#"><i class="fas fa-play-circle"></i>Play Trailer</a>
                                    <a class="btn btn-slider-pro afterglow" href="#"><i class="fas fa-play-circle"></i>Buy Now</a>
                                    <a class="btn btn-slider-pro afterglow" href="#"><i class="fas fa-play-circle"></i>Rent</a>
                                    <video id=""  class="d-none" poster="{{ $video->poster }}" width="960" height="540">
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
        <div id="video-post-container">
            <div class="clearfix"></div>
            @if($video->related_videos->count())

            <div id="video-more-like-this-details-section">
                <h3 id="more-videos-heading">More Like This</h3>
                
                <div class="row">
                    @foreach( $video->related_videos as $video)

                    <div class="col col-12 col-md-6 col-lg-6">
                        <div class="progression-studios-video-index-container">
                            <a href="#">
                                <div class="progression-studios-video-feaured-image"><img src="{{ $video->video->poster }}" alt="{{ $video->video->title }}"></div>
                    
                                <div class="progression-video-index-content">
                                    <div class="progression-video-index-table">
                                        <div class="progression-video-index-vertical-align">
                                
                                            <h2 class="progression-video-title">Planet Earth</h2>
                                
                                        
                                            <div class="clearfix"></div>
                                    
                                            <ul class="video-index-meta-taxonomy"><li>Drama</li></ul>												
                                            <div class="clearfix"></div>
                                
                                        </div><!-- close .progression-video-index-vertical-align -->
                                    </div><!-- close .progression-video-index-table -->
                                </div><!-- close .progression-video-index-content -->
                                <div class="video-index-border-hover"></div>
                        
                            </a>
                        </div><!-- close .progression-studios-video-index-container -->
                    </div><!-- close .col -->
                    @endforeach

                   
            
                   
                    
                </div><!-- close .row -->
                
                <div style="height:10px;"></div>

            </div><!-- close #video-more-like-this-details-section -->
            @endif

        </div><!-- close #video-post-container -->
        
        
        
        <div id="video-post-sidebar">
            <div class="content-sidebar-section video-sidebar-section-release-date">
                <h4 class="content-sidebar-sub-header">Release Date</h4>
                <div class="content-sidebar-short-description">January 17, 2019</div>
            </div><!-- close .content-sidebar-section -->
            
            <div class="content-sidebar-section video-sidebar-section-length">
                <h4 class="content-sidebar-sub-header">Duration</h4>
                <div class="content-sidebar-short-description">{{ $video->duration }}</div>
            </div><!-- close .content-sidebar-section -->
            
            <div id="video-post-recent-reviews-sidebar">
                <h3 class="content-sidebar-reviews-header">Starring</h3>
                
                <ul class="sidebar-reviews-pro">
                    <li>
                        <div class="progression-studios-sidebar-review-container">
            
                            <!--div id="sidebar-review-number">5</div-->
                            <div id="sidebar-review-rating-container">
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
                                </div>
                            </div>
    
    
                            <h5 id="sidebar-review-author">Jane Doe</h5>

                        </div><!-- close .progression-studios-sidebar-review-container -->
                    </li>
                    

                    </ul>
                
            </div><!-- close #video-post-recent-reviews-sidebar -->
    
            <div class="clearfix"></div>
        </div>
        <!-- close #video-post-sidebar -->
        
        
        <div class="clearfix"></div>
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->


@endsection
