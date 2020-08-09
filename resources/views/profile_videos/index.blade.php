@extends('layouts.access')

@section('content')
<div id="content-pro">
			
    <div class="container custom-gutters-pro">

        <div id="vayvo-progression-author-sidebar">
            <div id="content-sidebar-info">
                <div id="avatar-sidebar-large-profile" style="background-image:url('images/demo/profile-image.jpg')"></div>
                <div id="profile-sidebar-gradient"></div>
                <a href="profile.html#!" class="edit-profile-sidebar">Edit</a>
            </div><!-- close .content-sidebar-info -->
            
            <div id="vayvo-profile-sidebar-name">
                <h5>Jane Doe</h5>
                <h6>United States</h6>
              
            </div><!-- close #vayvo-profile-sidebar-name -->

                <div class="content-sidebar-section">
                    <h3 class="content-sidebar-sub-header">User Stats</h3>
                    <ul id="profile-watched-stats">
                        <li>
                            <span>5</span>
                                Watchlist
                        </li>
                        <li>
                            <span>8</span>
                                Reviews
                        </li>
                    </ul>
                </div>
                <!-- close .content-sidebar-section -->
                <div class="content-sidebar-section">
                    <h3 class="content-sidebar-sub-header">Biography</h3>
                    <div class="content-sidebar-biography-text">
                    Easily add-in a biography for your profile page...							</div>
                </div>
                <!-- close .content-sidebar-section -->

            </div><!-- close #vayvo-progression-author-content-sidebar -->
            
            <div id="vayvo-progression-author-content-container">
                <ul id="dashboard-sub-menu">
                    <li class="current"><a href="/">Account Settings</a></li>
                    <li><a href="/">Videos(0)</a></li>
                    <li><a href="/">Watchlist(0)</a></li>
                </ul>
                <!-- close #dashboard-sub-menu -->
                
                <div class="row">
                    @if( $user->videos->count())
                        @foreach($users->videos as $video)
                            <div class="col col-12 col-md-6 col-lg-6">
                                <div class="progression-studios-video-index-container">
                                    <a href="#">
                                
                                        <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                            
                                        <div class="progression-video-index-content">
                                            <div class="progression-video-index-table">
                                                <div class="progression-video-index-vertical-align">
                                        
                                                    <h2 class="progression-video-title">Polar Express</h2>
                                                    <div class="clearfix"></div>
                                                    <ul class="video-index-meta-taxonomy"><li>Sci-fi</li></ul>												
                                                    <div class="clearfix"></div>
                                        
                                                </div><!-- close .progression-video-index-vertical-align -->
                                            </div><!-- close .progression-video-index-table -->
                                        </div><!-- close .progression-video-index-content -->
                                        <div class="video-index-border-hover"></div>
                                
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
