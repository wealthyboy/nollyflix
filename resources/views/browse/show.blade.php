@extends('layouts.access')

@section('content')

<div id="video-page-title-pro" style="background-image:url({{ $video->poster }});">
    <a class="video-page-title-play-button afterglow" href="#Video"><i class="fas fa-play"></i><span>Watch Trailer</span></a>
    <div style="display:none;">
        <video id="Video-Vayvo-Single" poster="{{ $video->poster }}" width="960" height="540" class="afterglow-lightboxplayer" data-autoresize="fit">
            <source src="{{ $video->preview_link }}" type="video/mp4">
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
                        {!! $video->allGenres() !!}
                    </div>

        
                    <div class="content-sidebar-short-description mt-3"> Starring:
                       <?php  $casts = ''; $x= 1; 
                            foreach( $video->casts as  $cast ) {
                                $casts .="<a href='/$cast->username'>$cast->name &nbsp; $cast->last_name </a>" ;
                                if($x < count($video->casts)){
                                    $casts .= ' | ';
                                    $x++;
                                }
                            }
                        ?>
       
                    {!! $casts !!}
                    </div>

                    <div id="vayvo-video-post-content">
                       <p>{!! optional($video)->description !!}</p>           
                    </div><!-- #vayvo-video-post-content -->
                </div>
                
            </div>
            <div class="col-lg-4">
                <div id=""> 
                    <buttons />
                </div>
            </div>
        </div>

        <div style="background: #1c1f1f">
            <reviews />
        </div>

        
    </div><!-- close .container -->
 
</div><!-- close #content-pro -->


<checkout-index />
		
@endsection
