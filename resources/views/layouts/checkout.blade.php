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
        <script src="https://checkout.flutterwave.com/v3.js"></script><!-- Custom Document Ready JS -->

		<link rel="stylesheet" href="{{ asset('icons/faw/css/font_awesome.css') }}"><!-- FontAwesome Icons -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/style.css">
		<link rel="stylesheet" href="/css/overide.css">

		
		
	</head>


	<script>
		Window.user = {
			video: {!! isset($video) ? $video : 0 !!},
		}
	</script>
	<body>
		
	
		<div id="app">
  	 		@yield('content')
		</div><!-- close #content-pro -->
		
		<footer id="footer-pro">
			<div class="container">
				<div class="row">
					<div class="col-md">
						<div class="copyright-text-pro">&copy; {{ Config('app.name') }}  {{ date('Y') }}. All rights reserved.</div>
					</div><!-- close .col -->
					
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="#" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		
		<!-- All JavaScript in Footer -->
		<script src="/js/app.js?version={{ str_random(6) }}" defer></script><!-- Custom Document Ready JS -->
	</body>
</html>