@extends('layouts.access')

@section('content')
<div id="content-pro">
    <div class="container-fluid custom-gutters-pro">
        <div class="row">
           
            <div class="col col-12 col-md-7 col-lg-7">
                <div class="progression-studios-video-index-container">
                    <a href="#">
                
                        <div class="progression-studios-video-feaured-image"><img src="{{ $video->poster }}" alt="{{ $video->title }}"></div>
            
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
                        
                        <div class="mr-2">

                           
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
