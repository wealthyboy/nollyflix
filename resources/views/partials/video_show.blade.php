<div id="video-page-title-pro" style="background-image:url({{ $video->poster }});">
   @if(!empty($video->preview_link))
   <a class="video-page-title-play-button" data-trailer-trigger href="{{ $video->preview_link }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-play"></i><span>Watch Trailer</span></a>
   @endif
</div>
<!-- close #video-page-title-pro -->
@if(!empty($video->preview_link))
<div id="nollyflix-trailer-player" class="nollyflix-player" data-nollyflix-player tabindex="-1" hidden>
   <video class="nollyflix-player__video" data-player-video controls playsinline webkit-playsinline preload="metadata"
      poster="{{ optional($video)->poster }}">
      <source src="{{ $video->preview_link }}" type="video/mp4">
      @if($video->track_file)
      <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
      @endif
      Your browser does not support HTML video.
   </video>

   <div class="nollyflix-player__topbar" data-player-chrome>
      <span class="nollyflix-player__brand" aria-hidden="true">NOLLYFLIX</span>
      <button class="nollyflix-player__button nollyflix-player__close" type="button" data-player-action="close" aria-label="Close trailer">
         <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"></path></svg>
      </button>
   </div>

   <button class="nollyflix-player__center-play" type="button" data-player-action="toggle-play" aria-label="Play trailer" data-center-play>
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"></path></svg>
   </button>

   <div class="nollyflix-player__loader" data-player-loader role="status" aria-label="Loading video" hidden>
      <span></span>
   </div>

   <div class="nollyflix-player__error" data-player-error role="alert" hidden>
      <strong>We could not play this trailer.</strong>
      <span>Check your connection or open the original video.</span>
      <a href="{{ $video->preview_link }}" target="_blank" rel="noopener noreferrer">Open video</a>
   </div>

   <div class="nollyflix-player__controls" data-player-controls data-player-chrome>
      <label class="sr-only" for="trailer-progress">Trailer progress</label>
      <input id="trailer-progress" class="nollyflix-player__progress" data-player-progress type="range" min="0" max="100" step="0.1" value="0" aria-label="Trailer progress">

      <div class="nollyflix-player__control-row">
         <button class="nollyflix-player__button" type="button" data-player-action="toggle-play" aria-label="Play trailer" data-play-button>
            <svg data-icon="play" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"></path></svg>
            <svg data-icon="pause" viewBox="0 0 24 24" aria-hidden="true" hidden><path d="M7 5h4v14H7zM13 5h4v14h-4z"></path></svg>
         </button>

         <button class="nollyflix-player__button nollyflix-player__skip" type="button" data-player-action="rewind" aria-label="Rewind 10 seconds">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11 8V5l-5 4 5 4v-3a6 6 0 1 1-5.65 8H3.26A8 8 0 1 0 11 8z"></path><text x="8.5" y="18" font-size="7">10</text></svg>
         </button>

         <button class="nollyflix-player__button nollyflix-player__skip" type="button" data-player-action="forward" aria-label="Forward 10 seconds">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13 8V5l5 4-5 4v-3a6 6 0 1 0 5.65 8h2.09A8 8 0 1 1 13 8z"></path><text x="8.5" y="18" font-size="7">10</text></svg>
         </button>

         <span class="nollyflix-player__time" aria-live="off">
            <span data-player-current-time>0:00</span>
            <span aria-hidden="true"> / </span>
            <span data-player-duration>0:00</span>
         </span>

         <span class="nollyflix-player__title">{{ optional($video)->title }}</span>

         <div class="nollyflix-player__volume-control">
            <button class="nollyflix-player__button" type="button" data-player-action="toggle-mute" aria-label="Mute trailer" data-volume-button>
               <svg data-icon="volume" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 9v6h4l5 4V5L8 9H4zm11.5-.5a5 5 0 0 1 0 7M18 6a9 9 0 0 1 0 12"></path></svg>
               <svg data-icon="muted" viewBox="0 0 24 24" aria-hidden="true" hidden><path d="M4 9v6h4l5 4V5L8 9H4zM16 9l5 6M21 9l-5 6"></path></svg>
            </button>
            <label class="sr-only" for="trailer-volume">Trailer volume</label>
            <input id="trailer-volume" class="nollyflix-player__volume" data-player-volume type="range" min="0" max="1" step="0.05" value="1" aria-label="Trailer volume">
         </div>

         @if($video->track_file)
         <button class="nollyflix-player__button nollyflix-player__captions" type="button" data-player-action="captions" aria-label="Turn captions on" aria-pressed="false">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18v14H3zM6 10h5M6 14h5M14 10h4M14 14h4"></path></svg>
         </button>
         @endif

         <button class="nollyflix-player__button" type="button" data-player-action="fullscreen" aria-label="Enter fullscreen" data-fullscreen-button>
            <svg data-icon="maximize" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3H3v5M16 3h5v5M21 16v5h-5M8 21H3v-5"></path></svg>
            <svg data-icon="minimize" viewBox="0 0 24 24" aria-hidden="true" hidden><path d="M8 3v5H3M16 3v5h5M21 16h-5v5M8 21v-5H3"></path></svg>
         </button>
      </div>
   </div>
