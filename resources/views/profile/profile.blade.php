@extends('layouts.access')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center mt-9">
        <div class="col-6 col-lg-3 mb-5">
            <div class="card card-profile text-center card-plain">
                <div class="card-avatar">
                    <a href="#">
                        <img class="img" src="{{ $user->m_path() }}">
                    </a>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $user->fullname() }}</h4>
                    <p class="">{{ $user->bio }}</p>

                </div>
            </div>
        </div>
    </div>
</div>
<div id="content-pro">	
    <div class="container custom-gutters-pro">
        <div class="row">
            @if($user->profile_videos->count())
            @foreach($user->profile_videos as $video)
                <div class="col col-6 col-md-6 item col-lg-3">
                    <div class="progression-studios-video-index-container">
                        <a href="/browse/{{ $video->slug }}/user/{{ $user->slug }}">
                            <div class="progression-studios-video-feaured-image"><img src="{{ $video->tn_poster }}" alt="{{ $video->title }}"></div>
                        
                            <div class="progression-video-index-content">
                                <div class="progression-video-index-table">
                                    <div class="progression-video-index-vertical-align">
                                    
                                        <h2 class="progression-video-title"></h2>
                                    
                                        <div class="average-rating-video-post">
                                            <div class="average-rating-video-empty">
                                                <span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>
                                            </div>
                                            <div class="average-rating-overflow-width" style="width:70%;">
                                                <div class="average-rating-video-filled">
                                                    <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
                                                <div class="clearfix"></div>
                                                </div><!-- close .average-rating-video-filled -->
                                            </div><!-- close .average-rating-overflow-width -->
                                        </div><!-- close .average-rating-video-post -->
                                        <div class="clearfix"></div>
                                        
                                        <ul class="video-index-meta-taxonomy">
                                            <li>
                                                <svg viewBox="0 0 16 16"><path d="M13.781 7.25A3.96 3.96 0 0014 6a4 4 0 00-4-4C8.247 2 6.774 3.135 6.233 4.704A2.487 2.487 0 004.5 4 2.5 2.5 0 002 6.5c0 .273.055.531.135.776A3.5 3.5 0 003.5 14h9a3.5 3.5 0 003.5-3.5c0-1.48-.921-2.738-2.219-3.25zM6 11.25v-4.5L10.5 9 6 11.25z"></path></svg>
                                                Buy  {{ $video->currency }}{{ number_format($video->converted_buy_price) }} 
                                            </li>
                                            <li>
                                                <svg viewBox="0 0 16 16"><path d="M4 6h8v4H4z"></path><path d="M16 11V5a3 3 0 01-3-3H3a3 3 0 01-3 3v6a3 3 0 013 3h10a3 3 0 013-3zm-3-1a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1h8a1 1 0 011 1v4z"></path></svg>
                                                Rent  {{ $video->currency }}{{ number_format($video->converted_rent_price) }}
                                            </li>
                                        </ul>												
                                        <div class="clearfix"></div>
                                    
                                    </div><!-- close .progression-video-index-vertical-align -->
                                </div><!-- close .progression-video-index-table -->
                            </div><!-- close .progression-video-index-content -->
                            <div class="video-index-border-hover"></div>
                            
                        </a>
                    </div><!-- close .progression-studios-video-index-container -->
                    <div class="d-flex position-absolute links-section flex-column  justify-content-center">
                        <div class="mx-auto buy-rent-links">
                            <a href="{{ optional($video)->preview_link }}" class="btn anchor-btn rounded-0"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                        </div>
                    </div><!-- close #video-post-buttons-container -->
                </div><!-- close .col -->
            @endforeach
             
            @else
               <div class="col col-12 col-md-6 col-lg-4">
                     No videos for  {{ $user->fullname() }}
               </div>
            @endif
        </div><!-- close .row -->
    </div><!-- close .container -->
</div><!-- close #content-pro -->
@endsection
