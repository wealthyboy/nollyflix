<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	@include('partials.styles')
</head>


<script>
	Window.user = {
		video: {
			!!isset($video) ? $video : 0!!
		},
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
					<div class="copyright-text-pro">&copy; {{ Config('app.name') }} {{ date('Y') }}. All rights reserved.</div>
				</div><!-- close .col -->

			</div><!-- close .row -->
		</div><!-- close .container -->
	</footer>

	<a href="#" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
	<script src="https://checkout.flutterwave.com/v3.js"></script>
	<!-- All JavaScript in Footer -->
	<script src="/js/app.js?version={{ str_random(6) }}" defer></script><!-- Custom Document Ready JS -->
</body>

</html>