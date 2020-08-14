<!doctype html>
<html lang="en">
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
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@300;400;500;700&family=Lato:wght@300;400;700&display=swap">
		<link rel="stylesheet" href="{{ asset('icons/faw/css/font_awesome.css') }}"><!-- FontAwesome Icons -->
		<link rel="stylesheet" href="/icons/Iconsmind__Ultimate_Pack/Line icons/styles.min.css"><!-- iconsmind.com Icons -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/style.css">
		<link rel="stylesheet" href="/css/overide.css">

		
		
	</head>
	<body>
		<header id="masthead-pro">
			<div class="container">
				<nav class="navbar navbar-expand navbar-dark">
                    <a class="navbar-brand" href="/">
                        <img src="{{ $system_settings->logo_path() }}" width="180" height="150" class="d-inline-block align-top" alt="">
                    </a>
				</nav>
			</div><!-- close .container -->
		</header>
	
		<div id="">
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
							<li class="facebook-color"><a href="http://facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li class="twitter-color"><a href="http://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li class="youtube-color"><a href="http://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a></li>
							<li class="vimeo-color"><a href="http://vimeo.com" target="_blank"><i class="fab fa-vimeo-v"></i></a></li>
						</ul>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="#" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		
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