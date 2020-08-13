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
	<body>
	
		
		<section>
			@yield('content')
		</section>
		
		
		<!-- Required Framework JavaScript -->
		<script src="/js/libs/jquery-3.5.1.min.js"></script><!-- jQuery -->
		<script src="/js/libs/popper.min.js" defer></script><!-- Bootstrap Popper/Extras JS -->
		<script src="/js/libs/bootstrap.min.js" defer></script><!-- Bootstrap Main JS -->
		<!-- All JavaScript in Footer -->
        		<!-- Additional Plugins and JavaScript -->
		<script src="/js/bideo.js" defer></script><!-- Video Background JS Plugin -->
		<script src="/js/video-background.js" defer></script><!-- Video Background JS -->
		
		<!-- Additional Plugins and JavaScript -->
		<script src="/js/navigation.js" defer></script><!-- Header Navigation JS Plugin -->
		<script src="/js/jquery.flexslider-min.js" defer></script><!-- FlexSlider JS Plugin -->	
		<script src="/js/jquery-asRange.min.js" defer></script><!-- Range Slider JS Plugin -->
		<script src="/js/afterglow.min.js" defer></script><!-- Video Player JS Plugin -->
		<script src="/js/owl.carousel.min.js" defer></script><!-- Carousel JS Plugin -->
		<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
		<script src="/js/scripts.js" defer></script><!-- Custom Document Ready JS -->
		
		
	</body>
</html>
