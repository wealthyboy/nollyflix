@extends('layouts.access')

@section('content')
<div id="content-pro">
			
    <div class="container custom-gutters-pro">

        <div id="vayvo-progression-author-sidebar">
            @include('partials.profile_aside',['user' => $user])
        </div><!-- close #vayvo-progression-author-content-sidebar -->
            
        <div id="vayvo-progression-author-content-container">
            @include('partials.profile_nav_link',['user' => $user,'current' => 'watchlists'])

                <!-- close #dashboard-sub-menu -->
                
                <div class="row">
                    @if( $user->movies->count())
                        @foreach($user->movies as $video)
                            <div class="col col-6 col-md-4 col-lg-4">
                                <div class="progression-studios-video-index-container">
                                    <a href="{{ !$video->isVideoRentExpired() ? '/watch/'.$video->video->id  : '#' }}">
                                
                                        <div class="progression-studios-video-feaured-image"><img src="{{ $video->video->tn_poster }}" alt="{{ $video->video->title }}"></div>
                            
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
                                        <div class="mr-2">
                                            <h2 class="progression-video-title">Type: {{ $video->purchase_type == 'rent' ? 'Rented' : 'Bought'}}</h2>
                                            <h2 class="progression-video-title">Expires:  </h2>
                                        </div>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                
                                    </a>
                                </div><!-- close .progression-studios-video-index-container -->
                            </div><!-- close .col -->
                        @endforeach
                    @else
                        <div class="col col-12 col-md-6 col-lg-6">
                            No videos
                        </div>
                    @endif
                </div><!-- close .row -->

            </div><!-- close #vayvo-progression-author-content-container -->
              
        <div class="clearfix"></div>
    </div><!-- close .container -->
    
    
    
</div><!-- close #content-pro -->
@endsection
