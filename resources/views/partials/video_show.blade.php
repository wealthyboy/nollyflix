<div id="video-page-title-pro" style="background-image:url({{ $video->poster }});">
   <a class="video-page-title-play-button afterglow play-trailer" id="play-trailer" href="#Video"><i class="fas fa-play"></i><span>Watch Trailer</span></a>
</div>
<!-- close #video-page-title-pro -->
<div class="video-box">
   <video id="shows" class="video-js vjs-default-skin"  style="height: 85vh;width: 100%;object-fit: cover;" data-setup ='{}'
      poster='{{ optional($video)->poster }}'
      data-setup='{ "playbackRates": [1, 1.5, 2] }'>
      <source src="{{ optional($video)->preview_link }}">
      @if(optional($video->video)->track_file)
      <track src="{{ optional($video)->track_file }}" kind="subtitles" srclang="en" label="English">
      @endif
   </video>
   <div class="controls-container">
      <div class="progress-controls">
         <div class="p-bar">
            <div class="watched-bar"></div>
            <div class="playhead"></div>
         </div>
         <div class="time-remaining">
            00:00
         </div>
      </div>
      <div class="controls">
         <button class="play-pause">
            <svg class="playing" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
               <polygon points="5 3 19 12 5 21 5 3"></polygon>
            </svg>
            <svg class="paused" viewBox="0 0 24 24">
               <rect x="6" y="4" width="4" height="16"></rect>
               <rect x="14" y="4" width="4" height="16"></rect>
            </svg>
         </button>
         <button class="rewind">
            <svg viewBox="0 0 24 24">
               <path fill="#ffffff"
                  d="M12.5,3C17.15,3 21.08,6.03 22.47,10.22L20.1,11C19.05,7.81 16.04,5.5 12.5,5.5C10.54,5.5 8.77,6.22 7.38,7.38L10,10H3V3L5.6,5.6C7.45,4 9.85,3 12.5,3M10,12V22H8V14H6V12H10M18,14V20C18,21.11 17.11,22 16,22H14A2,2 0 0,1 12,20V14A2,2 0 0,1 14,12H16C17.11,12 18,12.9 18,14M14,14V20H16V14H14Z" />
            </svg>
         </button>
         <button class="fast-forward">
            <svg viewBox="0 0 24 24">
               <path fill="#ffffff"
                  d="M10,12V22H8V14H6V12H10M18,14V20C18,21.11 17.11,22 16,22H14A2,2 0 0,1 12,20V14A2,2 0 0,1 14,12H16C17.11,12 18,12.9 18,14M14,14V20H16V14H14M11.5,3C14.15,3 16.55,4 18.4,5.6L21,3V10H14L16.62,7.38C15.23,6.22 13.46,5.5 11.5,5.5C7.96,5.5 4.95,7.81 3.9,11L1.53,10.22C2.92,6.03 6.85,3 11.5,3Z" />
            </svg>
         </button>
         <button class="volume">
            <svg class="full-volume" viewBox="0 0 24 24">
               <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
               <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
            </svg>
            <svg class="muted" viewBox="0 0 24 24">
               <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
               <line x1="23" y1="9" x2="17" y2="15"></line>
               <line x1="17" y1="9" x2="23" y2="15"></line>
            </svg>
         </button>
         <p class="title mt-4">
            <span class="series">{{  optional($video)->title  }}</span>
         </p>
         <!-- <button class="help">
            <svg viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
              <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            </button> -->
         <button class="full-screen">
            <svg class="maximize" viewBox="0 0 24 24">
               <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
               </path>
            </svg>
            <svg class="minimize" viewBox="0 0 24 24">
               <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3">
               </path>
            </svg>
         </button>
      </div>
   </div>
