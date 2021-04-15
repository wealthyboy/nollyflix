
@if( $videos->count())

<div id="content-pro">
			
    <div class="container-fluid custom-gutters-pro">   
        <div id="">                
                <div class="row">
                    @if( $videos->count())
                        @foreach($videos as $video)
                        <div class="col item col-6 col-md-6 col-lg-2 mt-3">
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
                    </div><!-- close .progression-studios-video-index-container -->
                    <div class="d-flex position-absolute links-section flex-column  justify-content-center ">
                        <div class="mx-auto buy-rent-links">
                         @include('partials.links')
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