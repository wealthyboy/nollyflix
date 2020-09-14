@extends('layouts.access')

@section('content')

<div id="video-page-title-pro" style="background-image:url({{ $video->poster }});">
    <a class="video-page-title-play-button afterglow" href="#Video-Vayvo-Single"><i class="fas fa-play"></i></a>
    <div style="display:none;">
        <video id="Video-Vayvo-Single" poster="{{ $video->poster }}" width="960" height="540" class="afterglow-lightboxplayer" data-autoresize="fit">
            <source src="images/video/sample.mp4" type="video/mp4">
        </video>
    </div>
    
</div><!-- close #video-page-title-pro -->
		
		
		
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
                        <li id=""><span> 1hr 30mins</span></li>

                    </ul>
                    <div class="clearfix"></div>

                    <div class="content-sidebar-short-description mt-3"> Genres:
                        <?php   $len = count($video->genres); ?>
                        @foreach( $video->genres as $index =>  $genre )
                            <a href="{{ route('browse.genres', ['genre' => $genre->slug]) }}">{{ $genre->name .'   |  ' }} </a>
                            @if ($index == $len - 1) 
                               <a href="{{ route('browse.genres', ['genre' => $genre->slug]) }}">{{ $genre->name  }} </a>
                            @endif
                        @endforeach
                    </div>

        
                    <div class="content-sidebar-short-description mt-3"> Starring:
                        <?php   $len = count($video->casts); ?>
                        @foreach( $video->casts as $index =>  $cast )
                            <a href="/{{ $cast->username }}">{{ $cast->fullname() .'   |  ' }} </a>
                            @if ($index == $len - 1) 
                               <a href="/{{ $cast->username }}">{{ $cast->fullname() }} </a>
                            @endif
                        @endforeach
                    </div>
                    <div id="vayvo-video-post-content">
                       <p>{!! optional($video)->description !!}</p>           
                    </div><!-- #vayvo-video-post-content -->
                </div>

                <div class="comments">
                
                </div> 
            </div>
            <div class="col-lg-4">
                <div id=""> 
                    <buttons />
                </div>
            </div>
        </div>

        
    </div><!-- close .container -->
 
</div><!-- close #content-pro -->


<checkout-index />
		
@endsection
