<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="icon" href="https://nollyflix.tv/favicons/cropped-nflix-32x32.png" sizes="32x32" />
		<link rel="icon" href="https://nollyflix.tv/favicons/cropped-nflix-192x192.png" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="https://nollyflix.tv/favicons/cropped-nflix-180x180.png" />
		<meta name="msapplication-TileImage" content="https://nollyflix.tv/favicons/cropped-nflix-270x270.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/guest.css">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:400,700%7CMontserrat:300,400,600,700">
		
		<link rel="stylesheet" href="{{ asset('icons/faw/css/font_awesome.css') }}"><!-- FontAwesome Icons -->
		<link rel="stylesheet" href="/icons/Iconsmind__Ultimate_Pack/Line icons/styles.min.css"><!-- iconsmind.com Icons -->

		
		
		<title>{{ config('app.name', 'NollyFlix') }}</title>
	</head>
	<body>
		<header id="masthead-pro">
			<div class="container">
				
				<h1><a href="/"><img src="{{ $system_settings->logo_path() }}" class="" alt=" Store Logo" /></a></h1>
				<nav class="navbar navbar-expand navbar-dark">
					<div class="navbar-collapse collapse">
						<ul class="navbar-nav">
						
							<li class="nav-item">
								<a class="nav-link btn btn-primary text-danger" href="{{ route('login') }}">Login</a>
							</li>
						</ul>
					</div>
				</nav>
			</div><!-- close .container -->
			
		
		</header>
		
	
		<div id="content-pro">
  	 		@yield('content')
		</div><!-- close #content-pro -->
		
		<footer id="footer-pro">
			<div class="container">
				<div class="row">
					<div class="col-md">
						<div class="copyright-text-pro">&copy; {{ Config('app.name') }}  {{ date('Y') }}. All rights reserved.</div>
					</div><!-- close .col -->
					<div class="col-md">
						<ul class="social-icons-pro">
							<li class="facebook-color"><a href="http://facebook.com/progressionstudios" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li class="twitter-color"><a href="http://twitter.com/Progression_S" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li class="youtube-color"><a href="http://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a></li>
							<li class="vimeo-color"><a href="http://vimeo.com" target="_blank"><i class="fab fa-vimeo-v"></i></a></li>
						</ul>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="index.html#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		
	

		<!-- Required Framework JavaScript -->
		<script src="/js/libs/jquery-3.3.1.min.js"></script><!-- jQuery -->
		<script src="/js/libs/popper.min.js" defer></script><!-- Bootstrap Popper/Extras JS -->
		<script src="/js/libs/bootstrap.min.js" defer></script><!-- Bootstrap Main JS -->
		<!-- All JavaScript in Footer -->
		
		<!-- Additional Plugins and JavaScript -->
		<script src="/js/navigation.js" defer></script><!-- Header Navigation JS Plugin -->
		<script src="/js/guest.js" defer></script><!-- Custom Document Ready JS -->
		
	</body>
</html>