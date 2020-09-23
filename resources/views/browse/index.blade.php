@extends('layouts.access')

@section('content')

@include('includes.searching')

<section class="section-content">

@if ($featured_videos->count())
<div class="flexslider progression-studios-slider">
    <ul class="slides">
        @foreach($featured_videos as $video)
            <li class="progression_studios_animate_left">
                <div class="progression-studios-slider-image-background" style="background-image:url({{ optional($video->video)->poster }});">
                    <div class="progression-studios-slider-display-table">
                        <div class="progression-studios-slider-vertical-align">
                            <div class="container">
                                <div class="progression-studios-slider-caption-width">
                                    <div class="progression-studios-slider-caption-align">
                                        <h2><a href="/">{{ optional($video->video)->title }}</a></h2>
                                        <ul class="slider-video-post-meta-list">
                                            <li class="slider-video-post-meta-cat"><ul><li><a href="#"> {!! $video->video->genres[0]->name !!} </a></li></ul></li>
                                            <li class="slider-video-post-meta-year">{{ $video->created_at->format('Y') }}</li>
                                            <li class="slider-video-post-meta-rating"><span>{{ optional($video->video)->film_rating }}</span></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                        <?php  $read_more =  "<a href='/browse/'>Read More</a>" ?>
<div class="progression-studios-slider-excerpt"><?php echo  str_limit(html_entity_decode($video->video->description), $limit = 200, $end = '...') ?>  <?php  if (strlen($video->video->description)  > 200 ) {?>  <a href="{{ route('browse.show',['video' => $video->video->slug ]) }}">Read More</a>  <?php } ?></div>
                                        <a class="btn btn-slider-pro" data-fancybox  href="{{ optional($video->video)->preview_link }}"><i class="far fa-play-circle"></i>Play Trailer</a>
                                        <a class="btn btn-slider-pro"  data-type="buy" href="{{ route('browse.show',['video' => $video->video->slug ]) }}"><i class="fas fa-shopping-cart"></i>Buy {{ $video->video->currency }}{{ number_format($video->video->converted_buy_price) }} </a>
                                        <a class="btn btn-slider-pro"   data-type="rent"  href="{{ route('browse.show',['video' => $video->video->slug ]) }}"><i class="fas fa-shopping-cart"></i>Rent {{ $video->video->currency }}{{ number_format($video->video->converted_rent_price) }} </a>
                                    </div><!-- close .progression-studios-slider-caption-align -->
                                </div><!-- close .progression-studios-slider-caption-width -->
    
                            </div><!-- close .container -->
                            
                        </div><!-- close .progression-studios-slider-vertical-align -->
                    </div><!-- close .progression-studios-slider-display-table -->
            
                    <div class="progression-studios-slider-overlay-gradient"></div>
                
                </div><!-- close .progression-studios-slider-image-background -->
            </li>
        @endforeach
    </ul>
</div><!-- close .progression-studios-slider - See /js/script.js file for options -->
@endif

<div id="content-pro">
    <div class="container-fluid custom-gutters-pro">
        <div style="height:15px;"></div>
        @if($sections->count())
            @foreach($sections as $section)
            <h2 class="post-list-heading ">{{ $section->name }}<span></span></h2>
            
            <div class="progression-studios-elementor-carousel-container mb-5 progression-studios-always-arrows-on">
                <div id="progression-video-carousel" class="owl-carousel progression-video-carousel progression-carousel-theme">
                    @foreach($section->videos as $video)
                        <div class="item">
                            <div class="progression-studios-video-index-container">
                                <a href="/browse/{{ $video->slug }}">
                                    <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                                    <div class="progression-video-index-content">
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
                            <div class="d-flex position-absolute links-section flex-column  justify-content-center ">
                                <div class="mx-auto buy-rent-links">
                                    <a href="{{ optional($video)->preview_link }}" class="btn anchor-btn rounded-0"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                                    <a href="/browse/{{ $video->slug }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Buy {{ $video->currency }}{{ number_format($video->converted_buy_price) }} </a>
                                    <a href="/browse/{{ $video->slug }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Rent {{ $video->currency }}{{ number_format($video->converted_rent_price) }} </a>

                                </div>
                            </div><!-- close #video-post-buttons-container -->
                        </div><!-- close .item -->
                    
                    @endforeach

                </div><!-- close #progression-video-carousel - See /js/script.js file for options -->
            </div><!-- close .progression-studios-elementor-carousel-container  -->
            @endforeach

        @else
        @endif            
    
        <div class="clearfix"></div>
        
        </div>
    </div><!-- close .container --> 
</div><!-- close #content-pro -->
</section>


@include('includes.search')
@endsection