</div>
@endif
<div id="content-pro">
   <div class="container custom-gutters-pro">

      
      <div class="row">

         <div class="col-lg-8">
            <div id="">
               <h1 class="video-post-heading-title">{{ optional($video)->title }} <span class="ml-2 resolution">{{ optional($video)->resolution }}</span></h1>
               <div class="clearfix"></div>
               @php
                  $seasonCount = $video->episodes->pluck('season_number')->filter()->unique()->count();
                  $rating = $video->film_rating ?: 'PG';
               @endphp
               <ul id="video-post-meta-list">
                  <li id="video-post-meta-year">{{ optional($video->release_date)->format('Y') }}</li>
                  <li id="video-post-meta-rating"><span>{{ $rating }}</span></li>
                  @if($seasonCount)
                     <li id="video-post-meta-seasons"><span>{{ $seasonCount }} {{ $seasonCount > 1 ? 'Seasons' : 'Season' }}</span></li>
                  @endif
                  <li id=""><span>{{ $video->duration }}</span></li>
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
               <div class="content-sidebar-short-description mt-3"> Produced By:
                  <?php  $filmers = ''; $x= 1; 
                     foreach( $video->filmers as  $filmer ) {
                         $filmers .="<a class='text-danger' href='/$filmer->username'>$filmer->name &nbsp; $filmer->last_name </a>" ;
                         if($x < count($video->filmers)){
                             $filmers .= ' | ';
                             $x++;
                         }
                     }
                     ?>
                  {!! $filmers !!}
               </div>
               <div id="vayvo-video-post-content">
                  <p>{!! optional($video)->description !!}</p>
               </div>
               <!-- #vayvo-video-post-content -->
            </div>
         </div>
         <div class="col-lg-4 mb-5">
            <div class="mt-3" id="">
               @if($blocked)
                  <div class="alert alert-warning p-4 rounded mb-4" role="alert">
                     <strong class="d-block mb-2">This movie is not available in your region.</strong>
                     <div>You can still watch the preview and view the movie details.</div>
                  </div>
               @else
                  <buttons />
               @endif
            </div>
         </div>
       
      </div>



      <div class="container" style="background: #1c1f1f">
         <reviews />
      </div>





    <div class="container-fluid custom-gutters-pro">
        <div style="height:15px;"></div>
        @if($video->related_videos->count())
            <h2 class="post-list-heading">More Like {{ $video->title }}<span></span></h2>
            <div class="progression-studios-elementor-carousel-container mb-5 progression-studios-always-arrows-on">
                <div id="progression-video-carousel" class="owl-carousel progression-video-carousel progression-carousel-theme">
                    @foreach( $video->related_videos as $video)
                        <div class="item">
                            <div class="progression-studios-video-index-container">
                                <a href="/browse/{{ $video->video->slug }}">
                                    <div class="progression-studios-video-feaured-image"><img src="{{ $video->video->tn_poster }}" alt="{{ $video->video->title }}"></div>
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
                            </div><!-- close .progression-studios-video-index-container  -->
                            <div class="d-flex position-absolute links-section flex-column  justify-content-center">
                                <div class="mx-auto buy-rent-links">
                                   @include('partials.links',[ 'video' => $video->video ])
                                </div>
                            </div><!-- close #video-post-buttons-container -->
                        </div><!-- close .item -->
                    
                    @endforeach

                </div><!-- close #progression-video-carousel  file for options -->
            </div><!-- close .progression-studios-elementor-carousel-container  -->

        @else
        @endif            
    
        
    </div><!-- close .container --> 



   </div>
   <!-- close .container -->
   <modal />
</div>
<!-- close #content-pro -->
