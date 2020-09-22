
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
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@300;400;500;700&family=Lato:wght@300;400;700&display=swap">
		<link href="/css/vd.css?version={{ str_random(6) }}" rel="stylesheet" />
		<script src='https://kit.fontawesome.com/a076d05399.js'></script> 

		<link rel="stylesheet" href="/css/watch.css?version={{ str_random(6) }}">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
	</head>
	
	<body>
		<section>
			@yield('content')
		</section>
		<script src="/js/video.js?version={{ str_random(6) }}"></script>
		<script src="/js/watch.js?version={{ str_random(6) }}" defer></script><!-- Custom Document Ready JS -->
	</body>

</html>

   

