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
                                            <li class="slider-video-post-meta-year">{{ optional(optional($video->video)->release_date)->format('Y') }}</li>
                                            <li class="slider-video-post-meta-rating"><span>{{ optional($video->video)->film_rating }}</span></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                        <?php  $read_more =  "<a href='/browse/'>Read More</a>" ?>
<div class="progression-studios-slider-excerpt"><?php echo  str_limit(html_entity_decode($video->video->description), $limit = 200, $end = '...') ?>  <?php  if (strlen($video->video->description)  > 200 ) {?>  <a href="{{ route('browse.show',['video' => $video->video->slug ]) }}">Read More</a>  <?php } ?></div>
                                        <a class="btn btn-slider-pro" data-fancybox href="{{ optional($video->video)->playablePreviewLink() }}"><i class="far fa-play-circle"></i>Play Trailer</a>
                                        @if($video->video->isBlockedInCurrentRegion())
                                            <a class="btn btn-slider-pro" href="{{ route('browse.show',['video' => $video->video->slug ]) }}"><i class="fas fa-info-circle"></i>View Details</a>
                                        @else
                                            <a class="btn btn-slider-pro"  data-type="buy" href="{{ route('browse.show',['video' => $video->video->slug ]) }}"><i class="fas fa-shopping-cart"></i>Buy {{ $video->video->currency }}{{ number_format($video->video->converted_buy_price) }} </a>
                                            <a class="btn btn-slider-pro"   data-type="rent"  href="{{ route('browse.show',['video' => $video->video->slug ]) }}"><i class="fas fa-shopping-cart"></i>Rent {{ $video->video->currency }}{{ number_format($video->video->converted_rent_price) }} </a>
                                        @endif
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

        @if(isset($continueWatching) && $continueWatching->count())
        <style>
            .nollyflix-continue-section {
                margin-bottom: 42px;
            }

            .nollyflix-continue-card .progression-studios-video-feaured-image {
                position: relative;
            }

            .nollyflix-continue-card .progression-studios-video-feaured-image:after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(0, 0, 0, .58), transparent 48%);
                pointer-events: none;
            }

            .nollyflix-continue-play {
                position: absolute;
                left: 50%;
                top: 50%;
                z-index: 2;
                width: 48px;
                height: 48px;
                margin: -24px 0 0 -24px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, .72);
                border: 2px solid rgba(255, 255, 255, .92);
                color: #fff;
                font-size: 18px;
                transition: transform .18s ease, background-color .18s ease;
            }

            .nollyflix-continue-card:hover .nollyflix-continue-play {
                transform: scale(1.08);
                background: #ef0f1a;
            }

            .nollyflix-continue-progress {
                position: absolute;
                z-index: 3;
                left: 0;
                right: 0;
                bottom: 0;
                height: 5px;
                background: rgba(255, 255, 255, .28);
            }

            .nollyflix-continue-progress span {
                display: block;
                height: 100%;
                min-width: 3px;
                background: #ef0f1a;
            }

            .nollyflix-continue-title {
                display: block;
                margin-top: 10px;
                color: #fff;
                font-size: 16px;
                font-weight: 700;
                line-height: 1.25;
                text-decoration: none;
            }

            .nollyflix-continue-title:hover {
                color: #ef0f1a;
                text-decoration: none;
            }

            .nollyflix-continue-subtitle {
                margin-top: 4px;
                color: rgba(255, 255, 255, .62);
                font-size: 12px;
                line-height: 1.3;
            }

            @media (max-width: 767px) {
                .nollyflix-continue-section {
                    margin-bottom: 30px;
                }

                .nollyflix-continue-play {
                    width: 40px;
                    height: 40px;
                    margin: -20px 0 0 -20px;
                    font-size: 15px;
                }

                .nollyflix-continue-title {
                    font-size: 14px;
                }
            }
        </style>

        <div class="nollyflix-continue-section">
            <h2 class="post-list-heading">Continue Watching<span></span></h2>
            <div class="progression-studios-elementor-carousel-container progression-studios-always-arrows-on">
                <div class="owl-carousel progression-video-carousel progression-carousel-theme nollyflix-continue-carousel">
                    @foreach($continueWatching as $watchProgress)
                        @php
                            $continueVideo = $watchProgress->video;
                            $continueEpisode = $watchProgress->episode;
                            $continuePercent = $watchProgress->percent;
                            $continueEpisodeLabel = null;

                            if ($continueEpisode) {
                                $season = $continueEpisode->season_number ?: 1;
                                $episodeNumber = $continueEpisode->episode_number ?: 1;
                                $continueEpisodeLabel = 'S' . $season . ' E' . $episodeNumber;
                                if ($continueEpisode->title) {
                                    $continueEpisodeLabel .= ' · ' . $continueEpisode->title;
                                }
                            }
                        @endphp
                        <div class="item">
                            <div class="progression-studios-video-index-container nollyflix-continue-card">
                                <a href="{{ route('watch', ['video' => $continueVideo->slug]) }}" aria-label="Continue watching {{ $continueVideo->title }}">
                                    <div class="progression-studios-video-feaured-image">
                                        <img src="{{ $continueVideo->tn_poster ?: $continueVideo->poster }}" alt="{{ $continueVideo->title }}">
                                        <span class="nollyflix-continue-play"><i class="fas fa-play"></i></span>
                                        <span class="nollyflix-continue-progress" aria-hidden="true"><span style="width: {{ $continuePercent }}%;"></span></span>
                                    </div>
                                </a>
                            </div>
                            <a class="nollyflix-continue-title" href="{{ route('watch', ['video' => $continueVideo->slug]) }}">{{ $continueVideo->title }}</a>
                            <div class="nollyflix-continue-subtitle">{{ $continueEpisodeLabel ?: 'Resume movie' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($sections->count())
            @foreach($sections as $section)
            <h2 class="post-list-heading">{{ $section->name }}<span></span></h2>
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
                            <div class="d-flex position-absolute links-section flex-column  justify-content-center">
                                <div class="mx-auto buy-rent-links">
                                   @include('partials.links')
                                </div>
                            </div><!-- close #video-post-buttons-container -->
                        </div><!-- close .item -->
                    
                    @endforeach

                </div><!-- close #progression-video-carousel  file for options -->
            </div><!-- close .progression-studios-elementor-carousel-container  -->
            @endforeach

        @else
        @endif

        <style>
            .nollyflix-about-home {
                position: relative;
                overflow: hidden;
                margin: 12px 0 48px;
                padding: 46px 52px;
                border: 1px solid rgba(255, 255, 255, .08);
                border-radius: 18px;
                background: linear-gradient(135deg, rgba(239, 15, 26, .10) 0%, rgba(255, 255, 255, .035) 48%, rgba(0, 0, 0, .16) 100%);
            }

            .nollyflix-about-home:before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 86px;
                height: 3px;
                background: #ef0f1a;
            }

            .nollyflix-about-kicker {
                display: inline-block;
                margin-bottom: 10px;
                color: #ef0f1a;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 2px;
                text-transform: uppercase;
            }

            .nollyflix-about-home h2 {
                margin: 0 0 20px;
                max-width: 720px;
                color: #fff;
                font-size: 34px;
                font-weight: 700;
                line-height: 1.15;
            }

            .nollyflix-about-copy {
                max-width: 900px;
            }

            .nollyflix-about-copy p {
                margin: 0 0 15px;
                color: rgba(255, 255, 255, .76);
                font-size: 16px;
                line-height: 1.8;
            }

            .nollyflix-about-copy p:last-child {
                margin-bottom: 0;
            }

            .nollyflix-about-copy .nollyflix-about-closing {
                color: #fff;
                font-size: 18px;
                font-weight: 700;
            }

            @media (max-width: 767px) {
                .nollyflix-about-home {
                    margin-bottom: 34px;
                    padding: 32px 24px;
                    border-radius: 14px;
                }

                .nollyflix-about-home h2 {
                    font-size: 27px;
                }

                .nollyflix-about-copy p {
                    font-size: 15px;
                    line-height: 1.7;
                }

                .nollyflix-about-copy .nollyflix-about-closing {
                    font-size: 17px;
                }
            }
        </style>

        <section class="nollyflix-about-home" aria-labelledby="nollyflix-about-title">
            <span class="nollyflix-about-kicker">About Nollyflix</span>
            <h2 id="nollyflix-about-title">African stories. Global stage.</h2>
            <div class="nollyflix-about-copy">
                <p>Nollyflix is a premium video streaming platform dedicated to showcasing the best of Nollywood and African storytelling.</p>
                <p>Owned by Fortress Studios Ltd, Lagos, Nigeria, we were built to give authentic African stories a global stage. From blockbuster Nollywood films and original series to documentaries and emerging voices, Nollyflix brings you closer to the culture, drama, and creativity that defines us.</p>
                <p class="nollyflix-about-closing">We are more than a streaming platform. We are home for African cinema.</p>
            </div>
        </section>

        <div class="clearfix"></div>
        
        </div>
    </div><!-- close .container --> 
</div><!-- close #content-pro -->
</section>


@include('includes.search')
@endsection
