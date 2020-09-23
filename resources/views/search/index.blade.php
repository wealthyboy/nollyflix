
@if( $videos->count())

<div id="content-pro">
			
    <div class="container-fluid custom-gutters-pro">   
        <div id="">                
                <div class="row">
                    @if( $videos->count())
                        @foreach($videos as $video)
                        <div class="col item col-12 col-md-6 col-lg-3 mt-3">
                    <div class="progression-studios-video-index-container">
                        <a href="/browse/{{ $video->slug }}">

                            <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                        
                            <div class="progression-video-index-content">
                                <div class="progression-video-index-table">
                                    <div class="progression-video-index-vertical-align">
                                    
                                        <h2 class="progression-video-title"></h2>
                                    
                                     
                                        <div class="clearfix"></div>
                                        
                                        <div class="clearfix"></div>
                                    
                                    </div><!-- close .progression-video-index-vertical-align -->
                                </div><!-- close .progression-video-index-table -->
                            </div><!-- close .progression-video-index-content -->
                            <div class="video-index-border-hover"></div>
                            
                        </a>
                    </div><!-- close .progression-studios-video-index-container -->
                    <div class="d-flex position-absolute links-section flex-column  justify-content-center ">
                        <div class="mx-auto buy-rent-links">
                            <a href="{{ optional($video)->preview_link }}" class="btn anchor-btn rounded-0"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                            <a href="/browse/{{ $video->slug }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Buy {{ $video->currency }}{{ number_format($video->converted_buy_price) }} </a>
                            <a href="/browse/{{ $video->slug }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Rent {{ $video->currency }}{{ number_format($video->converted_rent_price) }} </a>

                        </div>
                    </div><!-- close #video-post-buttons-container -->
                </div><!-- close .col -->
                        @endforeach
                    @else
                        No videos
                    @endif
                </div><!-- close .row -->

            </div><!-- close #vayvo-progression-author-content-container -->
              
        <div class="clearfix"></div>
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->
@else
    No videos
@endif