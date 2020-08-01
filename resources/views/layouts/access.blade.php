<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		
		
		<link rel="stylesheet" href="icons/fontawesome/css/all.min.css"><!-- FontAwesome Icons -->
		<link rel="stylesheet" href="icons/dashicons/css/dashicons-min.css"><!-- DashIcons For Star Ratings -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/style.css">
		 <!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="icon" href="https://nollyflix.tv/images/favicons/cropped-nflix-32x32.png" sizes="32x32" />
		<link rel="icon" href="https://nollyflix.tv/images/favicons/cropped-nflix-192x192.png" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="https://nollyflix.tv/images/favicons/cropped-nflix-180x180.png" />
		<meta name="msapplication-TileImage" content="https://nollyflix.tv/images/favicons/cropped-nflix-270x270.png" />

		 <!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@300;400;500;700&family=Lato:wght@300;400;700&display=swap">
		
		<link rel="stylesheet" href="/icons/fontawesome/css/all.min.css"><!-- FontAwesome Icons -->
		<link rel="stylesheet" href="/icons/dashicons/css/dashicons-min.css"><!-- DashIcons For Star Ratings -->
		
		<title>{{ config('app.name', 'NollyFlix') }}</title>

		
	</head>
	<body>
		<header id="masthead-pro" class="sticky-header"><!-- Remove sticky-header class to remove sticky header -->
			<div class="header-container">
				
				<h1><a href="/"><img src="{{ $system_settings->logo_path() }}" alt="Nolly Flix Logo"></a></h1>
				
				<nav id="site-navigation-pro">
					<ul class="sf-menu">
					   @foreach( $global_categories   as  $category)
							<li class="normal-item-pro ">
						    	<a href="/browse/{{ $category->slug }}"><i class="fas fa-home"></i>{{ $category->name }}</a>
						    </li>
						@endforeach
					</ul>
				</nav>
				
				<!--div id="header-btn-right">
					<button class="btn btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" >Login</button>
				</div-->
					
				<div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
				
					
				<div id="header-user-profile">
					<div id="header-user-profile-click" class="noselect">
						<img src="/images/demo/user-profile.jpg" alt="Suzie">
						<div id="header-username">Jane Doe</div><i class="fas fa-angle-down"></i>
					</div><!-- close #header-user-profile-click -->
					<div id="header-user-profile-menu">
						<ul>
							<li><a href="{{ route('profile') }}"><i class="fa fa-user-circle"></i>My Profile</a></li>
							<li><a href="{{ route('profile') }}"><i class="fa fa-list-ul"></i>My Watchlist</a></li>
							<li><a href=""><i class="fa fa-power-off"></i>Log Out</a></li>
						</ul>
					</div><!-- close #header-user-profile-menu -->
				</div><!-- close #header-user-profile -->
				
				<div id="progression-studios-header-search-icon" class="noselect">
					<div class="progression-icon-search"></div>
				</div>
				

				<div id="video-search-header">
					<div class="container">
						
						<input type="text" placeholder="Search for Movies or TV Series" aria-label="Search" id="main-text-field">
						
						<div id="video-search-header-filtering">
							<form id="video-search-header-filtering-padding">
								<div class="row">
									<div class="col-sm extra-padding">
										<div class="dotted-dividers-pro">
											<h5>Type:</h5>
											<ul class="video-search-type-list">
												<li>
													<label class="checkbox-pro-container">Movies
													  <input type="checkbox" checked="checked" id="movies-type">
													  <span class="checkmark-pro"></span>
													</label>
								
													<label class="checkbox-pro-container">TV Series
													  <input type="checkbox" id="tv-type">
													  <span class="checkmark-pro"></span>
													</label>
												</li>
												<li>
													<label class="checkbox-pro-container">New Arrivals
													  <input type="checkbox" id="movie-type">
													  <span class="checkmark-pro"></span>
													</label>
								
													<label class="checkbox-pro-container">Documentary
													  <input type="checkbox" id="documentary-type">
													  <span class="checkmark-pro"></span>
													</label>
												</li>
											</ul><div class="clearfix"></div>

										</div><!-- close .dotted-dividers-pro -->
									</div><!-- close .col -->
									<div class="col-sm extra-padding">
										<div class="dotted-dividers-pro">
										<h5>Genres:</h5>
										<select class="custom-select">
										  <option selected>All Genres</option>
										  <option value="1">Action</option>
										  <option value="2">Adventure</option>
										  <option value="3">Drama</option>
										  <option value="4">Animation</option>
										  <option value="5">Documentary</option>
										  <option value="6">Drama</option>
										  <option value="7">Horror</option>
										  <option value="8">Thriller</option>
										  <option value="9">Fantasy</option>
										  <option value="10">Romance</option>
										  <option value="11">Sci-Fi</option>
										  <option value="12">Western</option>
										</select>
										</div><!-- close .dotted-dividers-pro -->
									</div><!-- close .col -->
									<div class="col-sm extra-padding">
										<div class="dotted-dividers-pro">
										<h5>Country:</h5>
										<select class="custom-select">
										  <option selected>All Countries</option>
										  <option value="1">Argentina</option>
										  <option value="2">Australia</option>
										  <option value="3">Bahamas</option>
										  <option value="4">Belgium</option>
										  <option value="5">Brazil</option>
										  <option value="6">Canada</option>
										  <option value="7">Chile</option>
										  <option value="8">China</option>
										  <option value="9">Denmark</option>
										  <option value="10">Ecuador</option>
										  <option value="11">France</option>
										  <option value="12">Germany</option>
										  <option value="13">Greece</option>
										  <option value="14">Guatemala</option>
										  <option value="15">Italy</option>
										  <option value="16">Japan</option>
										  <option value="17">asdfasdf</option>
										  <option value="18">Korea</option>
										  <option value="19">Malaysia</option>
										  <option value="20">Monaco</option>
										  <option value="21">Morocco</option>
										  <option value="22">New Zealand</option>
										  <option value="23">Panama</option>
										  <option value="24">Portugal</option>
										  <option value="25">Russia</option>
										  <option value="26">United Kingdom</option>
										  <option value="27">United States</option>
										</select>
										</div><!-- close .dotted-dividers-pro -->
									</div><!-- close .col -->
									<div class="col-sm extra-padding extra-range-padding">
										<h5>Average Rating:</h5>
						            	<div class="range-padding-top"><input class="range-example-rating-input" type="text" min="0" max="10" value="4,10" step="1" /></div>
										<!-- JS is under /js/script.js -->
									</div><!-- close .col -->
								</div><!-- close .row -->
								<div id="video-search-header-buttons">
									<a href="home.html#!" class="btn">Search Videos</a>
									<a href="home.html#!" class="btn reset-btn">Reset</a>
								</div><!-- close #video-search-header-buttons -->
							</form><!-- #video-search-header-filtering-padding -->
						</div><!-- close #video-search-header-filtering -->
						
					</div><!-- close .container -->
				</div><!-- close .video-search-header -->
				
				
				
				<div class="clearfix"></div>
			</div><!-- close .header-container -->
			
			<nav id="mobile-navigation-pro">
				<ul id="mobile-menu-pro">
				    @foreach( $global_categories   as  $category)
						<li class="normal-item-pro">
							<a href="/browse/{{ $category->slug }}"><i class="fas fa-home"></i>{{ $category->name }}</a>
						</li>
					@endforeach
				</ul>
				<!--button class="btn btn-mobile-pro btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" >Login</button-->
				
				<div id="search-mobile-nav-pro">
					<input type="text" placeholder="Search for Movies or TV Series" aria-label="Search">
				</div>
				
			</nav>
			<div id="progression-studios-header-shadow"></div>
		</header>
		
		<section>
			@yield('content')
		</section>
		
		<footer id="footer-pro">
			<div class="container">
				<div class="row">
					<div class="col-md">
						<div class="copyright-text-pro">&copy;  {{ Config('app.name') }}  {{ date('Y') }}. All rights reserved.</div>
					</div><!-- close .col -->
					<div class="col-md">
						<ul class="social-icons-pro">
							<li class="facebook-color"><a href="http://facebook.com/progressionstudios" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li class="twitter-color"><a href="http://twitter.com/Progression_S" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li class="instagram-color"><a href="http://instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></li>
							<li class="youtube-color"><a href="http://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a></li>
							<li class="vimeo-color"><a href="http://vimeo.com" target="_blank"><i class="fab fa-vimeo-v"></i></a></li>
						</ul>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="home.html#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		
	
		

		<!-- Required Framework JavaScript -->
		<script src="/js/libs/jquery-3.5.1.min.js"></script><!-- jQuery -->
		<script src="/js/libs/popper.min.js" defer></script><!-- Bootstrap Popper/Extras JS -->
		<script src="/js/libs/bootstrap.min.js" defer></script><!-- Bootstrap Main JS -->
		<!-- All JavaScript in Footer -->
		
		<!-- Additional Plugins and JavaScript -->
		<script src="/js/navigation.js" defer></script><!-- Header Navigation JS Plugin -->
		<script src="/js/jquery.flexslider-min.js" defer></script><!-- FlexSlider JS Plugin -->	
		<script src="/js/jquery-asRange.min.js" defer></script><!-- Range Slider JS Plugin -->
		<script src="/js/afterglow.min.js" defer></script><!-- Video Player JS Plugin -->
		<script src="/js/owl.carousel.min.js" defer></script><!-- Carousel JS Plugin -->
		<script src="/js/scripts.js" defer></script><!-- Custom Document Ready JS -->
		
		
	</body>
</html>
