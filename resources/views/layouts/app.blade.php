<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
     	@include('partials.styles')
	</head>

	<script>
		Window.content_owner = {
			user: {!! auth()->check() ? auth()->user() : null !!}
		}
	</script>
	<body>
		<header id="masthead-pro" class="sticky-header"><!-- Remove sticky-header class to remove sticky header -->
			<div class="header-container">
				<h1><a href="/">
					<img src="{{ $system_settings->logo_path() }}" alt="Nolly Flix Logo"></a>
				</h1>
				<nav id="site-navigation-pro"></nav>
				<div class="clearfix"></div>
			</div><!-- close .header-container -->
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
							<li class="instagram-color"><a href="https://www.instagram.com/Nollyflix2026" target="_blank" rel="noopener noreferrer" aria-label="Nollyflix on Instagram"><i class="fab fa-instagram"></i></a></li>
							<li class="twitter-color"><a href="https://x.com/Nollyflix2026" target="_blank" rel="noopener noreferrer" aria-label="Nollyflix on X"><span aria-hidden="true" style="font-family:Arial,sans-serif;font-size:18px;font-weight:700;line-height:1;">X</span></a></li>
							<li class="tiktok-color"><a href="https://www.tiktok.com/@Nollyflix_" target="_blank" rel="noopener noreferrer" aria-label="Nollyflix on TikTok"><i class="fab fa-tiktok"></i></a></li>
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