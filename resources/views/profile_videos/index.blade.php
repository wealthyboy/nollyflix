@extends('layouts.access')

@section('content')
<div id="content-pro">
    <div class="container custom-gutters-pro">
        <div id="vayvo-progression-author-sidebar">
            @include('partials.profile_aside',['user' => $user])
        </div><!-- close #vayvo-progression-author-content-sidebar --> 
        <div id="vayvo-progression-author-content-container">
        @include('partials.profile_nav_link',['user' => $user,'current' => 'videos'])

                <!-- close #dashboard-sub-menu -->
                
                <div class="row">
                    @if( $user->profile_videos->count())
                        @foreach($user->profile_videos as $video)
                            <div class="col col-6 col-md-3 col-lg-3">
                                <div class="progression-studios-video-index-container">
                                    <a href="#">
                                
                                        <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                            
                                        <div class="progression-video-index-content no-background">
                                            <div class="progression-video-index-table">
                                                <div class="progression-video-index-vertical-align">
                                                <div class="clearfix"></div>                                        
                                                </div><!-- close .progression-video-index-vertical-align -->
                                            </div><!-- close .progression-video-index-table -->
                                        </div><!-- close .progression-video-index-content -->
                                        <div class="video-index-border-hover"></div>
                                        <div class="ml-3">
                                            <small class="progression-video-title"><span>{{ $user->sales->count() }}</span> - Sold</small><br/>
                                            <small class="progression-video-title"><span>{{ $user->rents->count() }}</span> - Rented</small><br/>
                                            <small class="progression-video-title"><span>3</span> - Views</small>
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
