@extends('layouts.access')

@section('content')

@include('includes.searching')

<section class="section-content">
<div id="content-pro">
    <div class="container-fluid custom-gutters-pro nolly-xd-page-wrap">
        <header class="nolly-xd-page-hero nolly-xd-page-hero--compact">
            <span class="nolly-xd-eyebrow">DISCOVER NOLLYWOOD</span>
            <h1>{{ $category->name }}</h1>
            <p>Discover stories worth watching.</p>
            <span class="nolly-xd-accent-line" aria-hidden="true"></span>
        </header>
        <div style="height:15px;"></div>
         @if($category->sections->count())
         @foreach($category->sections as $section)
            <h2 class="post-list-heading ">{{ $section->name }}<span></span></h2>
            
            <div class="progression-studios-elementor-carousel-container mb-5 progression-studios-always-arrows-on">
                <div id="progression-video-carousel" class="owl-carousel progression-video-carousel progression-carousel-theme">
                    @foreach($section->videos as $video)
                        <div class="item nolly-xd-movie-item">
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
                            <a class="nollyflix-card-details" href="/browse/{{ $video->slug }}" aria-label="View {{ $video->title }}">
                                <span class="nollyflix-card-title">{{ $video->title }}</span>
                                <span class="nollyflix-card-genre">{{ optional($video->genres->first())->name ?: 'Nollywood' }}</span>
                            </a>
                            <div class="d-flex position-absolute links-section flex-column  justify-content-center ">
                                <div class="mx-auto buy-rent-links">
                                  @include('partials.links')
                                </div>
                            </div><!-- close #video-post-buttons-container -->
                        </div><!-- close .item -->
                    
                    @endforeach

                </div><!-- close #progression-video-carousel - See /js/script.js file for options -->
            </div><!-- close .progression-studios-elementor-carousel-container  -->
            @endforeach

        @else
           <div class="col col-12 col-md-6 col-lg-4">
                No videos for {{ $category->name }}
            </div>
        @endif            
    
        <div class="clearfix"></div>
        
        </div>
    </div><!-- close .container --> 
</div><!-- close #content-pro -->
</section>


@include('includes.search')
@endsection