</div>
<div id="content-pro">
   <div class="container custom-gutters-pro">
      <div class="row">
         <div class="col-lg-8">
            <div id="">
               <h1 class="video-post-heading-title">{{ optional($video)->title }} <span class="ml-2 resolution">{{ optional($video)->resolution }}</span></h1>
               <div class="clearfix"></div>
               <ul id="video-post-meta-list">
                  <li id="video-post-meta-year">{{ optional($video->release_date)->format('Y') }}</li>
                  <li id="video-post-meta-rating"><span>{{ $video->film_rating }}</span></li>
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
         @if( $system_settings->allow_multi_currency)
         <div class="col-lg-4 mb-5">
            <div class="">
               <div class="dotted-dividers-pro">
                  <h5>Switch Currency: {{ $video->currency }}</h5>
                  <form method="GET" id="form-currency"  action="/currency">
                     <select name="currency_id" id="switch-currency" class="custom-select">
                        @foreach($currencies as $currency)
                        @if ($currency->symbol === $video->currency)
                        <option value="{{ $currency->id }}"  selected>{{ $currency->symbol }} {{ $currency->iso_code3 }} </option>
                        @else
                        <option value="{{ $currency->id }}">{{ $currency->symbol }} {{ $currency->iso_code3 }} </option>
                        @endif 
                        @endforeach
                     </select>
                  </form>
               </div>
               <!-- close .dotted-dividers-pro -->
            </div>
            @endif
            <div  class="mt-3" id="">
               <buttons />
            </div>
         </div>
      </div>
      <div class="container" style="background: #1c1f1f">
         <reviews />
      </div>
      <div id="video-more-like-this-details-section">
         @if($video->related_videos->count())
         <h2 class="post-list-heading">More Like {{ $video->title }}<span></span></h2>
         <div class="progression-studios-elementor-carousel-container progression-studios-always-arrows-on">
            <div id="progression-video-carousel" class="owl-carousel progression-carousel-theme">
               @foreach( $video->related_videos as $video)
               <div class="item">
                  <div class="progression-studios-video-index-container">
                     <a href="/browse/{{ $video->slug }}">
                        <div class="progression-studios-video-feaured-image"><img src="{{ optional($video->video)->tn_poster }}" alt="{{ optional($video->video)->title }}"></div>
                        <div class="progression-video-index-content">
                           <div class="progression-video-index-table">
                              <div class="progression-video-index-vertical-align">
                                 <h2 class="progression-video-title"></h2>
                                 <div class="clearfix"></div>
                                 <ul class="video-index-meta-taxonomy text-center">
                                    <li>
                                       <svg viewBox="0 0 16 16">
                                          <path d="M13.781 7.25A3.96 3.96 0 0014 6a4 4 0 00-4-4C8.247 2 6.774 3.135 6.233 4.704A2.487 2.487 0 004.5 4 2.5 2.5 0 002 6.5c0 .273.055.531.135.776A3.5 3.5 0 003.5 14h9a3.5 3.5 0 003.5-3.5c0-1.48-.921-2.738-2.219-3.25zM6 11.25v-4.5L10.5 9 6 11.25z"></path>
                                       </svg>
                                       Buy  {{ optional($video->video)->currency }}{{ number_format(optional($video->video)->converted_buy_price) }} 
                                    </li>
                                    <li>
                                       <svg viewBox="0 0 16 16">
                                          <path d="M4 6h8v4H4z"></path>
                                          <path d="M16 11V5a3 3 0 01-3-3H3a3 3 0 01-3 3v6a3 3 0 013 3h10a3 3 0 013-3zm-3-1a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1h8a1 1 0 011 1v4z"></path>
                                       </svg>
                                       Rent  {{ optional($video->video)->currency }}{{ number_format(optional($video->video)->converted_rent_price) }}
                                    </li>
                                 </ul>
                              </div>
                              <!-- close .progression-video-index-vertical-align -->
                           </div>
                           <!-- close .progression-video-index-table -->
                        </div>
                        <!-- close .progression-video-index-content -->
                        <div class="video-index-border-hover"></div>
                     </a>
                  </div>
                  <!-- close .progression-studios-video-index-container  -->
                  <div class="d-flex position-absolute links-section flex-column  justify-content-center ">
                     <div class="mx-auto buy-rent-links">
                        <a href="{{ optional($video->video)->preview_link }}" class="btn anchor-btn rounded-0"  data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
                     </div>
                  </div>
                  <!-- close #video-post-buttons-container -->
               </div>
               <!-- close .item -->
               @endforeach
            </div>
            <!-- close #progression-video-carousel - See /js/script.js file for options -->
         </div>
         <!-- close .progression-studios-elementor-carousel-container  -->
         @else
         @endif 
         <div style="height:10px;"></div>
      </div>
      <!-- close #video-more-like-this-details-section -->
   </div>
   <!-- close .container -->
   <modal />
</div>
<!-- close #content-pro -->