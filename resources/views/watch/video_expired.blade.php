@extends('layouts.access')

@section('content')
<div id="content-pro">
    <div class="container-fluid custom-gutters-pro">
        <div class="row">
           
            <div class="col col-12 col-md-7 col-lg-7">
                <div class="progression-studios-video-index-container">
                    <a href="#">
                
                        <div class="progression-studios-video-feaured-image"><img src="{{ $video->video->tn_poster }}" alt="{{ $video->video->title }}"></div>
            
                        <div class="progression-video-index-content no-background">
                            <div class="progression-video-index-table">
                                <div class="progression-video-index-vertical-align">
                        
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

                            @if ($video->purchase_type == 'rent')
                                <h3 class="progression-video-title">Expires:  {{  $video->isVideoRentExpired() ? 'Video Expired' : $video->videoExpires()->format('d/m/y')  }}</h3>
                            @endif
                        </div>
                        
                
                    </a>
                </div><!-- close .progression-studios-video-index-container -->
            </div><!-- close .col -->

            <div class="col col-12 col-md-5 col-lg-5">
               <div class=""><h2 class="progression-video-title"></h2></div>
            </div>
        </div><!-- close .row --> 
        
    </div><!-- close .container -->
</div><!-- close #content-pro -->
@endsection
