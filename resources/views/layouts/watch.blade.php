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
		<link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="/css/watch.css">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
	
	</head>
	<style>
		body {
			margin: 0;
			padding: 0;
			width: 100vw;
			height: 100vh;
			overflow: hidden;
			background: black;
			font-family: 'Rubik', sans-serif;
		}
	</style>
	<body>
		<div class="video-container">
			@yield('content')
		</div>	
		<script src="/js/watch.js" defer></script><!-- Custom Document Ready JS -->
	</body>
</html>
