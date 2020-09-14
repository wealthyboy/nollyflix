
@extends('layouts.access')

@section('content')
<div id="content-pro">
			
    <div class="container-fluid custom-gutters-pro"> 
    <h1 class="post-list-heading mb-5">{{ $genre->name }}<span></span></h1>
  
        <div class="row">
            @if( $genre->videos->count())
               @foreach($genre->videos as $video)
                    <div class="col col-6 col-md-6 item col-lg-3">
                        <div class="progression-studios-video-index-container">
                            <a href="/browse/{{ $video->slug }}">
                                <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                            
                                <div class="progression-video-index-content">
                                    <div class="progression-video-index-table">
                                        <div class="progression-video-index-vertical-align">
                                        
                                            <h2 class="progression-video-title"></h2>
                                        
                                            <div class="average-rating-video-post">
                                                <div class="average-rating-video-empty">
                                                    <span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>
                                                </div>
                                                <div class="average-rating-overflow-width" style="width:70%;">
                                                    <div class="average-rating-video-filled">
                                                        <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
                                                    <div class="clearfix"></div>
                                                    </div><!-- close .average-rating-video-filled -->
                                                </div><!-- close .average-rating-overflow-width -->
                                            </div><!-- close .average-rating-video-post -->
                                            <div class="clearfix"></div>
                                            
                                            <ul class="video-index-meta-taxonomy">
                                                <li>
                                                    <i class="fas fa-shopping-cart"></i>Buy  {{ $video->currency }}{{ number_format($video->converted_buy_price) }} 
                                                </li>
                                                <li>
                                                    <i class="fas fa-shopping-cart"></i>Rent  {{ $video->currency }}{{ number_format($video->converted_rent_price) }}
                                                </li>
                                            </ul>												
                                            <div class="clearfix"></div>
                                        
                                        </div><!-- close .progression-video-index-vertical-align -->
                                    </div><!-- close .progression-video-index-table -->
                                </div><!-- close .progression-video-index-content -->
                                <div class="video-index-border-hover"></div>
                                
                            </a>
                        </div><!-- close .progression-studios-video-index-container -->
                        <div class="d-flex position-absolute links-section flex-column  justify-content-center">
                            <div class="mx-auto buy-rent-links">
                                <a href="{{ optional($video)->preview_link }}" class="btn anchor-btn rounded-0"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                            </div>
                        </div><!-- close #video-post-buttons-container -->
                    </div><!-- close .col -->
                @endforeach
            @else

            @endif
        </div><!-- close .row -->
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->

@include('includes.search')
@endsection
