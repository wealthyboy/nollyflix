<div id="video-page-title-pro" style="background-image:url({{ $video->poster }});">
    <a class="video-page-title-play-button  play-trailer" id="play-trailer" href="#Video"><i class="fas fa-play"></i><span>Watch Trailer</span></a>
    <!-- <video src="bbb.mp4"></video> -->
</div><!-- close #video-page-title-pro -->

<video id="video" class="video-js vjs-default-skin"  data-setup ='{}'
    controls poster='{{ optional($video)->poster }}'
    data-setup='{ "playbackRates": [1, 1.5, 2] }'>
    <source src="{{ optional($video)->preview_link }}">
    @if(optional($video->video)->track_file)
    <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
    @endif
</video>

		
		
		
<div id="content-pro">
    <div class="container custom-gutters-pro">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-8">
                <div id="">
                    <h1 class="video-post-heading-title">{{ optional($video)->title }} HD</h1>
                    <div class="clearfix"></div>
                    <ul id="video-post-meta-list">
                        <li id="video-post-meta-year">{{ $video->created_at->format('Y') }}</li>
                        <li id="video-post-meta-rating"><span>{{ $video->film_rating }}</span></li>
                        <li id=""><span>{{ $video->duration }}</span></li>
                    </ul>
                    <div class="clearfix"></div>

                    <div class="content-sidebar-short-description mt-3"> Genres:
                        {!! $video->allGenres() !!}
                    </div>

        
                    <div class="content-sidebar-short-description mt-3"> Starring:
                       <?php  $casts = ''; $x= 1; 
                            foreach( $video->casts as  $cast ) {
                                $casts .="<a href='/$cast->username'>$cast->name &nbsp; $cast->last_name </a>" ;
                                if($x < count($video->casts)){
                                    $casts .= ' | ';
                                    $x++;
                                }
                            }
                        ?>
       
                    {!! $casts !!}
                    </div>

                    <div class="content-sidebar-short-description mt-3"> Produced By:
                        <?php  $filmers = ''; $x= 1; 
                            foreach( $video->filmers as  $filmer ) {
                                $filmers .="<a href='/$filmer->username'>$filmer->name &nbsp; $filmer->last_name </a>" ;
                                if($x < count($video->filmers)){
                                    $filmers .= ' | ';
                                    $x++;
                                }
                            }
                        ?>
                        {!! $filmers !!}

                    </div>

                    <div id="vayvo-video-post-content">
                       <p>{!! optional($video)->description !!}</p>           
                    </div><!-- #vayvo-video-post-content -->
                </div>
                
            </div>
            <div class="col-lg-4">
                <div id=""> 
                    <buttons />
                </div>
            </div>
        </div>

        <div style="background: #1c1f1f">
            <reviews />
        </div>

        <div id="video-more-like-this-details-section">
          


            @if($video->related_videos->count())
            <h2 class="post-list-heading">More Like {{ $video->title }}<span></span></h2>
            <div class="progression-studios-elementor-carousel-container progression-studios-always-arrows-on">
                <div id="progression-video-carousel" class="owl-carousel progression-carousel-theme">
                    @foreach( $video->related_videos as $video)
                        <div class="item">
                            <div class="progression-studios-video-index-container">
                                <a href="/browse/{{ $video->slug }}">
                                    <div class="progression-studios-video-feaured-image"><img src="{{ optional($video->video)->tn_poster }}" alt="{{ optional($video->video)->title }}"></div>
                                    <div class="progression-video-index-content">
                                        <div class="progression-video-index-table">
                                            <div class="progression-video-index-vertical-align">
                                                <h2 class="progression-video-title"></h2>
                                                <div class="clearfix"></div> 
											
												<ul class="video-index-meta-taxonomy text-center">
                                                    <li>
                                                        <svg viewBox="0 0 16 16"><path d="M13.781 7.25A3.96 3.96 0 0014 6a4 4 0 00-4-4C8.247 2 6.774 3.135 6.233 4.704A2.487 2.487 0 004.5 4 2.5 2.5 0 002 6.5c0 .273.055.531.135.776A3.5 3.5 0 003.5 14h9a3.5 3.5 0 003.5-3.5c0-1.48-.921-2.738-2.219-3.25zM6 11.25v-4.5L10.5 9 6 11.25z"></path></svg>
                                                        Buy  {{ optional($video->video)->currency }}{{ number_format(optional($video->video)->converted_buy_price) }} 
                                                    </li>
                                                    <li>
                                                       <svg viewBox="0 0 16 16"><path d="M4 6h8v4H4z"></path><path d="M16 11V5a3 3 0 01-3-3H3a3 3 0 01-3 3v6a3 3 0 013 3h10a3 3 0 013-3zm-3-1a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1h8a1 1 0 011 1v4z"></path></svg>
                                                       Rent  {{ optional($video->video)->currency }}{{ number_format(optional($video->video)->converted_rent_price) }}
                                                    </li>
                                                </ul>	                                   
                                            </div><!-- close .progression-video-index-vertical-align -->
                                        </div><!-- close .progression-video-index-table -->
                                    </div><!-- close .progression-video-index-content -->
                                    <div class="video-index-border-hover"></div>
                                </a>
                            </div><!-- close .progression-studios-video-index-container  -->
                            <div class="d-flex position-absolute links-section flex-column  justify-content-center ">
                                <div class="mx-auto buy-rent-links">
                                    <a href="{{ optional($video->video)->preview_link }}" class="btn anchor-btn rounded-0"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                                </div>
                            </div><!-- close #video-post-buttons-container -->
                        </div><!-- close .item -->
                    
                    @endforeach

                </div><!-- close #progression-video-carousel - See /js/script.js file for options -->
            </div><!-- close .progression-studios-elementor-carousel-container  -->

            @else
            @endif 
            
            <div style="height:10px;"></div>

        </div><!-- close #video-more-like-this-details-section -->

        
    </div><!-- close .container -->
 
</div><!-- close #content-pro -->


<checkout-index />