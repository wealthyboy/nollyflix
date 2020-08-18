<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<title>{{ config('app.name', 'NollyFlix') }}</title>

		<link rel="icon" href="https://nollyflix.tv/favicons/cropped-nflix-32x32.png" sizes="32x32" />
		<link rel="icon" href="https://nollyflix.tv/favicons/cropped-nflix-192x192.png" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="https://nollyflix.tv/favicons/cropped-nflix-180x180.png" />
		<meta name="msapplication-TileImage" content="https://nollyflix.tv/favicons/cropped-nflix-270x270.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="canonical" href="https://nollyflix.tv/">
		<meta property="og:site_name" content="NollyFilx">
		<meta property="og:url" content="https://nollyflix.tv">
		<meta property="og:title" content=" NollyFlix tv">
		<meta property="og:type" content="website">
		<meta property="og:description" content="Watch nollywood movies online">
		<script src='https://kit.fontawesome.com/a076d05399.js'></script> 
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@300;400;500;700&family=Lato:wght@300;400;700&display=swap">
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/style.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

		<link rel="stylesheet" href="/css/overide.css">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
	
	</head>

	<script>
		Window.content_owner = {
			user: {!! isset($user) ? $user : null !!}
		}
	</script>
	<body>
		<header id="masthead-pro" class="sticky-header"><!-- Remove sticky-header class to remove sticky header -->
			<div class="header-container">
				
				<h1><a href="/"><img src="{{ $system_settings->logo_path() }}" alt="Nolly Flix Logo"></a></h1>
				
				<nav id="site-navigation-pro">
					<ul class="sf-menu">
					   @foreach( $global_categories   as  $category)
							<li class="normal-item-pro ">
						    	<a href="/browse/{{ $category->slug }}">{{ $category->name }}</a>
						    </li>
						@endforeach
					</ul>
				</nav>
				
				<!--div id="header-btn-right">
					<button class="btn btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" >Login</button>
				</div-->
					
				<div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
				
				<div class="header-user-profile" id="header-user-profile">
					<div id="header-user-profile-click" class="noselect header-user-profile-click">
						<img src="/images/profile_icon.png" alt="Suzie">
						<div id="header-username">{{ '' }}</div><i class="fas fa-angle-down"></i>
					</div><!-- close #header-user-profile-click -->
					<div class="header-user-profile-menu" id="header-user-profile-menu">
						<ul>
							<li><a href="{{ route('profile.index') }}"><i class="fa fa-user-circle"></i>My Profile</a></li>
							<li><a href="{{ route('profiles.watchlists') }}"><i class="fa fa-list-ul"></i>My Watchlist</a></li>
							<li>
							<a class="" href="/logout"
                                                        onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
													<i class="fas fa-sign-out-alt left"></i>
													
													Logout
                                                </a>
                                                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
							
							</li>
						</ul>
					</div><!-- close #header-user-profile-menu -->
				</div><!-- close #header-user-profile -->	
				<div id="header-user-profile">
					<div id="header-user-profile-click" class="noselect">
					   <i class="fas fa-shopping-cart"></i>
						<div id="header-username"><a href="/checkout">Cart <span id="cart-count">(0)</span></a></div>
					</div><!-- close #header-user-profile-click -->
					<div class="header-user-profile-menu" id="header-user-profile-menu">
						<ul>
							<li><a href="{{ route('profile.index') }}"><i class="fa fa-user-circle"></i>My Profile</a></li>
							<li><a href="{{ route('profiles.watchlists') }}"><i class="fa fa-list-ul"></i>My Watchlist</a></li>
							<li>
							<a class="" href="/logout"
                                                        onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                                    <i class="fas fa-sign-out left" aria-hidden="true"></i> Logout
                                                </a>
                                                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
							
							</li>
						</ul>
					</div><!-- close #header-user-profile-menu -->
				</div><!-- close #header-user-profile -->

		
				
				<div id="progression-studios-header-search-icon" class="noselect cursor-pointer">
					<div class="fas fa-search mt-4"></div>
				</div>
				

				<div id="video-search-header">
					<div class="container">
						<input type="text" placeholder="Search for Movies or TV Series" aria-label="Search" id="main-text-field">
					</div><!-- close .container -->
				</div><!-- close .video-search-header -->
				
				<div class="clearfix"></div>
			</div><!-- close .header-container -->
			
			<nav id="mobile-navigation-pro">
				<ul id="mobile-menu-pro">
				    @foreach( $global_categories   as  $category)
						<li class="normal-item-pro">
							<a href="/browse/{{ $category->slug }}">{{ $category->name }}</a>
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
							<li class="facebook-color"><a href="http://facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li class="twitter-color"><a href="http://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li class="instagram-color"><a href="http://instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></li>
							<li class="youtube-color"><a href="http://youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a></li>
							<li class="vimeo-color"><a href="http://vimeo.com/" target="_blank"><i class="fab fa-vimeo-v"></i></a></li>
						</ul>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="home.html#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		
	
		

		<!-- Required Framework JavaScript -->
		<script src="/js/libs/jquery-3.5.1.min.js"></script><!-- jQuery -->
		<script src="/js/libs/popper.min.js"></script><!-- Bootstrap Popper/Extras JS -->
		<script src="/js/libs/bootstrap.min.js"></script><!-- Bootstrap Main JS -->
		<!-- All JavaScript in Footer -->

		
		<!-- Additional Plugins and JavaScript -->
		<script src="/js/navigation.js"></script><!-- Header Navigation JS Plugin -->
		<script src="/js/bootstrap-notify.js"></script><!-- Header Navigation JS Plugin -->
		<script src="/js/jquery.flexslider-min.js"></script><!-- FlexSlider JS Plugin -->	
		<script src="/js/owl.carousel.min.js"></script><!-- Carousel JS Plugin -->
		<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
		<script src="/js/scripts.js?version={{ str_random(6) }}"></script><!-- Custom Document Ready JS -->
	</body>
</html>
