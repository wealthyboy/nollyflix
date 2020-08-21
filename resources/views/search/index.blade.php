@if( $videos->count())

<div id="content-pro">
			
    <div class="container-fluid custom-gutters-pro">   
        <div id="">                
                <div class="row">
                    @if( $videos->count())
                        @foreach($videos as $video)
                            <div class="col col-6 col-md-3 col-lg-3">
                                <div class="progression-studios-video-index-container">
                                    <a href="/watch/{{ $video->id }}">
                                
                                        <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                            
                                        <div class="progression-video-index-content no-background">
                                            <div class="progression-video-index-table">
                                                <div class="progression-video-index-vertical-align">
                                        
                                                    <h2 class="progression-video-title"></h2>
                                                    <div class="clearfix"></div>
                                                    <ul class="video-index-meta-taxonomy">
                                                        <li></li>
                                                    </ul>												
                                                    <div class="clearfix"></div>
                                        
                                                </div><!-- close .progression-video-index-vertical-align -->
                                            </div><!-- close .progression-video-index-table -->
                                        </div><!-- close .progression-video-index-content -->
                                        <div class="video-index-border-hover"></div>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="mr-2">
                                            <h3 class="progression-video-title">Type: {{ $video->purchase_type == 'rent' ? 'Rented' : 'Bought'}}</h3>
                                            <h3 class="progression-video-title">Price: {{ $video->price }}</h3>

                                           
                                        </div>
                                       
                                
                                    </a>
                                </div><!-- close .progression-studios-video-index-container -->
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