@extends('layouts.access')

@section('content')


<div id="content-pro">
    
    <div class="container-fluid custom-gutters-pro">
        
        <div style="height:15px;"></div>
        @if($category->sections->count())
            @foreach($category->sections as $section)
            <h2 class="post-list-heading">{{ $section->name }}<span></span></h2>
        
            <div class="progression-studios-elementor-carousel-container progression-studios-always-arrows-on">
                <div id="progression-video-carousel" class="owl-carousel progression-carousel-theme">
                    @foreach($section->videos as $video)

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
                        <div class="d-flex  flex-column justify-content-center ">
                            <div class="mx-auto buy-rent-links">
                                <a href="{{ optional($video)->preview_link }}" class="btn anchor-btn"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                                <a href="#" class="buy-video btn anchor-btn"   data-prop="{{ $video }}"  data-type="buy" id=""><i class="fas fa-shopping-cart"></i>Buy  {{ $video->currency }}{{ number_format($video->converted_buy_price) }} </a>
                                <a href="#" class="rent-video btn anchor-btn"  data-prop="{{ $video }}"  data-type="rent"id=""><i class="fas fa-shopping-cart"></i>Rent  {{ $video->currency }}{{ number_format($video->converted_rent_price) }}</a>
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
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->
@endsection
